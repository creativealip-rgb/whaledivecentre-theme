    </main>
</div>

<script>
function toggleMobileMenu() {
    const sidebar = document.querySelector('.dashboard-sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    const overlay = document.querySelector('.mobile-overlay');

    sidebar.classList.toggle('active');
    toggle.classList.toggle('active');
    overlay.classList.toggle('active');

    // Prevent body scroll when menu is open
    if (sidebar.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}



function ensureToastRoot() {
    let root = document.getElementById('member-toast-root');
    if (!root) {
        root = document.createElement('div');
        root.id = 'member-toast-root';
        root.style.cssText = 'position:fixed;top:16px;right:16px;z-index:9999;display:grid;gap:8px;max-width:320px;';
        document.body.appendChild(root);
    }
    return root;
}

function showToast(message, type = 'info', ms = 2600) {
    const colors = {
        info: ['#355F72', '#DCE9E6'],
        success: ['#166534', '#dcfce7'],
        error: ['#b91c1c', '#fee2e2'],
        warn: ['#92400e', '#fef3c7']
    };
    const [fg, bg] = colors[type] || colors.info;
    const root = ensureToastRoot();
    const item = document.createElement('div');
    item.style.cssText = `background:${bg};color:${fg};border:1px solid rgba(0,0,0,.08);padding:10px 12px;border-radius:10px;font-size:13px;font-weight:600;box-shadow:0 4px 18px rgba(0,0,0,.12);`;
    item.textContent = message;
    root.appendChild(item);
    setTimeout(() => {
        item.style.transition = 'all .18s ease';
        item.style.opacity = '0';
        item.style.transform = 'translateY(-4px)';
        setTimeout(() => item.remove(), 180);
    }, ms);
}

function setActiveDashboardMenu(pathname) {
    document.querySelectorAll('.dashboard-menu a').forEach(a => {
        const href = a.getAttribute('href') || '';
        if (!href.startsWith('/')) return;
        const aPath = href.replace(/\/$/, '') || '/';
        const curPath = pathname.replace(/\/$/, '') || '/';
        if (aPath === curPath) {
            a.classList.add('active');
        } else {
            a.classList.remove('active');
        }
    });
}

function executeInlineScripts(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(oldScript => {
        const newScript = document.createElement('script');
        if (oldScript.src) {
            newScript.src = oldScript.src;
        } else {
            newScript.textContent = oldScript.textContent;
        }
        [...oldScript.attributes].forEach(attr => {
            if (attr.name !== 'src') newScript.setAttribute(attr.name, attr.value);
        });
        oldScript.replaceWith(newScript);
    });
}

async function loadDashboardPage(url, push = true) {
    const main = document.querySelector('.dashboard-main');
    if (!main) return;

    try {
        const res = await fetch(url, { credentials: 'same-origin' });
        if (!res.ok) throw new Error('Failed to load page');
        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const nextMain = doc.querySelector('.dashboard-main');
        if (!nextMain) {
            window.location.href = url;
            return;
        }

        main.innerHTML = nextMain.innerHTML;
        executeInlineScripts(main);

        const newTitle = doc.querySelector('title');
        if (newTitle) document.title = newTitle.textContent;

        if (push) history.pushState({ dashboard: true }, '', url);
        setActiveDashboardMenu(new URL(url, window.location.origin).pathname);

        const sidebar = document.querySelector('.dashboard-sidebar');
        if (sidebar && sidebar.classList.contains('active')) toggleMobileMenu();
    } catch (e) {
        window.location.href = url;
    }
}

function initDashboardNav() {
    const sidebar = document.querySelector('.dashboard-sidebar');
    if (!sidebar) return;

    sidebar.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href') || '';
        if (!href.startsWith('/')) return;
        if (href === '/' || href.includes('wp_logout_url') || href.includes('logout')) return;

        const url = new URL(href, window.location.origin);
        const dashboardPaths = ['/dashboard', '/my-travels', '/wishlist', '/reviews', '/rewards', '/membership', '/notifications', '/settings', '/profile', '/travel-story', '/my-bookings'];
        const normalized = url.pathname.replace(/\/$/, '');
        const isDashboardPage = dashboardPaths.some(p => p === normalized || p === (normalized + '/'));
        if (!isDashboardPage) return;

        e.preventDefault();
        loadDashboardPage(url.toString(), true);
    });

    window.addEventListener('popstate', function() {
        loadDashboardPage(window.location.href, false);
    });
}

// Close menu on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const sidebar = document.querySelector('.dashboard-sidebar');
        if (sidebar.classList.contains('active')) {
            toggleMobileMenu();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    initDashboardNav();
    setActiveDashboardMenu(window.location.pathname);
});
</script>

<?php wp_footer(); ?>
</body>
</html>
