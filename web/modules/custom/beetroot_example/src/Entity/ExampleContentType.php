<?php

namespace Drupal\beetroot_example\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Example Content type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "example_content_type",
 *   label = @Translation("Example Content type"),
 *   label_collection = @Translation("Example Content types"),
 *   label_singular = @Translation("example content type"),
 *   label_plural = @Translation("example contents types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count example contents type",
 *     plural = "@count example contents types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\beetroot_example\Form\ExampleContentTypeForm",
 *       "edit" = "Drupal\beetroot_example\Form\ExampleContentTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\beetroot_example\ExampleContentTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   admin_permission = "administer example content types",
 *   bundle_of = "example_content",
 *   config_prefix = "example_content_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/example_content_types/add",
 *     "edit-form" = "/admin/structure/example_content_types/manage/{example_content_type}",
 *     "delete-form" = "/admin/structure/example_content_types/manage/{example_content_type}/delete",
 *     "collection" = "/admin/structure/example_content_types"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   }
 * )
 */
class ExampleContentType extends ConfigEntityBundleBase {

  /**
   * The machine name of this example content type.
   *
   * @var string
   */
  protected $id;

  /**
   * The human-readable name of the example content type.
   *
   * @var string
   */
  protected $label;

}
