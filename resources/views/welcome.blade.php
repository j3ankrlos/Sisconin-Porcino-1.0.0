<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sisconint-Porcino - Sistema de Control Integral</title>

        <!-- Favicons -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                overflow-x: hidden;
                position: relative;
            }

            /* Animated background particles */
            .bg-particles {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 0;
                pointer-events: none;
            }

            .particle {
                position: absolute;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                animation: float 20s infinite ease-in-out;
            }

            .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
            .particle:nth-child(2) { width: 60px; height: 60px; left: 20%; animation-delay: 2s; }
            .particle:nth-child(3) { width: 100px; height: 100px; left: 35%; animation-delay: 4s; }
            .particle:nth-child(4) { width: 50px; height: 50px; left: 50%; animation-delay: 1s; }
            .particle:nth-child(5) { width: 90px; height: 90px; left: 65%; animation-delay: 3s; }
            .particle:nth-child(6) { width: 70px; height: 70px; left: 80%; animation-delay: 5s; }

            @keyframes float {
                0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.3; }
                50% { transform: translateY(-100vh) rotate(360deg); opacity: 0.6; }
            }

            /* Navigation */
            nav {
                position: relative;
                z-index: 100;
                padding: 1.5rem 2rem;
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
            }

            nav a {
                padding: 0.75rem 1.5rem;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 50px;
                color: white;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                font-size: 0.95rem;
            }

            nav a:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            }

            /* Main Container */
            .container {
                position: relative;
                z-index: 10;
                max-width: 1400px;
                margin: 0 auto;
                padding: 2rem;
            }

            /* Hero Section */
            .hero {
                text-align: center;
                padding: 4rem 2rem;
                animation: fadeInUp 1s ease-out;
            }

            .logo-container {
                margin-bottom: 2rem;
                animation: scaleIn 0.8s ease-out;
            }

            .logo {
                width: 150px;
                height: 150px;
                margin: 0 auto;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(20px);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: 3px solid rgba(255, 255, 255, 0.3);
                animation: pulse 3s infinite;
            }

            .logo img {
                object-fit: contain;
            }

            h1 {
                font-family: 'Poppins', sans-serif;
                font-size: 5rem;
                font-weight: 800;
                background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 1rem;
                letter-spacing: -2px;
                line-height: 1.1;
            }

            .subtitle {
                font-size: 1.5rem;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 1rem;
                font-weight: 500;
            }

            .description {
                font-size: 1.1rem;
                color: rgba(255, 255, 255, 0.7);
                max-width: 600px;
                margin: 0 auto 3rem;
                line-height: 1.6;
            }

            /* CTA Buttons */
            .cta-buttons {
                display: flex;
                gap: 1.5rem;
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 4rem;
            }

            .btn {
                padding: 1rem 2.5rem;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                font-size: 1.1rem;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.3);
            }

            .btn-secondary:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: translateY(-3px);
            }

            /* Features Grid */
            .features {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                margin-bottom: 4rem;
            }

            .feature-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 20px;
                padding: 2.5rem;
                transition: all 0.4s ease;
                animation: fadeInUp 1s ease-out;
                animation-fill-mode: both;
            }

            .feature-card:nth-child(1) { animation-delay: 0.1s; }
            .feature-card:nth-child(2) { animation-delay: 0.2s; }
            .feature-card:nth-child(3) { animation-delay: 0.3s; }

            .feature-card:hover {
                transform: translateY(-10px);
                background: rgba(255, 255, 255, 0.15);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }

            .feature-icon {
                font-size: 3.5rem;
                margin-bottom: 1.5rem;
                display: block;
                animation: bounce 2s infinite;
            }

            .feature-card:nth-child(2) .feature-icon { animation-delay: 0.2s; }
            .feature-card:nth-child(3) .feature-icon { animation-delay: 0.4s; }

            .feature-card h3 {
                color: white;
                font-size: 1.5rem;
                margin-bottom: 1rem;
                font-weight: 700;
            }

            .feature-card p {
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.6;
                font-size: 1rem;
            }

            /* Info Section */
            .info-section {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 20px;
                padding: 3rem;
                animation: fadeInUp 1s ease-out 0.4s;
                animation-fill-mode: both;
            }

            .info-section h2 {
                color: white;
                font-size: 2rem;
                margin-bottom: 2rem;
                text-align: center;
                font-weight: 700;
            }

            .info-list {
                list-style: none;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .info-list li {
                color: rgba(255, 255, 255, 0.9);
                padding: 1rem 1.5rem;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 10px;
                transition: all 0.3s ease;
                border-left: 4px solid rgba(255, 255, 255, 0.3);
                font-size: 1rem;
            }

            .info-list li:hover {
                background: rgba(255, 255, 255, 0.1);
                transform: translateX(10px);
                border-left-color: white;
            }

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes scaleIn {
                from {
                    opacity: 0;
                    transform: scale(0.8);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            @keyframes pulse {
                0%, 100% {
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                }
                50% {
                    box-shadow: 0 25px 80px rgba(102, 126, 234, 0.5);
                }
            }

            @keyframes bounce {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
            }

            /* Responsive */
            @media (max-width: 768px) {
                h1 {
                    font-size: 3rem;
                }

                .subtitle {
                    font-size: 1.2rem;
                }

                .description {
                    font-size: 1rem;
                }

                .features {
                    grid-template-columns: 1fr;
                }

                .cta-buttons {
                    flex-direction: column;
                    align-items: stretch;
                }

                .btn {
                    justify-content: center;
                }

                nav {
                    flex-direction: column;
                    align-items: stretch;
                }

                .info-list {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <!-- Animated Background Particles -->
        <div class="bg-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Navigation -->
        @if (Route::has('login'))
            <nav>
                @auth
                    <a href="{{ route('admin.index') }}">🎯 Panel de Administración</a>
                @else
                    <a href="{{ route('login') }}">🔐 Iniciar Sesión</a>
                    <a href="{{ route('register') }}">✨ Registrarse</a>
                @endauth
            </nav>
        @endif

        <!-- Main Container -->
        <div class="container">
            <!-- Hero Section -->
            <div class="hero">
                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('images/LogoSISCONINT.png') }}" alt="Sisconint-Porcino Logo" onerror="this.style.display='none'">
                    </div>
                </div>
                <p class="description">
                    Tecnología de vanguardia para la gestión integral de tu negocio. 
                    Innovación, eficiencia y soluciones avanzadas en una sola plataforma.
                </p>

                <div class="cta-buttons">
                    @auth
                        <a href="{{ route('admin.index') }}" class="btn btn-primary">
                            🚀 Ir al Panel de Administración
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            🔓 Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-secondary">
                            📝 Crear Cuenta
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Features Section -->
            <div class="features">
                <div class="feature-card">
                    <span class="feature-icon">🚀</span>
                    <h3>Innovación</h3>
                    <p>Tecnologías de punta y soluciones modernas diseñadas para impulsar tu negocio hacia el futuro.</p>
                </div>

                <div class="feature-card">
                    <span class="feature-icon">⚡</span>
                    <h3>Rendimiento</h3>
                    <p>Optimización y velocidad en cada proceso. Resultados rápidos y eficientes garantizados.</p>
                </div>

                <div class="feature-card">
                    <span class="feature-icon">🔒</span>
                    <h3>Seguridad</h3>
                    <p>Protección avanzada para tus datos con los más altos estándares de seguridad del mercado.</p>
                </div>
            </div>

            <!-- Info Section -->
            <div class="info-section">
                <h2>¿Por qué elegir Sisconint-Porcino?</h2>
                <ul class="info-list">
                    <li>✅ Soluciones personalizadas para tu negocio</li>
                    <li>✅ Soporte técnico especializado 24/7</li>
                    <li>✅ Tecnología de última generación</li>
                    <li>✅ Seguridad y confiabilidad garantizadas</li>
                    <li>✅ Interfaz intuitiva y fácil de usar</li>
                    <li>✅ Actualizaciones constantes y mejoras</li>
                </ul>
            </div>
        </div>
    </body>
</html>
