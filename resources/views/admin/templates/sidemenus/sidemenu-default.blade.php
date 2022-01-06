<div class="nk-sidebar" data-content="sidebarMenu">
    <div class="nk-sidebar-inner" data-simplebar>
        <ul class="nk-menu nk-menu-md">

            {{-- Dashboards--}}
            <?php if(!get_user()->hasRole('warehouse','warehouse-supervisor')) : ?>
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">Dashboards</h6>
            </li><!-- .nk-menu-heading -->
            <li class="nk-menu-item">
                <a href="{{ url('dashboard') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                    <span class="nk-menu-text">Overview Dashboard</span>
                </a>
            </li><!-- .nk-menu-item -->
            <li class="nk-menu-item">
                <a href="{{ url('salesdashboard') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-cart"></em></span>
                    <span class="nk-menu-text">Sales Dashboard</span>
                </a>
            </li><!-- .nk-menu-item -->
            <li class="nk-menu-item">
                <a href="{{ url('productiondashboard') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-meter"></em></span>
                    <span class="nk-menu-text">Production Dashboard</span>
                </a>
            </li><!-- .nk-menu-item -->
            <?php endif; ?>

            {{-- Manage --}}
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">Manage</h6>
            </li><!-- .nk-menu-heading -->
            @canany(['view-products','create-categories','view-product-types','view-product-attributes'])
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-layers"></em></span>
                    <span class="nk-menu-text">Products</span>
                </a>
                <ul class="nk-menu-sub">
                    @can('view-products')
                    <li class="nk-menu-item">
                        <a href="{{ url('products') }}" class="nk-menu-link"><span class="nk-menu-text">View products</span><span class="nk-menu-badge badge-warning">Shows Online</span></a>
                    </li>
                    @endcan
                    @can('view-categories')
                    <li class="nk-menu-item">
                        <a href="{{ url('categories') }}" class="nk-menu-link"><span class="nk-menu-text">Categories</span><span class="nk-menu-badge badge-warning">Shows Online</span></a>
                    </li>
                    @endcan
                    @can('view-product-types')
                    <li class="nk-menu-item">
                        <a href="{{ url('product-types') }}" class="nk-menu-link"><span class="nk-menu-text">Types</span></a>
                    </li>
                    @endcan
                    @can('view-product-attributes')
                    <li class="nk-menu-item">
                        <a href="{{ url('attributes') }}" class="nk-menu-link"><span class="nk-menu-text">Attributes</span><span class="nk-menu-badge badge-warning">Shows Online</span></a>
                    </li>
                    @endcan
                </ul><!-- .nk-menu-sub -->
            </li><!-- .nk-menu-item -->
            @endcanany
            @can('view-customers')
            <li class="nk-menu-item">
                <a href="{{ url('customers') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                    <span class="nk-menu-text">Customers</span>
                </a>
            </li><!-- .nk-menu-item -->
            @endcan

            @can('view-production-orders')
            <li class="nk-menu-item">
                <a href="{{ url('productionorders') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-meter"></em></span>
                    <span class="nk-menu-text">Production Orders</span>
                </a>
            </li><!-- .nk-menu-item -->
            @endcan

            @can('view-sale-orders')
            <li class="nk-menu-item">
                <a href="{{ url('salesorders') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-cart"></em></span>
                    <span class="nk-menu-text">Sales Orders</span>
                </a>
            </li><!-- .nk-menu-item -->
            @endcan

            @can('view-training-dates')
            <li class="nk-menu-item">
                <a href="{{ url('training-dates') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-calendar-booking"></em></span>
                    <span class="nk-menu-text">Training Dates</span>
                </a>
            </li><!-- .nk-menu-item -->
            @endcan

            {{-- Finance --}}
            @canany(['view-invoices','view-purchase-orders','view-expenses','view-refunds','view-vat'])
                <li class="nk-menu-heading">
                    <h6 class="overline-title text-primary-alt">Finance</h6>
                </li><!-- .nk-menu-heading -->

                @can('view-invoices')
                <li class="nk-menu-item">
                    <a href="{{ url('invoices') }}" class="nk-menu-link">
                        <span class="nk-menu-icon"><em class="icon ni ni-cc-alt"></em></span>
                        <span class="nk-menu-text">Invoices</span>
                    </a>
                </li><!-- .nk-menu-item -->
                @endcan

                @can('view-purchase-orders')
                <li class="nk-menu-item">
                    <a href="{{ url('purchases') }}" class="nk-menu-link">
                        <span class="nk-menu-icon"><em class="icon ni ni-wallet-in"></em></span>
                        <span class="nk-menu-text">Purchase Orders</span>
                    </a>
                </li><!-- .nk-menu-item -->
                @endcan

                @can('view-expenses')
                <li class="nk-menu-item">
                    <a href="/expenses" class="nk-menu-link">
                        <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                        <span class="nk-menu-text">Expenses</span>
                    </a>
                </li><!-- .nk-menu-item -->
                @endcan

                @can('view-refunds')
                <li class="nk-menu-item">
                    <a href="{{ url('refunds') }}" class="nk-menu-link">
                        <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                        <span class="nk-menu-text">Refunds</span>
                    </a>
                </li><!-- .nk-menu-item -->
                @endcan

                @can('view-vat')
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                        <span class="nk-menu-icon"><em class="icon ni ni-coins"></em></span>
                        <span class="nk-menu-text">VAT</span>
                    </a>
                    <ul class="nk-menu-sub">
                        <li class="nk-menu-item">
                            <a href="{{ url('vat') }}" class="nk-menu-link"><span class="nk-menu-text">Reporting Tools</span></a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ url('vat/settings') }}" class="nk-menu-link"><span class="nk-menu-text">Access Settings</span></a>
                        </li>
                    </ul><!-- .nk-menu-sub -->
                </li><!-- .nk-menu-item -->
                @endcan
            @endcanany

            {{-- General--}}

            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">General</h6>
            </li><!-- .nk-menu-heading -->
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle">
                    <span class="nk-menu-icon"><em class="icon ni ni-user-list"></em></span>
                    <span class="nk-menu-text">Staff</span>
                </a>
                <ul class="nk-menu-sub">
                    @can('view-users')
                    <li class="nk-menu-item">
                        <a href="{{ url('users') }}" class="nk-menu-link"><span class="nk-menu-text">Staff List</span></a>
                    </li>
                    @endcan

                    <li class="nk-menu-item">
                        <a href="{{ url('calendar') }}" class="nk-menu-link"><span class="nk-menu-text">Calendar</span></a>
                    </li>

                    @can('create-roles')
                    <li class="nk-menu-item">
                        <a href="{{ url('roles') }}" class="nk-menu-link"><span class="nk-menu-text">Roles</span></a>
                    </li>
                    @endcan
                </ul><!-- .nk-menu-sub -->
            </li><!-- .nk-menu-item -->
            @canany(['view-shipping-options','view-couriers','view-suppliers'])
                <li class="nk-menu-item has-sub">
                    <a href="#" class="nk-menu-link nk-menu-toggle">
                        <span class="nk-menu-icon"><em class="icon ni ni-package"></em></span>
                        <span class="nk-menu-text">Shipping</span>
                    </a>
                    <ul class="nk-menu-sub">
                        @can('view-shipping-options')
                        <li class="nk-menu-item">
                            <a href="#" class="nk-menu-link"><span class="nk-menu-text">Options</span></a>
                        </li>
                        @endcan
                        @can('view-couriers')
                        <li class="nk-menu-item">
                            <a href="#" class="nk-menu-link"><span class="nk-menu-text">Couriers</span></a>
                        </li>
                        @endcan
                    </ul><!-- .nk-menu-sub -->
                </li><!-- .nk-menu-item -->
                @can('view-suppliers')
                <li class="nk-menu-item">
                    <a href="{{ url('suppliers') }}" class="nk-menu-link">
                        <span class="nk-menu-icon"><em class="icon ni ni-card-view"></em></span>
                        <span class="nk-menu-text">Suppliers</span>
                    </a>
                </li><!-- .nk-menu-item -->
                @endcan
            @endcanany
        </ul><!-- .nk-menu -->
    </div>
</div>
