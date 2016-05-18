@extends("layouts.template")

@section("page_title", "Bild bearbeiten")@stop

@section("content")

@if(Auth::user()->premium != '0')
<form id="edit_form" action="" method="post">
<div class="modal" id="tag-modal" role="dialog" aria-hidden="true" style="display: block; top: 80px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#edit-product" data-toggle="tab" style="font-size: 16px;">Produkt bearbeiten</a></li>
                </ul>
                <div class="tab-content">

                    <!-- Section Create Product -->
                    <div class="tab-pane active" id="edit-product">
                        <div class="form-group row">
                            @if($product->title != '')
                            <div class="col-sm-8">
                                <label for="input-title"></label>
                                <input type="text" class="form-control" name="title" id="input-title" value="{{ $product->title }}" placeholder="Titel" maxlength="50" required>
                            </div>
                            @endif
                            @if($product->title == '')
                            <div class="col-sm-8">
                                <label for="input-title"></label>
                                <input type="text" class="form-control" name="youtube" id="input-youtube" value="{{ $product->youtube }}" placeholder="Youtube Video Url" required>
                            </div>
                            @endif

                            <div class="col-sm-4">
                                <label for="input-title"></label>
                                <input type="text" class="form-control" name="price" id="input-price" value="{{ $product->price }}"
                                       placeholder="Preis">
                            </div>
                        </div>
                        @if($product->description != '')
                        <div class="form-group">
                            <label for="input-description"></label>
                            <textarea class="form-control" id="input-description"
                                      placeholder="Beschreibung" maxlength="180" name="description" style="height: 76px;" required>{{ $product->description }}</textarea>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="input-title"></label>
                            <input type="text" class="form-control" id="input-url" name="url" value="{{ $product->url }}"
                                   placeholder="Link zum Produkt"/>
                        </div>

                        <div class="text-right">
                            <a href="{{ URL::to('listing') }}" class="btn btn-default" data-dismiss="modal">Schließen</a>
                            <button type="submit" form="edit_form" class="btn btn-primary btn-save-tag">Speichern</button>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
</form>

@else

    <section id="tagger-ctrl">
        
        <div class="panel panel-default">
            <div class="panel-toolbar clearfix">
               <!--  <h3 class="pull-left padding-xs no-margin">
                    <img src="{{ url() }}/icons/tag.png"> <b>Interaktiver</b> Editor <a href="http://www.imagemarker.com/hilfe.html" target="blank"><img src="../../icons/hilfe.png" border="0"></a>
                </h3> -->
                 <h3 class="pull-left padding-xs no-margin">

                    @if(Auth::user()->premium == 0)

                     <img src="{{ url() }}/icons/tag.png"> <b>Interaktiver</b> Editor (Basic) <a href="http://www.imagemarker.com/hilfe.html" target="blank"><img src="{{ url() }}/icons/hilfe.png" border="0"></a>
              

                    @elseif(Auth::user()->premium == 1)

                     <img src="{{ url() }}/icons/tag.png"> <b>Interaktiver</b> Editor (Premium) <a href="http://www.imagemarker.com/hilfe.html" target="blank"><img src="{{ url() }}/icons/hilfe.png" border="0"></a>
              

                    @else(Auth::user()->premium == 2)

                     <img src="{{ url() }}/icons/tag.png"> <b>Interaktiver</b> Editor (Pro) <a href="http://www.imagemarker.com/hilfe.html" target="blank"><img src="{{ url() }}/icons/hilfe.png" border="0"></a>
              

                    @endif

                    
                </h3>

                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ $publicUrl }}" target="_blank">
                        <i class="fa fa-globe"></i>&nbsp; Bildvorschau
                    </a>

                    <!--<a class="btn btn-primary"
                       href="{{ $image->url }}">
                        <i class="fa fa-image"></i>&nbsp; Originalbild
                    </a>-->

                    <form action="{{ URL::current() }}" method="post" style="display: inline-block">
                        <input type="hidden" name="_action" value="delete_image"/>
                        <button class="btn btn-danger" id="btn-generate-code"
                                onclick="return confirm('Bild wirklich löschen?');">
                            <i class="fa fa-times"></i>&nbsp; Bild löschen
                        </button>
                    </form>
                </div>
            </div>

            <div class="panel-body">
                <div id="tag-image-container">

                    @include("app.partials.listing-edit-modal")


                    <div id="tag-image-holder" style="margin: 0; float: left; max-width: 780px;">
                        <div id="tag-imagger" style="visibility: hidden;"><img src="{{ $image->url }}" id="tag-image" /></div>
                        <style>
                            div#loading_container {
                                width: 780px;
                                background: #FFF;
                                margin: 0 auto;
                                text-align: center;
                                border-radius: 5px;
                                z-index: 99999;
                            }
                        </style>
                        <div id="loading_container"><img width="420" src="{{ url('assets/images/loading.jpg') }}"></div>
                    </div>
                </div>
            </div>
        
        </div>

    
        @include("app.partials.handlebar-templates")
        @include("app.partials.public-url-modal")

    </section>

@endif

@overwrite


@section("styles:end")
<style>
@foreach($tags_icons as $icon)
.tag-image-marker.i{{ $icon->id }} {
  background: url('{{ $icon->image }}');
  opacity: 1.0;
  width: 30px;
  height: 30px;
  background-size: 30px;
  border-radius: 0%!important;
}
@endforeach
</style>
@stop

@section("scripts_lib")
    <script src="{{ url('assets/scripts/handlebars.js') }}"></script>
@overwrite

@section("scripts_app_before")
    <script type="text/javascript">
        App.set('image_public_url', '{{ $publicUrl }}');
        App.set('tagger_image', {{ json_encode($image) }});
        App.set('tagger_image_tags', {{ $image->tags->toJSON() }})

    @foreach($tags_icons as $icon)
        $('#color-{{ $icon->id }}').click(function() {
          $("#color-div .active").removeClass('active');
          $(this).toggleClass('active');
          document.getElementById('color').value = 'i'+{{ $icon->id }};
          document.getElementById('color').checked = true;
        });
    @endforeach
    </script>
@stop