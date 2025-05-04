<?php

declare(strict_types=1);

namespace Drupal\post_api_rest\Normalizer;

use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\serialization\Normalizer\NormalizerBase;
use Symfony\Component\HttpFoundation\Request;

class PostListNormalizer extends NormalizerBase
{
    protected string $supportedInterfaceOrClass = 'Drupal\node\NodeInterface';

    public function supportsNormalization($entity, $format = null, array $context = []): bool
    {
        if (!is_object($entity) || !$this->checkFormat($format)) {
            return false;
        }

        /** @var Request $request */
        $request = $context['request'];
        $id = $request->attributes->has('id');

        return ($entity instanceof NodeInterface && $entity->getType() === 'post' && $id === false);
    }

    /**
     * @param NodeInterface $entity
     *
     * @return array{id: int, title: string, content: string, author: array{id: int, name: string}, _self: string}
     */
    public function normalize($entity, $format = null, array $context = []): array
    {
        /** @var Request $request */
        $request = $context['request'];
        $format = $request->query->get('_format', 'json');

        /** @var string $content */
        $content = $entity->get('field_content')->value;

        $urlNodeDetail = Url::fromRoute(
            'rest.post_resource.GET',
            ['id' => $entity->id()],
            ['query' => ['_format' => $format]]
        )->toString();

        return [
            'id' => (int) $entity->id(),
            'title' => (string) $entity->getTitle(),
            'content' => $content,
            'author' => [
                'id' => (int) $entity->getOwner()->id(),
                'name' => (string) $entity->getOwner()->getDisplayName(),
            ],
            '_self' => $urlNodeDetail,
        ];
    }
}
