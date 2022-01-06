<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/js/admin/bundle.js?ver=2.1.0') }}"></script>
<script src="{{ asset('assets/js/admin/scripts.js?ver=2.1.0') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<!--<script src="./assets/js/admin/toastr-alert.js?ver=2.1.0"></script>-->
@livewireScripts
<script type="text/javascript">
    var today = '{{ date('Y-m-d') }}';
    let loader = "<div class='loading text-center mt-3'><img width='100' src='{{ asset('assets/files/img/loading.gif') }}'></div>";
</script>
<script type="text/javascript" src="{{ asset('assets/js/admin/helpers.js') }}"></script>
@stack('scripts')
