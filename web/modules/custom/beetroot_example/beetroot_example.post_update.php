<?php

/**
 * @file
 * Post update scripts.
 */

use Drupal\block_content\Entity\BlockContent;
use Drupal\node\Entity\Node;

/**
 * Add new custom page.
 */
function beetroot_example_post_update_create_some_page(&$sandbox) {
  Node::create(
    [
      'type' => 'page',
      'title' => 'Some page',
      'status' => 1,
    ]
  )->save();
  \Drupal::messenger()->addMessage('Added Some page.');
}

/**
 * Add test block.
 */
function beetroot_example_post_update_add_test_block(&$sandbox) {
  BlockContent::create(
    [
      "uuid" => "a0c18c7d-9bb7-4ecf-931d-7f954e0da6a9",
      "type" => "basic",
      "status" => "1",
      "info" => "Test block",
      "reusable" => "1",
      "body" => [
        [
          "value" => "<p>test</p>\r\n",
          "summary" => "",
          "format" => "basic_html",
        ],
      ],
    ]
  )->save();
}
