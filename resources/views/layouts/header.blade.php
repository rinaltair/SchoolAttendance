<div class="header">
    <div class="header-left">
        <div class="menu-icon dw dw-menu"></div>
    </div>
    <div class="header-right">
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="{{ asset('vendors/images/photo1.jpg') }}" alt="">
                    </span>
                    @if(Auth::user()->role != 1)
                    <span class="user-name">Hai {{ Auth::user()->firstName }}!</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    @if(Auth::user()->role != 1)
{{--                    <a class="dropdown-item" href="/profile/"><i class="dw dw-user1"></i> Profil</a>--}}
                    @endif
                    {{-- <a class="dropdown-item" href="#"><i class="dw dw-settings2"></i> Setting</a> --}}
                    {{-- <a class="dropdown-item" href="#"><i class="dw dw-help"></i> Help</a> --}}
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="dropdown-item" href="/logout"><i class="dw dw-logout"></i> Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
