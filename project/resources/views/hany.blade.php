<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Your App</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- CSS Reset & Variables --- */
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --primary: #6366f1; /* Indigo */
            --primary-hover: #4f46e5;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; transition: 0.3s; }
        ul { list-style: none; }

        /* --- Header --- */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 5%;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .logo span { color: var(--primary); }

        nav ul { display: flex; gap: 2rem; }
        nav a { color: var(--text-muted); font-size: 0.95rem; }
        nav a:hover { color: var(--primary); }

        .auth-buttons { display: flex; gap: 1rem; }
        
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-text { color: var(--text-main); }
        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.39);
        }
        .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-1px); }

        /* --- Hero Section --- */
        .hero {
            text-align: center;
            padding: 6rem 1rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: -webkit-linear-gradient(315deg, #ffffff 0%, #94a3b8 74%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* --- Features Grid --- */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 4rem 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            transition: 0.3s;
        }

        .card:hover { border-color: var(--primary); }

        .icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .card h3 { margin-bottom: 0.5rem; }
        .card p { color: var(--text-muted); font-size: 0.95rem; }

        /* --- Footer --- */
        footer {
            margin-top: auto;
            text-align: center;
            padding: 2rem;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            header { flex-direction: column; gap: 1rem; }
            nav ul { gap: 1rem; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">Laravel<span>App</span></div>
        
        <nav>
            <ul>
                <li><a href="#">Features</a></li>
                <li><a href="#">Docs</a></li>
                <li><a href="#">Pricing</a></li>
            </ul>
        </nav>

        <div class="auth-buttons">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-text">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                    @endif
                @endauth
            @endif
        </div>
    </header>

    <section class="hero">
        <h1>Build something amazing with Laravel.</h1>
        <p>A secure, scalable, and modern foundation for your next big idea. Stop configuring and start coding.</p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="#" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1rem;">Start Building</a>
            <a href="https://laravel.com/docs" class="btn btn-text" style="border: 1px solid var(--border);">Read the Docs</a>
        </div>
    </section>

    <section class="features">
        <div class="card">
            <div class="icon">🚀</div>
            <h3>High Performance</h3>
            <p>Optimized for speed and efficiency to handle heavy workloads seamlessly.</p>
        </div>
        <div class="card">
            <div class="icon">🔒</div>
            <h3>Secure by Default</h3>
            <p>Built-in protection against SQL injection, XSS, and CSRF attacks.</p>
        </div>
        <div class="card">
            <div class="icon">⚡</div>
            <h3>Developer Friendly</h3>
            <p>Elegant syntax and powerful tools to make development a joy.</p>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} LaravelApp. Built with ❤️ and Laravel.</p>
    </footer>

</body>
</html>