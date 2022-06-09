<?php

namespace Drupal\Tests\beetroot_example\Functional;

use weitzman\DrupalTestTraits\ExistingSiteBase;

class ApiResponseTest extends ExistingSiteBase {

  public function testNodesList() {
    $user = $this->createUser(['access beetroot example page']);
    $this->drupalLogin($user);
    $node = $this->createNode([
      'type' => 'article',
      'title' => 'Test news'
    ]);
    $response = $this->drupalGet('/api/example/latest');
    $this->assertTrue(FALSE);
  }
}
