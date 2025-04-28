<?php

declare(strict_types=1);

namespace Drupal\posts_api;

final class PostList
{
    private function __construct(
        public array $post,
        public int $total,
    ) {
    }

    public static function create(
        string $post,
        string $total
    ): self {
        return new self(
            Post::create((array) json_decode($post, true)),
            (int) $total,
        );
    }
}
