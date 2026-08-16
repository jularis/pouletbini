<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <form action="" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <ul class="list-group">

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Email Notification'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you enable this module, the system will send emails to users where needed. Otherwise, no email will be sent.'); ?> <code><?php echo app('translator')->get('So be sure before disabling this module that, the system doesn\'t need to send any emails.'); ?></code></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get("Activer"); ?>" data-off="<?php echo app('translator')->get("Désactiver"); ?>" name="en"
                                        <?php if($general->en): ?> checked <?php endif; ?>>
                                </div>
                            </li>
                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('SMS Notification'); ?></p>
                                    <p class="mb-0">
                                        <small><?php echo app('translator')->get('If you enable this module, the system will send SMS to users where needed. Otherwise, no SMS will be sent.'); ?> <code><?php echo app('translator')->get('So be sure before disabling this module that, the system doesn\'t need to send any SMS.'); ?></code></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get("Activer"); ?>" data-off="<?php echo app('translator')->get("Désactiver"); ?>" name="sn"
                                        <?php if($general->sn): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                            <li
                                class="list-group-item d-flex flex-wrap flex-sm-nowrap gap-2 justify-content-between align-items-center">
                                <div>
                                    <p class="fw-bold mb-0"><?php echo app('translator')->get('Language Enable'); ?></p>
                                    <p class="mb-0">
                                       <small><?php echo app('translator')->get('If you disable this module, that means no one can change the language'); ?></small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-height="35"
                                        data-on="<?php echo app('translator')->get("Activer"); ?>" data-off="<?php echo app('translator')->get("Désactiver"); ?>" name="ln"
                                        <?php if($general->ln): ?> checked <?php endif; ?>>
                                </div>
                            </li>

                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get("Envoyer"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .toggle.btn-lg {
            height: 37px !important;
            min-height: 37px !important;
        }

        .toggle-handle {
            width: 25px !important;
            padding: 0;
        }

        .form-group {
            width: 125px;
            margin-bottom: 0;
            flex-shrink: 0
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/poulnymi/new.pouletbini.com/core/resources/views/admin/setting/configuration.blade.php ENDPATH**/ ?>