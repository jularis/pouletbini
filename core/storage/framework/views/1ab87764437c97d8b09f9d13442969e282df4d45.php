<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($general->siteName($pageTitle ?? '')); ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo e(getImage(getFilePath('logoIcon') . '/favicon.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/global/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/viseradmin/css/vendor/bootstrap-toggle.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/global/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/global/css/line-awesome.min.css')); ?>">

    <?php echo $__env->yieldPushContent('style-lib'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/viseradmin/css/vendor/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/viseradmin/css/app.css')); ?>"> 
    <style>
    hr {
    margin-top: 30px;
    margin-bottom: 30px; 
}
.error {
    color: red;
    font-weight: normal;
}
option:disabled {
    color: rgb(251 136 100) !important;
}
span.input-group-text.categorie {
    font-size: 12px;
}
</style>
    <?php echo $__env->yieldPushContent('style'); ?>
</head>

<body>
    <?php echo $__env->yieldContent('content'); ?>

    <script src="<?php echo e(asset('assets/global/js/jquery-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/bootstrap-toggle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/jquery.slimscroll.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/jquery.validate.js')); ?>"></script>
<script src="<?php echo e(asset('assets/global/js/messages_fr.js')); ?>"></script>
    <?php echo $__env->make('partials.notify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldPushContent('script-lib'); ?>
    <script src="<?php echo e(asset('assets/viseradmin/js/nicEdit.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/viseradmin/js/printThis.js')); ?>"></script>

    <script src="<?php echo e(asset('assets/viseradmin/js/vendor/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/viseradmin/js/app.js')); ?>"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_VVwtAhchqsINCTqin22MG1AzMn7d6gk"></script> 


    
    <script>
        "use strict";
        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });
        (function($) {
            $(document).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain', function() {
                $('.nicEdit-main').focus();
            });
        })(jQuery);

        $(document).ready(function() {
$("#flocal").validate();
});

function geoFindMe() {
  const status = document.querySelector("#status");
  function success(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

$('input[name=longitude]').val(longitude);
$('input[name=latitude]').val(latitude);
$("input[name=longitude], input[name=latitude]").attr({"readonly": 'readonly'})
  }
  function error() {
    status.textContent = "Unable to retrieve your location";
  }
  if (!navigator.geolocation) {
    status.textContent = "Geolocation is not supported by your browser";
  } else {
    // status.textContent = "Locating…";
    navigator.geolocation.getCurrentPosition(success, error);
  }
}
 
document.querySelector("#find-me").addEventListener("click", geoFindMe); 
    </script>

    <?php echo $__env->yieldPushContent('script'); ?>


</body>

</html>
<?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/layouts/master.blade.php ENDPATH**/ ?>