<?php

declare(strict_types=1);

namespace Drupal\repositories\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\repositories\Domain\UserRepository;
use Drupal\repositories\Domain\UserRepositoryInterface;
use Drupal\repositories\UserPresenter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserListController extends ControllerBase implements ContainerInjectionInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    #[\Override]
    public static function create(
        ContainerInterface $container
    ): self {
        return new self(
            $container->get('repository.jsonplaceholder'),
        );
    }

    /**
     * @return array{"#theme": string, "#type": string, "#items": string[], "#title": string}
     */
    public function index(): array
    {
        $storage = new UserRepository($this->userRepository);

        $users = $storage->all();

        $userPresent = UserPresenter::present($users);

        return [
            '#theme' => 'user_list',
            '#items' => $userPresent,
        ];
    }
}
