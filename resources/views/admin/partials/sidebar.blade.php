<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="{{ asset('images/logo/logo.png') }}" alt="navbar brand" class="navbar-brand" height="50" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                {{-- Dashboard (Visible to all roles) --}}
                <li class="nav-item active">
                    <a href="{{ route('admin.dashboard') }}" class="collapsed" aria-expanded="false">
                        <i class="bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Leads (Admin and Superuser) --}}
                @if (in_array(session('role_name'), ['Admin', 'Superuser']))
                    <li class="nav-item">
                        <a href="{{ route('leads-list') }}">
                            <i class="far fa-chart-bar"></i>
                            <p>Leads</p>
                        </a>
                    </li>

                    {{-- Orders --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarLayouts">
                            <i class="bi bi-cart-fill"></i>
                            <p>Orders</p>
                        </a>
                    </li>

                    {{-- Jobs --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#forms">
                            <i class="bi bi-bag-fill"></i>
                            <p>Jobs</p>
                        </a>
                    </li>

                    {{-- Aggregator --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#aggregatormenu">
                            <i class="fas fa-file"></i>
                            <p>Aggregator</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="aggregatormenu">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('aggregator-form') }}">
                                        <span class="sub-item">Aggregator Form</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('material-list') }}">
                                        <span class="sub-item">Aggregator List</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>                    
                @endif

                {{-- User Creation (Admin only) --}}
                @if (session('role_name') === 'Admin')
                    <li class="nav-item">
                        <a href="{{ route('usercreation-form') }}">
                            <i class="bi bi-person-plus-fill"></i>
                            <p>User Creation</p>
                        </a>
                    </li>

                    {{-- Reports --}}
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#reportmenu">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                            <p>Report</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="reportmenu">
                            <ul class="nav nav-collapse">
                                <li><a href="#"><span class="sub-item">Lead Report</span></a></li>
                                <li><a href="#"><span class="sub-item">Order Report</span></a></li>
                                <li><a href="#"><span class="sub-item">Payment Report</span></a></li>
                                <li><a href="#"><span class="sub-item">R&D Report</span></a></li>
                                <li><a href="#"><span class="sub-item">HR Report</span></a></li>
                                <li><a href="#"><span class="sub-item">PR Report</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                {{-- Task (Executives: Accounts, PR, HR, R&D) --}}
                @if (in_array(session('role_name'), ['Accounts', 'PR', 'HR', 'R&D']))
                    <li class="nav-item">
                        <a href="{{ route('task-list') }}">
                            <i class="bi bi-list-check"></i>
                            <p>Task</p>
                        </a>
                    </li>
                @endif

                {{-- Logout (Visible to all roles) --}}
                <li class="nav-item">
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <p>Sign Out</p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>

            </ul>
        </div>
    </div>
</div>
