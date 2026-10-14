<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas Pelatihan - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>

        :root {
            --purple-primary: #7a3ff2;
            --purple-strong: #4d1d95;
            --purple-mid: #9565ff;
            --purple-soft: #eee7ff;
            --purple-tint: #faf7ff;
            --ink: #20143f;
            --muted: #6f6581;
            --line: rgba(122, 63, 242, 0.16);
            --surface: rgba(255, 255, 255, 0.86);
            --shadow: 0 22px 60px rgba(54, 24, 108, 0.14);
            --ease: cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            background:
                linear-gradient(135deg, rgba(248, 245, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 42%, rgba(235, 229, 255, 0.88) 100%),
                repeating-linear-gradient(115deg, rgba(122, 63, 242, 0.045) 0 1px, transparent 1px 28px);
            overflow-x: hidden;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes softSweep {
            from { transform: translateX(-34%) skewX(-10deg); }
            to { transform: translateX(34%) skewX(-10deg); }
        }

        @keyframes floatPanel {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeInUp 0.72s var(--ease) forwards;
        }

        .navbar-custom {
            padding: 12px 0;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(122, 63, 242, 0.12);
            box-shadow: 0 12px 34px rgba(54, 24, 108, 0.08);
        }

        .navbar-logo-wrapper {
            background-color: #ffffff;
            border-radius: 14px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(122, 63, 242, 0.18);
        }

        .navbar-logo {
            width: auto;
            max-width: 140px;
            height: auto;
            max-height: 40px;
            object-fit: contain;
            display: block;
        }

        .brand-text {
            max-width: 260px;
            line-height: 1.15;
            font-weight: 800;
            color: var(--purple-strong);
            font-size: 1.5rem;
        }
        .btn-auth-mobile {
            padding: 8px 18px;
            font-size: 0.95rem;
        }

        .nav-link {
            position: relative;
            width: max-content;
            font-weight: 700;
            color: var(--muted) !important;
            transition: color 0.25s ease;
            cursor: pointer;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 2px;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--purple-primary), var(--purple-mid));
            border-radius: 99px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.28s var(--ease);
        }

        .nav-link:hover,
        .nav-link.active { color: var(--purple-primary) !important; }
        .nav-link:hover::after,
        .nav-link.active::after { transform: scaleX(1); }

        .btn-outline-custom-auth,
        .btn-custom-auth,
        .btn-outline-detail,
        .btn-custom-daftar {
            min-height: 42px;
            border-radius: 14px;
            font-weight: 800;
            transition: transform 0.28s var(--ease), box-shadow 0.28s var(--ease), background 0.28s ease, color 0.28s ease, border-color 0.28s ease;
        }

        .btn-outline-custom-auth {
            border: 1.5px solid rgba(122, 63, 242, 0.45);
            color: var(--purple-primary);
            background: rgba(255, 255, 255, 0.7);
            font-size: 0.86rem;
            padding: 8px 16px;
        }

        .btn-outline-custom-auth:hover {
            color: var(--purple-strong);
            border-color: var(--purple-primary);
            background: var(--purple-tint);
            transform: translateY(-2px);
        }

        .btn-custom-auth,
        .btn-custom-daftar {
            color: #ffffff;
            border: none;
            background: linear-gradient(135deg, var(--purple-mid) 0%, var(--purple-primary) 48%, var(--purple-strong) 100%);
            box-shadow: 0 14px 28px rgba(122, 63, 242, 0.28);
        }

        .btn-custom-auth {
            font-size: 0.86rem;
            padding: 8px 18px;
        }

        .btn-custom-auth:hover,
        .btn-custom-daftar:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 18px 38px rgba(77, 29, 149, 0.32);
        }

        .hero-section {
            position: relative;
            overflow: hidden;
            margin-bottom: -42px;
            padding: 58px 0 92px;
            color: #ffffff;
            background:
                linear-gradient(135deg, rgba(77, 29, 149, 0.98) 0%, rgba(122, 63, 242, 0.94) 54%, rgba(149, 101, 255, 0.9) 100%),
                repeating-linear-gradient(125deg, rgba(255, 255, 255, 0.08) 0 1px, transparent 1px 34px);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(100deg, transparent 0%, rgba(255, 255, 255, 0.18) 46%, transparent 74%);
            opacity: 0.65;
            animation: softSweep 7s ease-in-out infinite alternate;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            inset: auto 0 0;
            height: 80px;
            background: linear-gradient(180deg, transparent, rgba(250, 247, 255, 0.9));
        }

        .hero-content,
        .hero-highlight { position: relative; z-index: 1; }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            color: rgba(255, 255, 255, 0.92);
            font-size: 0.75rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .page-title {
            max-width: 780px;
            margin: 0;
            color: #ffffff;
            font-size: clamp(1.8rem, 4vw, 3.2rem);
            line-height: 1.15;
            font-weight: 800;
        }

        .hero-section .lead {
            max-width: 650px;
            color: rgba(255, 255, 255, 0.82);
            font-weight: 500;
            font-size: clamp(0.95rem, 1.5vw, 1.1rem);
            line-height: 1.6;
            margin-top: 10px;
        }

        .hero-highlight {
            width: min(100%, 350px);
            margin-left: auto;
            padding: 22px;
            border: 1px solid rgba(255, 255, 255, 0.26);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.14);
            box-shadow: 0 24px 60px rgba(32, 20, 63, 0.22);
            backdrop-filter: blur(16px);
            animation: floatPanel 5.5s ease-in-out infinite;
        }

        .hero-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .hero-stat {
            min-height: 92px;
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .hero-stat strong {
            display: block;
            font-size: 1.45rem;
            line-height: 1;
        }

        .hero-stat span {
            display: block;
            margin-top: 8px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .main-content { position: relative; z-index: 3; }

        .filter-container {
            position: relative;
            z-index: 4;
            padding: 18px;
            margin-bottom: 28px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 24px;
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
            animation: fadeInUp 0.8s var(--ease) forwards;
        }

        .filter-form {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) minmax(180px, 0.4fr) minmax(220px, auto);
            gap: 14px;
            align-items: center;
        }

        .search-input,
        .filter-select {
            width: 100%;
            min-height: 52px;
            border: 1.5px solid rgba(122, 63, 242, 0.14);
            border-radius: 16px;
            padding: 13px 16px;
            font-size: 0.93rem;
            font-weight: 600;
            color: var(--ink);
            background-color: rgba(255, 255, 255, 0.92);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
            transition: border-color 0.24s ease, box-shadow 0.24s ease, background 0.24s ease;
        }

        .search-input:focus,
        .filter-select:focus {
            border-color: rgba(122, 63, 242, 0.78);
            box-shadow: 0 0 0 5px rgba(122, 63, 242, 0.12);
            background-color: #ffffff;
            outline: none;
        }

        .btn-custom-daftar {
            font-size: 0.86rem;
            padding: 10px 14px;
        }

        .btn-custom-privat {
            background: linear-gradient(135deg, #7a3ff2 0%, #682bd8 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 14px;
            padding: 10px 16px;
            font-size: 0.86rem;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 8px 20px -4px rgba(122, 63, 242, 0.35);
            transition: all 0.25s var(--ease);
        }

        .btn-custom-privat:hover {
            background: linear-gradient(135deg, #6d2fd9 0%, #581ebf 100%);
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -4px rgba(122, 63, 242, 0.45);
        }

        .alert-modern {
            border-radius: 18px;
            padding: 1rem 1.15rem;
            margin-bottom: 1.4rem;
            font-weight: 700;
            border: 1px solid transparent;
            box-shadow: 0 14px 34px rgba(32, 20, 63, 0.08);
        }

        .alert-modern.success { color: #166534; background: #f0fdf4; border-color: #bbf7d0; }
        .alert-modern.error { color: #991b1b; background: #fef2f2; border-color: #fecaca; }

        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 28px;
            align-items: stretch;
            padding-bottom: 56px;
        }

        .kelas-grid-item { min-width: 0; }

        .kelas-card {
            position: relative;
            isolation: isolate;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(122, 63, 242, 0.14);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 44px rgba(54, 24, 108, 0.11);
            transition: transform 0.42s var(--ease), box-shadow 0.42s var(--ease), border-color 0.3s ease;
            will-change: transform;
        }

        .kelas-card::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(145deg, rgba(149, 101, 255, 0.16), transparent 48%);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .kelas-card::after {
            content: '';
            position: absolute;
            top: -45%;
            left: -70%;
            z-index: 3;
            width: 48%;
            height: 190%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.46), transparent);
            transform: rotate(22deg) translateX(0);
            transition: transform 0.82s ease;
            pointer-events: none;
        }

        .kelas-card:hover {
            transform: translateY(-8px);
            border-color: rgba(122, 63, 242, 0.42);
            box-shadow: 0 24px 55px -10px rgba(88, 28, 135, 0.22);
        }

        .kelas-card:hover::before { opacity: 1; }
        .kelas-card:hover::after { transform: rotate(22deg) translateX(390%); }

        /* Card Top Gradient Accent */
        .card-top-accent {
            height: 5px;
            width: 100%;
            background: linear-gradient(90deg, #7c3aed 0%, #ec4899 50%, #06b6d4 100%);
            border-radius: 16px 16px 0 0;
        }

        /* Colorful Badges */
        .badge-kategori-pill {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #ede9fe 0%, #fae8ff 100%);
            color: #6d28d9;
            font-size: 0.74rem;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 50rem;
            border: 1px solid rgba(124, 58, 237, 0.2);
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .badge-metode-pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 50rem;
        }

        .badge-metode-online {
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        .badge-metode-offline {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #047857;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 50rem;
            border: 1px solid #a7f3d0;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.28);
        }

        .card-body { position: relative; z-index: 2; }

        .judul-kelas {
            min-width: 0;
            margin-bottom: 0;
            color: #1e1b4b;
            font-size: 1.15rem;
            font-weight: 800;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.25s ease;
        }

        .kelas-card:hover .judul-kelas { color: #7c3aed; }

        /* Colorful Info Container */
        .info-container-colorful {
            background: linear-gradient(145deg, #fbfaff 0%, #f6f3ff 100%);
            border: 1px solid rgba(123, 90, 246, 0.14);
            border-radius: 18px;
            padding: 15px 16px;
            margin-top: auto;
            margin-bottom: 1.25rem;
            box-shadow: inset 0 1px 3px rgba(123, 90, 246, 0.04);
        }

        .icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-circle-purple {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #6d28d9;
        }

        .icon-circle-rose {
            background: #ffe4e6;
            color: #e11d48;
        }

        .icon-circle-amber {
            background: #fef3c7;
            color: #d97706;
        }

        .icon-circle-cyan {
            background: #cffafe;
            color: #0891b2;
        }

        .info-tag-label {
            font-size: 0.68rem;
            font-weight: 800;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1;
            margin-bottom: 3px;
        }

        .info-name-text {
            font-size: 0.94rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.25;
        }

        .info-sub-text {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
        }

        .info-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(123, 90, 246, 0.15), transparent);
            margin: 10px 0;
        }

        /* Price Container */
        .price-container {
            border-top: 1px dashed rgba(123, 90, 246, 0.2);
            padding-top: 12px;
        }

        .price-title-label {
            font-size: 0.72rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-highlight-text {
            font-size: 1.35rem;
            font-weight: 800;
            color: #5b21b6;
            letter-spacing: -0.4px;
        }

        /* Action Button */
        .btn-detail-action {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            color: #6d28d9;
            border: 1.5px solid rgba(109, 40, 217, 0.35);
            border-radius: 14px;
            font-size: 0.94rem;
            font-weight: 800;
            padding: 10px 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s var(--ease);
            text-decoration: none;
        }

        .btn-detail-action:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 10px 24px rgba(109, 40, 217, 0.34);
            transform: translateY(-2px);
        }

        .btn-detail-action i {
            transition: transform 0.2s ease;
        }

        .btn-detail-action:hover i {
            transform: translateX(3px);
        }

        .empty-state {
            max-width: 560px;
            margin-inline: auto;
            padding: 44px 28px;
            border: 1px solid rgba(122, 63, 242, 0.14);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
        }

        .empty-icon {
            display: inline-grid;
            place-items: center;
            width: 76px;
            height: 76px;
            margin-bottom: 18px;
            border-radius: 24px;
            color: var(--purple-primary);
            background: linear-gradient(135deg, var(--purple-soft), #ffffff);
            box-shadow: 0 16px 34px rgba(122, 63, 242, 0.16);
        }

        @media (min-width: 1400px) {
            .kelas-grid { grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); }
        }

        @media (max-width: 991.98px) {
            .navbar-custom { padding: 10px 0; }
            .navbar-collapse { padding-top: 14px; }
            .navbar-nav { gap: 10px !important; }
            .hero-section { margin-bottom: -34px; padding: 42px 0 76px; }
            .hero-highlight { margin: 24px 0 0; }
            .filter-form { grid-template-columns: 1fr 210px; }
            .filter-action { grid-column: 1 / -1; }
        }

        @media (max-width: 575.98px) {
            .container { padding-left: 10px; padding-right: 10px; }
            .navbar-logo-wrapper { padding: 4px 8px; border-radius: 8px; }
            .navbar-logo { max-width: 90px; max-height: 28px; }
            .brand-text { max-width: 80px; font-size: 0.75rem !important; line-height: 1.1; white-space: normal; }
            .btn-auth-mobile { padding: 4px 8px !important; font-size: 0.72rem !important; border-radius: 6px !important; min-height: 30px !important; display: inline-flex; align-items: center; }
            .hero-section { padding: 34px 0 68px; }
            .hero-eyebrow { font-size: 0.7rem; padding: 4px 10px; }
            .hero-highlight { width: 100%; padding: 16px; border-radius: 20px; }
            .hero-stat { min-height: 82px; padding: 13px; }
            .hero-stat strong { font-size: 1.18rem; }
            .filter-container { padding: 14px; border-radius: 20px; }
            .filter-form { grid-template-columns: 1fr; gap: 12px; }
            .kelas-grid { grid-template-columns: minmax(0, 1fr); gap: 22px; }
            .kelas-card { border-radius: 22px; }
            .kelas-card:hover { transform: translateY(-6px); }
            
            .card-body { padding: 18px !important; }
            .judul-kelas { font-size: 1rem; }
            .info-split { flex-wrap: wrap; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }

            .kelas-card:hover,
            .kelas-card:hover .img-wrapper img,
            .btn-custom-daftar:hover,
            .btn-outline-detail:hover,
            .btn-custom-auth:hover,
            .btn-outline-custom-auth:hover { transform: none; }
        }

        @media (max-width: 767.98px) {
            #modalCekStatus .modal-dialog {
                margin: 20px auto;
                width: 92%;
                max-width: 350px;
            }
            #modalCekStatus .modal-content {
                padding: 20px 16px !important;
                max-height: 90vh;
                overflow-y: auto;
            }
            #modalCekStatus .modal-header {
                padding-bottom: 0;
                margin-bottom: 12px;
                padding-left: 0;
                padding-right: 0;
            }
            #modalCekStatus .modal-title {
                font-size: 20px !important;
                line-height: 1.3 !important;
                display: flex;
                align-items: flex-start;
            }
            #modalCekStatus .modal-title i {
                margin-top: 3px;
            }
            #modalCekStatus .btn-close {
                margin-top: -4px;
            }
            #modalCekStatus .modal-body {
                padding: 0 !important;
            }
            #modalCekStatus .text-muted.small {
                font-size: 13px !important;
                line-height: 1.4 !important;
                margin-bottom: 16px !important;
            }
            #modalCekStatus .input-group {
                margin-bottom: 16px !important;
            }
            #keywordStatus {
                height: 46px !important;
                font-size: 14px !important;
                padding: 8px 14px !important;
            }
            #btnCek {
                height: 46px !important;
                font-size: 14px !important;
                padding: 8px 16px !important;
            }
            #hasilPencarianModal .card {
                padding: 14px !important;
            }
            #hasilPencarianModal h6 {
                font-size: 14px !important;
                margin-bottom: 10px !important;
            }
            #hasilPencarianModal .row.small,
            #hasilPencarianModal .small {
                font-size: 13px !important;
                margin-bottom: 6px !important;
            }
            #hasilPencarianModal .alert {
                font-size: 13px !important;
                line-height: 1.4 !important;
                padding: 12px 14px !important;
                margin-top: 10px !important;
                margin-bottom: 10px !important;
            }
            #hasilPencarianModal .btn {
                height: 44px !important;
                font-size: 14px !important;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-top: 8px !important;
            }
            #modalCekStatus .alert-light {
                margin-top: 16px !important;
                padding: 12px !important;
                font-size: 12px !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Atas -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container px-2 px-lg-4 flex-wrap flex-lg-nowrap">
        <a class="navbar-brand d-flex align-items-center gap-1 gap-lg-2 text-decoration-none me-0 me-lg-3" href="<?= base_url('/') ?>">
            <div class="navbar-logo-wrapper">
                <img src="<?= base_url('assets/img/logo_creativemu_admin.png') ?>" alt="Logo Creativemu" class="navbar-logo" onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/3413/3413535.png';">
            </div>
            <span class="brand-text">Creativemu Academy</span>
        </a>

        <div class="d-flex align-items-center ms-auto gap-1 gap-lg-2 order-lg-last">
            <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-custom-auth btn-auth-mobile">Login</a>
            <a href="<?= base_url('auth/register') ?>" class="btn btn-custom-auth btn-auth-mobile">Sign Up</a>
            
            <button class="navbar-toggler border-0 shadow-none p-1 ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="width: 1.3em; height: 1.3em;"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse justify-content-center order-last order-lg-0" id="navbarNav">
            <ul class="navbar-nav gap-2 gap-lg-4 mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('pelatihan/daftar-kelas') ?>">Program</a></li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCekStatus" style="cursor: pointer;">
                        <i class="bi bi-search me-1"></i> Cek Status Pendaftaran
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container px-lg-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 hero-content animate-fade-in">
                <div class="hero-eyebrow"><i class="bi bi-stars"></i> Kelas kreatif pilihan untuk naik level</div>
                <h1 class="page-title mb-3">Daftar Program Pelatihan</h1>
                <p class="lead mb-0">Temukan kelas pengembangan skill profesional dengan instruktur berpengalaman, jadwal fleksibel, dan materi yang siap dipakai untuk karier maupun bisnis.</p>
            </div>
            <div class="col-lg-4">
                <div class="hero-highlight border-0 bg-transparent shadow-none p-0">
                    <img src="<?= base_url('assets/img/foto_utama.png') ?>" alt="Ilustrasi Program Pelatihan" class="img-fluid w-100" style="object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content / Daftar Kelas -->
<div class="container px-lg-4 py-3 main-content">

    <!-- NOTIFIKASI FLASH MESSAGE (SUKSES / ERROR) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 p-3 mb-4 d-flex align-items-center animate-fade-in" role="alert" style="background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%); border: 1.5px solid #86efac !important; box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.15);">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0 shadow-sm" style="width: 44px; height: 44px; background: #10b981; color: #ffffff; font-size: 1.4rem;">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div class="flex-grow-1 pe-3">
                <div class="fw-bold mb-1" style="color: #065f46; font-size: 0.98rem;">Pendaftaran Berhasil Terkirim!</div>
                <div class="small fw-medium" style="color: #047857; font-size: 0.88rem; line-height: 1.5;"><?= esc(session()->getFlashdata('success')) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 p-3 mb-4 d-flex align-items-center animate-fade-in" role="alert" style="background: linear-gradient(135deg, #fef2f2 0%, #fff1f2 100%); border: 1.5px solid #fca5a5 !important; box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.15);">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3 flex-shrink-0 shadow-sm" style="width: 44px; height: 44px; background: #ef4444; color: #ffffff; font-size: 1.4rem;">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="flex-grow-1 pe-3">
                <div class="fw-bold mb-1" style="color: #991b1b; font-size: 0.98rem;">Pemberitahuan Pendaftaran:</div>
                <div class="small fw-medium" style="color: #b91c1c; font-size: 0.88rem; line-height: 1.5;"><?= esc(session()->getFlashdata('error')) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php
        // Kumpulkan kategori unik untuk dropdown filter
        $daftarKategori = [];
        if (!empty($kelas) && is_array($kelas)) {
            foreach ($kelas as $itemKelas) {
                if (!empty($itemKelas['kategori'])) {
                    $daftarKategori[$itemKelas['kategori']] = true;
                }
            }
        }
        $kategoriList = array_keys($daftarKategori);
        sort($kategoriList);

        $filterKeyword  = trim((string)($_GET['keyword'] ?? ''));
        $filterKategori = trim((string)($_GET['kategori'] ?? ''));

        // Terapkan filter jika keyword atau kategori dipilih
        $kelasList = $kelas ?? [];
        if ($filterKeyword !== '' || $filterKategori !== '') {
            $kelasList = array_filter($kelasList, function($k) use ($filterKeyword, $filterKategori) {
                $matchKey = true;
                $matchKat = true;
                if ($filterKeyword !== '') {
                    $haystack = strtolower(($k['nama_kelas'] ?? '') . ' ' . ($k['nama_mentor'] ?? '') . ' ' . ($k['kategori'] ?? '') . ' ' . ($k['deskripsi'] ?? ''));
                    $matchKey = (strpos($haystack, strtolower($filterKeyword)) !== false);
                }
                if ($filterKategori !== '') {
                    $matchKat = (strtolower($k['kategori'] ?? '') === strtolower($filterKategori));
                }
                return $matchKey && $matchKat;
            });
        }
    ?>

    <!-- Kotak Filter Pencarian -->
    <div class="filter-container">
        <form action="" method="get" class="filter-form">
            <div>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #7c3aed;"></i>
                    <input type="text" name="keyword" class="search-input ps-5" placeholder="Cari nama kelas atau materi..." value="<?= esc($filterKeyword) ?>">
                </div>
            </div>
            <div>
                <select name="kategori" class="filter-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategoriList as $kat): ?>
                        <option value="<?= esc($kat) ?>" <?= (strtolower($filterKategori) === strtolower($kat)) ? 'selected' : '' ?>>
                            <?= esc($kat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-action d-flex gap-2">
                <button type="submit" class="btn btn-custom-daftar flex-grow-1 py-2.5">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                <a href="<?= base_url('pelatihan/pendaftaran?jenis=Privat') ?>" class="btn btn-custom-privat py-2.5 d-inline-flex align-items-center justify-content-center text-decoration-none">
                    <i class="bi bi-person-fill-lock me-1.5 fs-6"></i>
                    <span>Kelas Privat</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Daftar Kelas Grid -->
    <div class="kelas-grid mt-2">
        <?php if (empty($kelasList)) : ?>
            <div class="kelas-grid-item text-center py-5" style="grid-column: 1 / -1;">
                <div class="card border-0 shadow-sm p-5 rounded-4 bg-white mx-auto animate-fade-in" style="max-width: 500px;">
                    <div class="p-4 rounded-circle d-inline-flex mx-auto mb-3" style="background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed;">
                        <i class="bi bi-inbox-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Tidak Ada Kelas yang Ditemukan</h4>
                    <p class="text-muted mb-3 small">Coba ubah kata kunci atau pilih kategori lain untuk menemukan kelas pelatihan yang tersedia.</p>
                    <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-sm btn-outline-custom-auth mx-auto">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
                    </a>
                </div>
            </div>
        <?php else : ?>
            <?php $delay = 0.08; foreach ($kelasList as $k) : ?>
                <div class="kelas-grid-item d-flex animate-fade-in" style="animation-delay: <?= $delay ?>s;">
                    <div class="card kelas-card w-100 h-100 border-0 shadow-sm" style="border-radius: 20px; transition: all 0.3s ease;">
                        <!-- Top Accent Multi-Color Ribbon -->
                        <div class="card-top-accent"></div>
                        
                        <div class="card-body p-4 d-flex flex-column h-100">
                            
                            <!-- Top Badges Row -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <span class="badge-kategori-pill">
                                    <i class="bi bi-tag-fill me-1"></i>
                                    <?= esc($k['kategori'] ?? 'Umum') ?>
                                </span>

                                <div class="d-flex align-items-center gap-1.5">
                                    <?php 
                                        $metode = !empty($k['metode_pembelajaran']) ? $k['metode_pembelajaran'] : ($k['tipe_kelas'] ?? '');
                                        $isOnline = (stripos((string)$metode, 'online') !== false);
                                    ?>
                                    <?php if (!empty($metode) && stripos((string)$metode, 'privat') === false): ?>
                                        <span class="badge-metode-pill <?= $isOnline ? 'badge-metode-online' : 'badge-metode-offline' ?>">
                                            <i class="bi <?= $isOnline ? 'bi-camera-video-fill' : 'bi-building-fill' ?> me-1"></i>
                                            <?= esc(ucwords($metode)) ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php 
                                        $status = strtolower($k['status'] ?? 'aktif');
                                        $isAktif = in_array($status, ['aktif', '1', 'active', 'approved']);
                                    ?>
                                    <span class="badge-status-pill">
                                        <span class="pulse-dot"></span>
                                        <?= $isAktif ? 'Aktif' : esc(ucwords($status)) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Class Title -->
                            <h3 class="judul-kelas mb-3" style="min-height: 3.3rem;">
                                <?= esc($k['nama_kelas']) ?>
                            </h3>
                            
                            <!-- Colorful Info Box -->
                            <div class="info-container-colorful">
                                <?php if (!empty($k['nama_mentor'])): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-circle icon-circle-purple me-3">
                                            <i class="bi bi-person-video3"></i>
                                        </div>
                                        <div class="d-flex flex-column overflow-hidden">
                                            <span class="info-tag-label">Instruktur</span>
                                            <strong class="info-name-text text-truncate" title="<?= esc($k['nama_mentor']) ?>">
                                                <?= esc($k['nama_mentor']) ?>
                                            </strong>
                                        </div>
                                    </div>
                                    <div class="info-divider"></div>
                                <?php endif; ?>

                                <?php if (!empty($k['lokasi_media'])): ?>
                                    <div class="d-flex align-items-center mb-2 px-1">
                                        <div class="icon-circle icon-circle-rose me-2.5" style="width: 28px; height: 28px; font-size: 0.82rem;">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <span class="info-sub-text text-truncate" title="<?= esc($k['lokasi_media']) ?>">
                                            <?= esc($k['lokasi_media']) ?>
                                        </span>
                                    </div>
                                    <div class="info-divider"></div>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-between align-items-center px-1">
                                    <?php if (!empty($k['jumlah_pertemuan'])): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-circle icon-circle-amber me-2" style="width: 28px; height: 28px; font-size: 0.82rem;">
                                                <i class="bi bi-calendar2-check-fill"></i>
                                            </div>
                                            <span class="info-sub-text fw-bold"><?= esc($k['jumlah_pertemuan']) ?> Pertemuan</span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($k['kapasitas']) && $k['kapasitas'] !== null && $k['kapasitas'] !== ''): ?>
                                        <div class="d-flex align-items-center ms-auto">
                                            <div class="icon-circle icon-circle-cyan me-2" style="width: 28px; height: 28px; font-size: 0.82rem;">
                                                <i class="bi bi-people-fill"></i>
                                            </div>
                                            <span class="info-sub-text fw-bold"><?= esc($k['kapasitas_tersedia'] ?? $k['kapasitas']) ?> Kuota</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Price Section -->
                            <?php 
                                $hargaK = $k['harga_reguler'] ?? $k['harga'] ?? $k['harga_privat'] ?? null;
                            ?>
                            <?php if ($hargaK !== null && $hargaK !== ''): ?>
                                <div class="price-container d-flex align-items-center mb-3.5 gap-2">
                                    <div class="d-flex align-items-center gap-1.5">
                                        <div class="icon-circle icon-circle-purple" style="width: 26px; height: 26px; font-size: 0.78rem;">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <span class="price-title-label">Investasi</span>
                                    </div>
                                    <strong class="price-highlight-text ms-auto">
                                        <?php if ((float)$hargaK > 0): ?>
                                            Rp <?= number_format((float)$hargaK, 0, ',', '.') ?>
                                        <?php else: ?>
                                            <span class="text-success">Gratis</span>
                                        <?php endif; ?>
                                    </strong>
                                </div>
                            <?php endif; ?>

                            <!-- Button Action -->
                            <div class="mt-auto">
                                <a href="<?= base_url('pelatihan/detail-kelas/' . $k['id_kelas']) ?>" class="btn-detail-action w-100">
                                    <span>Lihat Detail Kelas</span>
                                    <i class="bi bi-arrow-right-circle-fill ms-2 fs-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $delay += 0.08; endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL POPUP CEK STATUS -->
<div class="modal fade" id="modalCekStatus" tabindex="-1" aria-labelledby="modalCekStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg p-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalCekStatusLabel">
                    <i class="bi bi-search text-primary me-2" style="color: #5b21b6 !important;"></i>Cek Status Pendaftaran Pelatihan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted small mb-4">Masukkan Email atau Nomor HP yang Anda gunakan saat mendaftar untuk melacak status validasi pendaftaran Anda.</p>

                <form id="formCekStatus">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" id="keywordStatus" name="keyword" class="form-control rounded-start-pill border py-3 px-4" placeholder="Contoh: email@anda.com atau 0812345..." required>
                        <button class="btn btn-custom-daftar rounded-end-pill px-4 px-md-5" type="submit" id="btnCek">
                            Cek Sekarang
                        </button>
                    </div>
                </form>

                <div id="hasilPencarianModal" class="mt-4"></div>

                <div class="alert alert-light border text-muted small rounded-3 mb-0 mt-3">
                    <i class="bi bi-info-circle me-1" style="color: #5b21b6;"></i> <strong>Tips:</strong> Pastikan data email atau nomor handphone yang diinput sama persis.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script AJAX untuk Cek Status -->
<script>
document.getElementById('formCekStatus').addEventListener('submit', function(e) {
    e.preventDefault();
    lakukanCekStatus();
});

function lakukanCekStatus() {
    const keyword = document.getElementById('keywordStatus').value;
    if (!keyword) return;

    const hasilDiv = document.getElementById('hasilPencarianModal');
    const btnCek = document.getElementById('btnCek');

    btnCek.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mencari...';
    btnCek.disabled = true;
    hasilDiv.innerHTML = '';

    fetch('<?= base_url("pelatihan/ajax_cek_status") ?>?keyword=' + encodeURIComponent(keyword))
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                return response.text().then(text => { throw new Error(text); });
            }
        })
        .then(data => {
            btnCek.innerHTML = 'Cek Sekarang';
            btnCek.disabled = false;

            if (data.status === 'success') {
                let statusVal = data.data.status_pembayaran ? data.data.status_pembayaran.toLowerCase() : 'pending';

                let badgeClass = 'bg-warning text-dark';
                let statusTeks = 'Pending (Menunggu Validasi)';
                
                if (statusVal === 'valid' || statusVal === 'diterima') {
                    badgeClass = 'bg-success';
                    statusTeks = 'Diterima / Valid';
                } else if (statusVal === 'rejected' || statusVal === 'ditolak') {
                    badgeClass = 'bg-danger';
                    statusTeks = 'Ditolak';
                }

                let htmlInfoTambahan = '';
                if (statusVal === 'valid' || statusVal === 'diterima') {
                    htmlInfoTambahan = `
                        <div class="alert alert-success mt-3 mb-2 small">
                            <strong>Pendaftaran Anda telah disetujui!</strong> Silakan membuat akun terlebih dahulu untuk mengakses dashboard peserta.
                        </div>
                        <a href="<?= base_url('auth/register') ?>" class="btn btn-custom-daftar w-100 mt-2 btn-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> Buat Akun Sekarang
                        </a>
                    `;
                } else if (statusVal === 'rejected' || statusVal === 'ditolak') {
                    htmlInfoTambahan = `
                        <div class="alert alert-danger mt-3 mb-2 small">
                            <strong>Pendaftaran Ditolak!</strong> Alasan: ${data.data.alasan_penolakan || 'Tidak valid'}. Silakan perbarui data diri dan unggah ulang bukti pembayaran Anda.
                        </div>
                        <a href="<?= base_url('pelatihan/upload_ulang/') ?>${data.data.id_pendaftaran}" class="btn btn-warning fw-bold text-dark w-100 mt-2 btn-sm">
                            <i class="bi bi-pencil-square me-1"></i> Buka Menu Edit & Upload Ulang Data
                        </a>
                    `;
                } else {
                    htmlInfoTambahan = `
                        <div class="alert alert-info mt-3 mb-2 small">
                            Bukti pembayaran Anda sedang dalam antrean pengecekan oleh Admin. Mohon menunggu.
                        </div>
                    `;
                }

                hasilDiv.innerHTML = `
                    <div class="card border shadow-sm p-3 rounded-3 bg-light">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-check-circle-fill text-success me-1"></i> Data Ditemukan</h6>
                        <div class="row small mb-2">
                            <div class="col-6"><strong>Nama:</strong> ${data.data.nama}</div>
                            <div class="col-6"><strong>No HP:</strong> ${data.data.no_hp}</div>
                        </div>
                        <div class="small mb-2"><strong>Status:</strong> <span class="badge ${badgeClass}">${statusTeks}</span></div>
                        ${htmlInfoTambahan}
                    </div>
                `;
            } else {
                hasilDiv.innerHTML = `
                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            btnCek.innerHTML = 'Cek Sekarang';
            btnCek.disabled = false;
            hasilDiv.innerHTML = `
                <div class="alert alert-danger small mb-0">
                    <strong>Terjadi Kesalahan Sistem:</strong><br>${error.message}
                </div>
            `;
        });
}
</script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

