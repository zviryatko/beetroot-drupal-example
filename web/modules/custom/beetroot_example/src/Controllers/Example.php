<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\beetroot_example\Forms\ExampleForm;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Symfony\Component\HttpFoundation\JsonResponse;

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
  public function view() {
    $config = \Drupal::config('beetroot_example.settings');
    return [
      '#markup' => $config->get('important_text'),
    ];
  }

  public function form() {
    $form_state = new FormState();
    $form = \Drupal::formBuilder()->buildForm(ExampleForm::class, $form_state);
    return $form;
  }

  public function autocomplete(\Symfony\Component\HttpFoundation\Request $request) {
    $q = $request->query->get('q');
    $storage = $this->entityTypeManager()->getStorage('node');
    $ids = $storage->getQuery()
      ->condition('title', "%{$q}%", 'LIKE')
      ->condition('status', 1)
      ->execute();
    if (empty($ids)) {
      return new JsonResponse([]);
    }
    $nodes = $storage->loadMultiple($ids);
    $results = [];
    foreach ($nodes as $node) {
      $results[] = $node->label() . ' (' . $node->id() . ')';
    }
    return new JsonResponse($results);
  }
}
