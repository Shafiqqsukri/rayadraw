<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>🧧 RayaDraw — @yield('title', 'Selamat Hari Raya')</title>

    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold: #F5C842;
            --gold2: #e6a820;
            --green: #1a4a2e;
            --green2: #2d7a4f;
            --red: #C0392B;
            --cream: #FFF8E7;
            --text: #1a1a1a;
            --muted: #6b7280;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--green);
            color: var(--cream);
            min-height: 100vh;
        }

        /* Batik pattern background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23F5C842' fill-opacity='0.04'%3E%3Cpath d='M30 0L60 30L30 60L0 30z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .wrap {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 16px 60px;
        }

        /* Navbar */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0 24px;
        }

        .nav-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            color: var(--gold);
            letter-spacing: 3px;
            text-decoration: none;
            text-shadow: 0 0 20px rgba(245,200,66,.3);
        }

        .nav-links { display: flex; gap: 10px; }

        .nav-btn {
            padding: 8px 18px;
            border-radius: 10px;
            font-family: 'Nunito', sans-serif;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
            border: none;
        }

        .nav-btn-outline {
            background: transparent;
            border: 1px solid rgba(245,200,66,.3);
            color: rgba(255,248,231,.7);
        }

        .nav-btn-outline:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .nav-btn-gold {
            background: var(--gold);
            color: var(--green);
        }

        .nav-btn-gold:hover {
            background: var(--gold2);
            transform: translateY(-1px);
        }

        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-weight: 700;
            font-size: .9rem;
        }

        .alert-success {
            background: rgba(46,204,113,.15);
            border: 1px solid rgba(46,204,113,.3);
            color: #2ecc71;
        }

        .alert-error {
            background: rgba(231,76,60,.15);
            border: 1px solid rgba(231,76,60,.3);
            color: #e74c3c;
        }

        /* Cards */
        .card {
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(245,200,66,.15);
            border-radius: 20px;
            padding: 28px 24px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 14px 28px;
            border-radius: 12px;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            border: none;
            transition: all .2s;
            text-decoration: none;
            text-align: center;
        }

        .btn-gold {
            background: var(--gold);
            color: var(--green);
            box-shadow: 0 4px 20px rgba(245,200,66,.3);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(245,200,66,.45);
        }

        .btn-outline {
            background: transparent;
            color: rgba(255,248,231,.7);
            border: 1px solid rgba(255,255,255,.15);
        }

        .btn-outline:hover {
            border-color: rgba(255,255,255,.35);
            color: var(--cream);
        }

        .btn-red {
            background: var(--red);
            color: white;
        }

        .btn-red:hover {
            background: #a93226;
            transform: translateY(-1px);
        }

        /* Form fields */
        .field { margin-bottom: 18px; }

        .field label {
            display: block;
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(245,200,66,.2);
            background: rgba(255,255,255,.07);
            color: var(--cream);
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            outline: none;
            transition: all .25s;
            resize: vertical;
        }

        .field input:focus,
        .field textarea:focus {
            border-color: var(--gold);
            background: rgba(255,255,255,.1);
            box-shadow: 0 0 0 3px rgba(245,200,66,.12);
        }

        .field input::placeholder,
        .field textarea::placeholder {
            color: rgba(255,248,231,.3);
            font-weight: 400;
        }

        .field-error {
            color: #e74c3c;
            font-size: .8rem;
            margin-top: 5px;
            font-weight: 700;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
        }

        .divider-line { flex: 1; height: 1px; background: rgba(245,200,66,.15); }
        .divider span { color: var(--gold); opacity: .5; font-size: .85rem; }

        /* Section title */
        .section-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            color: var(--gold);
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .section-sub {
            color: rgba(255,248,231,.5);
            font-size: .85rem;
            margin-bottom: 20px;
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="wrap">
    <nav>
        <a href="{{ route('wishes.index') }}" class="nav-brand">🌙 RayaDraw</a>
        <div class="nav-links">
            <a href="{{ route('wishes.index') }}" class="nav-btn nav-btn-outline">Wall</a>
            <a href="{{ route('wishes.create') }}" class="nav-btn nav-btn-gold">+ Ucapan</a>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
    @endif

    @yield('content')
</div>

@stack('scripts')
</body>
</html>