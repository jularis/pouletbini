<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('manager.dashboard') }}" class="sidebar__main-logo"><img
                    src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('manager.dashboard') }}">
                    <a href="{{ route('manager.dashboard') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Tableau de Bord')</span>
                    </a>
                </li>
              
                <li class="sidebar-menu-item {{ menuActive('manager.staff*') }}">
                    <a href="{{ route('manager.staff.index') }}" class="nav-link ">
                        <i class="menu-icon las la-user-friends"></i>
                        <span class="menu-title">@lang("Gestion des Staffs")</span>
                    </a>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('manager.livraison.categorie.*', 3) }}">
                        <i class="menu-icon las la-tasks"></i>
                        <span class="menu-title">{{ __($general->site_name) }} @lang('Setting')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('manager.livraison.categorie.*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.categorie.index') }} ">
                                <a href="{{ route('manager.livraison.categorie.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Gestion Categorie')</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.categorie.produit.index') }} ">
                                <a href="{{ route('manager.livraison.categorie.produit.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Gestion Produit')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.categorie.client.index') }} ">
                                <a href="{{ route('manager.livraison.categorie.client.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Gestion Client')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive(['manager.livraison*','manager.livraison.delivery.queue','manager.livraison.manage.delivered','manager.livraison.manage.credit','manager.livraison.manage.list'], 3) }}">
                        <i class="menu-icon las la-university"></i>
                        <span class="menu-title">@lang('Gestion Commandes') </span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive(['manager.livraison*','manager.livraison.delivery.queue','manager.livraison.manage.delivered','manager.livraison.manage.credit','manager.livraison.manage.list'], 2) }} ">
                        <ul>
                        <li class="sidebar-menu-item {{ menuActive('manager.livraison.create') }}">
                    <a href="{{ route('manager.livraison.create') }}" class="nav-link ">
                        <i class="menu-icon las la-shipping-fast"></i>
                        <span class="menu-title">@lang('Enregistrement Commande')</span>
                    </a>
                </li>
                            <!-- <li class="sidebar-menu-item {{ menuActive('manager.livraison.sent.queue') }}">
                                <a href="{{ route('manager.livraison.sent.queue') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("En attente d'envoi")</span>
                                </a>
                            </li> -->
                            <!-- <li class="sidebar-menu-item {{ menuActive('manager.livraison.dispatch') }}">
                                <a href="{{ route('manager.livraison.dispatch') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("Expédiée")</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.upcoming') }}">
                                <a href="{{ route('manager.livraison.upcoming') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Encours')</span>
                                </a>
                            </li> -->
                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.delivery.queue') }}">
                                <a href="{{ route('manager.livraison.delivery.queue') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("En attente de reception")</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.manage.delivered') }}">
                                <a href="{{ route('manager.livraison.manage.delivered') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("Total Livré")</span>
                                </a>
                            </li>

                            <!-- <li class="sidebar-menu-item {{ menuActive('manager.livraison.manage.sent.list') }}">
                                <a href="{{ route('manager.livraison.manage.sent.list') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("Total Envoyé")</span>
                                </a>
                            </li> -->
                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.manage.credit') }}">
                                <a href="{{ route('manager.livraison.manage.credit') }}" class="nav-link ">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Total à Crédit')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.manage.annule') }}">
                                <a href="{{ route('manager.livraison.manage.annule') }}" class="nav-link ">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Total Annulé')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('manager.livraison.manage.list') }}">
                                <a href="{{ route('manager.livraison.manage.list') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang("Toutes les Commandes")</span>
                                </a>
                            </li>
                           

                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item  {{ menuActive('manager.magasin.income') }}">
                    <a href="{{ route('manager.magasin.income') }}" class="nav-link">
                        <i class="menu-icon las la-wallet"></i>
                        <span class="menu-title">@lang("Revenus des Magasins")</span>
                    </a>
                </li>
                <!-- <li class="sidebar-menu-item {{ menuActive('manager.magasin.index') }}">
                    <a href="{{ route('manager.magasin.index') }}" class="nav-link ">
                        <i class="menu-icon las la-university"></i>
                        <span class="menu-title">@lang('Liste des Magasins')</span>
                    </a>
                </li> -->
                <li class="sidebar-menu-item  {{ menuActive('ticket*') }}">
                    <a href="{{ route('manager.ticket.index') }}" class="nav-link">
                        <i class="menu-icon las la-ticket-alt"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                </li>
            </ul>
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
                <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
            </div>
        </div>


        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <div class="text-center mb-3 text-uppercase">
                <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
                <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
