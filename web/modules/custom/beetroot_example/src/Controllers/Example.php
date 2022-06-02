<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\beetroot_example\Forms\ExampleForm;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\SettingsCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Drupal\Core\Security\TrustedCallbackInterface;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeTypeInterface;
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

  public function api(Request $request) {
    $response = new AjaxResponse();
    $links = array_map(function (NodeTypeInterface $type) {
      return [
        '#type' => 'link',
        '#title' => $this->t('Node add %type', ['%type' => $type->label()]),
        '#url' => Url::fromRoute('node.add', ['node_type' => $type->id()]),
        '#attributes' => [
          'class' => ['use-ajax'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode([
            'width' => 'wide',
          ]),
        ],
      ];
    }, NodeType::loadMultiple());
    $links[] = [
      '#type' => 'link',
      '#title' => $this->t('Custom form'),
      '#url' => Url::fromRoute('example_route_with_form'),
      '#attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode([
          'width' => 'wide',
        ]),
      ],
    ];
    $element = [
      '#theme' => 'item_list',
      '#items' => $links,
      '#attributes' => ['id' => Html::getUniqueId('items-list')],
      '#attached' => [
        'library' => ['beetroot_example/custom'],
        'drupalSettings' => [
          'foo' => 'bar',
        ],
      ],
    ];
    $response->addCommand(new HtmlCommand('#ajax-wrapper', $element));
    $response->addCommand(new MessageCommand('Test message'));
    return $response;
  }

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
}
