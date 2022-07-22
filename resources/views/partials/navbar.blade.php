<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('index') }}">
        <div class="sidebar-brand-icon">
            {{-- <img src="{{ asset('/img/logo-bps.svg') }}"> --}}
        </div>
    </a>
    <hr class="sidebar-divider my-0">


    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('index') }}">
            <i class="nav-icon fas fa-desktop"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" data-toggle="collapse" data-target="#submaster"><i class="fas fa-database"></i>
            <span>Master</span></a>
        <ul class="nav nav-list collapse" id="submaster">
            <li class="nav-item {{ Request::is('kredit*') ? 'active' : '' }}"><a href="{{ route('kredit.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> Angka Kredit</a></li>
            <li class="nav-item {{ Request::is('golongan*') ? 'active' : '' }}"><a href="{{ route('golongan.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> Golongan</a></li>
            <li class="nav-item {{ Request::is('fungsional*') ? 'active' : '' }}"><a
                    href="{{ route('fungsional.index') }}" class="nav-link"><i class="fas fa-list"></i> Fungsional</a>
            </li>
            <li class="nav-item {{ Request::is('satker*') ? 'active' : '' }}"><a href="{{ route('satker.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> Satuan Kerja</a></li>
            <li class="nav-item {{ Request::is('tim*') ? 'active' : '' }}"><a href="{{ route('tim.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> Tim</a></li>
            <li class="nav-item {{ Request::is('satuan*') ? 'active' : '' }}"><a href="{{ route('satuan.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> Satuan Kegiatan</a></li>
            <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}"><a href="{{ route('user.index') }}"
                    class="nav-link"><i class="fas fa-users"></i> User</a></li>
        </ul>
    </li>
    <li class="nav-item {{ Request::is('ckp*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('ckp.index') }}">
            <i class="fas fa-walking"></i>
            <span>Kegiatan Saya</span>
        </a>
    </li>
</ul>
