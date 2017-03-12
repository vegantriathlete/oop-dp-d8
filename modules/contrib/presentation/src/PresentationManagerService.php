<?php

namespace Drupal\presentation;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * An implementation of PersonalizationIPServiceInterface.
 */
class PresentationManagerService implements PresentationManagerServiceInterface {

  /**
   * The entity storage for slides.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * Constructs a new Entity Storage Interface.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * {@inheritdoc}
   */
  public function createToc() {
    $slides = [];
    $query = $this->nodeStorage->getQuery()
      ->condition('status', 1)
      ->condition('type', 'slide')
      ->notExists('field_slide_previous');
    $entity_ids = $query->execute();
    $nodes = $this->nodeStorage->loadMultiple($entity_ids);
    // We aren't expecting there to be more than one slide that does not have
    // a previous slide. This logic will handle that situation gracefully,
    // though.
    foreach ($nodes as $node) {
      $slides[$node->nid->value] = [
        'title' => $node->getTitle(),
      ];
      while ($next_node = $this->getNextSlide($node)) {
        $slides[$next_node->nid->value] = [
          'title' => $next_node->getTitle(),
        ];
        $node = $next_node;
      }
    }
    return $slides;
  }

  /**
   * {@inheritdoc}
   */
  public function getPreviousSlide($slide) {
    if (isset($slide->field_slide_previous)) {
      $node = $slide->field_slide_previous->entity;
      return $node;
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getNextSlide($slide) {
    if (isset($slide->field_slide_next)) {
      $node = $slide->field_slide_next->entity;
      return $node;
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getSlideImages($slide) {
    $image_data = [];
    foreach ($slide->field_slide_image as $slide_image) {
      $image_data[] = $slide_image->getValue();
    }
    return $image_data;
  }

}
