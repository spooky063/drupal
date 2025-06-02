<?php

declare(strict_types=1);

namespace Drupal\setting_attribute_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

final class SettingController extends ControllerBase
{
    public function autowire(): Response
    {
        $service = \Drupal::getContainer()->get('setting_attribute_example.service_autowire');

        return new Response($service->value);
    }

    public function argument(): Response
    {
        $service = \Drupal::getContainer()->get('setting_attribute_example.service_argument');

        return new Response($service->value);
    }
}
