<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Erstellen Sie interaktive Bilder mit multimedialen Inhalten. Kostenlos starten!">
    <meta name="keywords" content="bilder gestalten, bilder taggen, fotos markieren, bild verlinken, bildupload, fotoupload, bild upload, bild hochladen, interaktiv, foto taggen , Bildpunkte, bild video,bild bearbeitung, bild bearbeiten, foto bearbeiten, interaktive produktbilder, interaktive bilder, interaktive grafiken, interaktive fotos, fotos taggen">
 <!-- Main CSS file -->
    <link rel="stylesheet" href="http://www.imagemarker.com/apps/assets/styles/styleeditor.css">
	
  <title>@yield("page_title") | ImageMarker</title>

  @yield("styles:end")
  <link href="{{ url() }}/assets/styles/font-awesome.css" rel="stylesheet">
  <link href="{{ url() }}/assets/styles/bootstrap.css" rel="stylesheet">
  <link href="{{ url() }}/assets/styles/application.css" rel="stylesheet">
  @yield("styles:end")

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="{{ url() }}/assets/scripts/html5shiv.js"></script>
  <script src="{{ url() }}/assets/scripts/respond.js"></script>
  <![endif]-->
</head>
<body class="@yield("body_class")"></body>