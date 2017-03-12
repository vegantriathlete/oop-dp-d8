<?php

/**
 * @file
 * Contains \Drupal\presentation\DisplayToc
 */

namespace Drupal\presentation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\presentation\PresentationManagerServiceInterface;
use Drupal\presentation\TocTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Display a table of contents for the presentation
 */
class DisplayToc extends ControllerBase {
  use TocTrait;

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
}
