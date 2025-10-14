<header class="header">
    <nav class="nav-container">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('images/logo.svg') }}" alt="Covadis Logo" class="h-8 w-auto" />
        </a>
        
        <ul class="nav-menu" id="nav-menu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') || request()->is('/') ? 'active' : '' }}">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9,22 9,12 15,12 15,22"/>
                    </svg>
                    Activiteiten
                </a>
            </li>
            
            @auth
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Mijn Activiteiten
                    </a>
                </li>
                
                @can('manage-users')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        Gebruikers beheren
                    </a>
                </li>
                @endcan
                
                @can('manage-activities')
                <li class="nav-item">
                    <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.index') ? 'active' : '' }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>
                        Activiteiten beheren
                    </a>
                </li>
                @endcan
                
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Profiel
                    </a>
                </li>
                
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="nav-link logout-button">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16,17 21,12 16,7"/>
                                <line x1="21" x2="9" y1="12" y2="12"/>
                            </svg>
                            Uitloggen
                        </button>
                    </form>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10,17 15,12 10,7"/>
                            <line x1="15" x2="3" y1="12" y2="12"/>
                        </svg>
                        Login
                    </a>
                </li>
            @endauth
        </ul>

        <div class="mobile-toggle" id="mobile-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>

<style>
/* --- exact dezelfde styling als eerder --- */
.header{background:#fff;color:#1f2937;box-shadow:0 1px 3px rgba(0,0,0,.1);border-bottom:1px solid #e5e7eb;position:sticky;top:0;z-index:1000}
.nav-container{max-width:1200px;margin:0 auto;padding:0 1.5rem;display:flex;justify-content:space-between;align-items:center;height:64px}
.logo{font-size:1.5rem;font-weight:700;color:#111827;text-decoration:none;transition:color .2s ease}
.logo:hover{color:#3b82f6}
.nav-menu{display:flex;list-style:none;gap:.5rem;align-items:center;margin:0;padding:0}
.nav-item{position:relative}
.nav-link{color:#6b7280;text-decoration:none;padding:.75rem 1rem;border-radius:8px;transition:all .2s ease;display:flex;align-items:center;gap:.5rem;font-weight:500;font-size:.875rem}
.nav-link:hover{background-color:#f3f4f6;color:#111827}
.nav-link.active{background-color:#FAA21B;color:#fff}
.logout-form{margin:0}
.logout-button{background:none;border:none;cursor:pointer;font-family:inherit;font-size:inherit;color:#6b7280;text-decoration:none;padding:.75rem 1rem;border-radius:8px;transition:all .2s ease;display:flex;align-items:center;gap:.5rem;font-weight:500;font-size:.875rem;width:100%}
.logout-button:hover{background-color:#f3f4f6;color:#111827}
.mobile-toggle{display:none;flex-direction:column;cursor:pointer;padding:.5rem;border-radius:6px;transition:background-color .2s ease}
.mobile-toggle:hover{background-color:#f3f4f6}
.mobile-toggle span{width:20px;height:2px;background-color:#6b7280;margin:2px 0;transition:.3s;border-radius:1px}
.mobile-toggle.active span:nth-child(1){transform:rotate(-45deg) translate(-4px,5px);background-color:#111827}
.mobile-toggle.active span:nth-child(2){opacity:0}
.mobile-toggle.active span:nth-child(3){transform:rotate(45deg) translate(-4px,-5px);background-color:#111827}
.icon{width:18px;height:18px}
@media (max-width:768px){
  .mobile-toggle{display:flex}
  .nav-menu{position:fixed;left:-100%;top:64px;flex-direction:column;background:#fff;width:100%;text-align:center;transition:.3s ease;box-shadow:0 4px 6px rgba(0,0,0,.1);padding:1rem 0;gap:0;border-top:1px solid #e5e7eb}
  .nav-menu.active{left:0}
  .nav-item{margin:.25rem 0;width:90%;margin-left:auto;margin-right:auto}
  .nav-link,.logout-button{padding:1rem;justify-content:center;width:100%;border-radius:8px}
  .nav-container{padding:0 1rem}
}
@media (max-width:480px){
  .nav-container{height:60px;padding:0 1rem}
  .logo{font-size:1.25rem}
  .nav-menu{top:60px}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileToggle = document.getElementById('mobile-toggle');
  const navMenu = document.getElementById('nav-menu');
  if (!mobileToggle || !navMenu) return;

  mobileToggle.addEventListener('click', () => {
    mobileToggle.classList.toggle('active');
    navMenu.classList.toggle('active');
  });

  const navLinks = document.querySelectorAll('.nav-link, .logout-button');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      mobileToggle.classList.remove('active');
      navMenu.classList.remove('active');
    });
  });

  document.addEventListener('click', (e) => {
    const insideNav = navMenu.contains(e.target);
    const onToggle = mobileToggle.contains(e.target);
    if (!insideNav && !onToggle && navMenu.classList.contains('active')) {
      mobileToggle.classList.remove('active');
      navMenu.classList.remove('active');
    }
  });
});
</script>
