<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPASP - Direktorat Pengelolaan Aset dan Sarana Prasarana</title>

    <!-- Font Google: Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (Ditambahkan agar class utility text-7xl, dll berfungsi) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* --- RESET & BASIC STYLES --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background-color: #f4f4f4;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        a { text-decoration: none; }
        ul { list-style: none; }

        /* --- VARIABLES --- */
        :root {
            --primary-green: #0a5f38;
            --primary-orange: #f7941d;
            --gradient-bar: linear-gradient(90deg, #0a5f38 0%, #f7941d 100%);
            --hero-overlay: linear-gradient(135deg, rgba(10, 95, 56, 0.85) 0%, rgba(247, 148, 29, 0.75) 100%);
        }

        .btn-buy-ticket {
            background-color: var(--primary-orange);
            color: white !important;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: inline-block;
        }

        .btn-buy-ticket:hover {
            background-color: #d67d14;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* --- HEADER STYLES --- */
        .site-header {
            background-color: transparent;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 100%;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: none;
        }

        .site-header.scrolled {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }

        .header-container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-inner-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 90px;
            padding: 10px 0;
        }

        /* Logo Styling */
        .site-branding {
            flex-shrink: 0;
            margin-right: 15px;
        }

        .custom-logo {
            height: 50px;
            width: auto;
            display: block;
            transition: transform 0.3s;
        }

        .custom-logo:hover {
            transform: scale(1.05);
        }

        /* Desktop Navigation */
        .desktop-navigation {
            flex-grow: 1;
            display: flex;
            justify-content: flex-end;
            margin-right: 25px;
        }

        .header-menu {
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
            flex-wrap: nowrap;
            align-items: center;
        }

        .header-menu li.menu-item a {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: all 0.3s ease;
            position: relative;
            white-space: nowrap;
            padding: 5px 0;
        }

        .header-menu li.menu-item a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0%;
            height: 2px;
            background-color: var(--primary-orange);
            transition: width 0.3s ease;
        }

        .header-menu li.menu-item a:hover::after,
        .header-menu li.menu-item a.active::after {
            width: 100%;
        }

        .header-menu li.menu-item a:hover,
        .header-menu li.menu-item a.active {
            color: var(--primary-orange);
            opacity: 1;
        }

        /* CTA Button */
        .header-cta {
            flex-shrink: 0;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            margin-left: 20px;
        }

        .hamburger-menu {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .hamburger-menu .close-icon {
            display: none;
        }

        /* --- HERO SECTION --- */
        .hero {
            position: relative;
            height: 90vh;
            min-height: 650px;
            background-image: url('https://images.unsplash.com/photo-1532443606622-263a4362d294?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding-bottom: 80px;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--hero-overlay);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1100px;
            padding-top: 40px;
            animation: fadeInUp 1s ease-out;
        }

        /* --- MODIFIKASI UKURAN FONT JUDUL DI SINI --- */
        /*
        .hero h1 {
            font-size: 2.8rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 15px;
            text-shadow: 0 10px 30px rgba(0,0,0,0.3);
            letter-spacing: -2px;
        }

        @media (min-width: 768px) {
            .hero h1 {
                font-size: 4.8rem;
            }
        }

        @media (min-width: 1024px) {
            .hero h1 {
                font-size: 6.8rem;
                line-height: 1;
                margin-bottom: 25px;
            }
        }
        */

        .hero-details {
            display: flex;
            justify-content: center;
            gap: 40px;
            font-weight: 600;
            /* Tambahan margin-top agar turun sedikit */
            margin-top: 40px;
            margin-bottom: 50px;
            text-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .hero-details span {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hero-details i {
            color: var(--primary-orange);
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn-outline {
            border: 2px solid rgba(255,255,255,0.8);
            padding: 15px 40px;
            color: white;
            font-weight: 800;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(5px);
            display: inline-block;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary-green);
            border-color: white;
            transform: translateY(-3px);
        }

        /* --- COUNTDOWN FLOATING BAR --- */
        .countdown-wrapper {
            position: relative;
            z-index: 10;
            margin-top: -80px;
            margin-bottom: 80px;
        }

        .countdown-bar {
            background: var(--gradient-bar);
            border-radius: 20px;
            padding: 35px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            color: white;
        }

        .countdown-text {
            max-width: 50%;
        }

        .countdown-text h3 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 8px;
            font-style: italic;
            letter-spacing: -0.5px;
        }

        .countdown-text p {
            opacity: 0.95;
            font-size: 1rem;
            font-weight: 500;
        }

        .timer-label {
            font-size: 1rem;
            font-weight: 500;
            color: white;
            margin-right: 20px;
            opacity: 0.95;
        }

        .timer {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .time-box {
            background: white;
            color: var(--primary-green);
            width: 85px;
            height: 95px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
        }

        .time-box::before {
            content: '';
            width: 30px;
            height: 3px;
            background: #eee;
            position: absolute;
            top: 15px;
            border-radius: 2px;
        }

        .time-box .num {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            margin-top: 5px;
            font-family: 'Montserrat', sans-serif;
        }

        .time-box .label {
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #888;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }

        /* --- RESPONSIVE / MOBILE STYLES --- */
        @media (max-width: 1024px) {
            .desktop-navigation, .desktop-cta { display: none; }
            .mobile-menu-toggle { display: block; }

            /* Drawer Mobile Style */
            .desktop-navigation.active {
                display: flex;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--primary-green);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
                border-top: 1px solid rgba(255,255,255,0.1);
                margin-right: 0;
            }

            .header-menu {
                flex-direction: column;
                align-items: flex-start;
                gap: 0;
                width: 100%;
            }

            .header-menu li.menu-item {
                width: 100%;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }

            .header-menu li.menu-item a {
                font-size: 15px;
                padding: 15px 0;
                display: block;
                width: 100%;
            }

            .hamburger-menu[aria-expanded="true"] .hamburger-icon {
                display: none;
            }
            .hamburger-menu[aria-expanded="true"] .close-icon {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .site-header { height: 70px; }
            .header-inner-custom { height: 70px; }
            .custom-logo { height: 40px; }

            .hero { height: auto; min-height: 500px; padding: 100px 0 100px 0; }

            /* Penyesuaian agar teks tidak keluar layar */
            .hero-content {
                width: 100%;
                padding-left: 10px;
                padding-right: 10px;
            }

            .hero-details { flex-direction: column; gap: 15px; font-size: 1.1rem; margin-bottom: 30px; }
            .hero-actions { flex-direction: column; gap: 15px; width: 80%; margin: 0 auto; }

            .countdown-wrapper { margin-top: -50px; }
            .countdown-bar {
                flex-direction: column;
                text-align: center;
                gap: 25px;
                padding: 30px 20px;
            }
            .countdown-text { max-width: 100%; }
            .timer { gap: 10px; }
            .time-box { width: 65px; height: 75px; }
            .time-box .num { font-size: 1.6rem; }
        }

        /* --- ANIMATIONS --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <header id="site-header" class="site-header baur-header menu-dropdown-tablet">
        <div class="header-container">
            <div class="header-inner-custom">
                <!-- Logo Section -->
                <div class="site-branding">
                    <div class="site-logo">
                        <a href="https://baurrun.com/" class="custom-logo-link" rel="home" aria-current="page">
                            <img src="/images/Unpad_logo.png" class="custom-logo" alt="Unpad Logo">
                        </a>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="site-navigation desktop-navigation" aria-label="Main menu">
                    <ul id="menu-header" class="header-menu">
                        <li id="menu-item-home" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#home" class="active">Home</a></li>
                        <li id="menu-item-about" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#about">Profil Pimpinan</a></li>
                        <li id="menu-item-race-info" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="/PeminjamanKendaraanUnpad">Peminjaman Kendaraan</a></li>
                        <li id="menu-item-prizes" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="https://pk3l.unpad.ac.id/">PK3L</a></li>
                        <li id="menu-item-faq-tnc" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#faq-tnc">Toga Unpad</a></li>
                    </ul>
                </nav>

                <!-- Buy Ticket Button (Desktop) -->
                <div class="header-cta desktop-cta">
                    <a href="/app/login" target="_blank" class="btn-buy-ticket">
                        Login aplikasi
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <div class="mobile-menu-toggle">
                    <div class="hamburger-menu" aria-label="Toggle Menu" aria-expanded="false">
                        <span class="hamburger-icon">
                            <svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 9C24 9.26522 23.8946 9.51957 23.7071 9.70711C23.5196 9.89464 23.2652 10 23 10H1C0.734784 10 0.48043 9.89464 0.292893 9.70711C0.105357 9.51957 0 9.26522 0 9C0 8.73478 0.105357 8.48043 0.292893 8.29289C0.48043 8.10536 0.734784 8 1 8H23C23.2652 8 23.5196 8.10536 23.7071 8.29289C23.8946 8.48043 24 8.73478 24 9ZM1 2H23C23.2652 2 23.5196 1.89464 23.7071 1.70711C23.8946 1.51957 24 1.26522 24 1C24 0.734784 23.8946 0.48043 23.7071 0.292893C23.5196 0.105357 23.2652 0 23 0H1C0.734784 0 0.48043 0.105357 0.292893 0.292893C0.105357 0.48043 0 0.734784 0 1C0 1.26522 0.105357 1.51957 0.292893 1.70711C0.48043 1.89464 0.734784 2 1 2ZM23 16H1C0.734784 16 0.48043 16.1054 0.292893 16.2929C0.105357 16.4804 0 16.7348 0 17C0 17.2652 0.105357 17.5196 0.292893 17.7071C0.48043 17.8946 0.734784 18 1 18H23C23.2652 18 23.5196 17.8946 23.7071 17.7071C23.8946 17.5196 24 17.2652 24 17C24 16.7348 23.8946 16.4804 23.7071 16.2929C23.5196 16.1054 23.2652 16 23 16Z" fill="white"></path>
                            </svg>
                        </span>
                        <span class="close-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.7076 18.2925C19.8005 18.3854 19.8742 18.4957 19.9245 18.6171C19.9747 18.7385 20.0006 18.8686 20.0006 19C20.0006 19.1314 19.9747 19.2615 19.9245 19.3829C19.8742 19.5043 19.8005 19.6146 19.7076 19.7075C19.6147 19.8004 19.5044 19.8741 19.383 19.9244C19.2616 19.9747 19.1315 20.0006 19.0001 20.0006C18.8687 20.0006 18.7386 19.9747 18.6172 19.9244C18.4958 19.8741 18.3855 19.8004 18.2926 19.7075L10.0001 11.4137L1.70757 19.7075C1.51993 19.8951 1.26543 20.0006 1.00007 20.0006C0.734704 20.0006 0.480208 19.8951 0.292568 19.7075C0.104927 19.5199 -0.000488276 19.2654 -0.000488281 19C-0.000488286 18.7346 0.104927 18.4801 0.292568 18.2925L8.58632 10L0.292568 1.7075C0.104927 1.51986 -0.000488281 1.26536 -0.000488281 0.999999C-0.000488281 0.734635 0.104927 0.48014 0.292568 0.292499C0.480208 0.104858 0.734704 -0.000556946 1.00007 -0.000556946C1.26543 -0.000556946 1.51993 0.104858 1.70757 0.292499L10.0001 8.58625L18.2926 0.292499C18.4802 0.104858 18.7347 -0.000556951 19.0001 -0.000556946C19.2654 -0.000556941 19.5199 0.104858 19.7076 0.292499C19.8952 0.48014 20.0006 0.734635 20.0006 0.999999C20.0006 1.26536 19.8952 1.51986 19.7076 1.7075L11.4138 10L19.7076 18.2925Z" fill="white"></path>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO / BANNER UTAMA -->
    <section class="hero" id="home">
        <div class="container hero-content">
            <h1 class="text-7xl lg:text-9xl font-extrabold leading-tight mb-4 tracking-tight">
                Selamat Datang
            </h1>
            <div class="w-full mx-auto">
                <!--
                   MENGHAPUS: whitespace-nowrap overflow-hidden text-ellipsis
                   Agar teks tidak terpotong (truncate) dan bisa wrap.
                -->
                <p class="text-xl lg:text-4xl font-bold tracking-tight mb-2 uppercase whitespace-nowrap">
                    Direktorat Pengelolaan Aset dan Sarana Prasarana
                </p>
                <p class="text-2xl lg:text-4xl font-semibold opacity-90">
                    Universitas Padjadjaran
                </p>
            </div>

            <div class="hero-details">
                <span class="text-1xl lg:text-1xl"><i class="fas fa-map-marker-alt"></i> Gedung Rektorat Lt. 3 Jl. Raya Bandung Sumedang KM.21, Hegarmanah, Kec. Jatinangor, Kabupaten Sumedang </span>
            </div>

            <div class="hero-actions">
                <a href="#" class="btn-outline">Fasilitas Mahasiswa</a>
                <a href="#" class="btn-outline">Fasilitas Kesehatan</a>
            </div>
        </div>
    </section>

    <!-- COUNTDOWN TIMER -->
    <div class="container countdown-wrapper">
        <div class="countdown-bar">
            <div class="countdown-text">
                <h3>SEDANG DALAM TAHAP PERBAIKAN</h3>
            </div>

            <div class="timer">
                <span class="timer-label">Akan siap dalam</span>
                <div class="time-box">
                    <span class="num" id="days">00</span>
                    <span class="label">Days</span>
                </div>
                <div class="time-box">
                    <span class="num" id="hours">00</span>
                    <span class="label">Hours</span>
                </div>
                <div class="time-box">
                    <span class="num" id="minutes">00</span>
                    <span class="label">Mins</span>
                </div>
                <div class="time-box">
                    <span class="num" id="seconds">00</span>
                    <span class="label">Secs</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        const targetDate = new Date('2026-01-08T16:50:00').getTime();
        function updateCountdown() {
            const now = new Date().getTime();
            const gap = targetDate - now;

            const second = 1000;
            const minute = second * 60;
            const hour = minute * 60;
            const day = hour * 24;

            const d = Math.floor(gap / day);
            const h = Math.floor((gap % day) / hour);
            const m = Math.floor((gap % hour) / minute);
            const s = Math.floor((gap % minute) / second);

            document.getElementById('days').innerText = d < 10 ? '0' + d : d;
            document.getElementById('hours').innerText = h < 10 ? '0' + h : h;
            document.getElementById('minutes').innerText = m < 10 ? '0' + m : m;
            document.getElementById('seconds').innerText = s < 10 ? '0' + s : s;
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Fungsi Toggle Menu Mobile
        const hamburgerBtn = document.querySelector('.hamburger-menu');
        const mainNav = document.querySelector('.desktop-navigation');

        if(hamburgerBtn && mainNav) {
            hamburgerBtn.addEventListener('click', () => {
                const isExpanded = hamburgerBtn.getAttribute('aria-expanded') === 'true';
                hamburgerBtn.setAttribute('aria-expanded', !isExpanded);
                mainNav.classList.toggle('active');
            });
        }

        // Script untuk Header Transparan saat Scroll
        const siteHeader = document.getElementById('site-header');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
