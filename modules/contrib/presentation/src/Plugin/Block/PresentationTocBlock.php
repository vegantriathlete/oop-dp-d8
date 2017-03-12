<?php

namespace Drupal\presentation\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\presentation\PresentationManagerServiceInterface;
use Drupal\presentation\TocTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an table of contents block for the presentation.
 *
 * @Block(
 *   id = "presentation_toc_block",
 *   admin_label = @Translation("Presentation Table of Contents"),
 *   category = @Translation("Lists")
 * )
 */
class PresentationTocBlock extends BlockBase implements ContainerFactoryPluginInterface {

  use TocTrait;

  /**
   * The Presentation Manager Service.
   *
   * @var \Drupal\presentation\PresentationManagerServiceInterface
   */
  protected $presentationManagerService;

  /**
   * Constructs a Presentation ToC object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\presentation\PresentationManagerServiceInterface
   *   $presentation_manager_service
   *   The Presentation Manager Service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PresentationManagerServiceInterface $presentation_manager_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->presentationManagerService = $presentation_manager_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('presentation.presentation_manager_service')
    );
  }

}
