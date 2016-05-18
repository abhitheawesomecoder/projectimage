@extends("layouts.template")

@section("page_title", "Bild hochladen")@stop

@section("content")
<section id="creator-ctrl">

    @include("app.partials.alerts")

    <div class="panel panel-default">
        <div class="panel-body">
            <p class="text-center">
                <img src="icons/photoup.png">
            </p>
            <h2 class="text-center">
                <font color="#000000">Wählen Sie ein Bild aus:</font>
            </h2>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">

                    <form action="#" method="post" enctype="multipart/form-data">
                          <input type="hidden" id="imgsize" name="imgsize" value="1">
                        <div class="form-group">
                            <input  id="input-button-image" type="file" class="form-control" id="input-file" name="image"/>
                        </div>
						 <div class="text-center">
                           <p style="font-size:12px; color:#6b6b6b;"> * Sie können Dateien in den Formaten jpeg, jpg, gif und png mit bis zu 1MB hochladen. </p><br>
                        </div>
                        <div class="center-block text-center">
                            <button class="btn btn-primary btn-lg">Bild hochladen &raquo;</button>
                        </div>
                    </form>
					
                </div>
            </div>
        </div>
    </div>
<br><br><br><br>
	</section>
@overwrite


