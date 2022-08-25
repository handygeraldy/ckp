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
            Dashboard</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" data-toggle="collapse" data-target="#submaster"><i class="fas fa-database"></i>
            Master</a>
        <ul class="nav nav-list collapse" id="submaster">
            <li class="nav-item {{ Request::is('kredit*') ? 'active' : '' }}"><a href="{{ route('kredit.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> <span>Angka Kredit</span></a></li>
            <li class="nav-item {{ Request::is('golongan*') ? 'active' : '' }}"><a href="{{ route('golongan.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> <span>Golongan</span></a></li>
            <li class="nav-item {{ Request::is('fungsional*') ? 'active' : '' }}"><a
                    href="{{ route('fungsional.index') }}" class="nav-link"><i class="fas fa-list"></i> <span>Fungsional</span></a>
            </li>
            <li class="nav-item {{ Request::is('satker*') ? 'active' : '' }}"><a href="{{ route('satker.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> <span>Satuan Kerja</span></a></li>
            <li class="nav-item {{ Request::is('tim*') ? 'active' : '' }}"><a href="{{ route('tim.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> <span>Tim</span></a></li>
            <li class="nav-item {{ Request::is('satuan*') ? 'active' : '' }}"><a href="{{ route('satuan.index') }}"
                    class="nav-link"><i class="fas fa-list"></i> <span>Satuan Kegiatan</span></a></li>
            <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}"><a href="{{ route('user.index') }}"
                    class="nav-link"><i class="fas fa-users"></i> <span>User</span></a></li>
        </ul>
    </li>
    <li class="nav-item {{ Request::is('ckp*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('ckp.index') }}">
            <i class="fas fa-walking"></i>
            Kegiatan Saya
        </a>
    </li>
    <li class="nav-item {{ Request::is('nilai*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('nilai.index') }}">
            <i class="fa-solid fa-file-pen"></i>
            Penilaian
        </a>
    </li>
</ul>
