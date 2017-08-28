<div class="navbar-container" id="navbar-container">
    <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
        <span class="sr-only">Toggle sidebar</span>

        <span class="icon-bar"></span>

        <span class="icon-bar"></span>

        <span class="icon-bar"></span>
    </button>

    <div class="navbar-header pull-left">
        <a href="{{route('home')}}" class="navbar-brand">
            <small>
                {{ config('app.name', 'Laravel') }}
            </small>
        </a>
    </div>

    <div class="navbar-buttons navbar-header pull-right" role="navigation">
        <ul class="nav ace-nav">

            <li class="light-blue">
                <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                    <img class="nav-user-photo" src="{{asset('assets/avatars/user.jpg')}}" alt="Jason's Photo"/>
                    <span class="user-info">
									<small>Welcome Admin,</small>
                                    {{auth()->user()->name}}
								</span>

                    <i class="ace-icon fa fa-caret-down"></i>
                </a>

                <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                    <li>
                        <a href="{{route('settings')}}">
                            <i class="ace-icon fa fa-cog"></i>
                            Settings
                        </a>
                    </li>

                    <li>
                        <a href="{{route('profile')}}">
                            <i class="ace-icon fa fa-user"></i>
                            Profile
                        </a>
                    </li>

                    <li class="divider"></li>

                    <li>

                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="ace-icon fa fa-power-off"></i>
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div><!-- /.navbar-container -->