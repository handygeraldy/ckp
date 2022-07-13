<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('index') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('/img/logo-bps.svg') }}">
        </div>
    </a>
    <hr class="sidebar-divider my-0">


    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('index') }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" data-toggle="collapse" data-target="#submaster"><i class="fas fa-fw fa-book"></i>
            <span>Master</span></a>
        <ul class="nav nav-list collapse" id="submaster">
            <li class="nav-item {{ Request::is('kredit*') ? 'active' : '' }}"><a href="{{ route('kredit.index') }}"
                    class="nav-link"><i class="fas fa-tasks"></i> Angka Kredit</a></li>
            <li class="nav-item {{ Request::is('golongan*') ? 'active' : '' }}"><a href="{{ route('golongan.index') }}"
                    class="nav-link"><i class="fas fa-tasks"></i> Golongan</a></li>
            <li class="nav-item {{ Request::is('fungsional*') ? 'active' : '' }}"><a href="{{ route('fungsional.index') }}"
                    class="nav-link"><i class="fas fa-tasks"></i> Fungsional</a></li>
            <li class="nav-item {{ Request::is('satker*') ? 'active' : '' }}"><a href="{{ route('satker.index') }}"
                    class="nav-link"><i class="fas fa-tasks"></i> Satuan Kerja</a></li>
            <li class="nav-item {{ Request::is('satuan*') ? 'active' : '' }}"><a href="{{ route('satuan.index') }}"
                    class="nav-link"><i class="fas fa-tasks"></i> Satuan Kegiatan</a></li>
        </ul>
    </li>
    <li class="nav-item {{ Request::is('input*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('ckp.create') }}">
            <i class="fas fa-edit"></i>
            <span>Input</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('kegiatan*') ? 'active' : '' }}">
        <a class="nav-link" href="">
            <i class="fas fa-folder-open"></i>
            <span>Kegiatan Saya</span>
        </a>
    </li>
</ul>
