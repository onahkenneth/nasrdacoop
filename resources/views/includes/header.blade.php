<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <!-- Logo-->
            <div><a href="{{url('/')}}" class="logo"><img src="{{ asset('assets/images/logo.png') }}" alt="" height="26"></a>
            </div><!-- End Logo-->
            <div class="menu-extras topbar-custom navbar p-0">
                <div class="search-wrap" id="search-wrap">
                    <div class="search-bar">
						<form  role="form" method="POST" action="">
						{{ csrf_field() }}
                        <input class="search-input" name="search" type="search" placeholder="Search" id="search">
                        </form> 
                        <a href="#" class="close-search toggle-search" data-target="#search-wrap"><i class="mdi mdi-close-circle"></i>
                        </a>
                    </div>
                </div>
                <ul class="list-inline ml-auto mb-0">
                    <!-- notification-->
                    <li class="list-inline-item dropdown notification-list"><a
                            class="nav-link waves-effect toggle-search" href="#" data-target="#search-wrap"><i
                                class="mdi mdi-magnify noti-icon"></i></a></li>
                    <li class="list-inline-item dropdown notification-list nav-user">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"> 
                            <span class="d-none d-md-inline-block ml-1">
                            @auth
                                {{auth()->user()->name}}
                            @endauth
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </li>
                    <li class="menu-item list-inline-item">
                        <!-- Mobile menu toggle--> <a class="navbar-toggle nav-link">
                            <div class="lines"><span></span> <span></span> <span></span></div>
                        </a><!-- End mobile menu toggle-->
                    </li>
                </ul>
            </div><!-- end menu-extras -->
            <div class="clearfix"></div>
        </div><!-- end container -->
    </div><!-- end topbar-main -->
    <!-- MENU Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    <li class="has-submenu"><a href="{{ route('dashboard') }}"><i class="dripicons-home"></i>
                            Dashboard</a>
                    </li>
                    @if(auth()->user()->can('generate IPPIS deduction file') && auth()->user()->can('import and reconcile IPPIS deduction file'))
                    <li class="has-submenu"><a href="{{ route('generateDeductions') }}"><i class="dripicons-suitcase"></i>
                            Deductions </a>
                    </li>
                    @endif
                    <li class="has-submenu"><a href="{{ route('members.index') }}"><i class="dripicons-suitcase"></i>
                            Members </a>
                    </li>
                    @can('generate reports')
                    <li class="has-submenu"><a href="#"><i class="dripicons-duplicate"></i> Reports <i
                                class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><a href="{{ route('reports') }}">General Reports</a></li>
                                    <li><a href="{{ route('reports.monthlyDefaults') }}">Monthly Defaults</a></li>
                                    <li><a href="{{ route('reports.loanDefaults') }}">Loan Defaults</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    <li class="has-submenu"><a href="#"><i class="dripicons-duplicate"></i> Settings <i
                                class="mdi mdi-chevron-down mdi-drop"></i></a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><a href="{{ route('getImportInitialLedgerSummary') }}">Import Ledger Summary</a></li>
                                    <li><a href="{{ route('getImportInitialLedger') }}">Import Ledger</a></li>
                                    <li><a href="{{ route('centers.index') }}">Centers</a></li>
                                    <li><a href="{{ route('users.index') }}">Users</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i class="dripicons-suitcase"></i>Logout </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    </li>
                </ul><!-- End navigation menu -->
            </div><!-- end #navigation -->
        </div><!-- end container -->
    </div><!-- end navbar-custom -->
</header>
