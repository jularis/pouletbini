<?php $__env->startSection('content'); ?>
    <div class="login-main">
        <div class="container custom-container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
                    <div class="login-area">
                        <div class="login-wrapper">
                            <div class="login-wrapper__top">
                                <h3 class="title text-white"><?php echo app('translator')->get('Bienvenue sur'); ?> <strong><?php echo e(__($general->site_name)); ?></strong>
                                </h3>
                                <p class="text-white"><?php echo e(__($pageTitle)); ?> <?php echo app('translator')->get('Tableau de Bord'); ?></p>
                            </div>
                            <div class="login-wrapper__body">
                                <form action="<?php echo e(route('staff.login')); ?>" method="POST"
                                    class="cmn-form mt-30 verify-gcaptcha login-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get("Nom d'utilisateur"); ?></label>
                                        <input type="text" class="form-control" value="<?php echo e(old("Nom d'utilisateur")); ?>"
                                            name="username" required>
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo app('translator')->get("Mot de Passe"); ?></label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <?php if (isset($component)) { $__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243 = $component; } ?>
<?php $component = App\View\Components\Captcha::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('captcha'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\Captcha::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243)): ?>
<?php $component = $__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243; ?>
<?php unset($__componentOriginalc0af13564821b3ac3d38dfa77d6cac9157db8243); ?>
<?php endif; ?>
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" name="remember" type="checkbox" id="remember">
                                            <label class="form-check-label" for="remember"><?php echo app('translator')->get('Se souvenir de moi'); ?></label>
                                        </div>
                                        <a href="<?php echo e(route('staff.password.request')); ?>"
                                            class="forget-text"><?php echo app('translator')->get('Mot de passe oublié?'); ?></a>
                                    </div>
                                    <button type="submit" class="btn cmn-btn w-100"><?php echo app('translator')->get('Connexion'); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('staff.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/staff/auth/login.blade.php ENDPATH**/ ?>