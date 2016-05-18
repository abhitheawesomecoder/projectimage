@extends("layouts.template")

@section("page_title", "Einstellungen")@stop

@section("content")
    <section id="setting-ctrl">
        <div class="row">
            <div class="col-md-8">
                <form method="post" action="{{ URL::current() }}" class="form-horizontal form-bordered">
                    <div class="panel panel-default margin-bottom-lg">
                    
                        <div class="panel-body">

                            @include("app.partials.alerts")

                            <fieldset>
                                <legend><font color="#000000">Benutzer-Einstellungen</font></legend>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="color: #000;">Shop oder Username:</label>

                                    <div class="col-sm-6">
                                        <input placeholder="Shop oder Username" class="form-control"
                                               type="text" name="username"
                                               value="{{ array_get(Auth::user(), 'username', '') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="color: #000;">Passwort:</label>

                                    <div class="col-sm-6">
                                        <input placeholder="Neues Passwort" class="form-control"
                                               type="password" name="password">

                                        <p class="help-block">
                                            
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="color: #000;">E-Mail:</label>

                                    <div class="col-sm-6">
                                        <input placeholder="Username" class="form-control"
                                               type="text" name="email"
                                               value="{{ array_get(Auth::user(), 'email', '') }}">
                                    </div>
                                </div>

                                @if ($user->admin != 1)
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="color: #000;">Upgrade-Code:</label>

                                    @if($my_coupon->count() > 0)
                                        @foreach($my_coupon->get() as $mcoupon)
                                        <div class="col-sm-6">
                                            <input placeholder="" class="form-control"
                                                   type="text" name="cc_coupon_code" value="{{ $mcoupon->coupon_code }}" style="cursor: pointer;" disabled="disabled">
                                        </div>

                                        @endforeach
                                    @else
                                        <div class="col-sm-6">
                                            <input placeholder="" class="form-control"
                                                   type="text" name="coupon_code_upgrade">
                                        </div>
                                        
                                        <button class="btn btn-primary" style="margin-top: 3px;">Upgrade</button>
                                    @endif

                                </div>
                                @endif
                                
                            </fieldset>
                            
                            @if ($user->admin == 1)
                            <fieldset>
                                <legend><font color="#000000">App Settings</font></legend>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="color: #000;">Default Currency</label>

                                    <div class="col-sm-6">
                                        <select name="app_currency" class="form-control">
                                            @foreach(\App\Lib\CurrencyService::getCurrencies() as $currency)
                                                <option value="{{ $currency }}"
                                                @if(array_get(Auth::user(), 'currency') == $currency)
                                                        selected @endif>{{ $currency }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            @endif

                            @if ($user->admin == 1)
                            <fieldset>
                                <legend><font color="#000000">Gutschein für Upgrade</font></legend>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="color: #000;">Gutscheincode:</label>

                                    <div class="col-sm-6">
                                        <input placeholder="" class="form-control"
                                               type="text" name="coupon_code">
                                    </div>

                                    <button class="btn btn-primary" style="margin-top: 3px;">Add</button>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend><font color="#000000"></font></legend>

                                @foreach($coupons as $coupon)
                                <div id="coupon-{{ $coupon->coupon_code }}" class="form-group" style="margin-bottom: 0px;">
                                    <label class="col-sm-3 control-label" style="color: #000;"></label>

                                    <div class="col-sm-6" style="color: #000;">{{ $coupon->coupon_code }}</div>
                                    <div id="delete-coupon" onclick="DELETE_COUPON('{{ $coupon->coupon_code }}');" class="btn btn-primary" style="padding: 0px; margin-top: -3px; border-color: transparent; background-color: transparent; color: #F00; font-size: 17px;"><i class="fa fa-remove"></i></div>

                                </div>
                                @endforeach

                            </fieldset>

                            @endif

                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <button class="btn btn-primary">Speichern</button>
                                    &nbsp;
                                    <button class="btn btn-default">Abbrechen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form class="form-horizontal">
 <fieldset>
                        <legend><font color="#000000">Account Details</font></legend>

                      

                    @if(Auth::user()->premium == 0)

                       <div class="form-group">
                            <div class="row">
                            <label style="text-align:left;padding-left: 40px;" class="col-sm-6 control-label">Basic-Mitglied<p><a href="upgrade">(Jetzt Upgraden)</a></label>
                            </div>
                            <div class="row">
                             <label style="text-align:left;padding-left: 40px;" class="col-sm-7 control-label">Gültig bis: unbegrenzt</label>
                             </div> 
                        </div>

                    @elseif(Auth::user()->premium == 1)

                        <div class="form-group">
                            <div class="row">
                            <label style="text-align:left;padding-left: 40px;" class="col-sm-6 control-label">Premium-Mitglied</label>
                            </div>
                            <div class="row">
                             <label style="text-align:left;padding-left: 40px;" class="col-sm-7 control-label">Gültig bis: @if(strtotime($user->premium_expire_date) != false && strtotime($user->premium_expire_date) > 0) {{ date("d.m.Y", strtotime($user->premium_expire_date) ) }} @else {{ "00.00.0000" }} @endif</label>
                             </div> 
                        </div>

                    @else(Auth::user()->premium == 2)

                       <div class="form-group">
                            <div class="row">
                            <label style="text-align:left;padding-left: 40px;" class="col-sm-6 control-label">Plus-Mitglied</label>
                            </div>
                            <div class="row">
                             <label style="text-align:left;padding-left: 40px;" class="col-sm-7 control-label">Gültig bis: @if(strtotime($user->premium_expire_date) != false && strtotime($user->premium_expire_date) > 0) {{ date("d.m.Y", strtotime($user->premium_expire_date ) ) }} @else {{ "00.00.0000" }} @endif</label>
                             </div> 
                        </div>

                    @endif
                            
                                      
                        
                    </fieldset><br><br>
                    <fieldset>
                        <legend><font color="#000000">Editor-Version</font></legend>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Version:</label>

                            <div class="col-sm-6">
                                <p class="form-control">
                                    2.1.0.0
                                </p>
                            </div>
							
                        </div>                     
						
                    </fieldset>
                   

                </form>
            </div>
        </div>
    </section>

@overwrite