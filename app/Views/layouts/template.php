<!DOCTYPE html>
<html lang="id">

<head>

    <?= $this->include('layouts/header'); ?>

</head>

<body>

    <?= $this->include('layouts/navbar'); ?>

    <?= $this->renderSection('content'); ?>

    <?= $this->include('layouts/footer'); ?>

</body>

</html>