
<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <?php echo Form::model($ferme, [
                        'method' => 'POST',
                        'route' => ['admin.ferme.store', $ferme->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]); ?>

                    <input type="hidden" name="id" value="<?php echo e($ferme->id); ?>">
                    <div class="form-group row">
                        <?php echo e(Form::label(__('Nom de la Ferme'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('nom', null, ['class' => 'form-control', 'id' => 'ferme', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <?php echo e(Form::label(__('Lieu'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('lieu', null, ['class' => 'form-control', 'id' => 'lieu', 'required']); ?>
                        </div>
                    </div>
  
                        <div class="form-group row">
                            <?php echo e(Form::label(__('Responsable'), null, ['class' => 'col-sm-4 control-label'])); ?>

                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('responsable', null, ['id' => 'responsable', 'placeholder' => 'Année de régéneration', 'class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <?php echo e(Form::label(__('Contact'), null, ['class' => 'col-sm-4 control-label'])); ?>

                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('contact', null, ['id' => 'contact', 'class' => 'form-control']); ?>
                            </div>
                        </div>

                    
                    <hr class="panel-wide">


                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block h-45 w-100"><?php echo app('translator')->get('Envoyer'); ?></button>
                    </div>
                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('breadcrumb-plugins'); ?>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.ferme.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.ferme.index')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>
 
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/ferme/edit.blade.php ENDPATH**/ ?>