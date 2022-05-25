<?php

namespace Drupal\beetroot_example\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\beetroot_example\BeetrootExampleInterface;

/**
 * Defines the beetroot example entity type.
 *
 * @ConfigEntityType(
 *   id = "beetroot_example",
 *   label = @Translation("Beetroot Example"),
 *   label_collection = @Translation("Beetroot Examples"),
 *   label_singular = @Translation("beetroot example"),
 *   label_plural = @Translation("beetroot examples"),
 *   label_count = @PluralTranslation(
 *     singular = "@count beetroot example",
 *     plural = "@count beetroot examples",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\beetroot_example\BeetrootExampleListBuilder",
 *     "form" = {
 *       "add" = "Drupal\beetroot_example\Form\BeetrootExampleForm",
 *       "edit" = "Drupal\beetroot_example\Form\BeetrootExampleForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "beetroot_example",
 *   admin_permission = "administer beetroot_example",
 *   links = {
 *     "collection" = "/admin/structure/beetroot-example",
 *     "add-form" = "/admin/structure/beetroot-example/add",
 *     "edit-form" = "/admin/structure/beetroot-example/{beetroot_example}",
 *     "delete-form" = "/admin/structure/beetroot-example/{beetroot_example}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "type",
 *     "plugins",
 *   }
 * )
 */
class BeetrootExample extends ConfigEntityBase implements BeetrootExampleInterface {

  /**
   * The beetroot example ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The beetroot example label.
   *
   * @var string
   */
  protected $label;

  /**
   * The beetroot_example text.
   *
   * @var string
   */
  protected $type;

  protected $plugins;

  public function getPlugins(): array {
    return array_filter($this->plugins);
  }

  public function getType(): string {
    return $this->type;
  }

}
