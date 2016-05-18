@extends("layouts.blank")

@section("page_title", "Forgot Password")@stop
@section("body_class", "page-login")@stop

@section("content")
    <section>
        {{ Form::open(array('route' => array('reset', $token))) }}
            {{ Form::hidden('token', $token) }}
            <div class="panel panel-login">
                <div class="panel-body">

                    @include("app.partials.alerts")

                    <div class="logo text-center">
                       <a href="http://www.imagemarker.com"><img src="../icons/logo.png" border="0"></a>
                    </div>

                    <br />

                    <p class="text-center margin-bottom-lg">Geben Sie Ihre E-Mail-Adresse und ein neues Passwort ein.</p>

                    <div class="input-group margin-bottom-md">
                        <span class="input-group-addon">
                          <i class="fa fa-user"></i>
                        </span>
                        <input class="form-control" placeholder="Ihre E-Mail" name="email" type="email" value="{{Input::old('email')}}">
                    </div>

                    <div class="input-group margin-bottom-md">
                        <span class="input-group-addon">
                          <i class="fa fa-lock"></i>
                        </span>
                        <input class="form-control" placeholder="Neues Passwort" name="password" type="password">
                    </div>

                    <div class="input-group margin-bottom-md">
                        <span class="input-group-addon">
                          <i class="fa fa-lock"></i>
                        </span>
                        <input class="form-control" placeholder="Passwort wiederholen" name="password_confirmation" type="password">
                    </div>
                </div>

                <div class="panel-footer">
                    <button type="submit" class="btn btn-success btn-block">
                        Änderung speichern <i class="fa fa-angle-right ml5"></i>
                    </button>
                    <p class="footer-addons">
                         <a href="{{ route('app.register') }}">Kostenlos anmelden</a> | <a href="{{ route('app.login') }}/">Einloggen</a>
                    </p>
                </div>
            </div>

        {{ Form::close() }}

    </section>
@overwrite