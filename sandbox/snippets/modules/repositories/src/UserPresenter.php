<?php

declare(strict_types=1);

namespace Drupal\repositories;

use Drupal\repositories\Domain\User;

class UserPresenter
{
    /**
     * @param User[] $users
     * @return UserList[]
     */
    public static function present(array $users): array
    {
        return array_map(static fn ($user): \Drupal\repositories\UserList => new UserList($user->name), $users);
    }
}
