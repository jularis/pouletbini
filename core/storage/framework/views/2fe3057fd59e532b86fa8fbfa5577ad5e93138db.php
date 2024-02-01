<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="<?php echo e(route('manager.dashboard')); ?>" class="sidebar__main-logo"><img
                    src="<?php echo e(getImage(getFilePath('logoIcon') . '/logo.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item <?php echo e(menuActive('manager.dashboard')); ?>">
                    <a href="<?php echo e(route('manager.dashboard')); ?>" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Tableau de Bord'); ?></span>
                    </a>
                </li>
              
                <li class="sidebar-menu-item <?php echo e(menuActive('manager.staff*')); ?>">
                    <a href="<?php echo e(route('manager.staff.index')); ?>" class="nav-link ">
                        <i class="menu-icon las la-user-friends"></i>
                        <span class="menu-title"><?php echo app('translator')->get("Gestion des Staffs"); ?></span>
                    </a>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="<?php echo e(menuActive('manager.livraison.categorie.*', 3)); ?>">
                        <i class="menu-icon las la-tasks"></i>
                        <span class="menu-title"><?php echo e(__($general->site_name)); ?> <?php echo app('translator')->get('Setting'); ?></span>
                    </a>
                    <div class="sidebar-submenu <?php echo e(menuActive('manager.livraison.categorie.*', 2)); ?> ">
                        <ul>
                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.categorie.index')); ?> ">
                                <a href="<?php echo e(route('manager.livraison.categorie.index')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Gestion Categorie'); ?></span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.categorie.produit.index')); ?> ">
                                <a href="<?php echo e(route('manager.livraison.categorie.produit.index')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Gestion Produit'); ?></span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.categorie.client.index')); ?> ">
                                <a href="<?php echo e(route('manager.livraison.categorie.client.index')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Gestion Client'); ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="<?php echo e(menuActive(['manager.livraison*','manager.livraison.delivery.queue','manager.livraison.manage.delivered','manager.livraison.manage.credit','manager.livraison.manage.list'], 3)); ?>">
                        <i class="menu-icon las la-university"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Gestion Commandes'); ?> </span>
                    </a>
                    <div class="sidebar-submenu <?php echo e(menuActive(['manager.livraison*','manager.livraison.delivery.queue','manager.livraison.manage.delivered','manager.livraison.manage.credit','manager.livraison.manage.list'], 2)); ?> ">
                        <ul>
                        <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.create')); ?>">
                    <a href="<?php echo e(route('manager.livraison.create')); ?>" class="nav-link ">
                        <i class="menu-icon las la-shipping-fast"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Enregistrement Commande'); ?></span>
                    </a>
                </li>
                            <!-- <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.sent.queue')); ?>">
                                <a href="<?php echo e(route('manager.livraison.sent.queue')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get("En attente d'envoi"); ?></span>
                                </a>
                            </li> -->
                            <!-- <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.dispatch')); ?>">
                                <a href="<?php echo e(route('manager.livraison.dispatch')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get("Expédiée"); ?></span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.upcoming')); ?>">
                                <a href="<?php echo e(route('manager.livraison.upcoming')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Encours'); ?></span>
                                </a>
                            </li> -->
                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.delivery.queue')); ?>">
                                <a href="<?php echo e(route('manager.livraison.delivery.queue')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get("En attente de reception"); ?></span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.manage.delivered')); ?>">
                                <a href="<?php echo e(route('manager.livraison.manage.delivered')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get("Total Livré"); ?></span>
                                </a>
                            </li>

                            <!-- <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.manage.sent.list')); ?>">
                                <a href="<?php echo e(route('manager.livraison.manage.sent.list')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get("Total Envoyé"); ?></span>
                                </a>
                            </li> -->
                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.manage.credit')); ?>">
                                <a href="<?php echo e(route('manager.livraison.manage.credit')); ?>" class="nav-link ">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Total à Crédit'); ?></span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.manage.annule')); ?>">
                                <a href="<?php echo e(route('manager.livraison.manage.annule')); ?>" class="nav-link ">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get('Total Annulé'); ?></span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item <?php echo e(menuActive('manager.livraison.manage.list')); ?>">
                                <a href="<?php echo e(route('manager.livraison.manage.list')); ?>" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title"><?php echo app('translator')->get("Toutes les Commandes"); ?></span>
                                </a>
                            </li>
                           

                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item  <?php echo e(menuActive('manager.magasin.income')); ?>">
                    <a href="<?php echo e(route('manager.magasin.income')); ?>" class="nav-link">
                        <i class="menu-icon las la-wallet"></i>
                        <span class="menu-title"><?php echo app('translator')->get("Revenus des Magasins"); ?></span>
                    </a>
                </li>
                <!-- <li class="sidebar-menu-item <?php echo e(menuActive('manager.magasin.index')); ?>">
                    <a href="<?php echo e(route('manager.magasin.index')); ?>" class="nav-link ">
                        <i class="menu-icon las la-university"></i>
                        <span class="menu-title"><?php echo app('translator')->get('Liste des Magasins'); ?></span>
                    </a>
                </li> -->
                <li class="sidebar-menu-item  <?php echo e(menuActive('ticket*')); ?>">
                    <a href="<?php echo e(route('manager.ticket.index')); ?>" class="nav-link">
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
<?php /**PATH C:\laragon\www\pouletbini\core\resources\views/manager/partials/sidenav.blade.php ENDPATH**/ ?>