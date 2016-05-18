@extends("layouts.template")

@section("page_title", "Inhalte")@stop

@section("content")
<div id="listing-ctrl" class="container">    
    <div id="products" class="row list-group" style="font-size: 16px; text-align: center;">
        <br><br><br>
		<p>   <img src="icons/lock.png"></p><br>
        <p><h2><b>Vielen Dank</b> für Ihre Anmeldung!</h2></p><br>
        <p>Wir haben Ihnen eine E-Mail mit dem Aktivierungslink geschickt.</p>
		<p>Nachdem Sie den Link bestätigt haben, ist Ihre Anmeldung abgeschlossen.</p><br>
		<p>Sollten Sie innerhalb weniger Sekunden keine E-Mail erhalten, schauen bitte in Ihrem Spam-Ordner nach.</p><br>
        <p><a href="{{ url() }}/resend_email">E-Mail erneut senden</a> | <a href="/help.html" target="blank">Hilfe & Kontakt</a></p><br>
    </div>
</div>
@overwrite