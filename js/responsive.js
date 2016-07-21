/**
 * @file
 * Bootstrap for responsive
 */

(function($, Drupal, window, document, undefined) {

  'use strict';


  Drupal.afsaResponsive = {

    actions: {
      mobile: [],
      desktop: [],
      tablet: [],
    },

    getBreakpoint: function() {
      return window.getComputedStyle(document.querySelector('body'), ':before').getPropertyValue('content').replace(/\"/g, '')
    },

    addAction: function(breakpoint, callback) {
      this.actions[breakpoint].push(callback);
      return this.actions[breakpoint].length - 1;
    },

    doActions: function(breakpoint, context, settings) {
      if (!this.actions[breakpoint]) {
        console.error('Undefined breakpoint ' + breakpoint);
        return;
      }

      $.each(this.actions[breakpoint], function(idx, action) {
        if (typeof action == 'function') {
          action.apply(this, context, settings);
        }
      });
    }
  }

  Drupal.behaviors.afsaResponsive = {
    attach: function(context, settings) {
      if ($('body').hasClass('afsa-responsive')) {
        return;
      }

      let breakpoint = Drupal.afsaResponsive.getBreakpoint();

      Drupal.afsaResponsive.doActions(breakpoint, context, settings);

      $('body').addClass('afsa-responsive');
      $(window).on('resize', function() {
        if (breakpoint != Drupal.afsaResponsive.getBreakpoint()) {
          breakpoint = Drupal.afsaResponsive.getBreakpoint();
          Drupal.afsaResponsive.doActions(breakpoint, context, settings);
        }
      });
    }
  }

})(jQuery, Drupal, this, this.document);
