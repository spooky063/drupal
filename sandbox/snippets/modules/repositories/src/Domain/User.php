<?php

declare(strict_types=1);

namespace Drupal\repositories\Domain;

use Symfony\Component\Serializer\Attribute\Groups;

class User
{
    public function __construct(
        public int $id,
        #[Groups(["user-list"])]
        public string $name,
    ) {
    }
}
