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
    @if (auth()->user()->role_id <= 5)
        <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" data-target="#submaster"><i class="fas fa-database"></i>
                Master</a>
            <ul class="nav nav-list {{ Request::is('kredit*') | Request::is('golongan*') | Request::is('fungsional*') | Request::is('satker*') ? '' : 'collapse' }}"
                id="submaster">
                <li class="nav-item {{ Request::is('kredit*') ? 'active' : '' }}"><a href="{{ route('kredit.index') }}"
                        class="nav-link"><i class="fas fa-list"></i> <span>Angka Kredit</span></a></li>
                <li class="nav-item {{ Request::is('golongan*') ? 'active' : '' }}"><a
                        href="{{ route('golongan.index') }}" class="nav-link"><i class="fas fa-list"></i>
                        <span>Golongan</span></a></li>
                <li class="nav-item {{ Request::is('fungsional*') ? 'active' : '' }}"><a
                        href="{{ route('fungsional.index') }}" class="nav-link"><i class="fas fa-list"></i>
                        <span>Fungsional</span></a>
                </li>
                <li class="nav-item {{ Request::is('satker*') ? 'active' : '' }}"><a
                        href="{{ route('satker.index') }}" class="nav-link"><i class="fas fa-list"></i> <span>Satuan
                            Kerja</span></a></li>
            </ul>
        </li>
    @endif
    <li class="nav-item {{ Request::is('user*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.index') }}">
            <i class="fas fa-users"></i>
            Pegawai
        </a>
    </li>
<<<<<<< HEAD
    {{--  --}}
    <li class="nav-item ">
        <a class="nav-link" data-toggle="collapse" data-target="#tim_kerja"><i class="fa-regular fa-folder"></i>
            Tim Kerja</a>
        <ul class="nav nav-list collapse"
            id="tim_kerja">
            <li class="nav-item nav-fill w-100 {{ Request::is('tim*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tim.index') }}">
                    <i class="fa-sharp fa-solid fa-people-group"></i>
                    Daftar Tim
                </a>
            </li>
            {{-- projek --}}
            <li class="nav-item nav-fill w-100 {{ Request::is('projek*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tim.index') }}">
                    <i class="fa-sharp fa-solid fa-people-group"></i>
                    Projek
                </a>
            </li>
            {{-- kegiatan --}}
            <li class="nav-item nav-fill w-100 {{ Request::is('kegiatan-tim*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tim.index') }}">
                    <i class="fa-sharp fa-solid fa-people-group"></i>
                    Kegiatan
                </a>
            </li>
        </ul>
    </li>
    <li class="nav-item ">
        <a class="nav-link" data-toggle="collapse" data-target="#ckp"><i class="fa-regular fa-folder"></i>
            CKP</a>
        <ul class="nav nav-list collapse"
            id="ckp">
            <li class="nav-item nav-fill w-100 {{ Request::is('ckp*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('ckp.index') }}">
                    <i class="fas fa-walking"></i>
                    Kegiatan
                </a>
            </li>
            @if (auth()->user()->role_id >= 11)
                <li class="nav-item nav-fill w-100 {{ Request::is('nilai*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('nilai.index') }}">
                        <i class="fa-solid fa-file-pen"></i>
                        Penilaian
                    </a>
                </li>
            @endif
            @if (auth()->user()->role_id <= 8)
                <li class="nav-item nav-fill w-100 {{ Request::is('approval*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('approval.index') }}">
                        <i class="fa-solid fa-clipboard-check"></i>
                        Approval
                    </a>
                </li>
            @endif
            <li class="nav-item nav-fill w-100 {{ Request::is('arsip*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('arsip.index') }}">
                    <i class="fa-regular fa-folder-open"></i>
                    Arsip
                </a>
            </li>
        </ul>
=======
    <li class="nav-item {{ Request::is('tim*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('tim.index') }}">
            <i class="fa-sharp fa-solid fa-people-group"></i>
            Tim Kerja
        </a>
    </li>
    @if ((auth()->user()->role_id > 11) | (auth()->user()->role_id < 8))
        <li class="nav-item {{ Request::is('ckp*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('ckp.index') }}">
                <i class="fas fa-walking"></i>
                Kegiatan Saya
            </a>
        </li>
    @endif
    @if ((auth()->user()->role_id == 11) | (auth()->user()->role_id < 8))
        <li class="nav-item {{ Request::is('nilai*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('nilai.index') }}">
                <i class="fa-solid fa-file-pen"></i>
                Penilaian
            </a>
        </li>
    @endif
    @if (auth()->user()->role_id <= 8)
        <li class="nav-item {{ Request::is('approval*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('approval.index') }}">
                <i class="fa-solid fa-clipboard-check"></i>
                Approval
            </a>
        </li>
    @endif
    <li class="nav-item {{ Request::is('arsip*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('arsip.index') }}">
            <i class="fa-regular fa-folder-open"></i>
            Arsip
        </a>
>>>>>>> 6885bee04f622a655064d7c0a4ebc9befdbfdfcc
    </li>
</ul>
