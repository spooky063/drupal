<?php

declare(strict_types=1);

namespace Drupal\post_api_symfony\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\post_api_symfony\Author;
use Drupal\post_api_symfony\Post;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class PostListController extends ControllerBase
{
    public const int LIMIT = 10;

    public function index(Request $request): Response
    {
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', self::LIMIT);

        $storage = $this->entityTypeManager()->getStorage('node');

        $count = $storage->getQuery()->accessCheck()
            ->condition('type', 'post')
            ->count()
            ->execute();

        $lastIdSeenQuery = $storage->getQuery()->accessCheck()
            ->condition('type', 'post')
            ->sort('nid')
            ->range($limit * ($page - 1), 1)
            ->execute();
        $lastIdSeen = array_values($lastIdSeenQuery);
        $lastIdSeenValue = reset($lastIdSeen);

        $nids = $storage->getQuery()->accessCheck()
            ->condition('type', 'post')
            ->sort('nid')
            ->condition('nid', $lastIdSeenValue, '>=')
            ->range(null, $limit)
            ->execute();
        $nodes = $storage->loadMultiple($nids);

        $posts = array_map(function (NodeInterface $node): \Drupal\post_api_symfony\Post {
            $author = new Author(
                (int) $node->getOwner()->id(),
                (string) $node->getOwner()->getDisplayName()
            );

            /** @var string $content */
            $content = $node->get('field_content')->value;

            return Post::create(
                (int) $node->id(),
                (string) $node->getTitle(),
                $content,
                $author
            );
        }, $nodes);

        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        $response = $serializer->normalize($posts, 'json', ['groups' => ['post_list']]);

        $metadata = new CacheableMetadata();
        $metadata->setCacheTags(['node_list']);
        $metadata->setCacheContexts(['url.query_args']);
        $metadata->setCacheMaxAge(60);

        $response = new CacheableJsonResponse(['posts' => $response, 'total' => $count], 200);
        $response->addCacheableDependency($metadata);

        return $response;
    }
}
