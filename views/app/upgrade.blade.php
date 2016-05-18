@extends("layouts.template")

@section("page_title", "Premium")@stop

@section("content")
    <div id="dashboard-ctrl">
        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-stat bg-blue">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                            <img src="icons/photos.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['images_count'] }}</h4>
                        <span class="stat-title">Bilder</font></span>
                    </div>
                </div>

                <div class="panel panel-stat bg-red">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                            <img src="icons/product.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['products_count'] }}</h4>
                        <span class="stat-title">Interaktive Inhalte</font></span>
                    </div>
                </div>

                <div class="panel panel-stat bg-orange">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                            <img src="icons/tags.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['tags_count'] }}</h4>
                        <span class="stat-title">Tags</font></span>
                    </div>
                </div>

                <div class="panel panel-stat bg-pink">
                    <div class="panel-body clearfix">
                        <div class="stat-icon">
                          <img src="icons/wert.png">
                        </div>
                        <h4 class="stat-heading"><font color="#ffffff">{{ $stats['value_count'] }}</h4>
                        <span class="stat-title">Wert Aller Inhalte</font></span>
                    </div>
                </div>

            </div>

            <div class="col-sm-8">
                <div id="chart">
                    <div class="panel panel-default" style="width: 49%; float: left; margin-right: 15px;">
                        <div class="panel-heading" style="background: #39B1D4;text-align: center">
                            <h3 class="no-margin"><b>Premium</b></h3>
														<small>Ideal für Unternehmer & Selbständige</small>
                        </div>

                        <div style="background-color:#ffffff;padding:10px;margin:10px">

                        <div style="background-color:#ffffff;>
                            <font color="#000000" style="border: none; display: inline-block; margin-top: 10px; padding-right: 20px;">
							<p>Werbefrei</p>
							<p>Unbegrenzter Bildupload</p>
							<p>Unbegrenzt Tags erstellen</p>
							<p>Unbegrenzt Inhalte erstellen</p>
							<p>YouTube Videos in Bildern</p>
							<p>Eigene Thumbnails hochladen</p>
							<p>50 Icons zur Auswahl</p>
							<p>+ eigene Icons hochladen</p><br><br><br><br><br>
							<p><small>Alle Bilder und Inhalte, die Sie innerhalb der Premiumzeit erstellt haben, sind im Anschluss weiterhin verfügbar.</small></p>
                        	</font>
                            <form style="display:none" id="payment_form" action="{{ url() }}/upgrade/1" method="post" style="text-align: center; padding-top: 40px; padding-bottom: 40px;">
                            	<input type="hidden" name="payment_id" value="1">
                                <button type="submit" form="payment_form" class="btn btn-default" style="padding: 15px 80px; font-size: 18px; width: 100%;">Premium-Upgrade bezahlen</button>
                           <div class="panel-heading" style="background: #9ABB4A;text-align: center">
                           <button class="no-margin"  id="button9">3 Monate auswählen</button>
                           </div>
                            </form>
							
                        </div>

<div style="text-align: right;margin-right: 40px;margin-bottom: -20px;"><span style="padding:20px">Einmalig pro Jahr</span><span style="font-size: 60px;font-weight: bold;">29</span><span style="display: inline-block;vertical-align: middle;margin-bottom: 30px;padding: 10px;font-weight: bold;font-size: 25px;">€</span></div>

                         </div>

                         <div class="panel-heading" style="background: #39B1D4;text-align: center">
                            <h3 style="cursor:pointer;cursor:hand;" class="no-margin"  id="h39">Premium auswählen</h3>
                        </div>
                    </div>






                    <div class="panel panel-default" style="width: 49%; float: left;">
                        <div class="panel-heading" style="background: #9ABB4A;text-align: center">
                            <h3 class="no-margin"><b>Plus</b></h3>
							<small>Ideal für Online-Shops & Affiliate</small>
                        </div>

                        <div style="background-color:#ffffff;padding:10px;margin:10px">

                        <div style="background-color:#ffffff;>
                           <font color="#000000" style="border: none; display: inline-block; margin-top: 10px; padding-right: 20px;">
							
							<p>Werbefrei</p>
							<p>Unbegrenzter Bildupload</p>
							<p>Unbegrenzt Tags erstellen</p>
							<p>Unbegrenzt Inhalte erstellen</p>
							<p>YouTube Videos in Bildern</p>
							<p>Preisanzeigen erstellen</p>
							<p>Verlinkungen erstellen</p>
							<p>Eigene Thumbnails hochladen</p>
							<p>50 Icons zur Auswahl</p>
							<p>+ eigene Icons hochladen</p><br><br>
							<p><small>Alle Bilder und Inhalte, die Sie innerhalb der Premiumzeit erstellt haben, sind im Anschluss weiterhin verfügbar.</small></p>
                            </font>
                            <form style="display:none" id="payment_form2" action="{{ url() }}/upgrade/2" method="post" style="text-align: center; padding-top: 40px; padding-bottom: 40px;">
                            	<input type="hidden" name="payment_id" value="2">
                                <button id="button29" type="submit" form="payment_form2" class="btn btn-default" style="padding: 15px 80px; font-size: 18px; width: 100%;">Premium-Upgrade bezahlen</button>
                            </form>
							
                        </div>

<div style="text-align: right;margin-right: 40px;margin-bottom: -20px;"><span style="padding:20px">Einmalig pro Jahr</span><span style="font-size: 60px;font-weight: bold;">39</span><span style="display: inline-block;vertical-align: middle;margin-bottom: 30px;padding: 10px;font-weight: bold;font-size: 25px;">€</span></div>

                       </div>

                        <div class="panel-heading" style="background: #9ABB4A;text-align: center">
                          <h3 style="cursor:pointer;cursorhand;" class="no-margin" id="h329">Plus auswählen</h3>
                        </div>
                    </div>
                   
                            <center>     
                                                  
                                <small><font style="margin-top: 20px;display: inline-block;">(Preis inklusive gesetzlicher länderabhängiger Mehrwertsteuer.)</font></small>
                            </center>    

                </div>
            </div>
        </div>
    </div>
@overwrite

