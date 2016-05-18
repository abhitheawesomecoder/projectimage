<div id="generate-code-modal" >
    <div class="modal-dialog">
        <div class="modal-content" style="    box-shadow: none;
    background: transparent;
    border: none;
    color: #000;">
	<img src="http://www.imagemarker.com/apps/icons/htmlcode.png"><br><br>
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="fa fa-code"></span> &nbsp; HTML-Code zum Bild <b>ohne Inhalte</b>
                </h4>
            </div>
            <div id="CodeModal" class="modal-body">

                <div class="form-group">
                    <textarea class="form-control" rows="1"><img src="{{ url() }}/uploads/images/{{ $image->path }}"></textarea>
                </div>
 <p class="help-block">
                    Dieser HTML-Code enthält das Bild ohne interaktive Inhalte.<br>
					Kopieren Sie den Code und fügen Sie diesen auf Ihrer Website ein.
                </p>
            </div>

            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="fa fa-code"></span> &nbsp; HTML-Code zum <b>interaktiven Bild</b>
                </h4>
            </div>
            <div id="CodeModal" class="modal-body">

                <div class="form-group">
                    <textarea class="form-control" id="code-textbox" rows="9"></textarea>
                </div>

             
                
                <button class="btn btn-success" id="btn-generate-code" data-clipboard data-clipboard-target="#code-textbox">
                    <i class="fa fa-code"></i>&nbsp; HTML-Code kopieren 
                </button><br><br>
				<p class="help-block">
                    Dieser HTML-Code enthält das Bild mit interaktiven Inhalten.<br>
					Klicken Sie auf "HTML-Code kopieren" und fügen Sie diesen auf Ihrer Website ein.
                </p>
            </div>

        </div>
    </div>
</div>
