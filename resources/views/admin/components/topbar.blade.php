<header>

    <div class="topbar d-flex align-items-center">

        <nav class="navbar navbar-expand">

            {{-- Mobile Menu --}}
            <div class="mobile-toggle-icon">
                <i class="bx bx-menu"></i>
            </div>


            {{-- Search --}}
            <div class="search-bar flex-grow-1">

                <div class="position-relative search-bar-box">

                    <input type="text" class="form-control search-control" placeholder="Search..." aria-label="Search">

                    <span class="position-absolute top-50 search-show translate-middle-y">
                        <i class="bx bx-search"></i>
                    </span>

                </div>

            </div>


            {{-- Right Side --}}
            <div class="top-menu ms-auto">

                <ul class="navbar-nav align-items-center">

                    {{-- User --}}
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            data-bs-toggle="dropdown">

                            <div class="user-box d-flex align-items-center">

                                <div class="user-info">

                                    <p class="user-name mb-0">
                                        {{ auth()->user()->name }}
                                    </p>

                                    <p class="designattion mb-0">
                                        Administrator
                                    </p>

                                </div>

                                <i class="bx bx-chevron-down ms-2"></i>

                            </div>

                        </a>


                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>

                                <div class="dropdown-item">

                                    <strong>
                                        {{ auth()->user()->name }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ auth()->user()->email }}
                                    </small>

                                </div>

                            </li>


                            <li>
                                <hr class="dropdown-divider">
                            </li>


                            <li>

                                <form method="POST" action="{{ route('admin.logout') }}">

                                    @csrf

                                    <button type="submit" class="dropdown-item">

                                        <i class="bx bx-log-out me-2"></i>

                                        Logout

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </li>

                </ul>

            </div>

        </nav>

    </div>

</header>
