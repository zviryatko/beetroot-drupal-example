<?php

namespace Drupal\beetroot_example\Plugin\rest\resource;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Represents NodesListResourse records as resources.
 *
 * @RestResource (
 *   id = "beetroot_example_nodes_list",
 *   label = @Translation("Nodes List"),
 *   uri_paths = {
 *     "canonical" = "/api/nodes"
 *   }
 * )
 */
class NodesListResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the record.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   */
  public function get(Request $request) {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $storage->getQuery()
      ->condition('status', 1)
      ->range(0, 10)
      ->execute();
    $data = [];
    $cache = new CacheableMetadata();
    if (!empty($ids)) {
      foreach ($storage->loadMultiple($ids) as $node) {
        $cache->addCacheableDependency($node);
        $url = $node->toUrl()->toString(TRUE);
        $cache->addCacheableDependency($url);
        $data[] = [
          'title' => $node->label(),
          'id' => $node->id(),
          'url' => $url->getGeneratedUrl(),
        ];
      }
    }
    $response = new ResourceResponse($data);
    $response->addCacheableDependency($cache);
    return $response;
  }

  /**
   * {@inheritDoc}
   */
  protected function getBaseRouteRequirements($method) {
    return [
      '_access' => 'TRUE',
    ];
  }

}
