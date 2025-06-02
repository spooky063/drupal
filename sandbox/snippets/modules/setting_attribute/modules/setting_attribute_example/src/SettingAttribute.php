<?php

declare(strict_types=1);

namespace Drupal\setting_attribute_example;

use Drupal\setting_attribute\Attribute\Setting;

final class SettingAttribute
{
    public function __construct(
        #[Setting('config_sync_directory')]
        public string $value
    ) {
    }
}
