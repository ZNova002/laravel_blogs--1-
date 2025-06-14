<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="header-wrap">
                <form class="form-header" action="" method="POST">
                    {{-- <input class="au-input au-input--xl" type="text" name="search" placeholder="Tìm kiếm" />
                    <button class="au-btn--submit" type="submit">
                        <i class="zmdi zmdi-search"></i>
                    </button> --}}
                </form>
                <div class="header-button">
                    <div class="noti-wrap">
                        {{-- <div class="noti__item js-item-menu">
                            <i class="zmdi zmdi-comment-more"></i>
                        </div>
                        <div class="noti__item js-item-menu">
                            <i class="zmdi zmdi-email"></i>
                        </div>
                        <div class="noti__item js-item-menu">
                            <i class="zmdi zmdi-notifications"></i>
                        </div> --}}
                    </div>
                    <div class="account-wrap">
                        <div class="account-item clearfix js-item-menu">
                            <div class="image">
                                <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            </div>
                            <div class="content">
                                <a class="js-acc-btn" href="#">admin</a>
                            </div>
                            <div class="account-dropdown js-dropdown">
                                <div class="info clearfix">
                                    <div class="image">
                                        <a href="#">
                                            <img src="{{ asset(auth()->user()->avatar ?? 'admin/images/icon/avatar3.jpg') }}" alt="{{ auth()->user()->name }}" />
                                        </a>
                                    </div>
                                    <div class="content">
                                        <h5 class="name">
                                            <a href="#">{{ auth()->user()->name }}</a>
                                        </h5>
                                        <span class="email">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                                <div class="account-dropdown__body">
                                    {{-- <div class="account-dropdown__item">
                                        <a href="/profile">
                                            <i class="zmdi zmdi-account"></i>Tài Khoảng</a>
                                    </div> --}}
                                    <div class="account-dropdown__item">
                                        <a href="/profile">
                                            <i class="zmdi zmdi-settings"></i>Cài đặt</a>
                                    </div>
                                </div>
                                <div class="account-dropdown__footer">
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="zmdi zmdi-power"></i> Logout
                                    </a>
                                </div>

                                <div class="account-dropdown__footer">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
