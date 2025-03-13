<?php

declare(strict_types=1);

namespace Drupal\repositories\Mapper;

use Drupal\repositories\Domain\User;
use Drupal\user\UserInterface;

class DrupalUserMapper
{
    /**
     * @param UserInterface[] $users
     *
     * @return User[]
     */
    public static function toDomain(array $users): array
    {
        return array_map(
            static fn ($user): User => new User((int)$user->id(), (string)$user->getDisplayName()),
            $users
        );
    }
}
