
<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <?php echo Form::model($arrivage, [
                        'method' => 'POST',
                        'route' => ['admin.arrivage.store', $arrivage->id],
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]); ?>

                    <input type="hidden" name="id" value="<?php echo e($arrivage->id); ?>">
                    <input type="hidden" name="ferme" value="<?php echo e($arrivage->bande->ferme->id); ?>">
                    <input type="hidden" name="bande" value="<?php echo e($arrivage->bande->numero_bande); ?>">
                    <div class="form-group row">
                            <label class="col-sm-4 control-label"><?php echo app('translator')->get('Ferme'); ?></label>
                            <div class="col-xs-12 col-sm-8">
                             <?php echo e($arrivage->bande->ferme->nom); ?>

                            </div>
                        </div>
                    <div class="form-group row">
                        <?php echo e(Form::label(__('Numero de la Bande'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                        <?php echo e($arrivage->bande->numero_bande); ?>

                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo e(Form::label(__('Quantité de poulets arrivée'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('total', $arrivage->total_poulet, ['class' => 'form-control', 'id' => 'total', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo e(Form::label(__('Date d\'arrivage'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('date_arrivage', $arrivage->date_arrivage, ['class' => 'form-control', 'id' => 'date_arrivage', 'required']); ?>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <table class="table table-striped table-bordered">
    <thead>
            <tr>
                <th>Unité</th>
                <th>Categorie</th>
                <th>Prix(FCFA)</th>
                <th class="text-center">Quantité</th>
            </tr>
        </thead>
    <tbody>
        <?php $__currentLoopData = $arrivage->arrivageDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 <tr>
            <td> 
            <?php echo Form::hidden('unite[]', $data->categorie->unite_id, array()); ?> 
            <?php echo e($data->categorie->unite->name); ?>

            </td>
            <td>
            <?php echo Form::hidden('categorie[]', $data->categorie_id, array()); ?>

            <?php echo e($data->categorie->name); ?>

            </td>
            <td>
            <?php echo Form::hidden('price[]', $data->price, array()); ?>

            <?php echo e($data->price); ?>

            </td>
            <td>
            <?php echo Form::number('quantite[]', $data->quantity, array('placeholder' => __('Qté'),'class' => 'form-control', 'min'=>'0')); ?> 
        </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>

</table>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back','data' => ['route' => ''.e(route('admin.arrivage.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('back'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('admin.arrivage.index')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
<?php $__env->stopPush(); ?>
 
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/arrivage/edit.blade.php ENDPATH**/ ?>