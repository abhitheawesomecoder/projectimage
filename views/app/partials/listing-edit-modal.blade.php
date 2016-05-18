<div id="TaggerProduct" style="display: block; float: left; margin-right: 20px;">
    <div class="modal-dialog" style="width: 300px; margin-top: 0px;">
        <div class="modal-content" style="background-color: #F0F0F0; box-shadow: none; border: none; border-radius: 0px;">
            <div id="modal-body" class="modal-body" style="display: block;">
                <div class="tab-content">
<form id="edit_form" action="" method="post">
                    <!-- Section Create Product -->
                    <div class="tab-pane active" id="create-product">
                        @if($product->title != '')
                        <div class="form-group">
                            <input name="title" type="text" class="form-control" id="input-title" value="{{ $product->title }}" placeholder="Titel" maxlength="50" required>
                        </div>
                        @endif
                        @if($product->title == '')
                        <div class="col-sm-8">
                            <input name="youtube" type="text" class="form-control" id="input-youtube" value="{{ $product->youtube }}" placeholder="Youtube Video Url" required>
                        </div>
                        @endif
                        @if($product->description != '')
                        <div class="form-group">
                            <textarea class="form-control" id="input-description"
                                      placeholder="Beschreibung" maxlength="180" name="description" style="height: 150px;" required>{{ $product->description }}</textarea>
                        </div>
                        @endif
                        <div class="form-group">
                            <input type="text" class="form-control" id="input-price"
                                    placeholder="Preis in EUR" name="price" value="{{ $product->price }}">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" id="input-url"
                                   placeholder="Link (z.B. www.website.de)" name="url" value="{{ $product->url }}" />
                        </div>
<style>
.btn-green {
    color: #FFF!important;
    background-color: #5CB85C;
    border-color: #5CB85C;
    width: 100%;
}
</style>
                        <div class="form-group" style="padding-top: 15px; display: inline-block; width: 100%;">
                            <input class="btn btn-green" type="submit" name="submit" value="Speichern">
                        </div>
</form>

                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
<input type="hidden" id="pageX" value="">
<input type="hidden" id="pageY" value="">
<input type="hidden" id="imgsize" value="1">
