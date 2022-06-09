<?php

namespace Drupal\Tests\beetroot_example\Unit;

use Drupal\beetroot_example\Plugin\TextCleanup\AddDots;
use Drupal\Tests\UnitTestCase;

class AddDotsTest extends UnitTestCase {

  /**
   * @dataProvider dataProviderFunction
   */
  public function testDotsAdded(string $original, string $expected) {
    $plugin = new AddDots([], '', '');
    $text = $plugin->cleanUp($original);
    $this->assertEquals($expected, $text);
  }

  public function dataProviderFunction() {
    return [
      'Dot added' => ['lorem ipsum', 'lorem ipsum.'],
      'Addition dot not added' => ['lorem ipsum.', 'lorem ipsum.'],
      'Multiple lines dots added' => ["lorem ipsum\r\nlorem ipsum\r\nlorem ipsum", "lorem ipsum.\r\nlorem ipsum.\r\nlorem ipsum."],
    ];
  }

}
