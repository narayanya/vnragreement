<div x-data="{ open: false, coreMasterOpen: false, masterOpen: false, verifyProductionOpen: false, formMasterOpen: false, locationMasterOpen: false, settingOpen: false }" class="navigation-shell">
    <aside class="sidebar-nav" aria-label="Sidebar navigation">
        <a href="{{ route('dashboard') }}" class="brand-mark">
            <x-application-logo class="brand-mark__logo" />
            <span><strong>Agreement</strong><small>Management system</small></span>
        </a>
        <div class="sidebar-nav__label">Workspace</div>
        <nav class="sidebar-nav__links">
            <a href="{{ route('dashboard') }}" class="dashboard-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}"><span class="nav-icon">&#9632;</span><span>Overview</span></a>
            <a href="#agreements" class="dashboard-nav-link"><span class="nav-icon">&#9776;</span><span>Agreements</span><span class="nav-count">24</span></a>

            <button type="button" class="dashboard-nav-link master-nav-link" @click="coreMasterOpen = !coreMasterOpen" :aria-expanded="coreMasterOpen.toString()"><span class="nav-icon">&#9673;</span><span>Core Master</span><span class="nav-chevron" :class="{ 'is-open': coreMasterOpen }">&#8964;</span></button>
            <div class="master-subnav" x-show="coreMasterOpen" x-transition>
                <a href="{{ route('core.api.index') }}" class="{{ request()->routeIs('core.api.index') ? 'is-active' : '' }}">Core API</a>
                <a href="{{ route('crops.index') }}" class="{{ request()->routeIs('crops.index') ? 'is-active' : '' }}">Crop</a>
                <a href="#master-organizer">Vertical</a>
                <a href="#master-company">Variety</a>
                <a href="#master-company">Company</a>
                <a href="#master-employee">Employee</a>
            </div>
            <button type="button" class="dashboard-nav-link master-nav-link" @click="masterOpen = !masterOpen" :aria-expanded="masterOpen.toString()"><span class="nav-icon">&#9673;</span><span>Master</span><span class="nav-chevron" :class="{ 'is-open': masterOpen }">&#8964;</span></button>
            <div class="master-subnav" x-show="masterOpen" x-transition>
                <a href="#master-formar">Formar</a>
                <a href="#master-organizer">Organizer</a>
                <a href="#master-season">Season</a>
                <a href="#master-employee-type">Contamination</a>
            </div>
            <button type="button" class="dashboard-nav-link master-nav-link" @click="verifyProductionOpen = !verifyProductionOpen" :aria-expanded="verifyProductionOpen.toString()"><span class="nav-icon">&#9673;</span><span>Verify production code</span><span class="nav-chevron" :class="{ 'is-open': verifyProductionOpen }">&#8964;</span></button>
            <div class="master-subnav" x-show="verifyProductionOpen" x-transition>
                <a href="#master-formar">Verify List</a>
                <a href="#master-organizer">Male</a>
                <a href="#master-company">Female</a>
            </div>
            <button type="button" class="dashboard-nav-link master-nav-link" @click="formMasterOpen = !formMasterOpen" :aria-expanded="formMasterOpen.toString()"><span class="nav-icon">&#9673;</span><span>Form master</span><span class="nav-chevron" :class="{ 'is-open': formMasterOpen }">&#8964;</span></button>
            
            <button type="button" class="dashboard-nav-link master-nav-link" @click="locationMasterOpen = !locationMasterOpen" :aria-expanded="locationMasterOpen.toString()"><span class="nav-icon">&#9673;</span><span>Location Master</span><span class="nav-chevron" :class="{ 'is-open': locationMasterOpen }">&#8964;</span></button>
            <div class="master-subnav" x-show="locationMasterOpen" x-transition>
                <a href="#master-formar">Country</a>
                <a href="#master-organizer">State</a>
                <a href="#master-company">District</a>
                <a href="#master-employee">Tahsil</a>
                <a href="#master-season">Block</a>
                <a href="#master-employee-type">Village</a>
            </div>
            <button type="button" class="dashboard-nav-link master-nav-link" @click="settingOpen = !settingOpen" :aria-expanded="settingOpen.toString()"><span class="nav-icon">&#9673;</span><span>Setting</span><span class="nav-chevron" :class="{ 'is-open': settingOpen }">&#8964;</span></button>
            <div class="master-subnav" x-show="settingOpen" x-transition>
                <a href="#master-formar">User List</a>
                <a href="#master-organizer">Permisstion</a>
                <a href="#master-company">Log Report</a>
            </div>
            <a href="#agreements" class="dashboard-nav-link"><span class="nav-icon">&#9776;</span><span>Report</span><span class="nav-count">24</span></a>
            

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