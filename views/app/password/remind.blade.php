@extends("layouts.blank")

@section("page_title", "Forgot Password")@stop
@section("body_class", "page-login")@stop

@section("content")
    <section>
        {{ Form::open() }}
            <div class="panel panel-login">
                <div class="panel-body">

                	@include("app.partials.alerts")

                    <div class="logo text-center">
					 <a href="http://www.imagemarker.com"><img src="../icons/logo.png" border="0"></a>
                      
                    </div>

                    <br />

                    <p class="text-center margin-bottom-lg">Geben Sie die E-Mail-Adresse ein um ein neues Passwort zu erhalten.</p>

                    <div class="input-group margin-bottom-md">
                        <span class="input-group-addon">
                          <i class="fa fa-user"></i>
                        </span>
                        <input class="form-control" placeholder="Ihre E-Mail" name="email" type="email" value="{{Input::old('email')}}">
                    </div>
                </div>

                <div class="panel-footer">
                    <button type="submit" class="btn btn-success btn-block">
                        Passwort zusenden <i class="fa fa-angle-right ml5"></i>
                    </button>
                    <p class="footer-addons">
                        <a href="{{ route('app.register') }}">Kostenlos anmelden</a> | <a href="{{ route('app.login') }}/">Einloggen</a>
                    </p>
                </div>
            </div>

        {{ Form::close() }}

    </section>
@overwrite