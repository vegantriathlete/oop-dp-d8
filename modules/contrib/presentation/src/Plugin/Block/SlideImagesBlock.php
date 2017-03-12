<?php

namespace Drupal\presentation\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\presentation\PresentationManagerServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides thumbnails of slide images.
 *
 * @Block(
 *   id = "presentation_slide_images",
 *   admin_label = @Translation("Slide Images"),
 *   category = @Translation("Image Display"),
 *   context = {
 *     "node" = @ContextDefinition(
 *       "entity:node",
 *       label = @Translation("Current Node")
 *     )
 *   }
 * )
 *
 */
class SlideImagesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Presentation Manager Service.
   *
   * @var \Drupal\presentation\PresentationManagerServiceInterface
   */
  protected $presentationManagerService;

  /**
   * Constructs Presentation Manager navigation block object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\presentation\PresentationManagerServiceInterface
   *   $personalization_ip_service
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
  public function build() {
    $build = array();
    $node = $this->getContextValue('node');

    $images_data = $this->presentationManagerService->getSlideImages($node);

    foreach ($images_data as $key => $image_data) {

      $file = File::load($image_data['target_id']);
      $link_text = [
        '#theme' => 'image_style',
        '#uri' => $file->getFileUri(),
        '#style_name' => 'presentation_thumbnail',
        '#alt' => $image_data['alt'],
      ];
      $url = Url::fromRoute('presentation.display_slide_image', array('node' => $node->nid->value, 'delta' => $key));
      $options = array(
        'attributes' => array(
          'class' => array(
            'use-ajax',
          ),
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode([
            'width' => 1080,
          ]),
        ),
      );
      $url->setOptions($options);
      $list_items[] = [
        '#type' => 'markup',
        '#markup' => Link::fromTextAndUrl(drupal_render($link_text), $url)
                     ->toString(),
      ];
    }
    if ($list_items) {
      $build['list'] = [
        '#theme' => 'item_list',
        '#items' => $list_items,
      ];
      $build['#attached']['library'][] = 'core/drupal.dialog.ajax';
      return $build;
    }
  }
}
