
<!DOCTYPE html>
<html lang="en">
  <head>
    @include('partials.head')
  </head>
  <body>
    <div class="container-scroller">
      
      <!-- partial:partials/_navbar.html -->
      @include('partials.navbar')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
      @include('partials.sidebar')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            @yield('content')
            
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          @include('partials.footer')
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
      @yield('scripts') 
    <!-- plugins:js -->
   @include('partials.scripts')

   
  
  </body>
</html>