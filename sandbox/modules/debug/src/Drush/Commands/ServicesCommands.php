<?php

declare(strict_types=1);

namespace Drupal\debug\Drush\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drush\Attributes as CLI;
use Drush\Commands\AutowireTrait;
use Drush\Commands\DrushCommands;

final class ServicesCommands extends DrushCommands
{
    use AutowireTrait;

    /**
     * List all services inside Drupal container.
     */
    #[CLI\Command(name: 'debug:services', aliases: ['sdebug'])]
    #[CLI\Usage(name: 'debug:services', description: 'List all services inside container.')]
    #[CLI\Usage(name: 'debug:services --filter=\'serviceId=entity_type\'')]
    #[CLI\FieldLabels(labels: [
        'serviceId' => 'Service Id',
        'class' => 'Class',
    ])]
    #[CLI\DefaultTableFields(fields: [
        'serviceId',
        'class',
    ])]
    #[CLI\FilterDefaultField(field: 'serviceId')]
    public function list(): RowsOfFields
    {
      $container = \Drupal::getContainer();
      $services = $container->getServiceIds();
      sort($services);

      $rows = [];
      foreach ($services as $serviceId) {
          $service = $container->get($serviceId);
          $class = get_class($service);

          $rows[] = [
            'serviceId' => $serviceId,
            'class' => sprintf('%s:class', $class),
          ];
      }

      return new RowsOfFields($rows);
    }
}
