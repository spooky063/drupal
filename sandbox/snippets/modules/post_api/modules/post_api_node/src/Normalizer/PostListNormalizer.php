<?php

declare(strict_types=1);

namespace Drupal\post_api_node\Normalizer;

use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\serialization\Normalizer\EntityNormalizer;

class PostListNormalizer extends EntityNormalizer
{
    public function supportsNormalization($entity, $format = null, array $context = []): bool
    {
        if (!is_object($entity) || !$this->checkFormat($format)) {
            return false;
        }

        return (
            $entity instanceof NodeInterface && $entity->getType() === 'post'
            && in_array('post_list', $context['groups'] ?? [])
        );
    }

    /**
     * @param NodeInterface $entity
     *
     * @return array{id: int, title: string, content: string, author: array{id: int, name: string}, _self: string}
     */
    public function normalize($entity, $format = null, array $context = []): array
    {
        parent::normalize($entity, $format, $context);

        /** @var string $content */
        $content = $entity->get('field_content')->value;

        $urlNodeDetail = Url::fromRoute('post_api_node.get.detail', ['id' => $entity->id()])->toString();

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
