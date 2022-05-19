<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\beetroot_example\Forms\ExampleForm;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Drupal\node\Entity\Node;
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
    $nodes = Node::loadMultiple();
    $output = [];
    $i = 0;
    foreach ($nodes as $node) {
      $i++;
      $links = [];
      if ($node->hasField('field_related_news')) {
        /** @var \Drupal\node\NodeInterface[] $related */
        $related = $node->get('field_related_news')->referencedEntities();
        foreach ($related as $item) {
          $links[] = [
            '#theme' => 'beetroot_example_news_link',
            '#url' => $item->toUrl('canonical')->toString(),
            '#title' => $item->label(),
          ];
        }
      }
      $theme = 'beetroot_example_news';
      if (count($nodes) == $i) {
        $theme = 'beetroot_example_news__last';
      }
      $output[] = [
        '#theme' => $theme,
        '#title' => $node->label(),
        '#content' => $node->get('body')->view(['label' => 'hidden']),
        '#links' => $links,
        '#type' => $node->bundle(),
      ];
    }
    return $output;
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
