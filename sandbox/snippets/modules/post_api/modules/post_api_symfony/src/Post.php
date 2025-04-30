<?php

declare(strict_types=1);

namespace Drupal\post_api_symfony;

use Drupal\Core\Url;
use Symfony\Component\Serializer\Attribute\Groups;

final class Post
{
    public function __construct(
        #[Groups(['post_list', 'post_detail'])]
        public int $id,
        #[Groups(['post_list', 'post_detail'])]
        public string $title,
        #[Groups(['post_list', 'post_detail'])]
        public string $content,
        #[Groups(['post_list', 'post_detail'])]
        public Author $author,
        #[Groups(['post_list'])]
        public string $_self,
    ) {
    }

    public static function create(
        int $id,
        string $title,
        string $content,
        Author $author,
    ): self {
        $urlNodeDetail = Url::fromRoute('post_api_symfony.get.detail', ['id' => $id])->toString();

        return new self(
            $id,
            $title,
            $content,
            $author,
            $urlNodeDetail,
        );
    }
}
