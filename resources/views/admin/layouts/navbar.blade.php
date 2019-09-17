@if (! Auth::guest())

    <div class="col-lg-1 col-sm-1 hidden-xs-down" id="side-navbar">
        <div class="col-12 col-lg-8 col-sm-12" id="content">
            <ul class="list-unstyled text-center" id="list-menu">
               <li><a href="/administrator#/" ><img src="/img/popcorn.svg" alt="logo" class="logo"></a></li>
                <li><a href="/administrator#/movies/manage"><i class="fa fa-film fa-2x" aria-hidden="true"></i></a></li>
                <li><a href="/administrator#/series/manage"><i class="fa fa-television fa-2x" aria-hidden="true"></i></a></li>
                 <li><a href="/administrator#/tv/manage"><img src="/img/live.svg" alt="actor" style="margin:13px;" width="25"></a></li>
                <li><a href="/administrator#/top/manage"><i class="fa fa-star fa-2x" aria-hidden="true"></i></a></li>
                <li><a href="/administrator#/actors/manage"><img src="/img/actor.svg" alt="actor" style="margin:13px;" width="25"></a></li>
                <li><a href="/administrator#/subtitle/manage"><img src="/img/caption.svg" alt="caption" style="margin:15px;" width="20"></a></li>
                <li><a href="/administrator#/report/manage"><i class="fa fa-flag fa-2x" aria-hidden="true"></i></a></li>
                <li><a href="/administrator#/comment/manage"><i class="fa fa-commenting-o fa-2x" aria-hidden="true"></i></a>
                <li><a href="/administrator#/users/manage" ><i class="fa fa-users fa-2x" aria-hidden="true"></i></a></li>
                <li>
                    <div class="dropdown">
                        <a id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cog fa-2x" aria-hidden="true"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <a href="/administrator#/settings/theme/manage" role="button" class="dropdown-item"><i class="fa fa-paint-brush" aria-hidden="true"></i> Theme </a>
                            <a href="/administrator#/settings/users/manage" role="button" class="dropdown-item"><i class="fa fa-users" aria-hidden="true"></i> Administrators Manage</a>
                            <a href="/administrator#/settings/backup" role="button" class="dropdown-item"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Backup</a>
                        </div>
                    </div>
                </li>
                <li class="mt-3" style="border:none !important;"><a href="/administrator#/profile">
                        @if(Auth::user()->image !== 'img/avatar.png')
                            <img src="{{Illuminate\Support\Facades\Storage::disk('s3')->url('users_image/').Auth::user()->image}}" alt="{{Auth::user()->image}}" width="40" id="avatar">
                        @else
                            <img src="{{Auth::user()->image}}" alt="{{Auth::user()->image}}" width="40" id="avatar">
                        @endif
                    </a></li>
            </ul>
        </div>
    </div>
    <div class="col-11 offset-1 p-4">
        
        <div id="administrator">
@endif
@yield('content')
