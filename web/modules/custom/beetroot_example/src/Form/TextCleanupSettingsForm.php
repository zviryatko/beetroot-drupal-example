<?php

namespace Drupal\beetroot_example\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Beetroot Example settings for this site.
 */
class TextCleanupSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'beetroot_example_text_cleanup_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['beetroot_example.text_cleanup.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $options = [];
    /**
     * @var \Drupal\beetroot_example\TextCleanupPluginManager $manager
     */
    $manager = \Drupal::service('plugin.manager.text_cleanup');
    foreach ($manager->getDefinitions() as $pluginId => $pluginDefinition) {
      $options[$pluginId] = $pluginDefinition['label'];
    }
    $form['plugins'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Plugins'),
      '#default_value' => $this->config('beetroot_example.text_cleanup.settings')
        ->get('plugins'),
      '#options' => $options,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('beetroot_example.text_cleanup.settings')
      ->set('plugins', $form_state->getValue('plugins'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
