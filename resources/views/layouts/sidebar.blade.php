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
                     <i class="bi bi-speedometer text-secondary me-2"></i><b>Dashboard</b>
                 </a>
             </li>
             @hasrole('admin')
                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/retailers/">
                         <i class="bi bi-person-lines-fill text-secondary me-2"></i><b>Retailers</b>
                     </a>
                 </li>

                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/brands/">
                         <i class="bi bi-slack text-secondary me-2"></i><b>Brands</b>
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
                 </li>

                 <li class="list-group-item">
                     <a class="link-dark d-block" data-bs-toggle="collapse" href="#userCollapse" role="button"
                         aria-expanded="false" aria-controls="userCollapse">
                         <i class="bi bi-people-fill text-secondary me-2"></i><b>Users</b>
                     </a>
                     <div class="collapse" id="userCollapse">
                         <ul class="list-group list-group-flush">
                             <li class="list-group-item nav-users">
                                 <a class="link-dark d-block" href="/admins/">
                                     <i class="bi bi-person-lines-fill text-secondary me-2"></i><b>List</b>
                                 </a>
                             </li>

                             <li class="list-group-item nav-premissions">
                                 <a class="link-dark d-block" href="/currencies/">
                                     <i class="bi bi-person-fill-check text-secondary me-2"></i><b>Currencies</b>
                                 </a>
                             </li>

                         </ul>
                     </div>
                     <script>
                         const userCollapse = new bootstrap.Collapse('#userCollapse', {
                             toggle: false
                         });
                         if (['users', 'premissions'].includes(navTarget))
                             userCollapse.show();
                     </script>
                 </li>

                 <li class="list-group-item">
                     <a class="link-dark d-block" data-bs-toggle="collapse" href="#settingCollapse" role="button"
                         aria-expanded="false" aria-controls="settingCollapse">
                         <i class="bi bi-gear text-secondary me-2"></i><b>Settings</b>
                     </a>
                     <div class="collapse" id="settingCollapse">
                         <ul class="list-group list-group-flush">
                             <li class="list-group-item nav-support">
                                 <a class="link-dark d-block" href="/locations/">
                                     <i class="bi bi-globe-asia-australia text-secondary me-2"></i><b>Locations</b>
                                 </a>
                             </li>

                             <li class="list-group-item nav-support">
                                 <a class="link-dark d-block" href="/currencies/">
                                     <i class="bi bi-currency-exchange text-secondary me-2"></i><b>Currencies</b>
                                 </a>
                             </li>

                             <li class="list-group-item nav-subsc">
                                 <a class="link-dark d-block" href="/categories/">
                                     <i class="bi bi-grid text-secondary me-2"></i><b>Categories</b>
                                 </a>
                             </li>

                             <li class="list-group-item nav-trans">
                                 <a class="link-dark d-block" href="/seasons/">
                                     <i class="bi bi-brilliance text-secondary me-2"></i><b>Seasons</b>
                                 </a>
                             </li>

                             <li class="list-group-item nav-refunds">
                                 <a class="link-dark d-block" href="/sizes/">
                                     <i class="bi bi-arrows-fullscreen text-secondary me-2"></i><b>Sizes</b>
                                 </a>
                             </li>
                         </ul>
                     </div>
                     <script>
                         const settingCollapse = new bootstrap.Collapse('#settingCollapse', {
                             toggle: false
                         });
                         if (['subsc', 'trans', 'refunds', 'promos'].includes(navTarget))
                             settingCollapse.show();
                     </script>
                 </li>
             @endhasrole

             @hasrole('retailer')
                 <li class="list-group-item nav-support">
                     <a class="link-dark d-block" href="/products/">
                         <i class="bi bi-box-seam-fill text-secondary me-2"></i><b>Products</b>
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
