<?php require 'vendor/autoload.php'; require 'system/bootstrap.php'; require 'app/Services/LaravelApiService.php'; \ = new App\Services\LaravelApiService(); print_r(\->request('GET', 'classes'));
