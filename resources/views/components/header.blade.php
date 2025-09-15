<header class="header">
    <nav class="nav-container">
        <a href="{{ route('home') }}" class="logo">
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="Covadis Logo"
                class="h-8 w-auto"
            />
            
        </a>
        
        <ul class="nav-menu" id="nav-menu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') || request()->is('/') ? 'active' : '' }}" style="background-color: #FAA21B;">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9,22 9,12 15,12 15,22"/>
                    </svg>
                    Activiteiten
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Mijn Activiteiten
                </a>
            </li>
        </ul>

        <div class="mobile-toggle" id="mobile-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>

<style>
    .header {
        background: #ffffff;
        color: #1f2937;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 64px;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .logo:hover {
        color: #3b82f6;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 0.5rem;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        color: #6b7280;
        text-decoration: none;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .nav-link:hover {
        background-color: #f3f4f6;
        color: #111827;
    }

    .nav-link.active {
        background-color: #3b82f6;
        color: white;
    }

    .mobile-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s ease;
    }

    .mobile-toggle:hover {
        background-color: #f3f4f6;
    }

    .mobile-toggle span {
        width: 20px;
        height: 2px;
        background-color: #6b7280;
        margin: 2px 0;
        transition: 0.3s;
        border-radius: 1px;
    }

    .mobile-toggle.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-4px, 5px);
        background-color: #111827;
    }

    .mobile-toggle.active span:nth-child(2) {
        opacity: 0;
    }

    .mobile-toggle.active span:nth-child(3) {
        transform: rotate(45deg) translate(-4px, -5px);
        background-color: #111827;
    }

    .icon {
        width: 18px;
        height: 18px;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .mobile-toggle {
            display: flex;
        }

        .nav-menu {
            position: fixed;
            left: -100%;
            top: 64px;
            flex-direction: column;
            background: #ffffff;
            width: 100%;
            text-align: center;
            transition: 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            gap: 0;
            border-top: 1px solid #e5e7eb;
        }

        .nav-menu.active {
            left: 0;
        }

        .nav-item {
            margin: 0.25rem 0;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .nav-link {
            padding: 1rem;
            justify-content: center;
            width: 100%;
            border-radius: 8px;
        }

        .nav-container {
            padding: 0 1rem;
        }
    }

    @media (max-width: 480px) {
        .nav-container {
            height: 60px;
            padding: 0 1rem;
        }

        .logo {
            font-size: 1.25rem;
        }

        .nav-menu {
            top: 60px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobile-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });

        document.addEventListener('click', function(event) {
            const isClickInsideNav = navMenu.contains(event.target);
            const isClickOnToggle = mobileToggle.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnToggle && navMenu.classList.contains('active')) {
                mobileToggle.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }
});
</script>