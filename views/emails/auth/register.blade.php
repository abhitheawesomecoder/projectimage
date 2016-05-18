<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Vielen Dank für Ihre Anmeldung auf ImageMarker!</h2>

		<div><p>
			Hallo {{ $username }},<p>
			
			vielen Dank für Ihre Anmeldung auf ImageMarker.<p>
			
			Bitte bestätigen Sie Ihre Anmeldung über den folgenden Link:<br>
			{{ url() }}/activate/{{ $code }}<p>
			
			Für Fragen und Anregungen stehen wir Ihnen gerne zur Verfügung.<br>
			Senden Sie uns einfach eine E-Mail an: info@imagemarker.com<p>
			
			Viele Grüße<br>
			Ihr Team von ImageMarker<p>
			
			----------------------------------------------------------------------<p>
			
			Einloggen:<br>
			http://imagemarker.com/apps<p>
			
			Hilfe:<br>
			http://imagemarker.com/help.html<p>
			
			----------------------------------------------------------------------<p>
			
			ImageMarker | Interaktive Bilder<br>
			Spichernstrasse 20<br>
			10777 Berlin<p>
			www.imagemarker.com<br>
			info@imagemarker.com
		</div>
	</body>
</html>