<?php

namespace Drupal\beetroot_example;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of beetroot examples.
 */
class BeetrootExampleListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['id'] = $this->t('Machine name');
    $header['type'] = $this->t('Type');
    $header['plugins'] = $this->t('Plugins');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /**
     * @var \Drupal\beetroot_example\BeetrootExampleInterface $entity
     */
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['type'] = $entity->getType();
    $row['plugins'] = implode(', ', $entity->getPlugins());
    return $row + parent::buildRow($entity);
  }

}
