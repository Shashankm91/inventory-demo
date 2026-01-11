<style>
    img{
        width:100% !important;
    }
</style>

<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
         <a class="navbar-brand brand-logo" href="{{url('/dashboard')}}"><img src="{{asset('assets/images/logo_latest.png')}}" alt="logo" style="height:100px;  object-fit:contain;"/></a>
          <a class="navbar-brand brand-logo-mini" href="{{url('/dashboard')}}"><img src="{{asset('assets/images/logo_latest.png')}}" alt="logo" style="height:100px;object-fit:contain;" /></a>
          <a class="navbar-brand brand-logo text-primary fw-bold fs-3" href="{{url('/')}}">
            <!-- <span class="brand-highlight">SM</span> Inventory<span class="text-dark">MS</span> -->
          </a>
         <a class="navbar-brand brand-logo-mini text-primary fw-bold fs-4 " href="index.html" id="logo-text">
          <!-- <img src="{{asset('assets/images/logo_latest.jpeg')}}" alt="logo" class="img-fluid logo"/> -->
           RM
        </a>

        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>

          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="{{asset('assets/images/faces/face1.jpg')}}" alt="image">
                  <span class="availability-status online"></span>
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">{{ auth()->user()->name}}</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="{{ route('profile.update') }}">
                  <i class="mdi mdi-profile me-2 text-success"></i>Profile</a>

                <form action="{{route('logout')}}" method="POST">
                  @csrf
                  <button class="dropdown-item" href="{{route('logout')}}" type="submit">
                    <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                  </button>
                </form>
              </div>
            </li>
            <li class="nav-item nav-logout d-none d-lg-block">
              <form action="{{route('logout')}}" method="POST">
                @csrf
                 <button type="submit" class="nav-link" href="{{route('logout')}}">
                   <i class="mdi mdi-power"></i>
                </button>
              </form>

            </li>

          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
