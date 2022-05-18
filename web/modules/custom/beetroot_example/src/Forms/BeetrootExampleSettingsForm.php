<?php

namespace Drupal\beetroot_example\Forms;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class BeetrootExampleSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'beetroot_example_settings';
  }

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames() {
    return ['beetroot_example.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('beetroot_example.settings');

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#default_value' => $config->get('enabled'),
      '#title' => $this->t('Enable Beetroot Academy functions'),
    ];
    $form['settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Beetroot Academy Settings'),
      '#open' => TRUE,
      '#states' => [
        'visible' => [
          ':input[name="enabled"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $form['settings']['important_number'] = [
      '#type' => 'number',
      '#default_value' => $config->get('important_number'),
      '#title' => $this->t('Some important number'),
      '#min' => 1,
      '#max' => 15,
      '#step' => 1,
    ];
    $form['settings']['important_text'] = [
      '#type' => 'textfield',
      '#default_value' => $config->get('important_text'),
      '#title' => $this->t('Some text'),
      '#states' => [
        'enabled' => [
          ':input[name="important_number"]' => ['value' => '15'],
        ],
      ],
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('beetroot_example.settings')
      ->set('enabled', $form_state->getValue('enabled'))
      ->set('important_number', $form_state->getValue('important_number'))
      ->set('important_text', $form_state->getValue('important_text'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
