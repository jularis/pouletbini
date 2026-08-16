<?php $__env->startSection('panel'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('Nom'); ?></th>
                                    <th><?php echo app('translator')->get("Nom d'utilisateur"); ?></th>
                                    <th><?php echo app('translator')->get('Email'); ?></th>
                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                    <?php if($adminId == Status::SUPER_ADMIN_ID): ?>
                                    <th><?php echo app('translator')->get('Action'); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span><?php echo e(__($admin->name)); ?></span>
                                        </td>
                                        <td>
                                            <span><?php echo e($admin->username); ?></span>
                                        </td>
                                        <td>
                                            <?php echo e($admin->email); ?>

                                        </td>
                                        <td> <?php  echo $admin->statusBadge; ?> </td>
                                        <?php if($adminId == Status::SUPER_ADMIN_ID): ?>
                                        <td>
                                        
                                            <?php if($admin->id != Status::SUPER_ADMIN_ID): ?>
                                            <?php if($admin->status == Status::DISABLE): ?>
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="<?php echo e(route('admin.status', $admin->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir activer cet admin?'); ?>">
                                                        <i class="la la-eye"></i> <?php echo app('translator')->get("Activer"); ?>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="<?php echo e(route('admin.status', $admin->id)); ?>"
                                                        data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir désactiver cet admin?'); ?>">
                                                        <i class="la la-eye-slash"></i> <?php echo app('translator')->get("Désactiver"); ?>
                                                    </button>
                                                <?php endif; ?>
                                            <button class="btn btn-sm btn-outline--primary editBtn"
                                                data-name="<?php echo e($admin->name); ?>" data-username="<?php echo e($admin->username); ?>"
                                                data-email="<?php echo e($admin->email); ?>" data-id="<?php echo e($admin->id); ?>">
                                                <i class="la la-pen"></i><?php echo app('translator')->get('Edit'); ?>
                                            </button>
                                            
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                data-action="<?php echo e(route('admin.remove', $admin->id)); ?>"
                                                data-question="<?php echo app('translator')->get('Etes-vous sûr de vouloir supprimer cet admin?'); ?>">
                                                <i class="las la-trash"></i> <?php echo app('translator')->get('Supprimer'); ?>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                         <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <?php if($admins->hasPages()): ?>
                    <div class="card-footer py-4">
                        <?php echo e(paginateLinks($admins)); ?>

                    </div>
                <?php endif; ?>
            </div>
             
        </div>
    </div>
    <!-- Create Modal -->
    <div class="modal fade" id="manageAdmin">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo app('translator')->get('Créer Admin'); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i></button>
                </div>
                <form action="<?php echo e(route('admin.store')); ?>" method="post" class="resetForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Nom'); ?></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Email'); ?></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo app('translator')->get("Nom d'utilisateur"); ?></label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group pass">
                            <label><?php echo app('translator')->get("Mot de Passe"); ?></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group cpass">
                            <label><?php echo app('translator')->get("Confirmerer Mot de Passe"); ?></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><?php echo app('translator')->get("Envoyer"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php if (isset($component)) { $__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b = $component; } ?>
<?php $component = App\View\Components\ConfirmationModal::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\ConfirmationModal::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b)): ?>
<?php $component = $__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b; ?>
<?php unset($__componentOriginalc51724be1d1b72c3a09523edef6afdd790effb8b); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb-plugins'); ?>
    <button type="button" class="btn btn-sm btn-outline--primary addAdmin">
        <i class="las la-plus"></i><?php echo app('translator')->get("Créer un nouveau"); ?>
    </button>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.editBtn').on('click', function() {
                let title = 'Mise à jour Admin'
                let name = $(this).data('name');
                let id = $(this).data('id');
                let username = $(this).data("username");
                let email = $(this).data('email');
                let modal = $('#manageAdmin');
                modal.find('.modal-title').text(title)
                modal.find('input[name=name]').val(name);
                modal.find('input[name=id]').val(id);
                modal.find('input[name=username]').val(username);
                modal.find('input[name=email]').val(email);
                modal.find('input[name=password_confirmation]').removeAttr('required','required');
                modal.find('input[name=password]').removeAttr('required','required');
                modal.find('label[for=password_confirmation]').removeClass('required');
                modal.find('label[for=password]').removeClass('required');
                modal.modal('show');
            });
            $('.addAdmin').on('click', function() {
                let modal = $('#manageAdmin');
                $('.resetForm').trigger('reset');
                $(`input[name=id]`).val(0);
                modal.find('.pass').removeClass('d-none');
                modal.find('.cpass').removeClass('d-none');
                modal.modal('show')
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/all.blade.php ENDPATH**/ ?>