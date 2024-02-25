<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{getImage(getFilePath('logoIcon') .'/favicon.png')}}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/viseradmin/css/vendor/bootstrap-toggle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">
    <link href="{{ asset('assets/global/froiden-helper/helper.css') }}" rel="stylesheet">
    @stack('style-lib')

    <link rel="stylesheet" href="{{asset('assets/viseradmin/css/vendor/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/viseradmin/css/app.css')}}"> 
<style type="text/css">
    .error{
        color: #ff0000;
        font-size: 10px;
    }
</style>
    @stack('style')
</head>
<body>
@yield('content')

<script src="{{asset('assets/global/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/global/js/jquery.validate.js')}}"></script> 
<script src="{{asset('assets/global/js/messages_fr.js')}}"></script> 
<script src="{{asset('assets/global/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/viseradmin/js/vendor/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset('assets/viseradmin/js/vendor/jquery.slimscroll.min.js')}}"></script>


@include('partials.notify')
@stack('script-lib')

<script src="{{ asset('assets/viseradmin/js/nicEdit.js') }}"></script>
<script src="{{ asset('assets/viseradmin/js/printThis.js') }}"></script>
<script src="{{ asset('assets/viseradmin/js/jquery.chained.js') }}"></script>
<script src="{{asset('assets/viseradmin/js/vendor/select2.min.js')}}"></script>
<script src="{{asset('assets/viseradmin/js/app.js')}}"></script>
<script src="{{ asset('assets/global/froiden-helper/helper.js') }}"></script>
{{-- LOAD NIC EDIT --}}
@include('admin.blocs.modals')
<script>
    "use strict";
    document.loading = 'loading';
        const MODAL_DEFAULT = '#myModalDefault';
        const MODAL_LG = '#myModal';
        const MODAL_XL = '#myModalXl';
        const MODAL_HEADING = '#modelHeading';
        const RIGHT_MODAL = '#task-detail-1';
        const RIGHT_MODAL_CONTENT = '#right-modal-content';
        const RIGHT_MODAL_TITLE = '#right-modal-title';

    bkLib.onDomLoaded(function() {
        $( ".nicEdit" ).each(function( index ) {
            $(this).attr("id","nicEditor"+index);
            new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
        });
    });
    (function($){
        $( document ).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain',function(){
            $('.nicEdit-main').focus();
        });
    })(jQuery);
</script>
<script>
$("#flocal").validate();
</script>
@stack('script')


</body>
</html>
