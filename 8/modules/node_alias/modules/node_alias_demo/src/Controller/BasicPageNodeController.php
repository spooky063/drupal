<?php

declare(strict_types=1);

namespace Drupal\node_alias_demo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\node\Entity\Node;

class BasicPageNodeController extends ControllerBase implements ContainerInjectionInterface
{

    public function index(Node $node): array
    {
        return [
            '#markup' => "You are in node {$node->getTitle()}"
        ];
    }

    public function getTitle(Node $node): string
    {
        return $node->getTitle();
    }
}
