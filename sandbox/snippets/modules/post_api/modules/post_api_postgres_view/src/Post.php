<?php

declare(strict_types=1);

namespace Drupal\post_api_postgres_view;

use Drupal\Core\Url;

final class Post
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public array $author,
        public string $_self,
    ) {
    }

    /**
     * @return array<array-key, Post>
     */
    public static function create(array $posts): array
    {
        return array_map(static function (array $post): Post {
            $route = Url::fromRoute('post_api_postgres_view.get.detail', ['id' => $post['id']])->toString();

            return new Post(
                $post['id'],
                $post['title'],
                $post['content'],
                $post['author'],
                $route,
            );
        }, $posts);
    }
}
