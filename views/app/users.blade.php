@extends("layouts.template")

@section("page_title", "Benutzer")@stop

@section("content")
    <section id="listing-ctrl" class="row">
    @if($users->count() != '0')
        @foreach($users as $user)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                   <h3 class="product-title">
                        
                        <div class="product-details" style="margin-top: 10px;">
                            <div class="product-summary">
                                Username: <strong>{{ $user->username }}</strong><br /><br />
                                Email: <strong>{{ $user->email }}</strong><br /><br />
                                Status: @if($user->admin == '1')
                                        <strong>Admin</strong>
                                        @endif
                                        @if($user->premium == '1' && $user->admin == '0')
                                        <strong>Premium</strong>
                                        @endif
                                        @if($user->premium != '1' && $user->admin == '0')
                                        <strong>User</strong>
                                        @endif<br/>
                            </div><a href="{{ URL::to('users/delete/' . $user->id) }}" style="position: absolute; top: 16px; right: 30px; border: transparent; background-color: transparent; color: #F00; font-size: 20px; display: inline-block;"><i class="fa fa-remove"></i></a>

                        </div>


                    </h3>
                </div>
            </div>
        @endforeach
    @else
        <div class="NO_IMAGE">
            <center><h3>Es wurde bisher keine Inhalte angelegt.</h3><br>Erstellen Sie jetzt Ihren ersten Inhalt. <a href="upload">Inhalt erstellen.</a></center>
        </div>
    @endif

    </section>

    <center><?php echo $users->links(); ?></center>
@overwrite