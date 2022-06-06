<?php

namespace Drupal\beetroot_example\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;

/**
 * Defines 'admin_notify_worker' queue worker.
 *
 * @QueueWorker(
 *   id = "admin_notify_worker",
 *   title = @Translation("Admin Notify"),
 *   cron = {"time" = 60}
 * )
 */
class AdminNotifyWorker extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($id) {
    /**
     * @var \Drupal\Core\Mail\MailManagerInterface $mailManager
     */
    $mailManager = \Drupal::service('plugin.manager.mail');
    $user = Node::load($id);
    // @todo Send emails
    $storage = \Drupal::entityTypeManager()->getStorage('user');
    /**
     * @var \Drupal\user\UserInterface[] $users
     */
    $users = $storage->loadMultiple($storage->getQuery()
      ->condition('role', 'admin')
      ->execute());
    foreach ($users as $recipient) {
      $mailManager->mail('beetroot_example', 'node_created',
        $recipient->getEmail(), 'en', ['new_user' => $user->label()]);
    }
  }

}
