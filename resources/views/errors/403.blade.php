<!DOCTYPE html>
<html lang="eng" class="js">

<head>
    <base href="/">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- Page Title  -->
    <title>Error 404 | Page not found</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin.css')  }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/skins/theme-orange.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/admin/custom.css') }}">
</head>

<body class="nk-body npc-default pg-error">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap nk-wrap-nosidebar">
            <!-- content @s -->
            <div class="nk-content ">
                <div class="nk-block nk-block-middle wide-md mx-auto">
                    <div class="nk-block-content nk-error-ld text-center">
                        <img class="nk-error-gfx" src="{{ asset('assets/files/img/errors/error-403.svg') }}" alt="">
                        <div class="wide-xs mx-auto">
                            <h3 class="nk-error-title">Permission Denied</h3>
                            <p class="nk-error-text">You do not have permission to access this resource.</p>
                            <a href="{{ url('/') }}" class="btn btn-lg btn-primary mt-2">Back To Home</a>
                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- content @e -->
    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->
<!-- JavaScript -->
<script src="{{ asset('assets/js/admin/bundle.js?ver=2.1.0') }}"></script>
<script src="{{ asset('assets/js/admin/scripts.js?ver=2.1.0') }}"></script>
</body>
</html>
