<?php

declare(strict_types=1);

namespace Drupal\posts_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Database\StatementInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

final class PostController extends ControllerBase
{
    public function __construct(
        public Connection $connection,
    ) {
    }

    public function index(int $id): Response
    {
        $sql = <<<SQL
        SELECT row_to_json(post) AS posts
        FROM (
          SELECT *
          FROM {post_node_with_author_json}
          WHERE id=:id
        ) post;
        SQL;

        $query = $this->connection->query($sql, [':id' => $id]);

        if (!$query instanceof StatementInterface) {
            throw new Exception('Error with detail post query.');
        }

        $post = $query->fetchAssoc();

        return new Response($post['posts'], headers: ['Content-Type' => 'application/json']);
    }
}
