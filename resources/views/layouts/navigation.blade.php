<div x-data="{ open: false, masterOpen: true }" class="navigation-shell">
    <aside class="sidebar-nav" aria-label="Sidebar navigation">
        <a href="{{ route('dashboard') }}" class="brand-mark">
            <x-application-logo class="brand-mark__logo" />
            <span><strong>Agreement</strong><small>Management system</small></span>
        </a>
        <div class="sidebar-nav__label">Workspace</div>
        <nav class="sidebar-nav__links">
            <a href="{{ route('dashboard') }}" class="dashboard-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}"><span class="nav-icon">&#9632;</span><span>Overview</span></a>
            <a href="#agreements" class="dashboard-nav-link"><span class="nav-icon">&#9776;</span><span>Agreements</span><span class="nav-count">24</span></a>
            
            <button type="button" class="dashboard-nav-link master-nav-link" @click="masterOpen = !masterOpen" :aria-expanded="masterOpen.toString()"><span class="nav-icon">&#9673;</span><span>Master</span><span class="nav-chevron" :class="{ 'is-open': masterOpen }">&#8964;</span></button>
            <div class="master-subnav" x-show="masterOpen" x-transition>
                <a href="#master-formar">Formar</a>
                <a href="#master-organizer">Organizer</a>
                <a href="#master-company">Company</a>
                <a href="#master-employee">Employee</a>
                <a href="#master-season">Season</a>
                <a href="#master-employee-type">Employee type</a>
            </div>
        </nav>
        <div class="sidebar-nav__footer">
            <a href="{{ route('profile.edit') }}" class="user-mini"><span class="user-mini__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span><span><strong>{{ Auth::user()->name }}</strong><small>Account settings</small></span></a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-link">Log out <span>&rarr;</span></button></form>
        </div>
    </aside>

    <header class="top-nav" aria-label="Top navigation">
        <a href="{{ route('dashboard') }}" class="brand-mark brand-mark--top"><x-application-logo class="brand-mark__logo" /><span><strong>Agreement</strong><small>Management system</small></span></a>
        <nav class="top-nav__links"><a href="{{ route('dashboard') }}" class="is-active">Overview</a><a href="#agreements">Agreements</a><div class="top-nav-master"><button type="button" @click="masterOpen = !masterOpen" :aria-expanded="masterOpen.toString()">Master <span class="nav-chevron" :class="{ 'is-open': masterOpen }">&#8964;</span></button><div class="top-nav-master__menu" x-show="masterOpen" x-transition><a href="#master-formar">Formar</a><a href="#master-organizer">Organizer</a><a href="#master-company">Company</a><a href="#master-employee">Employee</a><a href="#master-season">Season</a><a href="#master-employee-type">Employee type</a></div></div><a href="#activity">Activity</a><a href="#deadlines">Deadlines</a></nav>
        <div class="top-nav__actions"><a href="{{ route('profile.edit') }}" class="user-mini user-mini--top"><span class="user-mini__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span><span><strong>{{ Auth::user()->name }}</strong><small>Account settings</small></span></a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-link">Log out <span>&rarr;</span></button></form></div>
    </header>

    <div class="mobile-nav-bar">
        <a href="{{ route('dashboard') }}" class="brand-mark"><x-application-logo class="brand-mark__logo" /><span><strong>Agreement</strong><small>Management system</small></span></a>
        <button type="button" class="mobile-nav-toggle" @click="open = !open" :aria-expanded="open.toString()" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
    </div>
    <nav class="mobile-nav-links" x-show="open" x-transition><a href="{{ route('dashboard') }}">Overview</a><a href="#agreements">Agreements</a><button type="button" @click="masterOpen = !masterOpen" :aria-expanded="masterOpen.toString()">Master <span class="nav-chevron" :class="{ 'is-open': masterOpen }">&#8964;</span></button><div class="mobile-master-subnav" x-show="masterOpen" x-transition><a href="#master-formar">Formar</a><a href="#master-organizer">Organizer</a><a href="#master-company">Company</a><a href="#master-employee">Employee</a><a href="#master-season">Season</a><a href="#master-employee-type">Employee type</a></div><a href="#activity">Activity</a><a href="#deadlines">Deadlines</a><a href="{{ route('profile.edit') }}">Profile</a></nav>
</div>