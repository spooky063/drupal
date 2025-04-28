<?php

declare(strict_types=1);

namespace Drupal\posts_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Database\StatementInterface;
use Drupal\posts_api\PostList;
use Exception;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class PostListController extends ControllerBase
{
    public const int LIMIT = 10;

    public function __construct(
        public Connection $connection,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::LIMIT);

        $sql = <<<SQL
    WITH params AS (
      SELECT
        :page::int AS page,
        :limit::int AS nb_per_page
    ),
    last_seen AS (
      SELECT id
      FROM {post_node_with_author_json}
      ORDER BY id
      LIMIT 1 OFFSET (SELECT nb_per_page * (page - 1) FROM params)
    ),
    posts_data AS (
      SELECT *
      FROM {post_node_with_author_json}
      WHERE id >= (SELECT id FROM last_seen)
      ORDER BY id
      LIMIT (SELECT nb_per_page FROM params)
    )
    SELECT
      (SELECT json_agg(posts_data) FROM posts_data) AS posts,
      (SELECT COUNT(id) FROM {post_node_with_author_json}) AS total;
    SQL;

        $query = $this->connection->query($sql, [':page' => $page, ':limit' => $limit]);

        if (!$query instanceof StatementInterface) {
            throw new Exception('Error with listing posts query.');
        }

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $posts = PostList::create($result['posts'], $result['total']);

        return new JsonResponse($posts);
    }
}
