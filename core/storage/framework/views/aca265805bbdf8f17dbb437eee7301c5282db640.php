
<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <?php echo Form::model($bande, [
                        'method' => 'POST',
                        'route' => ['admin.bande.store', $bande->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]); ?>

                    <input type="hidden" name="id" value="<?php echo e($bande->id); ?>">
                    <div class="form-group row">
                            <label class="col-sm-4 control-label"><?php echo app('translator')->get('Ferme'); ?></label>
                            <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="ferme" required>
                                <option value=""><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                <?php $__currentLoopData = $fermes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ferme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ferme->id); ?>" <?php if($ferme->id==$bande->ferme_id): echo 'selected'; endif; ?>><?php echo e(__($ferme->nom)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            </div>
                        </div>
                    <div class="form-group row">
                        <?php echo e(Form::label(__('Numero de la bande'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('numero', $bande->numero_bande, ['class' => 'form-control', 'id' => 'bande', 'required']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.bande.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.bande.index')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>
 
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/bande/edit.blade.php ENDPATH**/ ?>