<div class="global-search-container">
    <div>
        <input wire:keyup.enter="search" wire:model.defer="query" class="form-control" type="text" placeholder="Search &amp; press enter (orders,customers,products,staff,purchases).">
    </div>
    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-left {{ $show_results ? 'show' : '' }}">
        <div class="dropdown-head">
            <span class="sub-title nk-dropdown-title">{{ $count }} total results showing</span>
            @if($count)
                <a id="mark-notification-read" wire:click="close" href="#">Close</a>
            @endif
        </div>
        <div class="dropdown-body">
            <div  wire:loading.class.remove="hide" class="wire-loader hide">
                <div class='loading text-center mt-3'><img width='50' src='{{ asset('assets/files/img/loading.gif') }}'></div>
            </div>

            <div class="nk-notification">

                <!-- Results -->
                @if($results && !empty($results))

                    @foreach($results as $table => $result)
                        <?php if(!count($result)) { continue; } ?>
                        <div class="dropdown-head">
                            <span class="sub-title nk-dropdown-title"><b>{{ $table }}</b> <span class="text-primary">{{ count($result) }}</span> results found {{ count($result) == 30 ? '(limited to 30 results)' : '' }}</span>
                        </div>
                        @foreach($result as $row => $result)
                        <?php
                            $header = ''; $sub_header = ''; $link = url('#');
                            if($table == 'Products')
                            {
                                $header = $result['title'];
                                $sub_header = $result['code'];
                                $link = url('products/'.$result['id']);
                            }

                            if($table == 'Customers') {
                                $header = $result['first_name'].' '.$result['last_name'];
                                $sub_header = $result['email'];
                                $link = url('customers/'.$result['id']);
                            }

                            if($table == 'Staff') {
                                $header = $result['first_name'].' '.$result['last_name'];
                                $sub_header = $result['email'];
                                $link = url('users/'.$result['id']);
                            }

                            if($table == 'Sales Orders') {
                                $header = '#'.$result['id'].' '.$result['first_name'].' '.$result['last_name'];
                                $sub_header = 'Order created : '.relative_time($result['created_at']).' ago';
                                $link = url('salesorders/'.$result['id']);
                            }

                            if($table == 'Purchases') {
                                $header = '#'.$result['id'].' '.$result['supplier_title'];
                                $sub_header = 'Total order cost £'.number_format($result['gross_cost'],2,'.',',').' | Created : '.format_date($result['created_at']);
                                $link = url('purchases/'.$result['id']);
                            }
                        ?>
                        <a href="{{ $link }}" class="nk-notification-item dropdown-inner">
                            <div class="nk-notification-content">
                                <div class="nk-notification-text"><span class="text-primary">{{ $header }}</span> </div>
                                <div class="nk-notification-time">{{ $sub_header }}</div>
                            </div>
                        </a>
                        @endforeach
                    @endforeach

                @else
                    <div class="alert alert-warning">No results found for <b>{{ $query }}</b></div>
                @endif
                <!-- end results -->

            </div><!-- .nk-notification -->
        </div><!-- .nk-dropdown-body -->

        <!--<div class="dropdown-foot center">
            <a href="#">View All</a>
        </div>-->
    </div>
</div>
