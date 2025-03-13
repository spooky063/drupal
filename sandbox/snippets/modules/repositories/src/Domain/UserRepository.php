<?php

declare(strict_types=1);

namespace Drupal\repositories\Domain;

class UserRepository
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ) {
    }

    /**
     * @return User[]
     */
    public function all(): array
    {
        return $this->repository->all();
    }
}
