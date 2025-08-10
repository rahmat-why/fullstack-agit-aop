<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Fullstack AGIT AOP')</title>

    
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        #sidebar {
            width: 220px;
            background-color: #dc3545; 
            color: white;
            min-height: 100vh;
            position: fixed;
            top: 56px; 
            left: 0;
            padding-top: 1rem;
            display: block;
        }

        #sidebar .nav-link {
            color: white;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background-color: #a71d2a;
            color: white;
        }

        
        #content {
            margin-left: 220px;
            padding: 1.5rem;
            width: calc(100% - 220px);
            margin-top: 56px; 
        }

        
        nav.navbar {
            position: fixed;
            width: 100%;
            z-index: 1030;
        }

        
        @media (max-width: 991.98px) {
            #sidebar {
                display: none;
            }
            #content {
                margin-left: 0;
                width: 100%;
                margin-top: 56px;
            }
        }

        
        .navbar-profile-img {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 50%;
        }

        
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.previous):not(.next) {
            background-color: #dc3545 !important; 
            color: white !important;
            border: none !important;
            border-radius: 0.25rem;
            margin: 0 2px;
            padding: 2px 8px; 
            font-size: 0.8rem; 
            cursor: pointer;
            display: inline-block;
        }

        
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.previous):not(.next):hover {
            background-color: #a71d2a !important; 
            color: white !important;
        }

        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.active {
            background-color: #a71d2a !important; 
            color: white !important;
            font-weight: bold;
            border: none !important;
            font-size: 0.8rem;
            padding: 2px 8px;
        }

        
        .dataTables_wrapper .dataTables_paginate .pagination > .page-item.active > .page-link {
            background-color: #a71d2a !important;
            border-color: #a71d2a !important;
            color: white !important;
            font-size: 0.8rem;
            padding: 2px 8px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.active,
        .dataTables_wrapper .dataTables_paginate .pagination > .page-item.active > .page-link {
            background-color: #a71d2a !important;
            border-color: #a71d2a !important;
            color: white !important;
            font-weight: bold;
            font-size: 0.8rem;
            padding: 2px 8px;
        }

        #firstTable_wrapper .dataTables_paginate .paginate_button.current,
        #firstTable_wrapper .dataTables_paginate .pagination > .page-item.active > .page-link {
            background-color: #a71d2a !important;
            color: white !important;
        }
        #secondTable_wrapper .dataTables_paginate .paginate_button.current,
        #secondTable_wrapper .dataTables_paginate .pagination > .page-item.active > .page-link {
            background-color: #ffffff !important;
            color: #a71d2a !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button > .page-link:not(.active) {
            background-color: #a71d2a !important;
            color: white !important;
            font-weight: bold;
            font-size: 0.8rem;
            padding: 2px 8px;
            border: none !important;
            border-radius: 0.25rem;
        }

        .nav-tabs .nav-link {
            color: #dc3545; /* Bootstrap's red */
            border: 1px solid #dc3545;
            background-color: #fff;
        }

        .nav-tabs .nav-link:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .nav-tabs .nav-link.active {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger fixed-top">
        <div class="container-fluid">
            <button class="btn btn-danger d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">Fullstack AGIT AOP</a>

            <div class="dropdown ms-auto me-3">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name=User" alt="Profile" class="navbar-profile-img me-2" />
                    <span>{{ Auth::check() ? Auth::user()->name . ' (' . Auth::user()->role . ')' : 'Guest' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <nav id="sidebar" class="d-none d-lg-block flex-column">
        <div class="px-3 pb-3">
            <input type="text" id="menuSearch" class="form-control form-control-sm" placeholder="Search menu...">
        </div>
        <ul class="nav flex-column px-2" id="menuList">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                href="{{ route('dashboard') }}">
                Dashboard
                </a>
            </li>

            @if(Auth::user()->role === 'librarian')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.view') ? 'active' : '' }}" 
                    href="{{ route('categories.view') }}">
                    Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('books.view') ? 'active' : '' }}" 
                    href="{{ route('books.view') }}">
                    Books
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('loans.view') ? 'active' : '' }}" 
                    href="{{ route('loans.view') }}">
                    Loans
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.view') ? 'active' : '' }}" 
                    href="{{ route('users.view') }}">
                    Users
                    </a>
                </li>
            @elseif(Auth::user()->role === 'member')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('loans.view') ? 'active' : '' }}" 
                    href="{{ route('loans.view') }}">
                    Loans
                    </a>
                </li>
            @endif
        </ul>
    </nav>

    <div class="offcanvas offcanvas-start bg-danger text-white" tabindex="-1" id="offcanvasSidebar" aria-labelledby="offcanvasSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasSidebarLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body px-3">
            <input type="text" id="menuSearchMobile" class="form-control form-control-sm mb-3" placeholder="Search menu...">
            <ul class="nav flex-column" id="menuListMobile">
                <li class="nav-item">
                    <a class="nav-link text-white active" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Loans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Users</a>
                </li>
            </ul>
        </div>
    </div>

    <main id="content">
        @yield('content')
    </main>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#menuSearch').on('keyup', function () {
            const searchText = $(this).val().toLowerCase();

            $('#menuList li.nav-item').each(function () {
                const linkText = $(this).text().toLowerCase();

                if (linkText.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#menuSearchMobile').on('keyup', function () {
            const searchText = $(this).val().toLowerCase();

            $('#menuListMobile li.nav-item').each(function () {
                const linkText = $(this).text().toLowerCase();

                if (linkText.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
