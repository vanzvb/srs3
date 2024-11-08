<nav class="sidebar">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="{{ asset('images/bflogo.png') }}" alt="">
            </span>

            <div class="text logo-text">
                <span class="name">{{ auth()->user()->name  }}</span>
                {{-- <span class="profession">{{ auth()->user()->role->name  }}</span> --}}
            </div>
        </div>

        <i class='bx bx-chevron-right toggle'></i>
    </header>

    <div class="menu-bar">
        <div class="menu">
            {{-- <li class="search-box">
                <i class='bx bx-search icon'></i>
                <input type="text" placeholder="Search...">
            </li> --}}

            <ul class="menu-links">
                @if(auth()->user()->role_id != 7)
                    <li class="nav-link">
                        <a href="{{ route('dashboard') }}">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role_id == 7 || auth()->user()->can('approve_hoa'))
                <li class="nav-link">
                    <a href="{{ route('hoa-approvers.index') }}">
                        <i class='bx bxs-user-check icon'></i>
                        <span class="text nav-text">HOA President</span>
                    </a>
                </li>
                @endif

                   {{-- @if (Auth::user()->can('access', \App\Models\SrsCalendarBlocker::class) || Auth::user()->can('access', \App\Models\SrsPrice::class) || Auth::user()->email == 'neilalegre@bffhai.com' || Auth::user()->email == 'miguel.lacupanto@bffhai.com' || Auth::user()->email == 'floyd.tabuzo@bffhai.com'
                   || auth()->user()->can('access_sticker_price')) --}}
                    <div>
                        <li class="nav-link">
                            <a href="#" class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#spc-collapse">
                                <i class='bx bx-user-circle icon'></i>
                                <span class="text nav-text" style="font-size: 15px;">SPC 2.0 <i class="bx bx-chevron-right toggle"></i></span>
                            </a>
                        </li>
                        <div class="collapse collapse-menu" id="spc-collapse">
                            <ul>
                                @can('access_sticker_price')
                                    <li class="nav-link">
                                        <a href="{{ route('scp-V2') }}">
                                            <i class='bx bx-purchase-tag-alt icon'></i>
                                            <span class="text nav-text" style="font-size: 15px;">STICKER PRICE</span>
                                        </a>
                                    </li>
                                @endcan

                              @if(auth()->user()->role_id == '4' || auth()->user()->role_id == '3' || auth()->user()->email == 'miguel.lacupanto@bffhai.com' || auth()->user()->email == 'floyd.tabuzo@bffhai.com' || auth()->user()->email == 'tirso.sulanguit@bffhai.com' || auth()->user()->email == 'chazt.tanyag@bffhai.com' || auth()->user()->email == 'lawenko.max@bffhai.com' || auth()->user()->email == 'jhun.paculan@bffhai.com')
                                <li class="nav-link">
                                    {{-- <a href="{{ route('spc.report') }}">
                                        <i class='bx bxs-report icon'></i>
                                        <span class="text nav-text">REPORTS</span>
                                    </a> --}}
                                </li>

                                <li class="nav-link">
                                    {{-- <a href="{{ route('spc-to-gl.index') }}">
                                        <i class='bx bxs-report icon'></i>
                                        <span class="text nav-text">SPC 2.0 TO GL</span>
                                    </a> --}}
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    
                {{-- @endif --}}

                {{-- @if(Auth::user()->email == "itqa@atomitsoln.com" || Auth::user()->email == "srsadmin@atomitsoln.com" || Auth::user()->email == "lito.tampis@atomitsoln.com" || Auth::user()->email = "tirso.sulanguit@bffhai.com" || Auth::user()->email = "miguel.lacupanto@bffhai.com" || Auth::user()->email = "lawenko.max@bffhai.com" || Auth::user()->email = "jhun.paculan@bffhai.com" || Auth::user()->email = "chazt.tanyag@bffhai.com" || Auth::user()->email = "mara.mara@bffhai.com" ) --}}

                @if (auth()->user()->email == 'itqa@atomitsoln.com'
                    || auth()->user()->email == 'srsadmin@atomitsoln.com'
                    || auth()->user()->email == 'lito.tampis@atomitsoln.com'
                    || auth()->user()->email == 'tirso.sulanguit@bffhai.com'
                    || auth()->user()->email == 'miguel.lacupanto@bffhai.com'
                    || auth()->user()->email == 'lawenko.max@bffhai.com'
                    || auth()->user()->email == 'jhun.paculan@bffhai.com'
                    || auth()->user()->email == 'chazt.tanyag@bffhai.com'
                    || auth()->user()->email == 'mara.mara@bffhai.com'
                    || auth()->user()->can('access_sims_receiving')
                    || auth()->user()->can('access_sims_issuance')
                    || auth()->user()->can('access_sims_inventory')
                    || auth()->user()->can('access_sims_reports')
                    || auth()->user()->can('access_sims_item_master')
                    || auth()->user()->can('access_sims_transfer')
                    || auth()->user()->can('access_sims_srs_to_gl')
                    || auth()->user()->can('access_sims_manual_release_reject'))
                	<div>
		                <li class="nav-link">
		                    <a href="#" class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#sims-collapse">
		                        <i class='bx bx-cabinet icon'></i>   
		                        <span class="text nav-text" style="font-size: 15px;">SIMS <i
		                                class="bx bx-chevron-right toggle"></i></span>
		                    </a>
		                </li>

                        @php
                            $prefixes = [
                                '/itemmaster',
                                '/receiving',
                                '/issuance',
                                '/inventory',
                                '/sims-report',
                                '/transfer',
                                '/srs_to_gl',
                                '/release',
                                '/misc'
                            ];
                            
                            $prefix = request()->route()->getPrefix();
                            
                            // check if current route has the prefix
                            $hasPrefix = in_array($prefix, $prefixes);
                        @endphp
                        
		                <div class="collapse-menu @if(!$hasPrefix) collapse @endif" id="sims-collapse">
		                    <ul>

                                @can('access_sims_item_master')
    		                    	<li class="nav-link">
    		                            <a href="{{ route('itemmaster.index') }}">
    		                                <i class='bx bx-package icon' ></i>
    		                                <span class="text nav-text">Item Master</span>
    		                            </a>
    		                        </li>
                                @endcan
                                
                                @can('access_sims_receiving')
    		                        <li class="nav-link">
    		                            <a href="{{ route('receiving.index') }}">
    		                                <i class='bx bxs-plus-square icon'></i>
    		                                <span class="text nav-text">Receiving</span>
    		                            </a>
    		                        </li>
                                @endcan

                                @can('access_sims_issuance')
                                    <li class="nav-link">
                                        <a href="{{route('issuance.index')}}">
                                            <i class='bx bxs-minus-square icon'></i>
                                            <span class="text nav-text">Issuance</span>
                                        </a>
                                    </li>
                                @endcan

                               
                                @can('access_sims_transfer')
                                    <li class="nav-link">
                                        <a href="{{route('transfer.index') }}">
                                            <i class='bx bx-transfer icon'></i>
                                            <span class="text nav-text">Transfer</span>
                                        </a>
                                    </li>
                                @endcan

                                    {{-- <li class="nav-link">
                                        <a href="{{ route('release.index') }}">
                                            <i class='bx bx-export icon'></i>
                                            <span class="text nav-text">Release/Reject</span>
                                        </a>
                                    </li> --}}

                                @can('access_sims_manual_release_reject')
                                    <li class="nav-link">
                                        <a href="{{ route('misc.index') }}">
                                            <i class='bx bxs-grid icon'></i>
                                            <span class="text nav-text">Manual Release <br>/ Reject</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('access_sims_srs_to_gl')
    		                        <li class="nav-link">
    		                        	<a href="{{ route('sims.srs_to_gl') }}">
    		                        		<i class='bx bxs-report icon'></i>
    		                        		<span class="text nav-text">SRS TO GL</span>
    		                        	</a>
    		                        </li>
                                @endif
                                
                                @can('access_sims_inventory')
                                    <li class="nav-link">
                                        <a href="{{ route('inventory.index') }}">
                                            <i class='bx bxs-component icon'></i>
                                            <span class="text nav-text">Inventory</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('access_sims_reports')
                                    <li class="nav-link">
                                        <a href="{{ route('sims.reports') }}">
                                            <i class='bx bxs-report icon'></i>
                                            <span class="text nav-text">Reports</span>
                                        </a>
                                    </li>
                                @endcan
		                    </ul>
		                </div>
		            </div>
                @endif


                @can('generateCashierReport', \App\Models\SrsUser::class)
                    <li class="nav-link">
                        <a href="{{ route('cashier.report') }}">
                            <i class='bx bx-group icon'></i>
                            <span class="text nav-text">Cashier Reports</span>
                        </a>
                    </li>
                @endcan

                {{--

                <li class="nav-link">
                    <a href="#">
                        <i class='bx bx-calendar-alt icon'></i>
                        <span class="text nav-text">Sticker Appoinment</span>
                    </a>
                </li>

            

                 --}}

                {{-- @can('access', \App\Models\SrsRequest::class) --}}
                    <li class="nav-link">
                        <a href="{{ route('requests') }}">
                            <i class='bx bx-clipboard icon'></i>
                            <span class="text nav-text">SRS Inbox</span>
                        </a>
                    </li>
                {{-- @endcan --}}

                @can('access', \App\Models\CrmMain::class)
                    <li class="nav-link">
                        <a href="/crm">
                            <i class='bx bxs-user-account icon'></i>
                            <span class="text nav-text">CRMx</span>
                        </a>
                    </li>
                @endcan

                @if(
                    auth()->user()->email == "itqa@atomitsoln.com" ||
                    auth()->user()->email == "lito.tampis@atomitsoln.com" ||
                    auth()->user()->email == "srsadmin@atomitsoln.com" || 
                    auth()->user()->email == "ronald.garcia@atomitsoln.com"  // RG - DevOps
                )
                    <li class="nav-link">
                        <a href="/crmxi">
                            <i class="bx bx-user icon"></i>
                            <span class="text nav-text">CRMXi</span>
                        </a>
                    </li>
                @endif

                {{-- @if(
                    auth()->user()->email == "itqa@atomitsoln.com" ||
                    auth()->user()->email == "lito.tampis@atomitsoln.com" ||
                    auth()->user()->email == "srsadmin@atomitsoln.com"
                )
                    <li class="nav-link">
                        <a href="/crm">
                            <i class="bx bx-user icon"></i>
                            <span class="text nav-text">CRMx</span>
                        </a>
                    </li>
                @endif --}}

                @can('access', \App\Models\SrsAppointment::class)
                <li class="nav-link">
                    <a href="{{ route('appointments') }}">
                        <i class='bx bx-calendar-alt icon'></i>
                        <span class="text nav-text" style="font-size: 15px;">Appointments</span>
                    </a>
                </li>
                @endcan

                {{-- @if (auth()->user()->role_id == 1) --}}
                @if (Auth::user()->can('access', \App\Models\SrsCalendarBlocker::class) || Auth::user()->can('access', \App\Models\SrsPrice::class))
                    <div>
                        <li class="nav-link">
                            <a href="#" class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#admin-collapse">
                                <i class='bx bx-user-circle icon'></i>
                                <span class="text nav-text" style="font-size: 15px;">Admin <i class="bx bx-chevron-right toggle"></i></span>
                            </a>
                        </li>
                        <div class="collapse collapse-menu" id="admin-collapse">
                            <ul>
                                @can('access', \App\Models\SrsCalendarBlocker::class)
                                    <li class="nav-link">
                                        <a href="{{ route('calendar-blocker') }}">
                                            <i class='bx bx-calendar-x icon'></i>
                                            <span class="text nav-text" style="font-size: 15px;">SRS Blocker</span>
                                        </a>
                                    </li>
                                @endcan
                                
                                @can('access', \App\Models\SrsPrice::class)
                                    <li class="nav-link">
                                        <a href="{{ route('sticker-pricing') }}">
                                            <i class='bx bx-purchase-tag-alt icon'></i>
                                            <span class="text nav-text" style="font-size: 15px;">Sticker Pricing</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('access', \App\Models\SPCSubCat::class)
                                <li class="nav-link">
                                    <a href="/sub-categories">
                                        <i class='bx bx-category icon'></i>
                                        <span class="text nav-text">Sub-Categories</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>

                    
                @endif

                @can('generateReport', \App\Models\SrsRequest::class)
                    <li class="nav-link">
                        <a href="{{ route('requests.report') }}">
                            <i class='bx bxs-report icon'></i>
                            <span class="text nav-text">MGMT Reports</span>
                        </a>
                    </li>
                @endcan

                @if(auth()->user()->email == 'miguel.lacopanto@bffhai.com')
                    <li class="nav-link">
                        <a href="{{ route('hoas') }}">
                            <i class='bx bx-home-circle icon'></i>
                            <span class="text nav-text">HOA</span>
                        </a>
                    </li>
                @endif

                @if(
                    auth()->user()->can('accessSuperAdmin', \App\Models\SrsUser::class) ||
                    auth()->user()->email == "miguel.lacupanto@bffhai.com"
                )
                    <div>
                        <li class="nav-link">
                            <a href="#" class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#super_admin-collapse">
                                <i class='bx bx-user-plus icon'></i>
                                <span class="text nav-text" style="font-size: 15px;">Super Admin <i class="bx bx-chevron-right toggle"></i></span>
                            </a>
                        </li>
                        <div class="collapse collapse-menu" id="super_admin-collapse">
                            <ul>
                            	
                            	@can('create', \App\Models\SrsUser::class)
                                <li class="nav-link">
                                    <a href="/admin/users">
                                        <i class='bx bxs-user-detail icon'></i>
                                        <span class="text nav-text">Users</span>
                                    </a>
                                </li>
                                @endcan

                                @can('access', \App\Models\SPCSubCat::class)
                                <li class="nav-link">
                                    <a href="/sub-categories">
                                        <i class='bx bx-category icon'></i>
                                        <span class="text nav-text">Sub-Categories</span>
                                    </a>
                                </li>
                                @endcan

                            	@if(auth()->user()->email != 'itqa@atomitsoln.com')
                                @if(
                                    auth()->user()->can('viewAny', \App\Models\SrsHoa::class) || 
                                    auth()->user()->email == "miguel.lacupanto@bffhai.com"
                                )
                                    <li class="nav-link">
                                        <a href="{{ route('hoas') }}">
                                            <i class='bx bx-home-circle icon'></i>
                                            <span class="text nav-text">HOA</span>
                                        </a>
                                    </li>
                                @endif

                                <li class="nav-link">
                                    <a href="{{ route('requests.ITReport') }}">
                                        <i class='bx bxs-report icon'></i>
                                        <span class="text nav-text" style="font-size: 15px;">Reports</span>
                                    </a>
                                </li>

                                <li class="nav-link">
                                    <a href="{{ route('biz_analytics') }}">
                                        <i class='bx bx-line-chart icon'></i>
                                        <span class="text nav-text" style="font-size: 15px;">Biz Analytics</span>
                                    </a>
                                </li>

                                <li class="nav-link">
                                    <a href="{{ route('appt_config') }}">
                                        <i class='bx bx-calendar-edit icon'></i>
                                        <span class="text nav-text" style="font-size: 13px;">Appointment Config</span>
                                    </a>
                                </li>
                                @endif

                            <li class="nav-link">
                                <a href="{{ route('scp-V2') }}">
                                    <i class='bx bx-purchase-tag-alt icon'></i>
                                    <span class="text nav-text" style="font-size: 15px;">SPC v2.0</span>
                                </a>
                            </li>

                           @if(auth()->user()->role_id == '4' || auth()->user()->role_id == '3' || auth()->user()->email == 'miguel.lacupanto@bffhai.com' || auth()->user()->email == 'floyd.tabuzo@bffhai.com' || auth()->user()->email == 'tirso.sulanguit@bffhai.com' || auth()->user()->email == 'chazt.tanyag@bffhai.com' || auth()->user()->email == 'lawenko.max@bffhai.com' || auth()->user()->email == 'jhun.paculan@bffhai.com')
                           <li>
                                    <a href="{{ route('spc.report') }}">
                                        <i class='bx bxs-report icon'></i>
                                        <span class="text nav-text">SPC v2.0 Reports</span>
                                    </a>
                                </li>

                                @endif

                            

                            </ul>
                        </div>
                    </div>
                @endif

                @can('access', \App\Models\cp\CpPermission::class)
                    <li class="nav-link">
                        <a href="{{ route('control_panel') }}">
                            <i class='bx bxs-hand icon'></i>
                            <span class="text nav-text" style="font-size: 15px;">Control Panel</span>
                        </a>
                    </li>
                @endcan

                @if(
                    auth()->user()->email == 'jhun.paculan@bffhai.com' ||
                    auth()->user()->email == 'lawenko.max@bffhai.com' ||
                    auth()->user()->email == 'miguel.lacupanto@bffhai.com' ||
                    auth()->user()->email == "itqa@atomitsoln.com" ||
                    auth()->user()->email == "lito.tampis@atomitsoln.com" ||
                    auth()->user()->email == "srsadmin@atomitsoln.com"
                )
                    <li class="nav-link">
                        {{-- <a href="{{ route('srs-blacklists.index') }}">
                            <i class="bx bx-block icon"></i>
                            <span class="text nav-text">SRS Blacklists</span>
                        </a> --}}
                    </li>
                @endif


               

                
                {{-- @can('create', \App\Models\SrsUser::class)
                    <li class="nav-link">
                        <a href="{{ route('register') }}">
                <i class='bx bx-user-plus icon'></i>
                <span class="text nav-text">Register User</span>
                </a>
                </li>
                @endcan --}}

            </ul>
        </div>

        <div class="bottom-content">
            <li class="">
                {{-- <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out icon'></i>
                    <span class="text nav-text">Logout</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form> --}}

            </li>

            <li class="mode">
                <div class="sun-moon">
                    <i class='bx bx-moon icon moon'></i>
                    <i class='bx bx-sun icon sun'></i>
                </div>
                <span class="mode-text text">Dark mode</span>

                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>

        </div>
    </div>
</nav>