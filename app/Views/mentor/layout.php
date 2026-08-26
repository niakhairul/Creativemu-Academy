<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Mentor') ?> - Creativemu Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body{background:#f6f7fb}.sidebar{width:260px;min-height:100vh;background:#22133c;position:fixed;color:#fff}.sidebar a{color:#d8ceef;text-decoration:none;display:block;padding:12px 18px;border-radius:12px;margin:6px 14px}.sidebar a.active,.sidebar a:hover{background:#794bc4;color:#fff}.main{margin-left:260px;padding:32px}.card{border-radius:14px}</style>
</head>
<body>
<div class="sidebar py-4">
    <div class="px-4 mb-4 fw-bold fs-5">Creativemu Mentor</div>
    <a href="<?= base_url('mentor/dashboard') ?>"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a>
    <a href="<?= base_url('mentor/kelas') ?>"><i class="fa-solid fa-book me-2"></i> Daftar Kelas</a>
    <a href="<?= base_url('mentor/profil') ?>"><i class="fa-solid fa-user me-2"></i> Profil Mentor</a>
    <a href="<?= base_url('logout') ?>" class="text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
</div>
<main class="main">
    <?php if(session()->getFlashdata('success')): ?><div class="alert alert-success"><?= session()->getFlashdata('success') ?></div><?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div><?php endif; ?>
    <?= $this->renderSection('content') ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
