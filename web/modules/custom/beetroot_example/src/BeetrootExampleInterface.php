<?php

namespace Drupal\beetroot_example;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a beetroot example entity type.
 */
interface BeetrootExampleInterface extends ConfigEntityInterface {

  /**
   * Get type.
   */
  public function getType(): string;

  /**
   * Get plugins list.
   */
  public function getPlugins(): array;

}
