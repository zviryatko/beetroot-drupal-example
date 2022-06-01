<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\beetroot_example\Forms\ExampleForm;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Shows example of controller for Academy.
 */
class Example extends ControllerBase implements TrustedCallbackInterface {

  /**
   * Shows Node's body field.
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

  /**
   * Provides example form.
   *
   * @return array
   *   Form build.
   */
  public function form() {
    $form_state = new FormState();
    $form = \Drupal::formBuilder()->buildForm(ExampleForm::class, $form_state);
    return $form;
  }

  /**
   * Provides autocomplete for nodes.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The json response.
   */
  public function autocomplete(Request $request) {
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

  public function cacheExample() {
    $response = \Drupal::httpClient()->request('GET', 'https://catfact.ninja/fact');
    if ($response->getStatusCode() !== 200) {
      return [];
    }
    $fact = json_decode($response->getBody());
    return       [
      '#lazy_builder' => [static::class . '::getCurrentTime', []],
      '#create_placeholder' => TRUE,
    ];
  }

  public static function getCurrentTime() {
    return [
      '#markup' => \Drupal::time()->getCurrentTime(),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  public static function trustedCallbacks() {
    return ['getCurrentTime'];
  }

}
