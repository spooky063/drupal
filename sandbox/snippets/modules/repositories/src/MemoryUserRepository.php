<?php

declare(strict_types=1);

namespace Drupal\repositories;

use Drupal\repositories\Domain\User;
use Drupal\repositories\Domain\UserRepositoryInterface;
use Drupal\repositories\Mapper\MemoryUserMapper;

class MemoryUserRepository implements UserRepositoryInterface
{
    /**
     * @return User[]
     */
    #[\Override]
    public function all(): array
    {
        $users = [
            ['id' => 1, 'name' => 'fixture_user1']
        ];
        return MemoryUserMapper::toDomain($users);
    }
}
