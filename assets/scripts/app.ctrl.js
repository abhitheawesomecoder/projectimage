App.controller('dashboard-ctrl', function (ctrl) {
  $('#h39').click(function() {
      $('#button9').click();
   });

    $('#h329').click(function() {
      $('#button29').click();
   });
  $(window).resize(function () {
    window.line.redraw();
  });

  appdat = App.get('impressions');  

  if(appdat.length == 0 ) {

   $("#morris-line-chart").text("Es sind noch keine Bildaufrufe vorhanden.");
   $("#morris-line-chart").css({
            'color' : '#000000',
            'text-align': 'center',
            'margin-top': '163px',
            'font-weight': 'bold',
            'margin-bottom': '163px'
        });

  }else{

  window.line = Morris.Line({
    element: 'morris-line-chart',
    data: appdat,
    xkey: 'day',
    ykeys: ['count'],
    xLabels: 'day',
    xLabelFormat: function (x) {
      return moment(x).format('ll');
    },
    labels: ['Impressionen'],
    lineColors: ['#00B1E1'],
    lineWidth: '2px',
    hideHover: true,
    resize: true,
    redraw: true
  });
  
  }


});

App.controller('listing-ctrl', function (ctrl) {

  var $ctrl = $(ctrl);

  $ctrl.on('click', '.tag-delete-btn', function (e) {
        e.preventDefault();
          if(confirm('Inhalt wirklich löschen?')) {
            var id = $(this).attr('data-product-id');          

             window.location = App.get('base_url') + '/delete-list/'+id;

          }else{


          }
   
 });





});
App.controller('images-ctrl', function (ctrl) {
  var $ctrl = $(ctrl);

  $ctrl.on('click', '.image-delete-btn', function () {
    if(confirm('Bild wirklich löschen?')) {
      var $item = $(this).parents('.image-item');
      var id = $item.data('image-id');

      $.ajax({
        url: App.get('base_url') + '/ajax/delete-image',
        type: 'DELETE',
        data: {image_id: id}
      }).done(function (response) {
        $item.fadeOut(function () {
          $item.remove();
        });
      });
    } else {
      
    }

    return false;
  });

});

App.controller('creator-ctrl', function (ctrl) {

  $("#input-button-image").change(function(){   

      imgsize = this.files[0].size;

      if(imgsize < 1048576) $("#imgsize").val("1"); else $("#imgsize").val("0");

  });

});

App.controller('tagger-ctrl', function (ctrl) {
  /**
   $(window).resize(function () {
    // TODO: update tag positions when the window is resized
  });/**/

  load_update_tagger();

  var $container = $('#tag-imagger');
  var $tagCtrl = $(ctrl);
  var $tagImage = $('#tag-image');
  var $tagModal = $('#TaggerProduct');
  var $codeModal = $('#generate-code-modal');
  var $urlModal = $('#public-url-modal');
  var $codeBox = $codeModal.find('#code-textbox');

  var tags = App.get('tagger_image_tags');
  var oW = App.get('tagger_image').width; // original image width
  var oH = App.get('tagger_image').height; // original image height

  /**
   * Helper for creating a tag marker on the image
   *
   * @param {{Number}} id
   * @param  {[type]} posX
   * @param  {[type]} posY
   * @return {[type]}
   */
  var placeTag = function (id, color, posX, posY) {

    if(color == ' 1') {
      def_color = ' blue';  
    }
    else if(color == ' 2') {
      def_color = ' black';     
    }
    else if(color == ' 3') {
      def_color = ' green';  
    }
    else if(color == ' 4') {
      def_color = ' yellow';   
    }
    else if(color == ' 5') {
      def_color = ' red';   
    }
    else {
      def_color = color;
    }

    var $marker = $('<a />', {
      class: 'tag-image-marker'+def_color,
      title: 'Klicken um Markierung zu löschen'
    }).data('tag-id', id).css({
      // Position X & Y are saved relative to original image sizes
      // since the tagger image is resized, we have to calculate a new posX and posY
      // in relation to its resized width and height
      left: $tagImage.offset().left + parseInt((posX * $tagImage.width()) / oW) + 'px',
      top: $tagImage.offset().top + parseInt((posY * $tagImage.height()) / oH) + 'px'
    });

    $marker.on('click', function () {
      if (confirm('Möchten Sie diese Markierung wirklich löschen?')) {
        $.ajax({
          url: App.get('base_url') + '/ajax/tag-image',
          type: 'DELETE',
          data: {tag_id: id, image_id: App.get('tagger_image').id}
        }).done(function (response) {
          console.log(response);
          tags.push(response.tagger_image_tags);
          //var $products_free_limit = $('#products_free_limit');
          //$products_free_limit.val(parseInt($products_free_limit.val())-1);
          $marker.remove();
          location.reload(true);
        });
      }
    });

   var imageData = App.get('image');
   var imageTags = imageData.tags;


   var code = Handlebars.compile($tagCtrl.find('#tpl-code').html())({
              image: imageData,
              tags_icons: App.get('tags_icons'),
              tags: imageTags,
              base_url: App.get('base_url'),

    });

   $container.html(code);

    //$('body').append($marker);
  };

	/**
	 * convertImgToBase64
	 * @param  {String}   url
	 * @param  {Function} callback
	 * @param  {String}   [outputFormat='image/png']
	 * @author HaNdTriX
	 * @example
		convertImgToBase64('http://goo.gl/AOxHAL', function(base64Img){
			console.log('IMAGE:',base64Img);
		})
	 */
	function readImage(input) {
	    if ( input.files && input.files[0] ) {
	        var FR= new FileReader();
	        FR.onload = function(e) {
	        	localStorage.removeItem("base64-image");
	            localStorage.setItem("base64-image", e.target.result);
	        };       
	        FR.readAsDataURL( input.files[0] );
	    }
	}

	function readIcon(input) {
	    if ( input.files && input.files[0] ) {
	        var FR= new FileReader();
	        FR.onload = function(e) {
	        	localStorage.setItem("base64-icon", '');
	        	localStorage.removeItem("base64-icon");
	            localStorage.setItem("base64-icon", e.target.result);

                          $.ajax({
              url: App.get('base_url') + '/ajax/upload-premium-icon',
              type: 'POST',
              data: {
                image: localStorage.getItem("base64-icon")
              }
                }).done(function (html) {
                  $("#color-div").html(html);
                   $('.color-n').click(function() {

                      $("#iconselect").val(1);

                     });
                });

                
	        };       
	        FR.readAsDataURL( input.files[0] );
	    }
	}

	$("#input-button-image").change(function(){
	    readImage( this );

      imgsize = this.files[0].size;

      if(imgsize < 500000) $("#imgsize").val("1"); else $("#imgsize").val("0");

	});

	$("#input-button-icon").change(function(){

		readIcon( this );

	

	});


  function YouTubeGetID(url){
    var ID = '';
    url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
    if(url[2] !== undefined) {
      ID = url[2].split(/[^0-9a-z_\-]/i);
      ID = ID[0];
    }
    else {
      ID = url;
    }
      return ID;
  }

  //$('#input-price').autoNumeric('init');

  // Create new product button clicked
    $tagModal.find('.btn-save-tag').on('click', function (e) {
      // Save button clicked.
      console.log("save");
      e.preventDefault();

      var $inputTitle = $tagModal.find('#input-title');
      var $inputDescription = $tagModal.find('#input-description');
      var $inputPrice = $tagModal.find('#input-price');
      var $inputUrl = $tagModal.find('#input-url');
      var $inputTitlePaynow = $tagModal.find('#input-title-paynow');
      var $inputDescriptionPaynow = $tagModal.find('#input-description-paynow');
      var $inputPricePaynow = $tagModal.find('#input-price-paynow');
      var $inputPaypalPaynow = $tagModal.find('#input-paypal-paynow');
      var $inputYoutubeVideo = $tagModal.find('#input-youtube-video');
      var $inputPriceVideo = $tagModal.find('#input-price-video');
      var $inputUrlVideo = $tagModal.find('#input-url-video');
      var $inputButton = $tagModal.find('#input-button-name');
      var product_yt = YouTubeGetID($inputYoutubeVideo.val());

      
      // Calculate clicked positions (x & y) in relation to original image sizes.
      var x = oW * parseInt(document.getElementById('pageX').value - $tagImage.offset().left - 5) / $tagImage.width();
      var y = oH * parseInt(document.getElementById('pageY').value - $tagImage.offset().top - 5) / $tagImage.height();

      $.get(App.get('base_url') + '/ajax/premium-account', function(data, status) {
        var products_free_limit = data.products;



      imgsize = parseInt(document.getElementById('imgsize').value);
      if($('#iconselect').val() == 1){
      if(imgsize == 1){

      //  if(products_free_limit == 'unlimited' || products_free_limit < data.free_max_products) {
          if($inputTitle.val() !== '') {
          
            if(YouTubeGetID($inputYoutubeVideo.val()) == '') { var product_yt = ''; } else { var product_yt = YouTubeGetID($inputYoutubeVideo.val()); }
            $.ajax({
              url: App.get('base_url') + '/ajax/tag-image',
              dataType: 'json',
              type: 'POST',
              data: {
                type: 'image',
                color: $tagModal.find('#color').val(),
                pos_y: y,
                pos_x: x,
                image_id: App.get('tagger_image').id,
                product_title: $inputTitle.val(),
                product_description: $inputDescription.val(),
                product_price: $inputPrice.val(),
                product_url: $inputUrl.val(),
                product_youtube: product_yt,
                product_button: $inputButton.val(),
                product_image: localStorage.getItem("base64-image"),
                product_id: parseInt($(this).data('product-id'))
              }
            }).done(function (res) {
              if (res.data) {
                tags.push(res.data);

                // Clear input fields
                $inputTitle.val('');
                $inputDescription.val('');
                $inputPrice.val('');
                $inputUrl.val('');
                $inputTitlePaynow.val('');
                $inputDescriptionPaynow.val('');
                $inputPricePaynow.val('');
                $inputPaypalPaynow.val('');
                $inputYoutubeVideo.val('');
                $inputPriceVideo.val('');
                $inputUrlVideo.val('');
                $inputButton.val('');
                $('#input-button-image').val('');
                localStorage.removeItem("base64-image");

                $tagModal.modal('hide');
                placeTag(res.data.id, ' '+res.data.color, res.data.pos_x, res.data.pos_y);
              }
            });

            $tagModal.data('modal', null);           

          } else {
            alert("Bitte geben Sie weitere Informationen ein.");
          }




       /* } else {
          alert("Ihr Account-Limit ist erreicht. Bitte Upgraden Sie auf Premium.");
          //window.location=App.get('base_url')+"/upgrade";
        }*/

        } else {
            alert("Das Vorschaubild darf nicht größer als 500 KB sein.");
          }
        }else{

           alert("Bitte wählen ein Icon aus!");

        }





      });
    });

    // Create new product button clicked
    $('.btn-save-tag-paynow').on('click.save', function (e) {
      // Save button clicked.
      e.preventDefault();

      var $inputTitle = $tagModal.find('#input-title');
      var $inputDescription = $tagModal.find('#input-description');
      var $inputPrice = $tagModal.find('#input-price');
      var $inputUrl = $tagModal.find('#input-url');
      var $inputTitlePaynow = $tagModal.find('#input-title-paynow');
      var $inputDescriptionPaynow = $tagModal.find('#input-description-paynow');
      var $inputPricePaynow = $tagModal.find('#input-price-paynow');
      var $inputPaypalPaynow = $tagModal.find('#input-paypal-paynow');
      var $inputYoutubeVideo = $tagModal.find('#input-youtube-video');
      var $inputPriceVideo = $tagModal.find('#input-price-video');
      var $inputUrlVideo = $tagModal.find('#input-url-video');


      // Calculate clicked positions (x & y) in relation to original image sizes.
      var x = oW * parseInt(document.getElementById('pageX').value - $tagImage.offset().left - 5) / $tagImage.width();
      var y = oH * parseInt(document.getElementById('pageY').value - $tagImage.offset().top - 5) / $tagImage.height();

      $.get(App.get('base_url') + '/ajax/premium-account', function(data, status) {
        var products_free_limit = data.products;
        if(products_free_limit == 'unlimited' || products_free_limit < data.free_max_products) {
          if($inputTitlePaynow.val() !== '' && $inputDescriptionPaynow.val() !== '' && $inputPaypalPaynow.val() !== '') {

            $.ajax({
              url: App.get('base_url') + '/ajax/tag-image',
              dataType: 'json',
              type: 'POST',
              data: {
                type: 'paynow',
                color: $tagModal.find('#color-paynow').val(),
                pos_y: y,
                pos_x: x,
                image_id: App.get('tagger_image').id,
                product_title: $inputTitlePaynow.val(),
                product_description: $inputDescriptionPaynow.val(),
                product_price: $inputPricePaynow.val(),
                product_paypal: $inputPaypalPaynow.val(),
                product_id: parseInt($(this).data('product-id'))
              }
            }).done(function (res) {
              if (res.data) {
                tags.push(res.data);

                // Clear input fields
                $inputTitle.val('');
                $inputDescription.val('');
                $inputPrice.val('');
                $inputUrl.val('');
                $inputTitlePaynow.val('');
                $inputDescriptionPaynow.val('');
                $inputPricePaynow.val('');
                $inputPaypalPaynow.val('');
                $inputYoutubeVideo.val('');
                $inputPriceVideo.val('');
                $inputUrlVideo.val('');

                $tagModal.modal('hide');
                placeTag(res.data.id, ' '+res.data.color, res.data.pos_x, res.data.pos_y);
              }
            });
          } else {
            alert("Bitte geben Sie weitere Informationen ein.");
          }
        } else {
          alert("Ihr Account-Limit ist erreicht. Bitte Upgraden Sie auf Premium oder Plus.");
          //window.location=App.get('base_url')+"/upgrade";
        }
      });
    });

    // Create new product button clicked
    $tagModal.find('.btn-save-tag-video').on('click.save', function (e) {
      // Save button clicked.
      e.preventDefault();

      var $inputTitle = $tagModal.find('#input-title');
      var $inputDescription = $tagModal.find('#input-description');
      var $inputPrice = $tagModal.find('#input-price');
      var $inputUrl = $tagModal.find('#input-url');
      var $inputTitlePaynow = $tagModal.find('#input-title-paynow');
      var $inputDescriptionPaynow = $tagModal.find('#input-description-paynow');
      var $inputPricePaynow = $tagModal.find('#input-price-paynow');
      var $inputPaypalPaynow = $tagModal.find('#input-paypal-paynow');
      var $inputYoutubeVideo = $tagModal.find('#input-youtube-video');
      var $inputPriceVideo = $tagModal.find('#input-price-video');
      var $inputUrlVideo = $tagModal.find('#input-url-video');

      // Calculate clicked positions (x & y) in relation to original image sizes.
      var x = oW * parseInt(document.getElementById('pageX').value - $tagImage.offset().left - 5) / $tagImage.width();
      var y = oH * parseInt(document.getElementById('pageY').value - $tagImage.offset().top - 5) / $tagImage.height();

      $.get(App.get('base_url') + '/ajax/premium-account', function(data, status) {
        var products_free_limit = data.products;
        if(products_free_limit == 'unlimited' || products_free_limit < data.free_max_products) {
          if($inputYoutubeVideo.val() !== '') {
            $.ajax({
              url: App.get('base_url') + '/ajax/tag-image',
              dataType: 'json',
              type: 'POST',
              data: {
                type: 'video',
                color: $tagModal.find('#color-video').val(),
                pos_y: y,
                pos_x: x,
                image_id: App.get('tagger_image').id,
                product_youtube: YouTubeGetID($inputYoutubeVideo.val()),
                product_description: '',
                product_price: $inputPriceVideo.val(),
                product_url: $inputUrlVideo.val(),
                product_id: parseInt($(this).data('product-id'))
              }
            }).done(function (res) {
              if (res.data) {
                tags.push(res.data);

                // Clear input fields
                $inputTitle.val('');
                $inputDescription.val('');
                $inputPrice.val('');
                $inputUrl.val('');
                $inputTitlePaynow.val('');
                $inputDescriptionPaynow.val('');
                $inputPricePaynow.val('');
                $inputPaypalPaynow.val('');
                $inputYoutubeVideo.val('');
                $inputPriceVideo.val('');
                $inputUrlVideo.val('');

                $tagModal.modal('hide');
                placeTag(res.data.id, ' '+res.data.color, res.data.pos_x, res.data.pos_y);
              }
            });
          } else {
            alert("Bitte geben Sie den Link zum YouTube Video ein.");
          }
        } else {
          alert("Sie haben das Limit für den kostenlosen Account erreicht. Bitte auf Premium upgraden.");
          //window.location=App.get('base_url')+"/upgrade";
        }
      });
    });

  // Clicked a point on the image
  $tagImage.on('click', function (e) {
    console.log("click");
    e.stopPropagation();




    Handlebars.compile($tagCtrl.find('#tpl-code').html())({
      image: App.get('tagger_image'),
      tags_icons: App.get('tags_icons'),
      tags: tags,
      base_url: App.get('base_url')
    });

    document.getElementById('pageX').value = e.pageX;
    document.getElementById('pageY').value = e.pageY;


    // Display the modal
    $("#modal-body").css('display', 'block');
    $("#modal-body-test").css('display', 'none');
     
    $('.color-n').click(function() {

      $("#iconselect").val(1);

     });

    $('#color-blue').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 1;
      document.getElementById('color').checked = true;
    });

    $('#color-black').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 2;
      document.getElementById('color').checked = true;
    });

    $('#color-green').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 3;
      document.getElementById('color').checked = true;
    });

    $('#color-yellow').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 4;
      document.getElementById('color').checked = true;
    });

    $('#color-red').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 5;
      document.getElementById('color').checked = true;
    });

    $('#color-1').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+1;
      document.getElementById('color').checked = true;
    });

    $('#color-2').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+2;
      document.getElementById('color').checked = true;
    });

    $('#color-3').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+3;
      document.getElementById('color').checked = true;
    });

    $('#color-4').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+4;
      document.getElementById('color').checked = true;
    });

    $('#color-5').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+5;
      document.getElementById('color').checked = true;
    });

    $('#color-6').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+6;
      document.getElementById('color').checked = true;
    });

    $('#color-7').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+7;
      document.getElementById('color').checked = true;
    });

    $('#color-8').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+8;
      document.getElementById('color').checked = true;
    });

    $('#color-9').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+9;
      document.getElementById('color').checked = true;
    });

    $('#color-10').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+10;
      document.getElementById('color').checked = true;
    });

    $('#color-11').click(function() {
      $("#color-div .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color').value = 'i'+11;
      document.getElementById('color').checked = true;
    });

    $('#color-blue-video').click(function() {
      $("#color-div-video .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-video').value = 1;
      document.getElementById('color-video').checked = true;
    });

    $('#color-black-video').click(function() {
      $("#color-div-video .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-video').value = 2;
      document.getElementById('color-video').checked = true;
    });

    $('#color-green-video').click(function() {
      $("#color-div-video .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-video').value = 3;
      document.getElementById('color-video').checked = true;
    });

    $('#color-yellow-video').click(function() {
      $("#color-div-video .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-video').value = 4;
      document.getElementById('color-video').checked = true;
    });

    $('#color-red-video').click(function() {
      $("#color-div-video .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-video').value = 5;
      document.getElementById('color-video').checked = true;
    });

    $('#color-blue-paynow').click(function() {
      $("#color-div-paynow .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-paynow').value = 1;
      document.getElementById('color-paynow').checked = true;
    });

    $('#color-black-paynow').click(function() {
      $("#color-div-paynow .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-paynow').value = 2;
      document.getElementById('color-paynow').checked = true;
    });

    $('#color-green-paynow').click(function() {
      $("#color-div-paynow .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-paynow').value = 3;
      document.getElementById('color-paynow').checked = true;
    });

    $('#color-yellow-paynow').click(function() {
      $("#color-div-paynow .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-paynow').value = 4;
      document.getElementById('color-paynow').checked = true;
    });

    $('#color-red-paynow').click(function() {
      $("#color-div-paynow .active").removeClass('active');
      $(this).toggleClass('active');
      document.getElementById('color-paynow').value = 5;
      document.getElementById('color-paynow').checked = true;
    });

    // Select product link clicked
    $(document).on('click', '.select-product', function (e) {
      e.preventDefault();

      $.ajax({
        url: App.get('base_url') + '/ajax/tag-image',
        dataType: 'json',
        type: 'POST',
        data: {
          pos_y: y,
          pos_x: x,
          image_id: App.get('tagger_image').id,
          product_id: parseInt($(this).data('product-id'))
        }
      }).done(function (res) {
        if (res.data) {
          tags.push(res.data);
          $tagModal.modal('hide');
          placeTag(res.data.id, ' '+res.data.color, res.data.pos_x, res.data.pos_y);
        }
      });
    });
  });

  setTimeout(function () {
    // Retrieve and display all existing tags
    for (var key in tags) {
      if (tags.hasOwnProperty(key)) {
        placeTag(tags[key].id, ' '+tags[key].color, tags[key].pos_x, tags[key].pos_y);
      }
    }
  }, 50);

  $tagModal.on('hide.bs.modal', function () {
    // Remove click listener when modal is closed.
    $tagModal.find('.btn-save-tag').off('click.save');
  });

  // ------------------------------------
  // ---- Code Generation
  // ------------------------------------

    $codeBox.val('');

    $codeBox.val(Handlebars.compile($tagCtrl.find('#tpl-code').html())({
      image: App.get('tagger_image'),
      tags_icons: App.get('tags_icons'),
      tags: tags,
      base_url: App.get('base_url')
    }));
  // ------------------------------------
  // ---- Public URL
  // ------------------------------------
  $tagCtrl.find('#btn-public-url').on('click', function (e) {
    e.preventDefault();

    $urlModal.modal();

    $urlModal.find('#url-textbox')
      .val(App.get('image_public_url'));
  });

  // ------------------------------------
  // ---- Modal Search Products
  // ------------------------------------
  $tagModal.find('.btn-search-product').on('click', function (e) {
    e.preventDefault();

    var $container = $tagModal.find('#search-product .list-group');
    var $mask = $tagModal.find('#search-product .maskable');
    var productItem = Handlebars.compile($tagCtrl.find('#tpl-product-item').html());

    $mask.addClass('mask');

    $.ajax({
      url: App.get('base_url') + '/ajax/search-products',
      dataType: 'json',
      data: {term: $tagModal.find('#search-product input').val()}
    }).done(function (response) {
      $container.empty();
      for (var key in response.data) {
        if (response.data.hasOwnProperty(key)) {
          response.data[key].description = response.data[key].description.substr(0, 200);
          $container.append(productItem({
            product: response.data[key]
          }));
        }
      }
    }).always(function () {
      $mask.removeClass('mask');
    });
  });

});

/**
 *
 */
App.controller('page-creator-listing', function (ctrl) {

});

App.controller('install-ctrl', function (ctrl) {
  $('.sidebar').css('min-height', $(document).height());

  var $ctrl = $(ctrl);
  var $contactList = $('#contact-list');
  var $subContacts = $('#contact-list-secondary');

  $subContacts.hide();
  $contactList
    .mouseenter(function () {
      $subContacts.fadeIn();
    }).mouseleave(function () {
      $subContacts.fadeOut();
    });

  var $articleBody = $('.article-body');

  $ctrl.find('.btn-breadcrumb .btn').on('click', function (e) {
    e.preventDefault();
  });

  $ctrl.find('.btn-install').on('click', function (e) {
    $articleBody.removeClass('active');
    $('#installation').addClass('active');
  });
});

/**
 * ---- Preview Image
 * --------------------------------
 */
App.controller('image-ctrl', function (ctrl) {
  var $ctrl = $(ctrl);

  var $container = $ctrl.find('#preview-container');

  var imageData = App.get('image');
  var imageTags = imageData.tags;
  
  var code = Handlebars.compile($ctrl.find('#tpl-code').html())({
    image: imageData,
    tags_icons: App.get('tags_icons'),
    tags: imageTags,
    base_url: App.get('base_url')
  });

  $container.html(code);

  load_update_preview();
});

function load_update_preview() {
    $(window).load(function() {
     $('#loading_container').addClass('hidden');
     $('#preview-container').css('visibility', 'visible');
    });
}

function load_update_tagger() {
    $(window).load(function() {
     $('#loading_container').addClass('hidden');
     $('#tag-imagger').css('visibility', 'visible');
    });
}

function DELETE_COUPON(CODE){
    $.ajax({
        type: "DELETE",
        url: App.get('base_url') + '/ajax/delete-coupon',
        data: "coupon_code=" + CODE,
        success: function(result){
            if(result == 'true') {
                $("#coupon-" + CODE).fadeOut();
            }
        }
    });
    return false;
}