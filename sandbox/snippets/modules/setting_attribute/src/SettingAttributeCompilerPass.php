<?php

declare(strict_types=1);

namespace Drupal\setting_attribute;

use Drupal\setting_attribute\Attribute\Setting;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SettingAttributeCompilerPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            if (!$definition->isAutowired()) {
                continue;
            }

            if (!$definition->getClass()) {
                continue;
            }

            $class = $definition->getClass();
            if (!class_exists($class)) {
                continue;
            }

            $constructor = (new ReflectionClass($class))->getConstructor();
            if (!$constructor) {
                continue;
            }

            $arguments = $this->resolveSettingsArguments($constructor, $container);
            if ($arguments !== []) {
                $definition->setArguments($arguments);
            }
        }
    }

    /**
     * @return array<array-key, string>
     */
    private function resolveSettingsArguments(
        ReflectionMethod $constructor,
        ContainerBuilder $container
    ): array {
        $arguments = [];

        foreach ($constructor->getParameters() as $index => $param) {
            $settingAttrs = $param->getAttributes(Setting::class);
            if (!$settingAttrs) {
                continue;
            }

            $key = $settingAttrs[0]->newInstance()->key;
            $paramName = 'settings.' . $key;

            if (!$container->hasParameter($paramName)) {
                throw new RuntimeException(sprintf("Parameter '%s' is missing", $paramName));
            }

            $arguments[$index] = '%' . $paramName . '%';
        }

        return $arguments;
    }
}
