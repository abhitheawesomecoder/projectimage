<div id="TaggerProduct" style="display: block; float: left; margin-right: 20px;">
    <div class="modal-dialog" style="width: 300px; margin-top: 0px;">
	<div class="panel-heading" style="background: #39B1D4;text-align: center">
                            <h3 class="no-margin"><b>Inhalte bearbeiten</b></h3></div>
          <div class="modal-content" 
		style="background-color: #f3f3f3; 
	  border-radius: 3px 3px 3px 3px; box-shadow: none; border: 3px; border-style: solid;">
            <div id="modal-body" class="modal-body" style="display: block;">
			
                <div class="tab-content">
				
<form id="edit_form" action="" method="post">
                    <!-- Section Create Product -->
                    <div class="tab-pane active" id="create-product">
                        @if($product->title != '')
                        <div class="form-group">
                            <input name="title" type="text" class="form-control" id="input-title" value="{{ $product->title }}" placeholder="Titel (max. 50 Zeichen)" maxlength="50" required>
                        </div>
                        @endif
                      
                      
                        <div class="form-group">
                            <textarea class="form-control" id="input-description"
                                      placeholder="Beschreibung (max. 180 Zeichen)" maxlength="180" name="description" style="height: 150px;" required>{{ $product->description }}</textarea>
                        </div>
                      


                        <div class="form-group">
                        <span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><b>YouTube</b> Video verknüpfen:</span>
                            <input type="text" class="form-control" name="youtube" id="input-youtube" value="{{ $product->youtube }}" placeholder="Link zum YouTube Video" maxlength="1000" required >
                        </div>
                      <!--   <div class="col-sm-8">
                            <input name="youtube" type="text" class="form-control" id="input-youtube" value="{{ $product->youtube }}" placeholder="Youtube Video Url" required>
                        </div> -->
                     
                      
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
