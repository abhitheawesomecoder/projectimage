<div class="row">
    @if($errors->count() > 0)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </div>
    @endif
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ implode('<br />', (array) Session::get('success')) }}
        </div>
    @endif
    @if (Session::has('message'))
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ implode('<br />', (array) Session::get('message')) }}
        </div>
    @endif
    @if (Session::has('warning'))
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ implode('<br />', (array) Session::get('warning')) }}
        </div>
    @endif
    @if(Session::has('info'))
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            @foreach(Session::get('info') as $info)
                <li>{{$info}}</li>
            @endforeach
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ implode('<br />', (array) Session::get('error')) }}
        </div>
    @elseif (Session::has('status'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ implode('<br />', (array) Session::get('status')) }}
        </div>
    @endif
</div>