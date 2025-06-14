<aside class="menu-sidebar d-none d-lg-block">
    <div class="{{ isActive('categories*') }}">
        <a href="/">
            <h1 class="logo">QLBLOGS</h1>
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            @php
                function isActive($pattern) {
                    return request()->is($pattern) ? 'active' : '';
                }
            @endphp
            <ul class="list-unstyled navbar__list">
                <li class="{{ isActive('/') }}">
                    <a href="/">
                        <i class="fas fa-tachometer-alt"></i>Dashboard
                    </a>
                </li>

                <li class="{{ isActive('users*') }}">
                    <a href="/users">
                        <i class="fa-solid fa-users"></i>Tài khoản
                    </a>
                </li>

                <li class="{{ isActive('categories*') }}">
                    <a href="/categories">
                        <i class="fas fa-list"></i>Danh mục
                    </a>
                </li>

                <li class="{{ isActive('posts*') }}">
                    <a href="/posts">
                        <i class="fa-solid fa-newspaper"></i>Bài viết
                    </a>
                </li>

                <li class="{{ isActive('comments*') }}">
                    <a href="/comments">
                        <i class="fa-solid fa-comment"></i>comments
                    </a>
                </li>

                <li class="{{ isActive('tags*') }}">
                    <a href="/tags">
                        <i class="fa-solid fa-tags"></i>Tag
                    </a>
                </li>

                <li class="{{ isActive('contacts*') }}">
                    <a href="/contacts">
                        <i class="fa-solid fa-phone-volume"></i></i>Liên hệ
                    </a>
                </li>

                <li class="{{ isActive('about*') }}">
                    <a href="/about">
                        <i class="fa-regular fa-address-card"></i>Về tôi
                    </a>
                </li>
                <li class="{{ isActive('settings*') }}">
                    <a href="/settings">
                        <i class="zmdi zmdi-settings"></i>settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
