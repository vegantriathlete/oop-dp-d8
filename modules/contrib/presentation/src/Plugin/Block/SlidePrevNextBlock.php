<?php

namespace Drupal\presentation\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\presentation\PresentationManagerServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a navigation block.
 *
 * @Block(
 *   id = "presentation_slide_navigation_block",
 *   admin_label = @Translation("Slide Navigation"),
 *   category = @Translation("Navigation"),
 *   context = {
 *     "node" = @ContextDefinition(
 *       "entity:node",
 *       label = @Translation("Current Node")
 *     )
 *   }
 * )
 *
 */
class SlidePrevNextBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Presentation Manager Service.
   *
   * @var \Drupal\presentation\PresentationManagerServiceInterface
   */
  protected $presentationManagerService;

  /**
   * Constructs a slide navigation block object.
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

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    // By default, the block navigates to the next slide.
    return array(
      'navigation' => 'next',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['navigation'] = array(
      '#type' => 'radios',
      '#title' => $this->t('To which slide to navigate'),
      '#default_value' => $this->configuration['navigation'],
      '#options' => array('next' => $this->t('Next slide'), 'previous' => $this->t('Previous Slide')),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['navigation'] = $form_state->getValue('navigation');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = array();
    $node = $this->getContextValue('node');

    if ($this->configuration['navigation'] == 'next') {
      $slide = $this->presentationManagerService->getNextSlide($node);
    }
    else {
      $slide = $this->presentationManagerService->getPreviousSlide($node);
    }

    if ($slide) {
      $url = Url::fromRoute('entity.node.canonical', array('node' => $slide->nid->value));
      $build['navigation'] = [
        '#type' => 'markup',
        '#markup' => Link::fromTextAndUrl(
          t('@title', array('@title' => $slide->getTitle())),
          $url
        )->toString(),
      ];
      return $build;
    }
  }
}
