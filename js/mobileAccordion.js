/**
 * @file
 * Mobile Accordion for long content.
 */
 (function($, Drupal, window, document, undefined) {

  'use strict';

  let headerClasses = [
    'ui-accordion-header',
    'ui-helper-reset',
    'ui-state-default',
    'ui-corner-all'
  ];
  let bodyClasses = [
    'ui-accordion-content',
    'ui-helper-reset',
    'ui-widget-content',
    'ui-corner-bottom'
  ];

  let resetAccordion = function() {
    let $body = $('article.view-mode-full h2').parent();
    $body.find('.ui-accordion-content').children().first().unwrap();
    $body.find('h2').removeClass(headerClasses.join(' '));
  }

  Drupal.afsaResponsive.addAction('mobile', function() {
    let $body = $('article.view-mode-full h2').parent();

    if ($body.length === 0) {
      return;
    }

    $body.find('h2')
      .each(function() {
        var $set = $(this).nextUntil('h2');
        $set.wrapAll($('<div />', {
          class: bodyClasses.join(' '),
          style: 'display: none'
        }));
        $(this).addClass(headerClasses.join(' '));
      })
      .click(function() {
        $(this).next('.ui-accordion-content').slideToggle();
      });
  });

  Drupal.afsaResponsive.addAction('tablet', resetAccordion);
  Drupal.afsaResponsive.addAction('desktop', resetAccordion);

})(jQuery, Drupal, this, this.document);
