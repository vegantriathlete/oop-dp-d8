<?php

/**
 * @file
 * Contains \Drupal\presentation\DisplaySlideImage
 */

namespace Drupal\presentation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\presentation\PresentationManagerServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Display a given image for a given slide
 */
class DisplaySlideImage extends ControllerBase {

  /**
   * Presentation Manager Service.
   *
   * @var \Drupal\presentation\PresentationManagerServiceInterface
   */
  protected $presentationManagerService;

  /**
   * {@inheritdoc}
   */
  public function __construct(PresentationManagerServiceInterface $presentation_manager_service) {
    $this->presentationManagerService = $presentation_manager_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('presentation.presentation_manager_service')
    );
  }

  /**
   * Display a slide image
   *
   * @param \Drupal\node\Entity\Node $node
   *   The fully loaded node entity
   * @param integer $delta
   *   The image instance to load
   *
   * @return array $render_array
   *   The render array
   */
  public function displaySlideImage(Node $node, $delta) {
    $images_data = $this->presentationManagerService->getSlideImages($node);
    if (isset($images_data[$delta])) {
      $file = File::load($images_data[$delta]['target_id']);
      $render_array['image_data'] = array(
        '#theme' => 'image_style',
        '#uri' => $file->getFileUri(),
        '#style_name' => 'presentation_large',
        '#alt' => $images_data[$delta]['alt'],
      );
      // @todo: Consider adding previous and next links to get to other images.
    }
    else {
      $render_array['image_data'] = array(
        '#type' => 'markup',
        '#markup' => $this->t('You are viewing @title. Unfortunately, there is no image defined for delta: @delta.', array('@title' => $node->title->value, '@delta' => $delta)),
      );
    }
    return $render_array;
  }
}
