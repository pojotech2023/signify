<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo">
          <img 
            src="{{ asset('images/logo/logo.png') }}"
            alt="navbar brand"
            class="navbar-brand"
            height="50"
          />
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
      <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">
          <li class="nav-item active">
            <a href="{{ route('admin.dashboard') }}"
              class="collapsed"
              aria-expanded="false"
            >
            <i class="bi bi-speedometer"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#base">
              <i class="far fa-chart-bar"></i>
              <p>Leads</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#sidebarLayouts">
                <i class="bi bi-cart-fill"></i>
              <p>Orders</p>
            </a>
            
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#forms">
                <i class="bi bi-bag-fill"></i>
              <p>Jobs</p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a data-bs-toggle="collapse" href="#forms">
                <i class="bi bi-bag-fill"></i>
              <p>Accounts</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#tables">
                <i class="bi bi-graph-up"></i>
              <p>R&D</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#maps">
                <i class="bi bi-chat-left-dots-fill"></i>
              <p>HR</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#charts">
                <i class="bi bi-gear-fill"></i>
              <p>PR</p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="widgets.html">
                <i class="bi bi-person-plus-fill"></i>
              <p>User Creation</p>
            </a>
          </li>
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
                      <span class="sub-item">Material List</span>
                    </a>
                  </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#reportmenu">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
              <p>Reprt</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="reportmenu">
              <ul class="nav nav-collapse">
                <li>
                  <a href="#">
                    <span class="sub-item">Lead Report</span>
                  </a>
                </li>
                <li>
                    <a href="#">
                      <span class="sub-item">Order Report</span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <span class="sub-item">Payment Report</span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <span class="sub-item">R&D Report</span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <span class="sub-item">HR Report</span>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <span class="sub-item">PR Report</span>
                    </a>
                  </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a href="../../documentation/index.html">
                <i class="bi bi-box-arrow-right"></i>
              <p>Sign Out</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>