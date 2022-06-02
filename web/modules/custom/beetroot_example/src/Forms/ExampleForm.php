<?php

namespace Drupal\beetroot_example\Forms;

use Drupal\Component\Utility\Random;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Form example for Academy.
 */
class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'beetroot_example_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#attached']['library'][] = 'beetroot_example/custom';
    $form['#attributes']['id'] = 'example-form';
    $form['group'] = [
      '#title' => $this->t('Group 1'),
      '#type' => 'details',
      '#open' => TRUE,
      '#access' => !($form_state->has('next_page') && $form_state->get('next_page')),
    ];
    $form['group']['name'] = [
      '#type' => 'textfield',
      '#title_display' => 'after',
      '#title' => $this->t('Name'),
      '#prefix' => $this->t('Some uniq title'),
      '#default_value' => (new Random())->word(10),
      '#attributes' => [
        'class' => ['first', 'second'],
        'id' => 'some-id-text',
        'data-foo' => 'bar',
      ],
      '#autocomplete_route_name' => 'example_route_with_form_autocomplete',
    ];
    $form['group']['text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Long text'),
      '#default_value' => (new Random())->paragraphs(),
    ];
    $form['group2'] = [
      '#title' => $this->t('Group 2'),
      '#type' => 'details',
      '#open' => TRUE,
      '#access' => $form_state->has('next_page') && $form_state->get('next_page'),
    ];
    $form['group2']['name'] = [
      '#type' => 'textfield',
      '#title_display' => 'after',
      '#title' => $this->t('Name 2'),
      '#default_value' => (new Random())->word(10),
    ];
    $form['group2']['number'] = [
      '#title' => $this->t('Number'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 9999,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['prev'] = [
      '#type' => 'submit',
      '#submit' => ['::submitPrev'],
      '#value' => $this->t('Prev step'),
      '#access' => ($form_state->has('next_page') && $form_state->get('next_page')),
      '#ajax' => [
        'callback' => '::refresh',
        'wrapper' => 'example-form',
      ],
    ];
    $form['actions']['next'] = [
      '#type' => 'submit',
      '#submit' => ['::submitNext'],
      '#value' => $this->t('Next step'),
      '#access' => !($form_state->has('next_page') && $form_state->get('next_page')),
      '#ajax' => [
        'callback' => '::refresh',
        'wrapper' => 'example-form',
      ],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#access' => ($form_state->has('next_page') && $form_state->get('next_page')),
    ];

    return $form;
  }

  /**
   * Ajax callback for the color dropdown.
   */
  public function refresh(array $form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation is optional.
    $text = $form_state->getValue('text');
    if (strlen($text) < 100) {
      $form_state->setErrorByName('text',
        $this->t('Text must be more than 100 chars.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $number = $form_state->getValue('number');
    $params = [
      'type' => 'article',
      'title' => $form_state->getValue('name'),
      'body' => $form_state->getValue('text'),
    ];
    $operations = [];
    foreach (range(1, $number) as $i) {
      $arg1 = $params;
      $arg1['title'] .= ' - ' . $i;
      $operations[] = ['\Drupal\beetroot_example\Forms\ExampleForm::createNode', [$arg1]];
    }
    batch_set([
      'title' => $this->t('Node creation'),
      'operations' => $operations,
    ]);
  }

  public static function createNode(array $params) {
    $node = Node::create($params);
    $node->save();
    \Drupal::messenger()->addStatus('Node added.');
  }

  /**
   * Ajax submit handler for next step.
   *
   * @param array $form
   *   Form build array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function submitNext(array &$form, FormStateInterface $form_state) {
    $form_state->set('next_page', TRUE);
    $form_state->setRebuild();
  }

  /**
   * Ajax submit handler for prev step.
   *
   * @param array $form
   *   Form build array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function submitPrev(array &$form, FormStateInterface $form_state) {
    $form_state->set('next_page', FALSE);
    $form_state->setRebuild();
  }

}
