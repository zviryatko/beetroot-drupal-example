<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class Example extends ControllerBase {

  public function view() {
    $users = $this->entityTypeManager()->getStorage('user')->loadMultiple();
    $names = [];
    foreach ($users as $user) {
      $names[] = $user->label();
    }
    return new JsonResponse(['hello' => 'world', 'users' => $names]);
  }
}
