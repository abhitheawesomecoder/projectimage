  <!-- FOOTER -->
  <div class="footer">
    <div class="container">
      <!-- COLUMNS -->
      <div class="row">
       <div class="col-sm-6 col-md-2 lj-footer-menu">
           <ul>
            <li> <img src="http://www.imagemarker.com/apps/icons/logo2.png" alt=""><p>
         </li>
         
          </ul>
        </div>
        <div class="col-sm-6 col-md-2 lj-footer-menu">
          
		  <ul>
            <li><a href="https://www.facebook.com/imagemarker.de" target="blank">Facebook</a></li>
            <li><a href="https://twitter.com/imagemarker" target="blank">Twitter</a></li>
            <li><a href="https://plus.google.com/101074435229150246190" target="blank">Google+</a><p></li>
          </ul>
        </div>
        <div class="col-sm-6 col-md-2 lj-footer-menu">
          <ul>
            <li><a href="/help.html" target="blank">Hilfe & Kontakt</a></li>
            <li><a href="/status.html" target="blank">Status</a></li>
            <li><a href="/contact.html" target="blank">Kontakt</a></li>
          </ul>
        </div>
		 <div class="col-sm-6 col-md-2 lj-footer-menu">
          <ul>
            <li><a href="/datenschutz.html" target="blank">Datenschutz</a></li>
            <li><a href="/agb.html" target="blank">AGB und Regeln</a></li>
            <li><a href="/impressum.html" target="blank">Impressum</a></li>
          </ul>
        </div>
      </div>
      <!-- /COLUMNS -->
    </div>
  </div>
  <!-- /FOOTER -->

<script src="{{ url() }}/assets/scripts/moment.js"></script>
<script src="{{ url() }}/assets/scripts/jquery.js"></script>
<script src="{{ url() }}/assets/scripts/jquery.autonumeric.js"></script>
<script src="{{ url() }}/assets/scripts/bootstrap.js"></script>
<script src="{{ url() }}/assets/scripts/app.lib.js"></script>
<script src="https://zenorocha.github.io/clipboard.js/bower_components/highlightjs/highlight.pack.min.js"></script>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.5/clipboard.min.js"></script>
<script type="text/javascript">
var clipboardDemos = new Clipboard('[data-clipboard]');

clipboardDemos.on('success', function(e) {
    e.clearSelection();

    console.info('Action:', e.action);
    console.info('Text:', e.text);
    console.info('Trigger:', e.trigger);

    showTooltip(e.trigger, 'Copied!');
});

clipboardDemos.on('error', function(e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);

    showTooltip(e.trigger, fallbackMessage(e.action));
});
</script>



@yield("scripts_lib")

<script type="text/javascript">
App.set('base_url', '{{ url() }}');
</script>

@yield("scripts_app_before")
<script src="{{ url() }}/assets/scripts/app.ctrl.js"></script>
@yield("scripts_app_after")


</body>
</html>