(function () {
  var ready = function (callback) {
    if (window.jQuery) {
      callback(jQuery);
    } else {
      window.setTimeout(function () {
        ready(callback);
      }, 100);
    }
  };

  var scriptPath = function () {
    var scripts = document.getElementsByTagName('SCRIPT');
    var path = '';
    if (scripts && scripts.length > 0) {
      for (var i in scripts) {
        if (scripts[i].src && scripts[i].src.match(/\/imgshop\.js$/)) {
          path = scripts[i].src.replace(/(.*)\/assets\/scripts\/imgshop\.js$/, '$1');
          break;
        }
      }
    }
    return path;
  };

  ready(function ($) {

    var inViewPort = function (elem) {
      var docViewTop = $(window).scrollTop();
      var docViewBottom = docViewTop + $(window).height();
      var elemTop = $(elem).offset().top;
      var elemBottom = elemTop + $(elem).height();

      return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    };

    var itemsInViewPort = function (items, callback) {
      var l = items.length;
      for (var i = 0; i < l; i++) {
        if (inViewPort(items[i])) {
          callback(items[i]);
        }
      }
    };

    var TRACK_TYPES = {
      impression: 'impression',
      click: 'click'
    };

    var track = function (type, $image) {
      var tracker = {};

      if (type === TRACK_TYPES.impression && $image.data('imgshop-tracked')) {
        return;
      }

      $image.data('imgshop-tracked', true);

      tracker.type = type;
      tracker.source = encodeURIComponent(document.URL);
      tracker.ua = navigator.userAgent;
      tracker.ip = null;
      tracker.image_id = $image.data('image-id');

      $.ajax({
        url: scriptPath() + '/analytics',
        data: tracker,
        type: 'POST',
        dataType: 'json'
      });
    };

    var imgshopImages = [];

    $('.imgshop').each(function () {
      var $tagger = $(this);
      var $image = $tagger.find('.imgshop-image');
      var oW = parseInt($image.data('original-width'));
      var oH = parseInt($image.data('original-height'));



     /* $tagger.find('.imgshop-tag').each(function (i, tag) {
        $(tag).css({
          left: parseInt(($(this).data('pos-x') * $image.width()) / oW) + 'px',
          top: parseInt(($(this).data('pos-y') * $image.height()) / oH) + 'px'
        }).show();
      });*/
    $tagger.find('.imgshop-tag').each(function (i, tag) {


        $(tag).css({
          left: parseInt(($(this).data('pos-x') * 100 ) / oW) + '%',
          top: parseInt(($(this).data('pos-y') * 100 ) / oH) + '%'
        }).show();
      });

      imgshopImages.push($(this));
    });

    itemsInViewPort(imgshopImages, function (image) {
      track(TRACK_TYPES.impression, image);
    });

    // Track Impressions
    $(window).on('scroll', function () {
      itemsInViewPort(imgshopImages, function (image) {
        track(TRACK_TYPES.impression, image);
      });
    });

    // Track Clicks
    $('.imgshop-tag-product-link').on('click', function () {
      var $image = $(this).parents('.imgshop');
      track(TRACK_TYPES.click, $image);
    });
  });

  //
})();
