@include("layouts.include-header")

<header id="header">
    <div class="container">
        <div class="header-left">
            <div class="nav navbar-default clearfix">
                <div class="container-fluid" style="float: left;">
                    <div class="navbar-header pull-left">
                        <a class="navbar-brand" href="{{ url() }}">
                         <img src="{{ url() }}/icons/logokl.png">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-right">
            <div class="col-lg-8 search-form">
                <form id="search_form" action="{{ URL::action('App\Controllers\AppController@search') }}" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="term" placeholder="Bilder oder Inhalte finden..." style="border-right: 0!important;
    border-top-left-radius: 5px!important;
    border-bottom-left-radius: 5px!important;
    border-left: 1px solid #CCC!important;
    border-top: 1px solid #CCC!important;
    border-bottom: 1px solid #CCC!important;">
                        <span class="input-group-btn">
                            <button type="submit" form="search_form" class="btn btn-default" style="border: 1px solid #CCC!important;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    background-color: #FFF!important;
    border-left: none!important;
    box-shadow: none!important;
    outline: 0;"><span class="glyphicon glyphicon-search" style="color: #A2A2A2;"></span></button>
                        </span>
                    </div><!-- /input-group -->
                </form>
            </div><!-- /.col-lg-6 -->
            <ul class="nav navbar-nav navbar-right">
                
                <li>
                    <a href="{{ route("app.upload") }}">
                        <span>Bild hochladen</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route("app.images") }}">
                        <span>Bilder</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route("app.listing") }}">
                        <span>Inhalte</span>
                    </a>
                </li>
               
				  <li>
                    <a href="{{ route("app.setting") }}">
                        <span>Einstellungen</span>
                    </a>
                </li>
				  <?php $user = Auth::user(); ?>
                @if($user->admin == '1' && $user->lock == '0')
                <li>
                    <a href="{{ route("app.users") }}">
                        <span>User</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route("app.logout") }}">
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

@if(Session::has('success'))
    <div class="alert-box success">
        <h4>{{ Session::get('success') }}</h4>
    </div>
@endif

@if(Session::has('error'))
    <div class="alert-box error">
        <h4>{{ Session::get('error') }}</h4>
    </div>
@endif

<div class="container">
    <div id="main-wrapper">
        @yield("content")
    </div>
</div>

@include("layouts.include-footer")