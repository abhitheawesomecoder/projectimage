<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Ihr neues Passwort für ImageMarker</h2>

		<div><p>
			Hallo,<p>
			
			Sie haben ein neues Passwort für Ihren Account angefordert.<p>
			
			Unter folgendem Link können Sie sich ein neues Passwort anlegen:<br> 
			{{ URL::to('password/reset', array($token)) }}.<p>
			
			Für Fragen und Anregungen stehen wir Ihnen gerne zur Verfügung.<br>
			Senden Sie uns einfach eine E-Mail an: kontakt@imagemarker.com<p>
			
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
			kontakt@imagemarker.com
		</div>
	</body>
</html>