<div class="sidebar bg--dark">
    <?php
        $upcomingCount = \App\Models\LivraisonInfo::where('receiver_magasin_id', auth()->user()->id)
            ->where('status', 1)
            ->count();
        // $deliveryCount = \App\Models\LivraisonInfo::where('receiver_magasin_id',)
    ?>
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="<?php echo e(route('staff.dashboard')); ?>" class="sidebar__main-logo"><img
                    src="<?php echo e(getImage(getFilePath('logoIcon') . '/logo.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item <?php echo e(menuActive('staff.dashboard')); ?>">
                    <a href="<?php echo e(route('staff.dashboard')); ?>" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Tableau de Bord'); ?></span>
                    </a>
                </li>
                
                <!-- <li class="sidebar-menu-item <?php echo e(menuActive('staff.livraison.sent.queue')); ?>">
                    <a href="<?php echo e(route('staff.livraison.sent.queue')); ?>" class="nav-link ">
                        <i class="menu-icon las la-hourglass-start"></i>
                        <span class="menu-title"><?php echo app('translator')->get("En attente d'envoi"); ?></span>
                    </a>
                </li>   -->
                <li class="sidebar-menu-item <?php echo e(menuActive('staff.livraison.delivery.queue')); ?>">
                    <a href="<?php echo e(route('staff.livraison.delivery.queue')); ?>" class="nav-link ">
                        <i class="menu-icon lab la-accessible-icon"></i>
                        <span class="menu-title"><?php echo app('translator')->get('En attente de reception'); ?></span>
                    </a>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="<?php echo e(menuActive('staff.livraison.manage*', 3)); ?>">
                        <i class="menu-icon las la-sliders-h"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Gestion Commandes'); ?> </span>
                    </a>
                    <div class="sidebar-submenu <?php echo e(menuActive('staff.livraison.manage*', 2)); ?> ">
                        <ul>
                            <!-- <li class="sidebar-menu-item <?php echo e(menuActive('staff.livraison.manage.sent.list')); ?>">
                                <a href="<?php echo e(route('staff.livraison.manage.sent.list')); ?>" class="nav-link ">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Total Envoyé'); ?></span>
                                </a>
                            </li> -->
                            <li class="sidebar-menu-item <?php echo e(menuActive('staff.livraison.manage.delivered')); ?>">
                                <a href="<?php echo e(route('staff.livraison.manage.delivered')); ?>" class="nav-link ">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Total Livré'); ?></span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item <?php echo e(menuActive('staff.livraison.manage.list')); ?>">
                                <a href="<?php echo e(route('staff.livraison.manage.list')); ?>" class="nav-link ">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Toutes les Commandes'); ?></span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>
                <!-- <li class="sidebar-menu-item <?php echo e(menuActive('staff.magasin.index')); ?>">
                    <a href="<?php echo e(route('staff.magasin.index')); ?>" class="nav-link ">
                        <i class="menu-icon las la-university"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Liste des Magasins'); ?></span>
                    </a>
                </li> -->
                <li class="sidebar-menu-item  <?php echo e(menuActive('staff.cash.livraison.income')); ?>">
                    <a href="<?php echo e(route('staff.cash.livraison.income')); ?>" class="nav-link">
                        <i class="menu-icon las la-wallet"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Revenus de Livraisons'); ?></span>
                    </a>
                </li>
                <li class="sidebar-menu-item  <?php echo e(menuActive('ticket*')); ?>">
                    <a href="<?php echo e(route('staff.ticket.index')); ?>" class="nav-link">
                        <i class="menu-icon las la-ticket-alt"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Support Ticket'); ?></span>
                    </a>
                </li>

            </ul>
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary"><?php echo e(__(systemDetails()['name'])); ?></span>
                <span class="text--success"><?php echo app('translator')->get('V'); ?><?php echo e(systemDetails()['version']); ?> </span>
            </div>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary"><?php echo e(__(systemDetails()['name'])); ?></span>
                <span class="text--success"><?php echo app('translator')->get('V'); ?><?php echo e(systemDetails()['version']); ?> </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->

<?php $__env->startPush('script'); ?>
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/poulnymi/new.pouletbini.com/core/resources/views/staff/partials/sidenav.blade.php ENDPATH**/ ?>