<?php $__env->startSection('panel'); ?>
<div class="row mb-none-30">
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <form action="<?php echo e(route('manager.livraison.store')); ?>" method="POST" id="flocal">
                <div class="card-body">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label for=""><?php echo app('translator')->get("Date estimée d'envoi"); ?></label>
                            <div class="input-group">
                                <input name="estimate_date" value="<?php echo e(old('estimate_date')); ?>" type="text" autocomplete="off"  class="form-control date" placeholder="Date estimée d'envoi" required>
                                <span class="input-group-text"><i class="las la-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-6 form-group">
                            <label for=""><?php echo app('translator')->get('Status de paiement'); ?></label>
                            <div class="input-group">
                                <select class="form-control" required name="payment_status">
                                    <option value="0" selected><?php echo app('translator')->get('IMPAYE'); ?></option>
                                    <option value="1"><?php echo app('translator')->get('PAYE'); ?></option>
                                </select>
                                <span class="input-group-text"><i class="las la-money-bill-wave-alt"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-6">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary  text-white"><?php echo app('translator')->get('Informations du Destinataire'); ?></h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label><?php echo app('translator')->get('Selectionner Magasin'); ?></label>
                                            <select class="form-control" name="magasin" id="magasin" required>
                                                <option value><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                                <?php $__currentLoopData = $magasins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $magasin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($magasin->id); ?>" <?php if(old('magasin') ==$magasin->id): echo 'selected'; endif; ?>><?php echo e(__($magasin->name)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label><?php echo app('translator')->get('Selectionner Client'); ?></label>
                                            <select class="form-control select2-basic" name="client" onchange="getCustomer()" required>
                                                <option value="Autre"><?php echo app('translator')->get('Autre'); ?></option>
                                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($client->id); ?>" <?php if(old('client') ==$client->id): echo 'selected'; endif; ?>
                                                data-name="<?php echo e($client->name); ?>" 
                                                data-phone="<?php echo e($client->phone); ?>" 
                                                        data-email="<?php echo e($client->email); ?>" 
                                                        data-address="<?php echo e($client->address); ?>"
                                                    ><?php echo e(__($client->name)); ?> - <?php echo e(__($client->phone)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label><?php echo app('translator')->get('Nom du client'); ?></label>
                                            <input type="text" class="form-control" name="receiver_name"
                                                value="<?php echo e(old('receiver_name')); ?>" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label><?php echo app('translator')->get('Téléphone'); ?></label>
                                            <input type="text" class="form-control" name="receiver_phone"
                                                value="<?php echo e(old('receiver_phone')); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="form-group col-lg-12">
                                            <label><?php echo app('translator')->get('Email'); ?></label>
                                            <input type="email" class="form-control" name="receiver_email"
                                                value="<?php echo e(old('receiver_email')); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label><?php echo app('translator')->get('Adresse'); ?></label>
                                            <input type="text" class="form-control" name="receiver_address"
                                                value="<?php echo e(old('receiver_address')); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary  text-white"><?php echo app('translator')->get('Informations Expéditeur'); ?></h5>
                                <div class="card-body">
                                    <div class="row">
                                    <div class="form-group col-lg-12">
                                            <label><?php echo app('translator')->get('Selectionner Staff'); ?></label>
                                            <select class="form-control select2-basic" name="staff" id="staff" onchange="getStaff()" required>
                                                <option value><?php echo app('translator')->get('Selectionner une Option'); ?></option>
                                                <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($staff->id); ?>" <?php if(old('staff') ==$staff->id): echo 'selected'; endif; ?> data-chained="<?php echo e($staff->magasin_id); ?>" data-name="<?php echo e(__($staff->firstname)); ?> <?php echo e(__($staff->lastname)); ?>" data-phone="<?php echo e(__($staff->mobile)); ?>" data-email="<?php echo e(__($staff->email)); ?>" data-address="<?php echo e(__($staff->address)); ?>"><?php echo e(__($staff->lastname)); ?> <?php echo e(__($staff->firstname)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label><?php echo app('translator')->get('Nom'); ?></label>
                                            <input type="text" class="form-control" name="sender_name"
                                                value="<?php echo e(old('sender_name')); ?>" readonly required>
                                        </div>
                                        <div class=" form-group col-lg-6">
                                            <label><?php echo app('translator')->get('Téléphone'); ?></label>
                                            <input type="text" class="form-control" value="<?php echo e(old('sender_phone')); ?>"
                                                name="sender_phone" readonly required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label><?php echo app('translator')->get('Email'); ?></label>
                                            <input type="email" class="form-control" name="sender_email" readonly
                                                value="<?php echo e(old('sender_email')); ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label><?php echo app('translator')->get('Adresse'); ?></label>
                                            <input type="text" class="form-control" name="sender_address"
                                                value="<?php echo e(old('sender_address')); ?>" readonly >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    <div class="row mb-30">
                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                            <div class="card-header bg--primary">
                            
                            <div class="form-group row">
                            <div class="col-xs-12 col-sm-8">
                            <h5 class="text-white"><?php echo app('translator')->get('Informations de Livraison'); ?></h5>
                            </div>
                        <div class="col-xs-12 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('NUMERO DE BANDE'); ?></label>
                                <select class="form-control select2-multi-select float-end" name="bande[]" id="bande" multiple required> 
                                                <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($produit->arrivage->bande_id); ?>" <?php if(in_array(@$produit->arrivage->bande_id, @request()->bande ?? array())): echo 'selected'; endif; ?>><?php echo e(__($produit->arrivage->bande->numero_bande)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            </div>
                    </div>
                                
          </div>
                                
                                <div class="card-body">
                                    <div class="row">
                                    <input type="hidden" name="total" id="total" class="form-control" />
                                    <input type="hidden" name="qtecommande" id="qtecommande" class="form-control" />
                                    <input type="hidden" name="somme" id="somme" class="form-control" />
                                    <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                        <th>Unite</th>
                        <th>Categorie</th>
                        <th>Numero de Bande</th>
                        <th>Prix unitaire</th>
                        <th>Quantité initiale</th>
                        <th>Quantité commandée</th>
                         </tr></thead> 
                                <tbody id="listeproduits" style="text-align: center;">

                                </tbody>
                            </table>

                                    </div><br><br>
                                    <div class="border-line-area">
                                        <h6 class="border-line-title"><?php echo app('translator')->get('Resume'); ?></h6>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-text"><?php echo app('translator')->get('Reduction'); ?></span>
                                                <input type="number" name="discount"  value="<?php echo e(old('discount')); ?>" class="form-control bg-white text-dark discount" value="0">
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" d-flex justify-content-end mt-2">
                                        <div class="col-md-4  d-flex justify-content-between">
                                            <span class="fw-bold"><?php echo app('translator')->get('Sous-total'); ?>:</span>
                                            <div><span class="subtotal">0.00</span> <?php echo e($general->cur_sym); ?></div>
                                        </div>
                                    </div>
                                    <div class=" d-flex justify-content-end mt-2">
                                        <div class="col-md-4  d-flex justify-content-between">
                                            <span class="fw-bold"><?php echo app('translator')->get('Total'); ?>:</span>
                                            <div><span class="total">0.00</span> <?php echo e($general->cur_sym); ?></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-30">
                        <div class="col-lg-12">
                            <div class="card border--primary mt-3">
                                <h5 class="card-header bg--primary text-white"><?php echo app('translator')->get('Frais de Livraison'); ?> 
                                </h5>
                                <div class="card-body">
                                    <div class="row">
									<div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="number"  class="form-control" name="frais_livraison" value="<?php echo e(old('frais_livraison') ?? '1000'); ?>" required>
                                                        <span class="input-group-text"><?php echo e(__($general->cur_text)); ?></span>
                                                    </div>
                                                </div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn--primary h-45 w-100 Submitbtn"> <?php echo app('translator')->get("Envoyer"); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-lib'); ?>
<script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.fr.js')); ?>"></script>
<script src="<?php echo e(asset('assets/viseradmin/js/vendor/datepicker.en.js')); ?>"></script>
<script src="<?php echo e(asset('assets/viseradmin/js/jquery.chained.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style-lib'); ?>
<link  rel="stylesheet" href="<?php echo e(asset('assets/viseradmin/css/vendor/datepicker.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
    "use strict";
    $("#staff").chained("#magasin");
    function getStaff() {
        let name = $("#staff").find(':selected').data('name');
        let phone = $("#staff").find(':selected').data('phone');
        let email = $("#staff").find(':selected').data('email');
        let address = $("#staff").find(':selected').data('address');
        $('input[name=sender_name]').val(name);
        $('input[name=sender_email]').val(email);
        $('input[name=sender_phone]').val(phone); 
        $('input[name=sender_address]').val(address); 
    }
    function getCustomer() {
        var staffSelect = $("#client").val();
        
        var receiver_name = $("#client").find(':selected').data('name');
        var receiver_phone = $("#client").find(':selected').data('phone');
        var receiver_email = $("#client").find(':selected').data('email');
        var receiver_address = $("#client").find(':selected').data('address'); 
        if(staffSelect !='Autre'){
        $('input[name=receiver_name]').val(receiver_name).attr("readonly",'readonly');
        $('input[name=receiver_email]').val(receiver_email).attr("readonly",'readonly');
        $('input[name=receiver_phone]').val(receiver_phone).attr("readonly",'readonly');
        $('input[name=receiver_address]').val(receiver_address).attr("readonly",'readonly');
        }else{
        $('input[name=receiver_name]').val('').removeAttr("readonly",'readonly');
        $('input[name=receiver_email]').val('').removeAttr("readonly",'readonly');
        $('input[name=receiver_phone]').val('').removeAttr("readonly",'readonly');
        $('input[name=receiver_address]').val('').removeAttr("readonly",'readonly');
        }
        
    }
    $('#bande').change(function() {

var urlsend = '<?php echo e(route('manager.livraison.get.produit')); ?>';

$.ajax({
    type: 'GET',
    url: urlsend,
    data: $('#flocal').serialize(),
    success: function(html) {
        $('#listeproduits').html(html.tableau);
        $("#total").val(html.total);
        $("#qtecommande").val(html.total);
        $("#somme").val(0);
    }

});
});
let discount=0;
function getQuantite(id, k, s)
{
update_amounts(id, k, s);
calculation();
}
function update_amounts(id, k, s) {
            let total = $('#total').val();
            var sum = 0;
            let qtecommande = parseInt($('#qtecommande').val());
            let max = $('.quantity-' + id).attr('max');

            let quantite = 0;
            let somme = 0;
 

            $('.quantity-' + id).each(function() {
                var qty = $(this).val();
                quantite = parseInt(quantite) + parseInt(qty);
                //  if(quantite>max){
                //     $('#qte-'+k).val(0); 
                //     } 
            });

            $('.totaux').each(function() {
                var nb = $(this).val();
                // update Quantite survecue 
                sum = parseInt(sum) + parseInt(nb);
            });

            if (sum > total) {
                $('#qte-' + k).val(0);
                $('.totaux').each(function() {
                    var nb = $(this).val();
                    sum = parseInt(sum) + parseInt(nb);
                });
            } else {
                $('#qtecommande').val(sum);
            }
            for (let i = 1; i < 6; i++) {
                var soustotal = 0;

                $('.st-' + i).each(function() {
                    var nb = $(this).val();
                    soustotal = parseInt(soustotal) + parseInt(nb);

                });
                $('#soustotal-' + i).val(soustotal);

            }

            $("#qtecommande").attr({
                "max": total,
                "min": 0
            });
        }
        

$('.discount').on('input',function (e) {
    this.value = this.value.replace(/^\.|[^\d\.]/g, '');
    discount=parseFloat($(this).val() || 0); 

    calculation();
});
        function calculation ( ) {
            let items    = $('#listeproduits').find('.single-item');
            let subTotal = 0;

            $.each(items, function (i, item) {
                let price = parseFloat($(item).find('.price').val() || 0);
                let quantity = parseFloat($(item).find('.totaux').val() || 0);
                
                subTotal+=price*quantity;
            });

            subTotal=parseFloat(subTotal);

            // let discountAmount = (subTotal/100)*discount;
            let discountAmount = discount;
            let total = subTotal-discountAmount;

            $('.subtotal').text(subTotal.toFixed(2));
            $('.total').text(total.toFixed(2));
        };

        $('.date').datepicker({
            language  : 'fr',
            dateFormat: 'yyyy-mm-dd',
            minDate   : new Date()
        });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .border-line-area {
            position: relative;
            text-align: center;
            z-index: 1;
        }
        .border-line-area::before {
            position: absolute;
            content: '';
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e5e5;
            z-index: -1;
        }
        .border-line-title {
            display: inline-block;
            padding: 3px 10px;
            background-color: #fff;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('manager.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/livraison/create.blade.php ENDPATH**/ ?>