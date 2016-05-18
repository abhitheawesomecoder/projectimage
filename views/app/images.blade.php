@extends("layouts.template")

@section("page_title", "Bilder")@stop

@section("content")
    <section id="images-ctrl" class="row images-list">
    @if($images->count() != '0')
        @foreach($images as $image)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="image-item" data-image-id="{{ $image->id }}">
                    <a href="{{ route('app.tagger', ['id' => $image->id]) }}" class="image-preview">
                        <img src="{{ $image->url }}" class="img-responsive"/>
                    </a>

                   

                    <div class="clearfix">
                        <small class="text-muted pull-left">
                            Hochgeladen am: {{ $image->created_at }}
                        </small>
                        <span title="Total Tags"
                              class=" pull-right label label-default">{{ $image->tags->count() }}</span>
                    </div>

                    <a class="image-delete-btn" href="#">
                        Löschen
                    </a>
                </div>
            </div>
        @endforeach
    @else
        <div class="NO_IMAGE">
            <center><h3 style="border: none; display: inline-block; margin-top: 20px;"><b>Erstes Bild hochladen.</b><br>Sie können nun Ihr erstes Bild hochladen.</h3><br><a href="upload">Bild hochladen und gestalten</a> | <a href="http://imagemarker.com/help.html" target='blank'>Hilfe</a> </center>
        </div>
    @endif

    </section>

    <center><?php echo $images->links(); ?></center>
@overwrite