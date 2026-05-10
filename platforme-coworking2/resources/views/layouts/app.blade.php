<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CoWork Tunisie')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --bg-navbar: #ffffff;
            --bg-soft: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-card: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-hover: 0 12px 28px rgba(0,0,0,0.15);
            --primary: #1a56db;
            --primary-dark: #1e429f;
            --accent: #f59e0b;
        }

        [data-theme="dark"] {
            --bg-main: #0f172a;
            --bg-card: #1e293b;
            --bg-navbar: #0f172a;
            --bg-soft: #334155;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --shadow-card: 0 2px 8px rgba(0,0,0,0.4);
            --shadow-hover: 0 12px 28px rgba(0,0,0,0.6);
            --primary: #60a5fa;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        .navbar {
            background: linear-gradient(135deg, #1a56db, #1e429f) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.4s ease;
        }

        .navbar-brand { font-weight: 700; font-size: 1.4rem; color: #fff !important; }
        .navbar-brand span { color: #f59e0b; }
        .nav-link { color: rgba(255,255,255,0.9) !important; font-weight: 500; transition: color .2s; }
        .nav-link:hover { color: #f59e0b !important; }

        [data-theme="dark"] .navbar-light .navbar-brand,
        [data-theme="dark"] .navbar-light .nav-link { color: var(--text-main) !important; }
        [data-theme="dark"] .navbar-light .nav-link:hover { color: var(--primary) !important; }

        .card {
            background-color: var(--bg-card) !important;
            color: var(--text-main) !important;
            border: none !important;
            border-radius: 12px;
            box-shadow: var(--shadow-card);
            transition: transform 0.35s cubic-bezier(0.4,0,0.2,1), box-shadow 0.35s ease, background-color 0.4s ease;
        }
        .card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }

        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

        .badge-disponible { background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: .8rem; font-weight: 600; }
        .badge-indisponible { background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: .8rem; font-weight: 600; }
        .star { color: #f59e0b; }
        .star-empty { color: #d1d5db; }

        footer { background: #1e293b; color: #94a3b8; padding: 2rem 0; margin-top: 4rem; }
        .page-header { background: linear-gradient(135deg, #1a56db, #7c3aed); color: white; padding: 3rem 0; margin-bottom: 2rem; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,86,219,.15); }
        .alert { border-radius: 10px; }

        [data-theme="dark"] .text-muted { color: var(--text-muted) !important; }
        [data-theme="dark"] .bg-light { background-color: var(--bg-soft) !important; }
        [data-theme="dark"] .text-dark { color: var(--text-main) !important; }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select { background-color: var(--bg-soft); color: var(--text-main); border-color: var(--border-color); }
        [data-theme="dark"] .form-control::placeholder { color: var(--text-muted); }
        [data-theme="dark"] .table { color: var(--text-main); }
        [data-theme="dark"] .modal-content { background-color: var(--bg-card); color: var(--text-main); }
        [data-theme="dark"] .dropdown-menu { background-color: var(--bg-card); border-color: var(--border-color); }
        [data-theme="dark"] .dropdown-item { color: var(--text-main); }
        [data-theme="dark"] .dropdown-item:hover { background-color: var(--bg-soft); }

        .theme-toggle {
            background: var(--bg-soft);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            width: 42px; height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        .theme-toggle:hover { transform: rotate(20deg) scale(1.1); background: var(--primary); color: white; }

        .btn { position: relative; overflow: hidden; }
        .btn::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 0; height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }
        .btn:active::after { width: 300px; height: 300px; }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        .alert-danger, .is-invalid { animation: shake 0.4s ease; }
        .counter { display: inline-block; }

        @yield('extra-css')
    </style>
    @yield('head')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-building me-2"></i>Co<span>Work</span> Tunisie
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('espaces.index') }}">
                        <i class="fas fa-map-marker-alt me-1"></i> Espaces
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('plan') }}">
                        <i class="fas fa-map me-1"></i> Plan
                    </a>
                </li>
                @if(auth('client')->check())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reservations.index') }}">
                            <i class="fas fa-calendar-check me-1"></i> Mes réservations
                        </a>
                    </li>
                    {{-- Menu Admin visible uniquement pour les admins --}}
                    @if(auth('client')->user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-tachometer-alt me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.espaces.index') }}">
                                    <i class="fas fa-building me-2"></i>Espaces
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.reservations.index') }}">
                                    <i class="fas fa-calendar-check me-2"></i>Réservations
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.clients.index') }}">
                                    <i class="fas fa-users me-2"></i>Clients
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.avis.index') }}">
                                    <i class="fas fa-star me-2"></i>Avis
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @endif
            </ul>

            <ul class="navbar-nav">
                @if(auth('client')->check())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> {{ auth('client')->user()->nom }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profil') }}">
                                <i class="fas fa-user me-2"></i>Mon profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="px-3 py-1">
                            <button id="themeToggle" class="theme-toggle" title="Changer de thème" aria-label="Toggle theme">
                                <i class="fas fa-moon"></i>
                            </button>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Inscription
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="container text-center">
        <p class="mb-1"><strong style="color:#f8fafc">CoWork Tunisie</strong> — Plateforme de réservation d'espaces de travail</p>
        <p class="mb-0" style="font-size:.85rem">Projet Intégré · 2 LBC-BIS · Esprit School of Business · 2025–2026</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    // === INIT AOS ===
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 60 });

    // === DARK MODE ===
    (function() {
        const toggle = document.getElementById('themeToggle');
        if (!toggle) return;
        const icon = toggle.querySelector('i');
        const html = document.documentElement;
        const saved = document.cookie.split('; ').find(r => r.startsWith('theme='))?.split('=')[1];
        applyTheme(saved || 'light');

        toggle.addEventListener('click', () => {
            const next = (html.getAttribute('data-theme') || 'light') === 'light' ? 'dark' : 'light';
            applyTheme(next);
            document.cookie = `theme=${next}; max-age=${60*60*24*30}; path=/; SameSite=Lax`;
        });

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
    })();

    // === COMPTEURS ANIMÉS ===
    document.querySelectorAll('.counter[data-target]').forEach(el => {
        const target = parseInt(el.dataset.target);
        const duration = 1500;
        const startTime = performance.now();
        function tick(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) requestAnimationFrame(tick);
            else el.textContent = target;
        }
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { requestAnimationFrame(tick); observer.disconnect(); } });
        });
        observer.observe(el);
    });
</script>

@yield('scripts')

<!-- ============ Chatbot ============ -->
<div id="chatbot-widget">
    <button id="chatbot-toggle" class="btn btn-primary rounded-circle shadow">
        <i class="fas fa-comments fs-4"></i>
    </button>
    <div id="chatbot-box" class="card shadow d-none">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background:#1a56db">
            <strong><i class="fas fa-robot me-2"></i>Assistant CoWork</strong>
            <button id="chatbot-close" class="btn-close btn-close-white"></button>
        </div>
        <div id="chatbot-messages" class="card-body" style="height:320px;overflow-y:auto;font-size:.9rem">
            <div class="text-muted small">👋 Bonjour ! Posez-moi une question sur nos espaces.</div>
        </div>
        <div class="card-footer p-2">
            <form id="chatbot-form" class="d-flex gap-2">
                @csrf
                <input type="text" id="chatbot-input" class="form-control form-control-sm" placeholder="Votre question..." required>
                <button class="btn btn-sm btn-primary"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>

<style>
#chatbot-widget { position: fixed; bottom: 20px; right: 20px; z-index: 9999; }
#chatbot-toggle { width: 60px; height: 60px; }
#chatbot-box { width: 350px; position: absolute; bottom: 75px; right: 0; }
.chat-msg { padding: 8px 12px; border-radius: 12px; margin-bottom: 8px; max-width: 80%; }
.chat-msg.user { background: #1a56db; color: white; margin-left: auto; }
.chat-msg.bot  { background: #f1f5f9; color: #1e293b; }
</style>

<script>
(function() {
    const toggle   = document.getElementById('chatbot-toggle');
    const box      = document.getElementById('chatbot-box');
    const close    = document.getElementById('chatbot-close');
    const form     = document.getElementById('chatbot-form');
    const input    = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');
    const csrf     = document.querySelector('meta[name="csrf-token"]')?.content
                  || document.querySelector('input[name="_token"]')?.value;

    toggle.addEventListener('click', () => box.classList.toggle('d-none'));
    close.addEventListener('click',  () => box.classList.add('d-none'));

    function addMsg(text, who) {
        const div = document.createElement('div');
        div.className = 'chat-msg ' + who;
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const q = input.value.trim();
        if (!q) return;
        addMsg(q, 'user');
        input.value = '';
        addMsg('...', 'bot');
        const loading = messages.lastChild;
        try {
            const res = await fetch('{{ route("chatbot.repondre") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ question: q })
            });
            const data = await res.json();
            loading.textContent = data.reponse;
        } catch (err) {
            loading.textContent = "Erreur de connexion. Réessayez.";
        }
    });
})();
</script>

</body>
</html>