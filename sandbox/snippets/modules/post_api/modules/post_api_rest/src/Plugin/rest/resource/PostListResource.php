<?php

declare(strict_types=1);

namespace Drupal\post_api_rest\Plugin\rest\resource;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\rest\Attribute\RestResource;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[RestResource(
    id: "post_list_resource",
    label: new TranslatableMarkup("Post List Resource"),
    serialization_class: \Drupal\post_api_rest\Normalizer\PostListNormalizer::class,
    uri_paths: [
    "canonical" => "/api/rest/posts",
    ]
)]
final class PostListResource extends ResourceBase
{
    public const int LIMIT = 10;

    public function get(Request $request): Response
    {
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', self::LIMIT);

        $storage = \Drupal::entityTypeManager()->getStorage('node');

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

        $metadata = new CacheableMetadata();
        $metadata->setCacheTags(['node_list']);
        $metadata->setCacheContexts(['url.query_args']);
        $metadata->setCacheMaxAge(60);

        $posts = ['posts' => $nodes, 'total' => $count];

        $response = new ResourceResponse($posts);
        $response->addCacheableDependency($metadata);

        return $response;
    }
}
