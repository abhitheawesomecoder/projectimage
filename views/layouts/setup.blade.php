@include("layouts.include-header")


<section id="install-ctrl" class="clearfix">
    <nav class="navbar navbar-default visible-xs" role="navigation">

        <div class="navbar-header">

            <a type="button" class="navbar-toggle nav-link" href="http://github.com/netaviva">
                <i class="fa fa-github"></i>
            </a>


            <a type="button" class="navbar-toggle nav-link" href="http://twitter.com/netaviva">
                <i class="fa fa-twitter"></i>
            </a>


            <a type="button" class="navbar-toggle nav-link" href="mailto:build@netaviva.net">
                <i class="fa fa-envelope"></i>
            </a>

            <a class="navbar-brand" href="http://netaviva.net" target="_blank">
                <img src="{{ url('assets/images/netaviva_200x120_white.png') }}" class="img-circle"/>
                Netaviva
            </a>
        </div>
    </nav>

    <div class="col-sm-3 sidebar hidden-xs">
        <header class="sidebar-header" role="banner">
            <a href="http://netaviva.net" target="_blank">
                <img src="{{ url('assets/images/netaviva_200x120_white.png') }}" class="img-circle"/>
            </a>
        </header>
        <div id="bio" class="text-center">
            We build websites and apps for people who are serious about their ideas.
        </div>
        <div id="contact-list" class="text-center">
            <ul class="list-unstyled list-inline">
                <li>
                    <a class="btn btn-default btn-sm" href="https://github.com/Netaviva" target="_blank">
                        <i class="fa fa-github-alt fa-lg"></i>
                    </a>
                </li>
                <li>
                    <a class="btn btn-default btn-sm" href="https://twitter.com/netaviva" target="_blank">
                        <i class="fa fa-twitter fa-lg"></i>
                    </a>
                </li>
                <li>
                    <a class="btn btn-default btn-sm" href="mailto:build@netaviva.net" target="_blank">
                        <i class="fa fa-envelope fa-lg"></i>
                    </a>
                </li>
            </ul>
            <ul id="contact-list-secondary" class="list-unstyled list-inline">
                <li>
                    <a class="btn btn-default btn-sm" href="https://www.facebook.com/netaviva" target="_blank">
                        <i class="fa fa-facebook fa-lg"></i>
                    </a>
                </li>
                <li>
                    <a class="btn btn-default btn-sm" href="https://plus.google.com/netaviva/posts" target="_blank">
                        <i class="fa fa-google-plus fa-lg"></i>
                    </a>
                </li>
                <li>
                    <a class="btn btn-default btn-sm" href="http://netaviva.net" target="_blank">
                        <i class="fa fa-globe fa-lg"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-sm-9 col-sm-offset-3">
        @yield("content")
        <div class="clearfix"></div>
        <footer>
            <hr/>
            <p>
                &copy; 2014 - 2014 with love from <a href="http://netaviva.net" target="_blank" title="Netaviva">Netaviva</a>
                | <a href="http://mvp.netaviva.net" target="_blank">MVP Projects</a>
            </p>
        </footer>
    </div>
</section>

@include("layouts.include-footer")