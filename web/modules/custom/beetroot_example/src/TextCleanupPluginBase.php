<?php

namespace Drupal\beetroot_example;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for text_cleanup plugins.
 */
abstract class TextCleanupPluginBase extends PluginBase implements TextCleanupInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['title'];
  }

}
