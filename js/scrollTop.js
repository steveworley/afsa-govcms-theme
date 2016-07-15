/**
 * @file
 * Contains scroll to top functionality.
 */

 (function ($, Drupal, window, document, undefined) {


  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.scrollTop = {
    attach: function(context, settings) {
      $('body').once('scrollTop', function() {

        var $el = $('.js__scroll_top');

        $(window).on('scroll', function() {
          // If we have scrolled we should show the scroll to top link.
          $(this).scrollTop() > 50 ? $el.fadeIn('slow') : $el.fadeOut('slow');
        });

        $el.on('click', function() {
          // When clicked animate window scroll to top.
          $('body, html').animate({scrollTop: 0}, 500);
        });
      });
    }
  };


 })(jQuery, Drupal, this, this.document);
