<?php

declare(strict_types=1);

namespace Drupal\routes\Action;

use ArrayIterator;
use Drupal\Component\Serialization\Yaml;
use Drupal\Core\Extension\Extension;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\routes\Route as ExampleRoute;
use Symfony\Component\Routing\Route;

final class GetRoutes
{
    /**
     * @return ExampleRoute[]
     */
    public function execute(): array
    {
        /** @var RouteProviderInterface $routesProvider */
        $routesProvider = \Drupal::service('router.route_provider');
        /** @var Route[] $routes */
        $routes = $routesProvider->getAllRoutes();
        return $this->getRouteListProperties($routes);
    }

    /**
     * @param array|ArrayIterator<string, Route> $routes
     * @return ExampleRoute[]
     */
    private function getRouteListProperties(array|ArrayIterator $routes): array
    {
        $routeProperties = $this->getRouteListWithModuleAndType();

        $routeList = [];
        foreach ($routes as $routeName => $route) {
            $routeProperty = $routeProperties[$routeName] ?? null;
            if (!$routeProperty) {
                continue;
            }

            $hasOptionAdminRoute = $route->hasOption('_admin_route');
            $isAdminRoute = $hasOptionAdminRoute && $route->getOption('_admin_route');

            if (count($route->getMethods()) === 0) {
                $routeList[] = new ExampleRoute(
                    $routeProperty['module'],
                    $routeName,
                    $route->getPath(),
                    $isAdminRoute,
                    'ANY',
                    $routeProperty['type']
                );
            }

            foreach ($route->getMethods() as $method) {
                $routeList[] = new ExampleRoute(
                    $routeProperty['module'],
                    $routeName,
                    $route->getPath(),
                    $isAdminRoute,
                    $method,
                    $routeProperty['type']
                );
            }
        }
        return $routeList;
    }

    /**
     * @return array<array{module: string, type: string}>
     */
    private function getRouteListWithModuleAndType(): array
    {
      /** @var ModuleHandlerInterface $moduleHandler */
        $moduleHandler = \Drupal::service('module_handler');
      /** @var Extension[] $moduleList */
        $moduleList = $moduleHandler->getModuleList();

        $moduleRouteList = [];
        foreach ($moduleList as $name => $module) {
            $routingFilePath =  DRUPAL_ROOT . DIRECTORY_SEPARATOR . $module->getPath();
            $routingFilePath .= DIRECTORY_SEPARATOR . $name . '.routing.yml';
            if (!file_exists($routingFilePath)) {
                continue;
            }

            $routingFileContents = @file_get_contents($routingFilePath);
            if ($routingFileContents === false) {
                continue;
            }
          /** @var array<string, Route> $moduleRoutes */
            $moduleRoutes = Yaml::decode($routingFileContents);

            $type = 'core';
            if (str_starts_with($module->getPathname(), 'modules/custom')) {
                $type = 'custom';
            } elseif (str_starts_with($module->getPathname(), 'modules/contrib')) {
                $type = 'contrib';
            }

            foreach ($moduleRoutes as $routeName => $route) {
                $moduleRouteList[$routeName] = [
                'module' => (string) $name,
                'type' => $type,
                ];
            }
        }

        return $moduleRouteList;
    }
}
