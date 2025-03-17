<?php

declare(strict_types=1);

namespace Drupal\debug;

final class Route
{
    public function __construct(
        public string $moduleName,
        public string $routeName,
        public string $path,
        public bool $isAdminRoute,
        public string $method,
        public string $moduleType,
    ) {
    }
}
