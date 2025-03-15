<?php

declare(strict_types=1);

namespace Drupal\routes\Drush\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\routes\Action\GetRoutes;
use Drush\Attributes as CLI;
use Drush\Commands\AutowireTrait;
use Drush\Commands\DrushCommands;

final class RoutesCommands extends DrushCommands
{
    use AutowireTrait;

    public function __construct()
    {
        parent::__construct();
    }

    #[CLI\Command(name: 'routes:list', aliases: ['rlist'])]
    #[CLI\Usage(name: 'routes:list', description: 'List all routes of the application.')]
    #[CLI\Usage(name: 'routes:list --filter=\'group=custom\'', description: 'List all routes from custom module.')]
    #[CLI\FieldLabels(labels: [
        'group' => 'Group',
        'module_name' => 'Module Name',
        'route_name' => 'Route Name',
        'is_admin_route' => 'Admin Route',
        'route_verb' => 'Method',
        'route_path' => 'Route path'
    ])]
    #[CLI\DefaultTableFields(fields: [
        'group',
        'module_name',
        'route_name',
        'is_admin_route',
        'route_verb',
        'route_path'
    ])]
    #[CLI\FilterDefaultField(field: 'route_name')]
    public function list(): RowsOfFields
    {
        $routes = new GetRoutes();
        $routeList = $routes->execute();

        usort($routeList, function ($a, $b) {
            return [$a->moduleType, $a->moduleName, $a->routeName] <=> [$b->moduleType, $b->moduleName, $b->routeName];
        });

        $rows = [];
        foreach ($routeList as $route) {
            $rows[] = [
                'group' => $route->moduleType,
                'module_name' => $route->moduleName,
                'route_name' => $route->routeName,
                'is_admin_route' => $route->isAdminRoute ? 'true' : 'false',
                'route_verb' => $route->method,
                'route_path' => $route->path,
            ];
        }

        return new RowsOfFields($rows);
    }
}
