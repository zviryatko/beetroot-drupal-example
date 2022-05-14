<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

/**
 * Shows example of controller for Academy.
 */
class Example extends ControllerBase {

  /**
   * Shows Node's body field.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node to show it's body field.
   *
   * @return array
   *   Node view renderable array.
   */
  public function view(NodeInterface $node) {
    return $this->entityTypeManager()
      ->getViewBuilder($node->getEntityTypeId())
      ->viewField($node->get('body'), 'teaser');
  }

}
