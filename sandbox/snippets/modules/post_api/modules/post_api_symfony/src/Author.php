<?php

declare(strict_types=1);

namespace Drupal\post_api_symfony;

use Symfony\Component\Serializer\Attribute\Groups;

final class Author
{
    public function __construct(
        #[Groups(['post_list', 'post_detail'])]
        public int $id,
        #[Groups(['post_list', 'post_detail'])]
        public string $name,
    ) {
    }
}
