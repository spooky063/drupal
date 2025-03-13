<?php

declare(strict_types=1);

namespace Drupal\repositories\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\repositories\Domain\UserRepository;
use Drupal\repositories\Domain\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserListApiController extends ControllerBase implements ContainerInjectionInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly Serializer $serializer,
    ) {
    }

    #[\Override]
    public static function create(
        ContainerInterface $container
    ): self {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        return new self(
            $container->get('repository.memory'),
            $serializer
        );
    }

    public function index(): JsonResponse
    {
        $storage = new UserRepository($this->userRepository);

        $users = $storage->all();

        //$serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        // $usersApi = $serializer->normalize($users, 'json', [
        //   AbstractNormalizer::IGNORED_ATTRIBUTES => ['id'],
        // ]);

        $usersApi = $this->serializer->normalize($users, 'json', [
            'groups' => ['user-list']
        ]);

        return new JsonResponse($usersApi);
    }
}
