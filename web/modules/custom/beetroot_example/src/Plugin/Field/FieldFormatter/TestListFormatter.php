<?php

namespace Drupal\beetroot_example\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * An example of field formatter.
 *
 * @FieldFormatter(
 *   id = "test_list",
 *   label = @Translation("Test List"),
 *   field_types = {
 *     "list_integer",
 *     "list_float",
 *     "list_string",
 *   }
 * )
 */
class TestListFormatter extends FormatterBase {

  /**
   * {@inheritDoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $output = [];
    foreach ($items as $item) {
      $output[] = ['#markup' => $item->value];
    }
    return $output;
  }

}
