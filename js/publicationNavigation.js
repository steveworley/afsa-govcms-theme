/**
 * @file
 * Build a fixed sidebar navigation for publications.
 */
(function ($, Drupal, window, document, undefined) {

Drupal.behaviors.publicationNavigation = {
  attach: function(context, settings) {
    $('.node').once('build-nav', function() {
      var $block = $('.publication-navigation.block');
      var $list = $('<ul/>');
      var $li;
      // Start at 2 because we are only working with h2 and h3.
      var depth = 2;

      $block.empty();
      $list.appendTo($block);

      $(this).find('h2, h3').each(function(idx) {
        var $this = $(this);
        var level = $this.prop('tagName').charAt(1);

        $this.before($('<a/>', {'name': 'pub-nav-' + idx}));

        if (level > depth) {
          $li.append('<ul/>');
          $list = $li.find('ul');
        } else if (level < depth) {
          $list = $list.parent();
        }

        $li = $('<li/>', { html: $('<a/>', {text: $(this).text(), href: '#pub-nav-' + idx})});
        $li.appendTo($list);
        depth = level;
      });

      $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
          if (target.length) {
            $('html, body').animate({
              scrollTop: target.offset().top - 100
            }, 1000);
            return false;
          }
        }
      });

    });
  }
};


})(jQuery, Drupal, this, this.document);
