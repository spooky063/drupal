<?php

declare(strict_types=1);

namespace Drupal\setting_attribute;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\Site\Settings;

final class SettingAttributeServiceProvider extends ServiceProviderBase
{
    #[\Override]
    public function register(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new SettingAttributeCompilerPass());
    }

    #[\Override]
    public function alter(ContainerBuilder $container): void
    {
        $settings = Settings::getAll();
        $this->addSettingsParameters($settings, $container);
    }

    private function addSettingsParameters(
        array $settings,
        ContainerBuilder $container,
        string $prefix = 'settings'
    ): void {
        foreach ($settings as $key => $value) {
            $parameterName = $prefix . '.' . $key;

            if (is_array($value)) {
                $container->setParameter($parameterName, $value);
                $this->addSettingsParameters($value, $container, $parameterName);
            } else {
                $container->setParameter($parameterName, $value);
            }
        }
    }
}
