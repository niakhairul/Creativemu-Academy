(() => {
    const init = () => {

        const sidebar = document.querySelector('#sidebar, .sidebar');

        if (!sidebar) return;

        // Jangan membuat tombol lebih dari satu
        if (document.querySelector('.admin-mobile-menu')) return;

        const backdrop = document.createElement('div');
        backdrop.className = 'admin-sidebar-backdrop';

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'admin-mobile-menu';
        toggle.setAttribute('aria-label', 'Buka menu navigasi');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.innerHTML =
            '<i class="fa-solid fa-bars" aria-hidden="true"></i>';

        document.body.classList.add('admin-generated-menu');

        const close = () => {
            sidebar.classList.remove('show');
            backdrop.classList.remove('is-visible');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.innerHTML =
                '<i class="fa-solid fa-bars" aria-hidden="true"></i>';
        };

        const open = () => {
            sidebar.classList.add('show');
            backdrop.classList.add('is-visible');
            toggle.setAttribute('aria-expanded', 'true');
            toggle.innerHTML =
                '<i class="fa-solid fa-xmark" aria-hidden="true"></i>';
        };

        toggle.addEventListener('click', () => {
            sidebar.classList.contains('show') ? close() : open();
        });

        backdrop.addEventListener('click', close);

        sidebar.addEventListener('click', (event) => {
            if (event.target.closest('a')) {
                close();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                close();
            }
        });

        window.addEventListener('resize', () => {
            if (window.matchMedia('(min-width: 992px)').matches) {
                close();
            }
        });

        document.body.append(backdrop, toggle);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();