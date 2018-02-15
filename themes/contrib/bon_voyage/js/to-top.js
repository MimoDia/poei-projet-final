/**
 * @file
 * Back to Top Button.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.ToTopBehavior = {
    attach: function (context, settings) {

      $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
          $('#to-top').fadeIn();
        }
        else {
          $('#to-top').fadeOut();
        }
      });

      // Scroll body to 0px on click.
      $('#to-top').click(function () {
        $('body,html').stop(false, false).animate({
          scrollTop: 0
        }, 800);
        return false;
      });
    }
  };
})(jQuery);
