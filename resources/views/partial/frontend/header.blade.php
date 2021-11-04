
<!-- Header -->
<header id="wn__header" class="oth-page header__area header__absolute sticky__header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-7 col-lg-2">
                <div class="logo">
                    <a href="{{route('frontend.index')}}">
                        <img src="{{asset('frontend/images/logo.png')}}" alt="logo images">
                    </a>
                </div>
            </div>
            <div class="col-lg-8 d-none d-lg-block">
                <nav class="mainmenu__nav">
                    <ul class="meninmenu d-flex justify-content-start">
                        <li class="drop with--one--item"><a href="{{route('frontend.index')}}">Home</a></li>
                        <li class="drop with--one--item"><a href="{{route('post_show','about-us')}}">About Us</a></li>
                        <li class="drop with--one--item"><a href="{{route('post_show','our-vision')}}">Our Vision</a></li>



                        <li class="drop"><a href="javascript:void(0);">Blog</a>
                            <div class="megamenu dropdown">
                                <ul class="item item01">
                                    @foreach($global_categories as $category)
                                        <li><a href="{{route('frontend.category.post',$category->slug)}}">{{$category->name}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                        <li><a href="{{route('frontend.contact')}}">Contact Us</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-8 col-sm-8 col-5 col-lg-2">
                <ul class="header__sidebar__right d-flex justify-content-end align-items-center">
                    <li class="shop_search"><a class="search__active" href="#"></a></li>
                    <notification-component></notification-component>
                    <li class="setting__bar__icon"><a class="setting__active" href="#"></a>
                        <div class="searchbar__content setting__block">
                            <div class="content-inner">
                                <div class="switcher-currency">
                                    <strong class="label switcher-label">
                                        <span>My Account</span>
                                    </strong>
                                    <div class="switcher-options">
                                        <div class="switcher-currency-trigger">
                                            <div class="setting__menu">
                                                @guest()
                                                    <span><a href="{{route('frontend.show.login')}}">Login</a></span>
                                                    <span><a href="{{route('frontend.show.register')}}">Register</a></span>
                                                @else
                                                    <span><a href="{{route('frontend.dashboard')}}">Dashboard</a></span>
                                                    <span><a href=href="{{ route('frontend.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></span>
                                                    <form id="logout-form" action="{{ route('frontend.logout') }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                @endguest
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Start Mobile Menu -->
        <div class="row d-none">
            <div class="col-lg-12 d-none">
                <nav class="mobilemenu__nav">
                    <ul class="meninmenu">
                        <li><a href="{{route('frontend.index')}}">Home</a></li>
                        <li><a href="{{route('post_show','about-us')}}">About Us</a></li>
                        <li><a href="{{route('post_show','our-vision')}}">Our Vision</a></li>

                        <li><a href="javascript:void(0);">Blog</a>
                            <ul>
                                @foreach($global_categories as $category)
                                    <li><a href="{{route('frontend.category.post',$category->slug)}}">{{$category->name}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li><a href="{{route('frontend.contact')}}">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- End Mobile Menu -->
        <div class="mobile-menu d-block d-lg-none">
        </div>
        <!-- Mobile Menu -->
    </div>
</header>
<!-- //Header -->
<!-- Start Search Popup -->
<div class="box-search-content search_active block-bg close__top">

    {!! Form::open(['route'=>'frontend.search','method'=>'get' ,"id"=>"search_mini_form" ,"class"=>"minisearch"]) !!}
    <div class="field__search">
        {!! Form::text('keyword',old('keyword',request()->keyword) ,['placeholder'=>'Search...']) !!}
        <div class="action">
            <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('search_mini_form').submit();"><i class="zmdi zmdi-search"></i></a>
        </div>
    </div>
    {!! Form::close() !!}




            <input type="text" placeholder="Search entire store here...">
            <div class="action">
                <a href="#"><i class="zmdi zmdi-search"></i></a>
            </div>

    <div class="close__wrap">
        <span>close</span>
    </div>
</div>
<!-- End Search Popup -->
<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area bg-image--4">

</div>
<!-- End Bradcaump area -->



