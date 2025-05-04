<?php

declare(strict_types=1);

namespace Drupal\post_api_rest\Plugin\rest\resource;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\node\NodeInterface;
use Drupal\rest\Attribute\RestResource;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpFoundation\Response;

#[RestResource(
    id: "post_resource",
    label: new TranslatableMarkup("Post Resource"),
    serialization_class: \Drupal\post_api_rest\Normalizer\PostNormalizer::class,
    uri_paths: [
    "canonical" => "/api/rest/posts/{id}",
    ]
)]
final class PostResource extends ResourceBase
{
    public function get(int $id): Response
    {
        $storage = \Drupal::entityTypeManager()->getStorage('node');

        $node = $storage->load($id);

        if (!$node instanceof NodeInterface) {
            return new ResourceResponse(sprintf('No entity found for id %d', $id), 400);
        }

        if ($node->getType() !== 'post') {
            return new ResourceResponse(sprintf('No entity found for id %d', $id), 400);
        }

        $response = new ResourceResponse($node);
        $response->addCacheableDependency($node);

        return $response;
    }
}
