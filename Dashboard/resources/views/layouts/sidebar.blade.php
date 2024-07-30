 <div class="offcanvas offcanvas-start" tabindex="-1" id="navOffcanvas">
     <script>
         const navTarget = 'target';
         $(function() {
             $(`.nav-${navTarget} b`).addClass('text-danger');
         });
     </script>
     <div class="offcanvas-body">
         <ul class="list-group list-group-flush">
             <li class="list-group-item nav-dashboard">

                 <a class="link-dark d-block" href="/">
                     <i class="bi bi-speedometer text-secondary me-2"></i><b>{{ __('Dashboard') }}</b>
                 </a>
             </li>
             @hasrole('admin')
                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/retailers/">
                         <i class="bi bi-person-lines-fill text-secondary me-2"></i><b>{{ __('Retailers') }}</b>
                     </a>
                 </li>

                 {{-- <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/brands/">
                         <i class="bi bi-slack text-secondary me-2"></i><b>{{ __('Brands') }}</b>
                     </a>
                 </li>

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/categories/">
                         <i class="bi bi-bezier text-secondary me-2"></i><b>Categories</b>
                     </a>
                 </li>

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/subcategories/">
                         <i class="bi bi-bezier2 text-secondary me-2"></i><b>Subcategories</b>
                     </a>
                 </li>

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/sizes/">
                         <i class="bi bi-aspect-ratio text-secondary me-2"></i><b>Sizes</b>
                     </a>
                 </li> --}}

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/admins/">
                         <i class="bi bi-people-fill text-secondary me-2"></i><b>{{ __('Users') }}</b>
                     </a>
                 </li>

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/customers/">
                         <i class="bi bi-person-video text-secondary me-2"></i><b>{{ __('Customers') }}</b>
                     </a>
                 </li>

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/settings/">
                         <i class="bi bi-gear text-secondary me-2"></i><b>{{ __('Settings') }}</b>
                     </a>
                 </li>
             @endhasrole

             @hasrole('retailer')
                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/products/">
                         <i class="bi bi-box-seam-fill text-secondary me-2"></i><b>{{ __('Products') }}</b>
                     </a>
                 </li>

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/orders/">
                         <i class="bi bi-cart4 text-secondary me-2"></i><b>{{ __('Orders') }}</b>
                     </a>
                 </li>
             @endhasrole


         </ul>
     </div>
     <div class="d-flex">
         <a href="#" class="d-block p-3 flex-grow-1 border-top rounded-0 link-dark">
             <i class="bi bi-person-circle text-warning me-2"></i>
             <b>{{ auth()->user()->user_name }}</b>
         </a>
         <form action="{{ route('logout') }}" method="post" class="d-block p-2 border-top border-start rounded-0">
             @csrf
             <button type="submit" class="btn border-0"><i class="bi bi-power text-danger"></i></button>
         </form>
     </div>
 </div>
