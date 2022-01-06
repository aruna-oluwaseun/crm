<?php

    $role_permissions = $detail->permissions;
    $role_permissions_slugs = [];
    if($role_permissions) {
        foreach ($role_permissions as $role_permission)
        {
            $role_permissions_slugs[] = $role_permission->slug;
        }
    }

?>
<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/editors/summernote.css?ver=2.2.0') }}">
@endpush
{{ view('admin.templates.header') }}

<body class="nk-body npc-default has-apps-sidebar has-sidebar">

<div class="nk-app-root">
    {{ view('admin.templates.sidebar') }}
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            {{ view('admin.templates.topmenu') }}
            <!-- main header @e -->
            {{ view('admin.templates.sidemenus.sidemenu-default') }}

            <!-- content @s -->
            <div class="nk-content ">

                {{ view('admin.templates.alert') }}

                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">

                            <div class="nk-block nk-block-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">Role Detail</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="nk-block">
                                <div class="card card-bordered">
                                    <div class="card-aside-wrap">
                                        <div class="card-inner card-inner-lg">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">Role #{{$detail->title}}</h4>
                                                        <div class="nk-block-des">
                                                            <p>
                                                                Created at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
                                                                <?php if($detail->updated_by) : ?>
                                                                Last updated on <b>{{ date('dS F Y H:ia', strtotime($detail->updated_at)) }}</b>.
                                                                <?php endif; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="post" action="{{ url('roles/'.$detail->id) }}" id="user-detail-form" class=" form-validate">
                                                @csrf
                                                @method('PUT')

                                                <!-- Details -->
                                                <div id="tab-details" class="tab card">

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Role title *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="title" value="{{ old('title', $detail->title) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Slug *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="slug" value="{{ old('slug', $detail->slug) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>

                                                </div><!-- end Details -->

                                                <!-- User Activity -->
                                                <div id="tab-permissions" style="display: none;" class="tab card">

                                                    <div class="nk-block-head">
                                                        <div class="nk-block-between">
                                                            <div class="nk-block-head-content">
                                                                <h4 class="nk-block-title">{{ $detail->title }}</h4>
                                                                <p>Toggle the permissions you want associated with this role. All users with this roles will be defaulted with the below permissions.</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="divider mt-0"></div>

                                                    <?php
                                                        $permissions = permissions();
                                                    ?>
                                                    <?php if($permissions->count()) : ?>
                                                    <p><b>{{ $permissions->count() }}</b> permissions available</p>

                                                        <div class="row">
                                                            <?php foreach($permissions as $permission) : ?>
                                                            <div class="col-md-4">
                                                                <div class="custom-control custom-checkbox mb-3" style="display: block">
                                                                    <input name="permissions[]" type="checkbox" {{ in_array($permission->slug, $role_permissions_slugs) ? 'checked' : '' }} class="custom-control-input" id="{{ $permission->slug }}" value="{{ $permission->id }}">
                                                                    <label class="custom-control-label" for="{{ $permission->slug }}"> {{ $permission->title }}</label>
                                                                </div>
                                                            </div>
                                                            <?php endforeach; ?>

                                                        </div>

                                                        <div class="form-group mt-3">
                                                            <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                        </div>


                                                    <?php else : ?>

                                                    <div class="alert alert-warning">No role or permissions found</div>

                                                    <?php endif; ?>

                                                </div>
                                                <!-- end user activity -->
                                            </form>
                                        </div><!-- .card-inner -->

                                        <!-- Menu -->
                                        <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg toggle-screen-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">

                                            <div class="card-inner-group" data-simplebar="init">
                                                <div class="simplebar-wrapper" style="margin: 0px;">
                                                    <div class="simplebar-height-auto-observer-wrapper">
                                                        <div class="simplebar-height-auto-observer"></div>
                                                    </div>
                                                    <div class="simplebar-mask">
                                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                            <div class="simplebar-content-wrapper" style="height: auto; overflow: hidden;">
                                                                <div class="simplebar-content" style="padding: 0px;">
                                                                    {{--<div class="card-inner">
                                                                        --}}{{--<a href="{{ url('products/create?id='.$detail->id) }}" class="btn btn-round btn-icon btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $detail->title }}"><em class="icon ni ni-copy"></em></a>--}}{{--

                                                                    </div>--}}<!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-card">
                                                                            <div class="user-info">
                                                                                <span class="lead-text">{{ $detail->title }}</span>
                                                                                <span class="sub-text">{{ $detail->slug }}</span>
                                                                            </div>
                                                                            {{--<div class="user-action">
                                                                                <div class="dropdown">
                                                                                    <a class="btn btn-icon btn-trigger mr-n2" data-toggle="dropdown" href="#" aria-expanded="false"><em class="icon ni ni-more-v"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <li><a href="#"><em class="icon ni ni-camera-fill"></em><span>Change Photo</span></a></li>
                                                                                            <!--<li><a href="#"><em class="icon ni ni-edit-fill"></em><span>Update Profile</span></a></li>-->
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>--}}
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-permissions"><em class="icon ni ni-shield-check"></em><span>Role Permissions</span></a></li>
                                                                        </ul>
                                                                    </div><!-- .card-inner -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="simplebar-placeholder" style="width: auto; height: 550px;"></div>
                                                </div>
                                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                                </div>
                                                <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                                    <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                                </div>
                                            </div><!-- .card-inner-group -->

                                        </div><!-- card-aside -->
                                    </div><!-- card-aside-wrap -->
                                </div><!-- .card -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- content @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- main @e -->
</div>


@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
@endpush
{{ view('admin.templates.footer') }}
</body>
<script type="text/javascript">
    $(document).ready(function() {
        // change tab
        $(document).on('click', '.tab-links li a', function(event) {
            event.preventDefault();
            $('.tab-links li a').removeClass('active');
            $('.tab').hide();
            $(this).addClass('active');
            $($(this).attr('href')).show();
            var href = window.location.href.replace(/#.*$/,'') + $(this).attr('href');
            window.history.pushState({href: href}, '', href);
        });

        // Show tab on page load
        if($(location).attr('hash').length)  {
            $('.tab-links li a[href="'+$(location).attr('hash')+'"]').trigger('click');
        }

        // slug
        $(document).on('keyup','[name="title"]', function(event) {
            var title = $(this).val();
            setTimeout(function() {
                var slug = title.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
                $('[name="slug"]').val(slug);
            }, 400);

        });
    });
</script>


</html>
