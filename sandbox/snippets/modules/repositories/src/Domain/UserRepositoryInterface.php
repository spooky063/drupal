<?php

declare(strict_types=1);

namespace Drupal\repositories\Domain;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function all(): array;
}
