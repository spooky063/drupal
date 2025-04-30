<?php

declare(strict_types=1);

namespace Drupal\post_api_postgres_view\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Database\StatementInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        if (!$post) {
            return new JsonResponse(sprintf('No entity found for id %d', $id), 400);
        }

        $metadata = new CacheableMetadata();
        $metadata->setCacheTags([sprintf('node:%s', $id)]);
        $metadata->setCacheMaxAge(60);

        $response = new CacheableResponse($post['posts'], 200, ['Content-Type' => 'application/json']);
        $response->addCacheableDependency($metadata);

        return $response;
    }
}
