<?php

declare(strict_types=1);

namespace Drupal\repositories;

class UserList
{
    public function __construct(
        public string $name,
    ) {
    }
}
