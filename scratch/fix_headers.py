import os
import re

# We will apply the standard header format for top-navbar
# Specifically focusing on replacing the top-navbar contents to align with dashboard.php

# 1. Buku Induk
buku_induk_path = 'app/Views/admin/buku_induk/index.php'
with open(buku_induk_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace top-navbar in buku_induk
top_navbar_regex = re.compile(r'<div class="top-navbar">([\s\S]*?)</div>\s*<!-- FILTER CARD -->', re.IGNORECASE)

buku_induk_replacement = """<div class="top-navbar d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-toggle-btn d-lg-none" onclick="toggleSidebar()" style="background: none; border: none; font-size: 1.3rem; color: var(--dark-purple);"><i class="fas fa-bars"></i></button>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: var(--dark-purple); font-size: 1.25rem;">Buku Induk Peserta</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Dokumentasi data induk, status sertifikasi, dan riwayat pendaftaran peserta</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <?php $exportQuery = http_build_query($filters); ?>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('admin/buku-induk/export-excel?' . $exportQuery); ?>" class="btn btn-excel btn-sm" target="_blank" style="background: #107c41; color: #fff; padding: 7px 14px; border-radius: 8px; font-weight: 600; font-size: 0.82rem; text-decoration: none;">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="<?= base_url('admin/buku-induk/cetak?' . $exportQuery); ?>" class="btn btn-print btn-sm" target="_blank" style="background: #475569; color: #fff; padding: 7px 14px; border-radius: 8px; font-weight: 600; font-size: 0.82rem; text-decoration: none;">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
                
                <?php
                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
                ?>
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light border" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
                    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
                </div>

                <div class="admin-profile d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-purple);">
                    <div class="admin-info d-none d-sm-block">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.85rem; color: var(--dark-purple);"><?= esc(session()->get('nama')); ?></h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER CARD -->"""

new_content = top_navbar_regex.sub(buku_induk_replacement, content)
with open(buku_induk_path, 'w', encoding='utf-8') as f:
    f.write(new_content)
print(f"Updated {buku_induk_path}")


# 2. Angket Index
angket_path = 'app/Views/admin/angket/index.php'
with open(angket_path, 'r', encoding='utf-8') as f:
    content = f.read()

# For angket, we replace <section class="top-navbar"> ... </section>
angket_regex = re.compile(r'<section class="top-navbar">([\s\S]*?)</section>', re.IGNORECASE)

angket_replacement = """<section class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-toggle-btn d-lg-none" onclick="toggleSidebar()" style="background: none; border: none; font-size: 1.3rem; color: var(--dark-purple); margin-right: 12px;"><i class="fas fa-bars"></i></button>
                <div>
                    <h1 class="page-title mb-1" style="font-size: 1.25rem; font-weight: 700; color: var(--dark-purple);">
                        Monitoring Angket
                    </h1>
                    <p class="page-subtitle mb-0" style="color: var(--muted-text); font-size: 0.75rem;">
                        Ringkasan evaluasi instruktur, tempat pelatihan, dan saran peserta.
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php
                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
                ?>
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light border" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
                    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
                </div>

                <div class="admin-profile d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-purple);">
                    <div class="admin-info d-none d-sm-block">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.85rem; color: var(--dark-purple);"><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Administrator</small>
                    </div>
                </div>
            </div>
        </section>"""

new_content = angket_regex.sub(angket_replacement, content)
with open(angket_path, 'w', encoding='utf-8') as f:
    f.write(new_content)
print(f"Updated {angket_path}")

# 3. Hasil Angket
hasil_path = 'app/Views/admin/angket/hasil.php'
with open(hasil_path, 'r', encoding='utf-8') as f:
    content = f.read()

hasil_regex = re.compile(r'<div class="top-navbar">([\s\S]*?)</div>\s*<!-- Filter Section -->', re.IGNORECASE)

hasil_replacement = """<div class="top-navbar d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-toggle-btn d-lg-none" onclick="toggleSidebar()" style="background: none; border: none; font-size: 1.3rem; color: var(--dark-purple); margin-right: 12px;"><i class="fas fa-bars"></i></button>
                <div class="dash-header m-0 p-0">
                    <h3 class="mb-1 fw-bold" style="font-size: 1.25rem; color: var(--dark-purple);">Hasil Angket Siswa</h3>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Daftar rekapitulasi penilaian peserta.</p>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="d-flex gap-2">
                    <?php 
                        $filterQuery = http_build_query($filters); 
                        $exportUrl = base_url('admin/angket/export_excel_hasil?' . $filterQuery); 
                    ?>
                    <a href="<?= $exportUrl; ?>" class="btn btn-sm text-white px-3" style="background-color: #107c41; border-radius: 8px; font-weight: 600; font-size: 0.82rem; text-decoration: none;">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                </div>

                <?php
                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
                ?>
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light border" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
                    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
                </div>

                <div class="admin-profile d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-purple);">
                    <div class="admin-info d-none d-sm-block">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.85rem; color: var(--dark-purple);"><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->"""

new_content = hasil_regex.sub(hasil_replacement, content)
with open(hasil_path, 'w', encoding='utf-8') as f:
    f.write(new_content)
print(f"Updated {hasil_path}")

