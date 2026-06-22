(function () {
    const html = document.documentElement;
    const storedTheme = localStorage.getItem('pims-theme');
    if (storedTheme) {
        html.setAttribute('data-bs-theme', storedTheme);
    }

    const toggle = document.getElementById('themeToggle');
    if (toggle) {
        const syncIcon = () => {
            const dark = html.getAttribute('data-bs-theme') === 'dark';
            toggle.innerHTML = dark ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon-stars"></i>';
        };
        syncIcon();
        toggle.addEventListener('click', () => {
            const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('pims-theme', next);
            syncIcon();
        });
    }

    document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const target = document.querySelector(thumb.dataset.galleryThumb);
            if (!target) return;
            target.src = thumb.src;
            document.querySelectorAll('[data-gallery-thumb]').forEach((item) => item.classList.remove('active'));
            thumb.classList.add('active');
        });
    });

    document.querySelectorAll('[data-qty]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.querySelector(button.dataset.qty);
            if (!input) return;
            const delta = button.dataset.action === 'increase' ? 1 : -1;
            input.value = Math.max(1, parseInt(input.value || '1', 10) + delta);
        });
    });

    document.querySelectorAll('[data-confirm]').forEach((node) => {
        node.addEventListener('click', (event) => {
            if (!confirm(node.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    const chartDefaults = {
        borderColor: 'rgba(148, 163, 184, 0.22)',
        color: getComputedStyle(document.documentElement).getPropertyValue('--muted') || '#64748b',
    };

    if (window.Chart) {
        Chart.defaults.color = chartDefaults.color;
        Chart.defaults.borderColor = chartDefaults.borderColor;

        const sales = document.getElementById('monthlySalesChart');
        if (sales) {
            new Chart(sales, {
                type: 'line',
                data: {
                    labels: JSON.parse(sales.dataset.labels || '[]'),
                    datasets: [{
                        label: 'Sales',
                        data: JSON.parse(sales.dataset.values || '[]'),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.12)',
                        tension: 0.42,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } },
                },
            });
        }

        const distribution = document.getElementById('inventoryChart');
        if (distribution) {
            new Chart(distribution, {
                type: 'doughnut',
                data: {
                    labels: JSON.parse(distribution.dataset.labels || '[]'),
                    datasets: [{
                        data: JSON.parse(distribution.dataset.values || '[]'),
                        backgroundColor: ['#4f46e5', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#64748b'],
                    }],
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } },
            });
        }
    }
})();
