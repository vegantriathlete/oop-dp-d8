/**
 * @file
 * Slide behaviors
 */

(function ($, window, Drupal) {

  'use strict';

  /**
   * Reveal <li> items.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the behavior for the block settings summaries.
   */
  Drupal.behaviors.revealListItems = {
    attach: function (context, settings) {

      // Set up the first list item to be displayed and hide the rest
      $('ul.reveal > li').hide().addClass('hidden');
      $('ul.reveal li:first-child').show().removeClass('hidden').addClass('visible');

      // As the list is clicked reveal another item
      $('ul.reveal', context).on('click', function() {
        $('li.hidden').eq(0).show().removeClass('hidden').addClass('visible');
      });

    }
  };

})(jQuery, window, Drupal);
