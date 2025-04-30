<?php

declare(strict_types=1);

namespace Drupal\post_api_node\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class PostController extends ControllerBase
{
    public function __construct(
        public SerializerInterface $serializer,
    ) {
    }

    #[\Override]
    public static function create(
        ContainerInterface $container
    ): self {
        return new self(
            $container->get('serializer'),
        );
    }

    public function index(int $id): Response
    {
        $node = $this->entityTypeManager()->getStorage('node')->load($id);

        if (!$node instanceof NodeInterface) {
            return new JsonResponse(sprintf('No entity found for id %d', $id), 400);
        }

        $nodeSerialized = $this->serializer->serialize($node, 'json', ['groups' => ['post_detail']]);

        $metadata = new CacheableMetadata();
        $metadata->setCacheTags([sprintf('node:%s', $id)]);
        $metadata->setCacheMaxAge(5);

        $response = new CacheableResponse($nodeSerialized, 200, ['Content-Type' => 'application/json']);
        $response->addCacheableDependency($metadata);

        return $response;
    }
}
