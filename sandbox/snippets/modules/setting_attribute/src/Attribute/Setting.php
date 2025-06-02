<?php

declare(strict_types=1);

namespace Drupal\setting_attribute\Attribute;

use Drupal\Component\Plugin\Attribute\AttributeBase;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class Setting extends AttributeBase
{
    public function __construct(
        public readonly string $key,
    ) {
    }
}
