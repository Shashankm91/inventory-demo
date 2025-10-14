  <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="{{asset('assets/images/faces/face1.jpg')}}" alt="profile" />
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">{{ auth()->user()->name}}</span>
                  <span class="text-secondary text-small">{{auth()->user()->role}}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('products.index') }}">
                <span class="menu-title">Products</span>
                <i class="mdi mdi-hammer-wrench menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('invoices.index') }}" target="_blank">
                <span class="menu-title">Invoices</span>
                <i class="mdi mdi-cash-multiple menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('quotations.index') }}" target="_blank">
                <span class="menu-title">Quotations</span>
                <i class="mdi mdi-clipboard-text-outline menu-icon"></i>
              </a>
            </li>
            @if(auth()->user()->role==='admin')
            <li class="nav-item">
              <a class="nav-link" href="{{ route('suppliers.index') }}" target="_blank">
                <span class="menu-title">Suppliers</span>
                <i class="mdi mdi-truck menu-icon"></i>
              </a>
            </li>
            @endif

          </ul>
        </nav>