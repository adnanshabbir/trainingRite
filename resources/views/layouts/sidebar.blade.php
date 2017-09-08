<script type="text/javascript">
    try {
        ace.settings.check('sidebar', 'fixed')
    } catch (e) {
    }
</script>

<ul class="nav nav-list">

    {{--Home Or Dashboard--}}
    <li class="{{ (\Request::route()->getName() == 'home') ? 'active' : '' }}">
        <a href="{{route('home')}}">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Dashboard </span>
        </a>

        <b class="arrow"></b>
    </li>


    {{--Send Messages--}}
    <li class="{{ (\Request::route()->getName() == 'create_bulk_messages') ? 'active' : '' }}">
        <a href="{{route('create_bulk_messages')}}">
            <i class="menu-icon fa fa-bolt"></i>
            <span class="menu-text"> Send Messages </span>
        </a>

        <b class="arrow"></b>
    </li>




    {{--Messages Logs--}}
    <?php $messageLogs = [ 'inbound_logs', 'outbound_logs' ];?>
    <li @if(in_array( \Request::route()->getName(), $messageLogs)) class="active open" @endif>
        {{--<li class="active open">--}}
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-envelope"></i>
            <span class="menu-text">Message Logs</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>

        <ul class="submenu">

            {{--Inbound Logs--}}
            <li class="{{ (\Request::route()->getName() == 'inbound_logs') ? 'active' : '' }}">
                <a href="{{route('inbound_logs')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Inbound
                </a>
                <b class="arrow"></b>
            </li>


            {{--Outbound Logs--}}
            <li class="{{ (\Request::route()->getName() == 'outbound_logs') ? 'active' : '' }}">
                <a href="{{route('outbound_logs')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Outbound
                </a>
                <b class="arrow"></b>
            </li>
        </ul>
    </li>



    {{--Call flow--}}
    <li class="{{ (\Request::route()->getName() == 'set_call_flow') ? 'active' : '' }}">
        <a href="{{route('set_call_flow')}}">
            <i class="menu-icon fa fa-phone"></i>
            <span class="menu-text"> Call </span>
        </a>

        <b class="arrow"></b>
    </li>



    {{--Cals Logs--}}
    <?php $callsLogs = [ 'inbound_calls_logs', 'outbound_calls_logs' ];?>
    <li @if(in_array( \Request::route()->getName(), $callsLogs)) class="active open" @endif>
        {{--<li class="active open">--}}
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-cloud"></i>
            <span class="menu-text">Calls Logs</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>

        <ul class="submenu">

            {{--Inbound Logs--}}
            <li class="{{ (\Request::route()->getName() == 'inbound_calls_logs') ? 'active' : '' }}">
                <a href="{{route('inbound_calls_logs')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Inbound
                </a>
                <b class="arrow"></b>
            </li>


            {{--Outbound Logs--}}
            <li class="{{ (\Request::route()->getName() == 'outbound_calls_logs') ? 'active' : '' }}">
                <a href="{{route('outbound_calls_logs')}}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Outbound
                </a>
                <b class="arrow"></b>
            </li>
        </ul>
    </li>





    {{--API Settings--}}
    <li class="{{ (\Request::route()->getName() == 'settings') ? 'active' : '' }}">
        <a href="{{route('settings')}}">
            <i class="menu-icon fa fa-cogs"></i>
            <span class="menu-text"> Settings </span>
        </a>

        <b class="arrow"></b>
    </li>


    {{--Profile--}}
    <li class="{{ (\Request::route()->getName() == 'profile') ? 'active' : '' }}">
        <a href="{{route('profile')}}">
            <i class="menu-icon fa fa-user"></i>
            <span class="menu-text"> Profile </span>
        </a>

        <b class="arrow"></b>
    </li>


    {{--Logout--}}
    <li>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <i class="menu-icon fa fa-sign-out"></i> <span class="nav-label">Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>


</ul><!-- /.nav-list -->

<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
    <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
       data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>

<script type="text/javascript">
    try {
        ace.settings.check('sidebar', 'collapsed')
    } catch (e) {
    }
</script>