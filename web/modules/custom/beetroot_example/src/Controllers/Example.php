<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\beetroot_example\Forms\ExampleForm;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\Core\Url;
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
        /**
         * @var \Drupal\node\NodeInterface[] $related
         */
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

  /**
   * Example of cached response.
   */
  public function cacheExample() {
    $response = \Drupal::httpClient()
      ->request('GET', 'https://catfact.ninja/fact');
    if ($response->getStatusCode() !== 200) {
      return [];
    }
    $fact = json_decode($response->getBody());
    return [
      [
        '#lazy_builder' => [static::class . '::getCurrentTime', []],
        '#create_placeholder' => TRUE,
      ],
      [
        '#markup' => $fact->fact,
        '#cache' => [
          'max-age' => -1,
        ],
      ],
      '#cache' => [
        'max-age' => -1,
      ],
    ];
  }

  /**
   * Example of lazy builder callback.
   */
  public static function getCurrentTime() {
    return [
      '#markup' => \Drupal::time()->getCurrentTime(),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * {@inheritDoc}
   */
  public static function trustedCallbacks() {
    return ['getCurrentTime'];
  }

  /**
   * Example of ajax response.
   */
  public function api(Request $request) {
    $response = new AjaxResponse();
    $element = [
      '#type' => 'container',
      [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'class' => ['custom-react-list'],
        ],
      ],
    ];
    $response->addCommand(new HtmlCommand('#ajax-wrapper', $element));
    return $response;
  }

  /**
   * Example of ajax link.
   */
  public function ajaxLink() {
    return [
      [
        '#theme' => 'container',
        '#attributes' => ['id' => 'ajax-wrapper'],
      ],
      [
        '#type' => 'link',
        '#title' => $this->t('Ajax link'),
        '#url' => Url::fromRoute('example_route_api'),
        '#attributes' => ['class' => ['use-ajax']],
      ],
    ];
  }

  /**
   * Show 10 latest nodes.
   */
  public function latest() {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $storage->getQuery()
      ->range(0, 10)
      ->condition('status', 1)
      ->execute();
    $output = [];
    $nodes = $storage->loadMultiple($ids);
    foreach ($nodes as $node) {
      $output[] = [
        'title' => $node->label(),
        'url' => $node->toUrl('canonical')->toString(),
      ];
    }
    return new JsonResponse($output);
  }

}
