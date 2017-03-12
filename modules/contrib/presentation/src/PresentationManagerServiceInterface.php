<?php

namespace Drupal\presentation;

/**
 * Interface that defines what functionality a presentation manager must
 * provide.
 */
interface PresentationManagerServiceInterface {

  /**
   * Create a table of contents of all the slides
   *
   * @return array $slides
   *   Associative array keyed by NID in the order the slides appear.
   *   Each element contains an associative array keyed by property.
   *   $slides[NID] =
   *     [
   *       'title' => The unsanitized node title,
   *     ]
   */
  public function createToc();

  /**
   * Retrieve the previous slide in the presentation
   *
   * @param \Drupal\node\NodeInterface $slide
   *   The fully loaded slide
   * @return \Drupal\node\NodeInterface $node
   *   The fully loaded previous node
   */
  public function getPreviousSlide($slide);

  /**
   * Retrieve the next slide in the presentation
   *
   * @param \Drupal\node\NodeInterface $slide
   *   The fully loaded slide
   * @return \Drupal\node\NodeInterface $node
   *   The fully loaded next node
   */
  public function getNextSlide($slide);

  /**
   * Retrieve the images attached to a slide.
   *
   * @param \Drupal\node\NodeInterface $slide
   *   The fully loaded slide
   * @return array $image_data
   */
  public function getSlideImages($slide);

}
