<div class="modal-header">
    <h5 class="modal-title">{{$pageTitle}}</h5>
    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
                    {!! Form::open([
                        'route' => ['admin.ferme.store'],
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'id' => 'fmodal',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" name="ajax" value="oui"/>
                    <div class="form-group row">
                        {{ Form::label(__('Nom de la Ferme'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('nom', null, ['class' => 'form-control', 'id' => 'ferme', 'required']); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label(__('Lieu'), null, ['class' => 'col-sm-4 control-label']) }}
                        <div class="col-xs-12 col-sm-8">
                            <?php echo Form::text('lieu', null, ['class' => 'form-control', 'id' => 'lieu', 'required']); ?>
                        </div>
                    </div>
  
                        <div class="form-group row">
                            {{ Form::label(__('Responsable'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('responsable', null, ['id' => 'responsable', 'placeholder' => 'Nom & Prénoms', 'class' => 'form-control']); ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            {{ Form::label(__('Contact'), null, ['class' => 'col-sm-4 control-label']) }}
                            <div class="col-xs-12 col-sm-8">
                                <?php echo Form::text('contact', null, ['id' => 'contact', 'class' => 'form-control']); ?>
                            </div>
                        </div>
                        <div class="modal-footer"> 
    <button class="btn btn--primary w-100 h-45" id="save-content">Enregistrer</button>
</div>
                    {!! Form::close() !!} 
    </div>
   

</div>   

<script>
  
    $('#save-content').click(function() {
        $.easyAjax({
            container: '#fmodal',
            type: "GET", 
            blockUI: true, 
            url: "{{ route('admin.ferme.store.modal') }}",
            data: $('#fmodal').serialize(),
            success: function(response) { 
                $('#ferme').html(response.data);
                $(MODAL_XL).modal('hide'); 
            }
        })
    });
</script>

