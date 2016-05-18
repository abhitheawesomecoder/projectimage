@extends("layouts.template")

@section("page_title", "Dashboard")@stop

@section("content")
    <div id="dashboard-ctrl">
        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-stat bg-blue">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                            <img src="icons/photos.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['images_count'] }}</h4>
                        <span class="stat-title">Bilder</font></span>
                    </div>
                </div>

                <div class="panel panel-stat bg-red">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                            <img src="icons/product.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['products_count'] }}</h4>
                        <span class="stat-title">Interaktive Inhalte</font></span>
                    </div>
                </div>

                <div class="panel panel-stat bg-orange">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                          <img src="icons/tags.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['tags_count'] }}</h4>
                        <span class="stat-title">Tags</font></span>
                    </div>
                </div>

                <div class="panel panel-stat bg-pink">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                          <img src="icons/wert.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['value_count'] }}</h4>
                        <span class="stat-title">Wert Aller Inhalte</font></span>
                    </div>
                </div>

            </div>
            <div class="col-sm-8">
                <div id="chart">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="no-margin"><img src="icons/impression.png"> Statistik</h3>
                        </div>
                        <div class="panel-body">
                            <div id="morris-line-chart" class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row images-list">
            <div class="col-sm-12">
               
            </div>
            @if($recentImages->count() != '0')
                @foreach($recentImages as $image)
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="image-item">
                            <a href="{{ route('app.tagger', ['id' => $image->id]) }}" class="image-preview">
                                <img src="{{ $image->url }}" class="img-responsive"/>
                            </a>

                            <div class="clearfix">
                                <small class="text-muted pull-left">
                                    Hochgeladen am: {{ $image->created_at }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                    <center><h3 style="border: none; display: inline-block; margin-top: 20px;"><b>Herzlich willkommen!</b><br>Sie können nun Ihr erstes Bild hochladen.</h3><br><a href="upload">Bild hochladen und gestalten</a> | <a href="http://imagemarker.com/help.html" target='blank'>Hilfe</a> </center>
            @endif
        </div>
    </div>
@overwrite

@section("scripts_lib")
    <script src="{{ url() }}/assets/scripts/raphael.js"></script>
    <script src="{{ url() }}/assets/scripts/morris.js"></script>
@overwrite
@section("scripts_app_before")
    <script type="text/javascript">
        App.set('impressions', {{ json_encode($impressions) }});
    </script>
@overwrite
@section("styles:end")
    <link href="{{ url() }}/assets/styles/morris.css" rel="stylesheet">
@overwrite