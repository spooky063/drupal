<?php

declare(strict_types=1);

namespace Drupal\post_api_rest\Normalizer;

use Drupal\node\NodeInterface;
use Drupal\serialization\Normalizer\NormalizerBase;
use Symfony\Component\HttpFoundation\Request;

class PostNormalizer extends NormalizerBase
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

        return ($entity instanceof NodeInterface && $entity->getType() === 'post' && $id);
    }

    /**
     * @param NodeInterface $entity
     *
     * @return array{id: int, title: string, content: string, author: array{id: int, name: string}}
     */
    public function normalize($entity, $format = null, array $context = []): array
    {
        /** @var string $content */
        $content = $entity->get('field_content')->value;

        return [
            'id' => (int) $entity->id(),
            'title' => (string) $entity->getTitle(),
            'content' => $content,
            'author' => [
                'id' => (int) $entity->getOwner()->id(),
                'name' => (string) $entity->getOwner()->getDisplayName(),
            ],
        ];
    }
}
