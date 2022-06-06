<?php

namespace Drupal\beetroot_example;

/**
 * An example of dependencies.
 */
class Foo {

  /**
   * List of services.
   *
   * @var FooServiceInterface[]
   */
  protected array $services;

  /**
   * Callback to collect services.
   */
  public function addService(FooServiceInterface $service) {
    $this->services[] = $service;
  }

  /**
   * Do something.
   */
  public function execute() {
    foreach ($this->services as $service) {
      $service->execute();
    }
  }

}
