<?php

declare(strict_types=1);

namespace Drupal\post_api_symfony\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\post_api_symfony\Author;
use Drupal\post_api_symfony\Post;
use Drupal\serialization\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class PostController extends ControllerBase
{
    public function index(int $id): Response
    {
        $node = $this->entityTypeManager()->getStorage('node')->load($id);

        if (!$node instanceof NodeInterface) {
            return new JsonResponse(sprintf('No entity found for id %d', $id), 400);
        }

        $author = new Author(
            (int) $node->getOwner()->id(),
            (string) $node->getOwner()->getDisplayName()
        );

        /** @var string $content */
        $content = $node->get('field_content')->value;
        $post = Post::create(
            (int) $node->id(),
            (string) $node->getTitle(),
            $content,
            $author
        );

        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        $posts = $serializer->normalize($post, 'json', ['groups' => ['post_detail']]);

        $metadata = new CacheableMetadata();
        $metadata->setCacheTags([sprintf('node:%s', $id)]);
        $metadata->setCacheMaxAge(5);

        $response = new CacheableJsonResponse($posts, 200);
        $response->addCacheableDependency($metadata);

        return $response;
    }
}
