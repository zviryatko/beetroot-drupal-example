<?php

namespace Drupal\beetroot_example\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the example content entity edit forms.
 */
class ExampleContentForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New example content %label has been created.', $message_arguments));
        $this->logger('beetroot_example')->notice('Created new example content %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The example content %label has been updated.', $message_arguments));
        $this->logger('beetroot_example')->notice('Updated example content %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.example_content.canonical', ['example_content' => $entity->id()]);

    return $result;
  }

}
