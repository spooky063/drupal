<?php

declare(strict_types=1);

namespace Drupal\request_validation_to_object\Service;

use Drupal\request_validation_to_object\Dto\PostDto;

class PostService
{
    public PostDto $data;

    public function __construct(PostDto $data)
    {
        $this->data = $data;
    }

    /**
     * @todo Do something with the datas
     */
    public function saveData(): void
    {
    }
}
