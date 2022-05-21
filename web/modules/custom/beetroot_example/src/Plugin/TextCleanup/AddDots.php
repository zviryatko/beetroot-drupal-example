<?php

namespace Drupal\beetroot_example\Plugin\TextCleanup;

use Drupal\beetroot_example\TextCleanupPluginBase;

/**
 * Plugin implementation of the text_cleanup.
 *
 * @TextCleanup(
 *   id = "add_dots",
 *   label = @Translation("Add dots"),
 *   description = @Translation("Adding dots to each line if not exists.")
 * )
 */
class AddDots extends TextCleanupPluginBase {

  /**
   * {@inheritdoc}
   */
  public function cleanUp(string $text): string {
    $lines = explode("\r\n", $text);
    foreach ($lines as &$line) {
      $line = trim($line);
      if (!str_ends_with($line, '.')) {
        $line .= '.';
      }
    }
    return implode("\r\n", $lines);
  }

}
