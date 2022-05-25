<?php

namespace Drupal\beetroot_example;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a beetroot example entity type.
 */
interface BeetrootExampleInterface extends ConfigEntityInterface {

  public function getType(): string;

  public function getPlugins(): array;
}
