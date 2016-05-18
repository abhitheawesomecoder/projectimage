@extends("layouts.blank")

@section("body_class", "preview-image")

@section("header")
@show

@section("content")


    <div id="image-ctrl">
    	<style>
    		div#loading_container {
			    width: 1000px;
			    background: #FFF;
			    margin: 0 auto;
			    text-align: center;
			    border-radius: 5px;
			}
    	</style>
    	<div id="loading_container"><img width="420" src="{{ url('assets/images/loading.jpg') }}"></div>
        <div id="preview-container" style="visibility: hidden;"></div>
        @include("app.partials.handlebar-templates")
    </div>
@overwrite

@section("scripts_lib")
    <script src="{{ url('assets/scripts/handlebars.js') }}"></script>
@overwrite

@section("scripts_app_before")
    <script type="text/javascript">
        App.set('image', {{ json_encode($image) }});
        App.set('tags_icons', {{ json_encode($tags_icons) }});
    </script>
@stop

