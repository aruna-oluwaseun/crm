<!DOCTYPE html>
<html lang="eng" class="js">

{{ view('admin.templates.header') }}

<body class="nk-body npc-default pg-auth">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap nk-wrap-nosidebar">
            <!-- content @s -->
            <div class="nk-content ">
                <div class="nk-split nk-split-page nk-split-md">
                    <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                        <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                            <a href="#" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
                        </div>
                        <div class="nk-block nk-block-middle nk-auth-body">
                            <div class="brand-logo pb-5">
                                <a href="#" class="logo-link">
                                    <img class="logo-light logo-img logo-img-lg" src="{{ asset('assets/files/img/Jenflow-Logo.png') }}" alt="logo">
                                    <img class="logo-dark logo-img logo-img-lg" src="{{ asset('assets/files/img/Jenflow-Logo.png') }}" alt="logo-dark">
                                </a>
                            </div>
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h5 class="nk-block-title">Sign-In</h5>
                                    <div class="nk-block-des">
                                        <p>Welcome to Jenflow Systems.</p>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head -->
                            {{ view('admin.templates.alert') }}
                            <form action="{{ url('authenticate') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="default-01">Email</label>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" id="email" placeholder="Enter your email address or username">
                                </div><!-- .foem-group -->
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="password">Passcode</label>
                                        <a class="link link-primary link-sm" tabindex="-1" href="#" data-toggle="tooltip" data-placement="top" title="For now speak to to Rich">Forgot Code?</a>
                                    </div>
                                    <div class="form-control-wrap">
                                        <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Enter your password">
                                    </div>
                                </div><!-- .foem-group -->
                                <div class="form-group">
                                    <div class="custom-control custom-control-sm custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="remember-me" name="remember_me" value="1">
                                        <label class="custom-control-label" for="remember-me">Remember me</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                                </div>
                            </form><!-- form -->
                        </div><!-- .nk-block -->
                        <div class="nk-block nk-auth-footer">
                            <div class="mt-3">
                                <p>&copy; {{ date('Y') }} Jenflow. All Rights Reserved.</p>
                            </div>
                        </div><!-- .nk-block -->
                    </div><!-- .nk-split-content -->
                    <div class="nk-split-content nk-split-stretch bg-abstract"></div><!-- .nk-split-content -->
                </div><!-- .nk-split -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- content @e -->
    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->
<!-- JavaScript -->
{{ view('admin.templates.footer') }}

</html>
