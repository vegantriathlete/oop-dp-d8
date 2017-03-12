<?php

namespace Drupal\presentation;

use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides logic to build the table of contents.
 *
 * We are using a trait because we provice both a page and a block that present
 * a table of contents and we don't want to repeat ourselves. It's better to
 * have the code in just one place.
 */
trait TocTrait {
  public function build() {
    $slides = $this->presentationManagerService->createToc();

    foreach ($slides as $nid => $slide) {
      $url = Url::fromRoute('entity.node.canonical', array('node' => $nid));
      $list_items[] = [
        '#type' => 'markup',
        '#markup' => Link::fromTextAndUrl($slide['title'], $url)->toString(),
      ];
    }
    if ($list_items) {
      $build['list'] = [
        '#theme' => 'item_list',
        '#items' => $list_items,
      ];
    }
    else {
      $build['empty'] = [
        '#type' => 'markup',
        '#markup' => t('There are no slides to present.'),
      ];
    }
    $build['#cache']['tags'][] = 'node_list';
    return $build;
  }
}
