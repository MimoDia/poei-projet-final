/**
 * @file
 * Stik Up menu script.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.StickUpBehavior = {
    attach: function (context, settings) {
      var docWidth = $('body').find('.container').width();

      function addEventsFunction() {
        var documentScroll = 0;
        var thisOffsetTop = 0;
        var thisOuterHeight = 0;
        var _document = $(document);
        var pseudoBlock;
        var _this = $('.stickUpTop');

        $('<div class="pseudoStickyBlock"></div>').insertAfter(_this);
        pseudoBlock = $('.pseudoStickyBlock');
        pseudoBlock.css({position: 'relative', display: 'block'});

        thisOffsetTop = parseInt(_this.offset().top);
        var _window = $(window);
        thisOuterHeight = parseInt(_this.outerHeight(true));
        _document.on('scroll', function () {
          documentScroll = parseInt(_window.scrollTop());
          var docWidth = $('body').find('.container').width();
          if (thisOffsetTop < documentScroll && docWidth > 780) {
            _this.addClass('isStuck');
            if ($('body').hasClass('toolbar-fixed') && $('body').hasClass('toolbar-horizontal')) {
              _this.css({position: 'fixed', top: '85px'});
            }
            else {
              _this.css({position: 'fixed', top: 0});
            }
            pseudoBlock.css({height: thisOuterHeight});
          }
          else {
            _this.removeClass('isStuck');
            _this.css({position: 'relative', top: 0});
            pseudoBlock.css({height: 0});
          }
        }).trigger('scroll');
      }

      if (docWidth > 780) {

        addEventsFunction();

      }
    }
  };
})(jQuery);
