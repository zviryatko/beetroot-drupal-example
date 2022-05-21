<?php

namespace Drupal\beetroot_example;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * TextCleanup plugin manager.
 */
class TextCleanupPluginManager extends DefaultPluginManager {

  /**
   * Constructs TextCleanupPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/TextCleanup',
      $namespaces,
      $module_handler,
      'Drupal\beetroot_example\TextCleanupInterface',
      'Drupal\beetroot_example\Annotation\TextCleanup'
    );
    $this->alterInfo('text_cleanup_info');
    $this->setCacheBackend($cache_backend, 'text_cleanup_plugins');
  }

}
