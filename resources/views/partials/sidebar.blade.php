<!-- Sidebar Start -->
<div class="startbar d-print-none">
    <!-- Brand -->
    <div class="brand">
        <a href="/dashboard" class="logo">
            <span>
                <img src="/assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
                <img src="/assets/images/logo-light.png" alt="logo-large" class="logo-lg logo-light">
                <img src="/assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark">
            </span>
        </a>
    </div>

    <!-- Menu -->
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label mt-2"><span>Navigation</span></li>

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link {!! Request::is('dashboard') ? 'active' : '' !!}" href="/dashboard">
                            <i class="iconoir-home menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Customers -->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarCustomers" data-bs-toggle="collapse">
                            <i class="iconoir-user menu-icon"></i>
                            <span>Customers</span>
                        </a>
                        <div class="collapse" id="sidebarCustomers">
                            <ul class="list-unstyled ps-0">
                                <li class="nav-item">
                                    <a href="{{ route('customers.create') }}" class="nav-link ms-4">
                                        <i class="iconoir-plus-circle me-2"></i>
                                        <span>Add New Customer</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('customers.index') }}" class="nav-link ms-4">
                                        <i class="iconoir-list me-2"></i>
                                        <span>Customer List</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- JobSheets -->
                    <li class="nav-item">
                        <a class="nav-link" href="#sidebarJobsheets" data-bs-toggle="collapse">
                            <i class="iconoir-clipboard-check menu-icon"></i>
                            <span>JobSheets</span>
                        </a>
                        <div class="collapse" id="sidebarJobsheets">
                            <ul class="list-unstyled ps-0">
                                <li class="nav-item">
                                    <a href="{{ route('jobsheets.create') }}" class="nav-link ms-4">
                                        <i class="iconoir-plus-circle me-2"></i>
                                        <span>Add New JobSheet</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('jobsheets.index') }}" class="nav-link ms-4">
                                        <i class="iconoir-list me-2"></i>
                                        <span>JobSheet List</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- WhatsApp -->
                    <li class="nav-item">
                        <a class="nav-link {!! Request::is('whatsapp*') ? 'active' : '' !!}" href="#sidebarWhatsapp"
                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="sidebarWhatsapp">
                            <i class="iconoir-message-text menu-icon"></i>
                            <span>WhatsApp</span>
                        </a>
                        <div class="collapse" id="sidebarWhatsapp">
                            <ul class="list-unstyled ps-0">
                                <li class="nav-item">
                                    <a href="/whatsapp/service" class="nav-link ms-4">
                                        <i class="iconoir-tools me-2"></i>
                                        <span>Service Notifications</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/whatsapp/festival" class="nav-link ms-4">
                                        <i class="iconoir-calendar me-2"></i>
                                        <span>Festival Messages</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Reports -->
                    <li class="nav-item">
                        <a class="nav-link {!! Request::is('reports') ? 'active' : '' !!}" href="/reports">
                            <i class="iconoir-reports menu-icon"></i>
                            <span>Reports</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="nav-item">
                        <a class="nav-link {!! Request::is('settings') ? 'active' : '' !!}" href="/settings">
                            <i class="iconoir-settings menu-icon"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="startbar-overlay d-print-none"></div>
<!-- Sidebar End -->