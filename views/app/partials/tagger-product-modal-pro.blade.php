<div id="TaggerProduct" style="display: block; float: left; margin-right: 20px;">
    <div class="modal-dialog" style="width: 300px; margin-top: 0px;">
         <div class="modal-content" 
		style="background-color: #f3f3f3; 
	  border-radius: 3px 3px 3px 3px; box-shadow: none; border: 3px; border-style: solid;">
	    	
            <div id="modal-body-test" class="modal-body" style="display: block; color: #000;">
              <p align="justify"><h3 class="no-margin"><b>So funktioniert´s!</b></h3></p><br>
				
				<p align="justify"><b>1.</b> Klicken Sie auf die Stelle in Ihrem Bild, welche Sie interaktiv gestalten möchten.</p><br>
				
				<p align="justify"><b>2.</b> Geben Sie Ihrem Bild einen Titel und fügen Sie weitere Informationen ein, die in Ihrem Bild erscheinen sollen.</p><br>
				
				<p align="justify"><b>3.</b> Wählen Sie ein Icon aus oder laden Sie Ihr eigenes Icon hoch.</p><br>
				
				<p align="justify"><b>4.</b> Klicken Sie auf "Speichern".</p><br>
				
				<p align="justify">Sie haben die Möglichkeit mehrere Markierungen (Tags) in Ihrem Bild zu setzen. 
				Wenn Sie mit der Bearbeitung fertig sind, klicken Sie auf "Code generieren" um den HTML-Code von Ihrem Bild zu erhalten. 
				Diesen können Sie anschließend in Ihre Website, Ihren Online-Shop, Blog, Forum oder auf anderen Internetseiten einbinden.</p>
            </div>
            <div id="modal-body" class="modal-body" style="display: none;">
                <div class="tab-content">

                    <!-- Section Create Product -->
                    <div class="tab-pane active" id="create-product">
                        <div class="form-group">
                            <input type="text" class="form-control" data-type="pro" id="input-title" placeholder="Titel (max. 50 Zeichen)" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="input-description"
                                      placeholder="Beschreibung (max. 180 Zeichen)" maxlength="180" style="height: 150px;" required></textarea>
                        </div>
                        <div class="form-group">
						<span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><b>Preisanzeige</b> im Bild:</span>
                            <input type="text" class="form-control" id="input-price" placeholder="Preis in EUR" maxlength="8">
                        </div>

                        <div class="form-group">
						<span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><b>YouTube</b> Video verknüpfen:</span>
                            <input type="text" class="form-control" id="input-youtube-video" placeholder="Link zum YouTube Video" maxlength="1000" required>
                        </div>

                        <div class="form-group">
						<span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><b>Verlinkung</b> einer Website:</span>
                            <input type="text" class="form-control" id="input-url"
                                   placeholder="Link (z.B. www.website.de)"/>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" id="input-button-name"
                                   placeholder="Linkname (z.B. Meine Website)" maxlength="20" />
                        </div>

                        <div class="form-group">
						<span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><b>Vorschaubild</b> hochladen:</span>
                            @if(Auth::user()->premium == '0')
                            <span style="float: left;padding-bottom: 15px;"><a style="color: #000;text-decoration: underline;font-weight: bold;" href="http://imagemarker.com/apps/upgrade">Become a Premium Member</a><span/>
                            @else
                            <input type="file" class="form-control" id="input-button-image" placeholder="Upload Image" />
                            @endif                           
                        </div>
                     
                           
                        <input style="display: none;" type="checkbox" id="color" name="color" value="1" checked>
                        <div id="color-div" class="text-left" style="float: left;color: #fff;margin-top: -7px;">
                            <span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><b>Wählen</b> Sie ein Icon aus:</span><br><br>
                         <!--    <a id="color-blue" class="color-1" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/blue.png"></a>
                            <a id="color-black" class="color-1" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/black.png"></a>
                            <a id="color-green" class="color-1" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/green.png"></a>
                            <a id="color-yellow" class="color-1" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/yellow.png"></a>
                          -->    @foreach($tags_icons as $icon)
                            <a id="color-{{ $icon->id }}" class="color-{{ $icon->id }} color-n" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="{{ $icon->image }}"></a>
                             
                                @if($icon->premium == '1' && Auth::user()->premium == '1' || Auth::user()->premium == '2')
                                     @endif
                                @if($icon->premium == '0')
                                      @endif
                                @if($icon->premium == '1' && Auth::user()->admin == '1' && Auth::user()->premium == '0')
                                 @endif
                            @endforeach
                        </div>
						
                        <div class="form-group">
                            <span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><br><b>Eigenes</b> Icon hochladen:</span>
                            @if(Auth::user()->premium == '0')
                            <span style="float: left;"><a style="color: #000;text-decoration: underline;font-weight: bold;" href="http://imagemarker.com/apps/upgrade">Become a Premium Member</a><span/>
                            @else
                            <input type="file" class="form-control" id="input-button-icon" placeholder="Upload Icon" />
                            @endif
                        </div>

                        <div class="form-group" style="padding-top: 15px; display: inline-block; width: 100%;">
                            <button type="button" class="btn btn-primary btn-save-tag" style="width: 100%;">Speichern</button>
                        </div>
                        <div class="form-group" style="display: inline-block; width: 100%;">
                            <button type="button" class="btn btn-default" onclick="location.reload()" style="width: 100%;">Schließen</button>
                        </div>


                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
<input type="hidden" id="pageX" value="">
<input type="hidden" id="pageY" value="">
<input type="hidden" id="imgsize" value="1">
