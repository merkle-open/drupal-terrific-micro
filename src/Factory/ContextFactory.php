<?php

namespace Drupal\terrific\Factory;

use Drupal\node\NodeInterface;
use Drupal\magic_context\Model\ContentPage;

final class ContextFactory {
  private $map = [
    'content_page' => ContentPage::class,
  ];

  public function createFromNode(NodeInterface $node) {
    $model = new $this->map[$node->getType()]($node->getFields());
    return $model;
  }
}