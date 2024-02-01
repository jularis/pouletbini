
<?php $__env->startSection('panel'); ?>
    <div class="row mb-none-30">
        <div class="col-lg-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <?php echo Form::open([
                        'route' => ['admin.arrivage.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'flocal',
                        'enctype' => 'multipart/form-data',
                    ]); ?>

 
                    <div class="form-group row">
                            <label class="col-sm-4 control-label"><?php echo app('translator')->get('Ferme'); ?></label>
                            <div class="col-xs-12 col-sm-8">
                            <select class="form-control" name="ferme" id="ferme" required>
                                <option value=""></option>
                                <?php $__currentLoopData = $fermes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ferme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ferme->id); ?>" <?php if($ferme->id==old('ferme_id')): echo 'selected'; endif; ?>><?php echo e(__($ferme->nom)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            </div>
                        </div>
                    <div class="form-group row autosaisie">
                        <?php echo e(Form::label(__('Numero de la bande'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                        <select class="form-control  select2-auto-tokenize" name="bande" id="bande" required>
                                 
                            </select> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo e(Form::label(__('Quantité de poulets arrivée'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::number('total', null, ['class' => 'form-control', 'id' => 'total', 'required']); ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php echo e(Form::label(__('Date d\'arrivage'), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::date('date_arrivage', null, ['class' => 'form-control', 'id' => 'date_arrivage', 'required']); ?>
                        </div>
                    </div>
                    
                    <hr class="panel-wide">
                    <div class="form-group row">
                        <?php echo e(Form::label(__(''), null, ['class' => 'col-sm-4 control-label'])); ?>

                        <div class="col-xs-12 col-sm-8">
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
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 <tr>
            <td> 
            <?php echo Form::hidden('unite[]', $data->unite_id, array()); ?> 
            <?php echo e($data->unite->name); ?>

            </td>
            <td>
            <?php echo Form::hidden('categorie[]', $data->id, array()); ?>

            <?php echo e($data->name); ?>

            </td>
            <td>
            <?php echo Form::hidden('price[]', $data->price, array()); ?>

            <?php echo e($data->price); ?>

            </td>
            <td>
            <?php echo Form::number('quantite[]', null, array('placeholder' => __('Qté'),'class' => 'form-control', 'min'=>'0')); ?> 
        </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>

</table>
                        </div>
                    </div>
                    <hr class="panel-wide">
                    <div class="form-group row">
                        <button type="submit" class="btn btn--primary w-100 h-45"> <?php echo app('translator')->get('Envoyer'); ?></button>
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

<?php $__env->startPush('script'); ?>
 
    <script>
        $('#ferme').select2({});
         $('.select2-auto-tokenize').select2({
            dropdownParent: $('.autosaisie'),
            tags: true,
        });
         $('#ferme').change(function(){ 
var urlsend='<?php echo e(route("admin.arrivage.getBande")); ?>'; 
  $.ajax({
            type:'POST',
            url: urlsend,
            data: $('#flocal').serialize(),
            success:function(html){
            $('#bande').html(html);
            }

        });
});
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/admin/arrivage/create.blade.php ENDPATH**/ ?>