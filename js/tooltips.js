/**
 * @file
 * Bind tooltips to the sup > a's. This uses the Tooltipster.
 *
 * @see http://iamceege.github.io/tooltipster/
 */

(function($, Drupal, window, document, undefined) {

  'use strict';

  Drupal.behaviors.afsa_tooltips = {
    attach: function(context, settings) {
      // Determine the API path.
      let url = window.location.origin + settings.basePath + 'glossary.xml';

      $('sup a', context).tooltipster({
        content: 'Loading...',
        // This option allows the tooltip to be interacted with eg. click links.
        interactive: true,
        contentAsHTML: true,
        maxWidth: 500,
        updateAnimation: null,
        functionBefore: function(instance, helper) {
          let $origin = $(helper.origin);

          if ($origin.data('loaded') === true) {
            // Prevent multiple AJAX requests from happening per tooltip.
            return;
          }

          // Attempt to match the last part of a URL which will typically
          // relate to a glossary term and use this as the title.
          let title = $origin.attr('href').split('/').slice(-1)[0];
          title = title.replace(/-/g, ' ');

          if ($origin.attr('name') != '') {
            // As an override the title for the Glossary term can be set
            // explicitly via the name attribute of the link, which can be
            // updated via the UI.
            title = $origin.attr('name');
          }

          // Perform an AJAX request to the compiled URL.
          $.get(url + '?title=' + title, function(data) {
            let $data = $(data);
            $origin.data('loaded', true);

            if ($data.find('item:first').length > 0) {
              instance.content($data.find('item:first').find('description').text());
            } else {
              instance.content('No term listed in glossary');
            }
          });
        }
      });
    },
  }

})(jQuery, Drupal, this, this.document);
