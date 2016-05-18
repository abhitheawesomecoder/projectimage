@extends("layouts.blank")

@section("page_title", "Einloggen")@stop
@section("body_class", "page-login")@stop

@section("content")
    <section>
        <form action="{{ route('app.login') }}/" method="post">
            <div class="panel panel-login">
                <div class="panel-body">

                    @include("app.partials.alerts")

                    <div class="logo text-center">
                    <a href="http://www.imagemarker.com"><img src="icons/logo.png" border="0"></a>
                    </div>

                    <br />

                    <p class="text-center margin-bottom-lg">Einloggen auf ImageMarker</p>

                    <div class="input-group margin-bottom-md">
                        <span class="input-group-addon">
                          <i class="fa fa-user"></i>
                        </span>
                        <input class="form-control" placeholder="Shop oder Username" name="username" type="text">
                    </div>


                    <div class="input-group margin-bottom-md">
                        <span class="input-group-addon">
                          <i class="fa fa-lock"></i>
                        </span>
                        <input class="form-control" placeholder="Passwort" name="password" type="password">
                    </div>
                </div>

                <div class="panel-footer">
                    <button type="submit" class="btn btn-success btn-block">
                        Einloggen <i class="fa fa-angle-right ml5"></i>
                    </button>
                    <p class="footer-addons">
                        <a href="{{ route('app.register') }}">Kostenlos anmelden</a> | <a href="{{ route('remind') }}">Passwort vergessen</a>
                    </p>
                </div>
            </div>

        </form>

    </section><br><br><br><br><br><br><br><br><br><br><br>
@overwrite

