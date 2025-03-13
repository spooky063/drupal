<?php

declare(strict_types=1);

namespace Drupal\repositories;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\repositories\Domain\User;
use Drupal\repositories\Domain\UserRepositoryInterface;
use Drupal\repositories\Mapper\DrupalUserMapper;
use Drupal\user\Entity\User as DrupalUser;

class DrupalUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityTypeManagerInterface $entityTypeManager,
    ) {
    }

    /**
     * @return User[]
     */
    #[\Override]
    public function all(): array
    {
        $query = $this->entityTypeManager->getStorage('user')->getQuery();
        $uids = $query->condition('name', 'fixture_%', 'LIKE')->accessCheck()->execute();
        $users = DrupalUser::loadMultiple($uids);
        return DrupalUserMapper::toDomain($users);
    }
}
