@extends("layouts.template")

@section("page_title", "Inhalte")@stop

@section("content")
<style type="text/css">
.glyphicon { margin-right:5px; }
.thumbnail
{
    margin-bottom: 20px;
    padding: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    border-radius: 0px;
}

.item.list-group-item
{
    float: none;
    width: 100%;
    background-color: #fff;
    margin-bottom: 10px;
}
.item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover
{
    background: #dbdbdb;
}

.item.list-group-item .list-group-image
{
    margin-right: 10px;
}
.item.list-group-item .thumbnail
{
    margin-bottom: 0px;
}
.item.list-group-item .caption
{
    padding: 9px 9px 0px 9px;
}
.item.list-group-item:nth-of-type(odd)
{
    background: #eeeeee;
}

.item.list-group-item:before, .item.list-group-item:after
{
    display: table;
    content: " ";
}

.item.list-group-item img
{
    float: left;
}
.item.list-group-item:after
{
    clear: both;
}
.list-group-item-text
{
    margin: 0 0 11px;
}
</style>


<div id="listing-ctrl" class="container">    
    <div id="products" class="row list-group">
       

       @if($products->count() != '0')
        @foreach($products as $product)   
        {{-- $product->image --}}
      
        <div class="item  col-xs-4 col-lg-4 grid-group-item list-group-item">
            <div class="row">
             <div class="col-md-3">
                <div class="thumbnail">
                   @if($product->image)
                    <img class="group list-group-image" src="{{ $product->image }}" alt="">
                    @else
                    <img class="group list-group-image" src="http://imagemarker.com/apps/icons/noimage.png" alt="">
                   @endif
                </div>
              </div>
          <div class="col-md-9">
            <div class="row" style="margin-top: 30px;">
               <div class="col-md-8">
                 <div class="caption">
                        <h4 class="group inner list-group-item-heading">
                            @if($product->title != '')
                        <a href="{{ URL::to('listing/edit/' . $product->id) }}" style="color: #000;">{{ $product->title }}</a>
                        @endif
                        @if($product->title == '')
                        <a href="{{ URL::to('listing/edit/' . $product->id) }}" style="color: #000;">{{ $product->youtube }}</a>
                        @endif
                        </h4>
                        <p class="group inner list-group-item-text">
                              {{  $product->description }}
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                  <small class="product-url">
                        {{ $product->url }}
                    </small>
                            </div>
                          
                        </div>
                    </div>
                 </div>

                  <div class="col-md-2">
                    <p class="lead">
                                        @if($product->price != '0')
                        € {{ str_replace('.', ',', $product->price) }}
                        @endif
                    </p>
                 </div>

                  <div class="col-md-2">
                      <a class="btn btn-success" href="{{ URL::to('listing/edit/' . $product->id) }}" style="margin-bottom: 5px; width: 120px;">Bearbeiten</a><br><br>
                  
                    @if(Auth::user()->premium != '0' || Auth::user()->admin == '1')
                    @endif
                         <a class="btn btn-danger tag-delete-btn" data-product-id="{{ $product->id }}" style="width: 120px;">Inhalt löschen</a>
                          
                 </div>

                </div>


                </div>
             
              </div>
        </div>
        
 @endforeach
    @else
        <div class="NO_IMAGE">
            <center><h3 style="border: none; display: inline-block; margin-top: 20px;"><b>Keine Inhalte vorhanden.</b><br>Sie können nun Ihren ersten Inhalt erstellen.</h3><br><a href="upload">Bild hochladen und gestalten</a> | <a href="http://imagemarker.com/help.html" target='blank'>Hilfe</a>  </center>
        </div>
    @endif

   <center><?php echo $products->links(); ?></center>

    </div>
</div>

@if(0)
********************************************

    <section id="listing-ctrl" class="row">
    @if($products->count() != '0')
        @foreach($products as $product)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                   <h3 class="product-title">
                        @if($product->title != '')
                        <a href="{{ URL::to('listing/edit/' . $product->id) }}" style="color: #000;">{{ $product->title }}</a>
                        @endif
                        @if($product->title == '')
                        <a href="{{ URL::to('listing/edit/' . $product->id) }}" style="color: #000;">{{ $product->youtube }}</a>
                        @endif
                    </h3>
                    </h3>

                    <h4 class="product-price">
                        @if($product->price != '0')
                        € {{ str_replace('.', ',', $product->price) }}
                        @endif
                    </h4>

                    <div class="product-details">
                        <div class="product-summary">
                            {{ $product->description }}
                        </div>
                    </div>

                  <small class="product-url">
                        <i class="fa fa-globe"></i> {{ $product->url }}
                    </small>
                </div>
            </div>
        @endforeach
    @else
        <div class="NO_IMAGE">
            <center><h3 style="border: none; display: inline-block; margin-top: 20px;"><b>Keine Inhalte vorhanden.</b><br>Sie können nun Ihren ersten Inhalt erstellen.</h3><br><a href="upload">Bild hochladen und gestalten</a> | <a href="http://imagemarker.com/hilfe.html" target='blank'>Hilfe</a>  </center>
        </div>
    @endif

    </section>

    <center><?php echo $products->links(); ?></center>
    @endif
@overwrite