<?php

declare(strict_types=1);

namespace Drupal\post_api_postgres_view;

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
