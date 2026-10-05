<?php $isMentor = isset($isMentor) ? $isMentor : false; ?>
<style>
/* --- Sidebar Styling (Disalin persis dari dashboard.php) --- */
#sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background-color: var(--sidebar-bg, #1c1032);
    color: var(--sidebar-text, #c8bfe7);
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 4px 0 20px rgba(121, 75, 196, 0.08);
    overflow-y: auto;
}

#sidebar .sidebar-header {
    padding: 20px 15px 15px 15px;
    background: transparent;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
}

#sidebar .logo-card {
    background-color: #ffffff;
    border-radius: 14px;
    padding: 10px 14px;
    display: inline-block;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    width: 85%;
}

#sidebar .logo-card img {
    max-width: 100%;
    height: 45px;
    object-fit: contain;
}

#sidebar .panel-title {
    color: #a497c6;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    margin-top: 12px;
    margin-bottom: 0;
    text-transform: uppercase;
}

#sidebar .nav {
    padding: 12px 10px;
}

#sidebar .nav-item {
    margin-bottom: 4px;
}

#sidebar .nav-link {
    color: var(--sidebar-text, #c8bfe7);
    padding: 9px 14px;
    display: flex;
    align-items: center;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 0.85rem;
    text-decoration: none;
}

#sidebar .nav-link i {
    margin-right: 10px;
    font-size: 0.95rem;
    width: 18px;
    text-align: center;
}

#sidebar .nav-link:hover {
    background-color: rgba(121, 75, 196, 0.2);
    color: #ffffff;
}

#sidebar .nav-link.active {
    background: var(--sidebar-active-gradient, linear-gradient(135deg, #794bc4 0%, #5931a0 100%));
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
}

#sidebar .nav-link.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.2);
    color: #ff6b6b !important;
}

#sidebar .nav-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

/* --- Responsif Layout --- */
@media (max-width: 992px) {
    #sidebar {
        width: 70px;
    }
    #sidebar .sidebar-header .logo-card {
        padding: 6px;
        width: 100%;
    }
    #sidebar .sidebar-header img {
        height: 30px;
    }
    #sidebar .panel-title, #sidebar span {
        display: none;
    }
    #sidebar .nav-link {
        justify-content: center;
        padding: 10px;
    }
    #sidebar .nav-link i {
        margin-right: 0;
    }
}
</style>

<nav id="sidebar">
    <div class="sidebar-header">
        <div class="logo-card">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy">
        </div>
        <div class="panel-title"><?= $isMentor ? 'PANEL MENTOR' : 'PANEL ADMIN' ?></div>[cite: 7]
    </div>
    
    <ul class="nav flex-column">
        <?php if ($isMentor): ?>
            <!-- Menu Khusus Mentor -->
            <li class="nav-item">
                <a href="<?= base_url('mentor/dashboard'); ?>" class="nav-link <?= url_is('mentor/dashboard*') ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard Mentor</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('mentor/kelas'); ?>" class="nav-link <?= url_is('mentor/kelas*') ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> <span>Daftar Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= url_is('mentor/laporan*') ? '' : 'collapsed' ?>" data-bs-toggle="collapse" href="#submenuLaporanMentor" role="button" aria-expanded="<?= url_is('mentor/laporan*') ? 'true' : 'false' ?>">
                    <i class="fas fa-file-lines"></i> <span>Laporan</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem; transition: transform 0.3s;"></i>
                </a>
                <div class="collapse <?= url_is('mentor/laporan*') ? 'show' : '' ?>" id="submenuLaporanMentor">
                    <ul class="nav flex-column ms-2">
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('mentor/laporan-peserta'); ?>" class="nav-link <?= url_is('mentor/laporan-peserta*') ? 'active' : '' ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                                <i class="fas fa-chart-pie" style="width: 14px; margin-right: 8px;"></i> <span>Laporan Peserta</span>
                            </a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('mentor/laporan-kehadiran'); ?>" class="nav-link <?= url_is('mentor/laporan-kehadiran*') ? 'active' : '' ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                                <i class="fas fa-calendar-check" style="width: 14px; margin-right: 8px;"></i> <span>Laporan Kehadiran</span>
                            </a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('mentor/laporan-angket'); ?>" class="nav-link <?= url_is('mentor/laporan-angket*') ? 'active' : '' ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                                <i class="fas fa-star-half-stroke" style="width: 14px; margin-right: 8px;"></i> <span>Laporan Angket</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('mentor/profil'); ?>" class="nav-link <?= url_is('mentor/profil*') ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> <span>Profil Mentor</span>
                </a>
            </li>

        <?php else: ?>
            <!-- Menu Khusus Admin -->
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link <?= url_is('admin/dashboard*') ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link <?= url_is('admin/master-kelas*') ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> <span>Master Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/mentor'); ?>" class="nav-link <?= url_is('admin/mentor*') ? 'active' : '' ?>">
                    <i class="fas fa-chalkboard-user"></i> <span>Instruktur</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link <?= url_is('admin/data-peserta*') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link <?= url_is('admin/validasi*') ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
            <!-- Menu Baru: Manajemen KBM -->
            <li class="nav-item">
                <a href="<?= base_url('admin/manajemen-kbm'); ?>" class="nav-link <?= url_is('admin/manajemen-kbm*') ? 'active' : '' ?>">
                    <i class="fas fa-graduation-cap"></i> <span>Manajemen KBM</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link <?= url_is('admin/buku-induk*') ? 'active' : '' ?>">
                    <i class="fas fa-book-open"></i> <span>Buku Induk</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link <?= (url_is('admin/angket*') || url_is('admin/hasil_angket*')) ? 'active' : '' ?>">
                    <i class="fas fa-award"></i> <span>Angket</span>
                </a>
                <?php if (url_is('admin/angket*') || url_is('admin/hasil_angket*')): ?>
                <ul class="nav flex-column ms-3 mt-1" style="list-style:none;">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/hasil_angket'); ?>" class="nav-link <?= url_is('admin/hasil_angket*') ? 'active' : '' ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                            <i class="fas fa-poll-h" style="width: 14px; margin-right: 8px;"></i> <span>Hasil Angket</span>
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link <?= url_is('admin/sertifikat*') ? 'active' : '' ?>">
                    <i class="fas fa-award"></i> <span>Sertifikat</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= url_is('admin/laporan*') ? '' : 'collapsed' ?>" data-bs-toggle="collapse" href="#submenuLaporanAdmin" role="button" aria-expanded="<?= url_is('admin/laporan*') ? 'true' : 'false' ?>">
                    <i class="fas fa-file-lines"></i> <span>Laporan</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem; transition: transform 0.3s;"></i>
                </a>
                <div class="collapse <?= url_is('admin/laporan*') ? 'show' : '' ?>" id="submenuLaporanAdmin">
                    <ul class="nav flex-column ms-3 mt-1" style="list-style:none;">
                        <li class="nav-item">
                            <a href="<?= base_url('admin/laporan-peserta'); ?>" class="nav-link <?= (url_is('admin/laporan-peserta*') || url_is('admin/laporan')) ? 'active' : ''; ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                                <i class="fas fa-users" style="width: 14px; margin-right: 8px;"></i> <span>Laporan Peserta</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/laporan-kehadiran'); ?>" class="nav-link <?= url_is('admin/laporan-kehadiran*') ? 'active' : ''; ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                                <i class="fas fa-calendar-check" style="width: 14px; margin-right: 8px;"></i> <span>Laporan Kehadiran</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/laporan-mentor'); ?>" class="nav-link <?= url_is('admin/laporan-mentor*') ? 'active' : ''; ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                                <i class="fas fa-chalkboard-user" style="width: 14px; margin-right: 8px;"></i> <span>Laporan Instruktur</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/laporan-angket'); ?>" class="nav-link <?= url_is('admin/laporan-angket*') ? 'active' : ''; ?>" style="padding: 6px 14px; font-size: 0.78rem;">
                                <i class="fas fa-star" style="width: 14px; margin-right: 8px;"></i> <span>Laporan Angket Mentor</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link <?= url_is('admin/pengaturan*') ? 'active' : '' ?>">
                    <i class="fas fa-gear"></i> <span>Pengaturan</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item mt-3">
            <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>