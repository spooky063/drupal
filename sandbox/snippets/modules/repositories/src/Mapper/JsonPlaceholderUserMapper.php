<?php

declare(strict_types=1);

namespace Drupal\repositories\Mapper;

use Drupal\repositories\Domain\User;

class JsonPlaceholderUserMapper
{
    /**
     * @param array<array{id: int, name: string}> $users
     * @return User[]
     */
    public static function toDomain(array $users): array
    {
        return array_map(static fn ($user): User => new User($user['id'], $user['name']), $users);
    }
}
