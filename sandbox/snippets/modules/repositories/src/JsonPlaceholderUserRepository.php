<?php

declare(strict_types=1);

namespace Drupal\repositories;

use Drupal\repositories\Domain\User;
use Drupal\repositories\Domain\UserRepositoryInterface;
use Drupal\repositories\Mapper\JsonPlaceholderUserMapper;
use Error;
use GuzzleHttp\ClientInterface;

class JsonPlaceholderUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly ClientInterface $client,
    ) {
    }

    /**
     * @return User[]
     * @throws Error
     */
    #[\Override]
    public function all(): array
    {
        try {
            $response = $this->client->request('GET', 'https://jsonplaceholder.typicode.com/users');
            /** @var array<array{id: int, name: string, ...}> $users */
            $users = json_decode($response->getBody()->getContents(), true);

            return JsonPlaceholderUserMapper::toDomain($users);
        } catch (\Exception $exception) {
            throw new Error($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
