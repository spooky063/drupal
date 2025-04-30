<?php

declare(strict_types=1);

namespace Drupal\post_api_node\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class PostListController extends ControllerBase
{
    public const int LIMIT = 10;

    public function __construct(
        public SerializerInterface $serializer,
    ) {
    }

    #[\Override]
    public static function create(ContainerInterface $container): self
    {
        return new self(
            $container->get('serializer'),
        );
    }

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

        $nodeSerialized = $this->serializer->serialize($nodes, 'json', ['groups' => ['post_list']]);

        $posts = ['posts' => json_decode($nodeSerialized, true), 'total' => $count];

        $metadata = new CacheableMetadata();
        $metadata->setCacheTags(['node_list']);
        $metadata->setCacheContexts(['url.query_args']);
        $metadata->setCacheMaxAge(60);

        $response = new CacheableJsonResponse($posts, 200);
        $response->addCacheableDependency($metadata);

        return $response;
    }
}
