<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'America',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => true,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],




    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'America',
    'logo_img' => 'vendor/adminlte/dist/img/connectlife.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'America',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/connectlife.png',
            'alt' => 'America',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/connectlife.png',
            'alt' => 'Accert',
            'effect' => 'animation__shake',
            'width' => 627,
            'height' => 357,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,


    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => '',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => '',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => 'sidebar-mini bg-fundo',
    'classes_brand' => 'bg-logo',
    'classes_brand_text' => '',
    'classes_content_wrapper' => 'bg-fundo',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'bg-escuro elevation-4',
    'classes_sidebar_nav' => 'nav-child-indent',
    'classes_topnav' => 'bg-escuro',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => '',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => '',
    'sidebar_collapse' => true,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => false,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => false,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '/admin/orcamento',
    'logout_url' => '/logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        // [
        //     'text'    => 'Dashboard',
        //     'icon'    => 'fas fa-home',
        //     'url'    => '/admin',
        //     'icon_color' => 'white',
        //     'classes'  => 'text-white',
        // ],
        [
            'text'        => 'Orçamento',
            'url'         => 'admin/orcamento',
            'icon'        => 'fas fa-money-bill',
            'icon_color' => 'white',
            'classes'  => 'text-white',
            'can'       => ''
        ],

        [
            "text" => "Contratos",
            "url" => "admin/contratos",
            "icon" => "fas fa-id-card-alt",
            'icon_color' => 'white',
            'classes' => 'text-white',
            'can'       => 'contratos'
        ],

        [
            "text" => "Contrato",
            "url" => "admin/contrato",
            "icon" => "fas fa-id-card-alt",
            'icon_color' => 'white',
            'classes' => 'text-white',
            'can'       => 'contrato'
        ],

        [
            "text" => "Financeiro",
            "url" => "admin/financeiro",
            "icon" => "fas fa-coins",
            'icon_color' => 'white',
            'classes' => 'text-white financeiro_adminlte',
            'can'       => 'financeiro'
        ],

        [
            "text" => "Financeiro - Tharllyany",
            "url" => "admin/gerente",
            "icon" => "fas fa-money-check-alt",
            "icon_color" => 'white',
            "classes" => "text-white",
            'can' => 'comissao'
        ],




        // [
        //     "text" => "Comissões",
        //     "url" => "admin/comissao",
        //     "icon" => "fas fa-id-card-alt",
        //     'icon_color' => 'white',
        //     'classes' => 'text-white',
        //     'can'       => 'comissao'
        // ],

        // [
        //     "text" => "Premiações",
        //     "url" => "admin/premiacao",
        //     "icon" => "fas fa-crown",
        //     'icon_color' => 'white',
        //     'classes' => 'text-white',
        //     'can' => 'premiacoes'
        // ],

        [
            'text' => 'Tabela de Preços',
            'url'  => 'admin/tabela',
            'icon' => 'fas fa-money-bill',
            'classes' => 'text-white configuracoes_adminlte',
            'can' => 'configuracoes',
            'active' => ['tabela',"http://localhost:8000/admin/tabela/*"]
        ],

        [
            'text' => 'Corretores',
            'url'  => 'admin/corretores/active_inative',
            'icon' => 'fas fa-users',
            'classes' => 'text-white configuracoes_adminlte',
            'can' => 'configuracoes',
            'active' => ['corretores',"http://localhost:8000/admin/corretores/*"]
        ],


        /*
        [
            'text'    => 'Configurações',
            'icon'    => 'fas fa-cog',
            'icon_color' => 'white',
            'classes' => 'text-white configuracoes_adminlte',
            'can' => 'configuracoes',
            'submenu' => [
                [
                    'text' => 'Corretora',
                    'url'  => 'admin/corretora',
                    'icon' => 'fas fa-hands-helping',
                    'classes' => 'text-white',
                    'active' => ['corretora',"http://localhost:8000/admin/corretora/*"]
                ],
               [
                    'text' => 'Colaborador',
                    'url'  => '',
                    'icon' => 'fas fa-users',
                    'classes' => 'text-white',
                    'active' => ['corretores',"http://localhost:8000/admin/corretores/*"]
                ],
                 [
                    'text' => 'Tabela de Preços',
                    'url'  => 'admin/tabela',
                    'icon' => 'fas fa-money-bill',
                    'classes' => 'text-white',
                    'active' => ['tabela',"http://localhost:8000/admin/tabela/*"]
                ],

                // [
                //     'text' => 'Administradora',
                //     'url'  => 'admin/administradora',
                //     'icon' => 'fab fa-superpowers',
                //     'classes' => 'text-white',
                //     'active' => ['administradora',"http://localhost:8000/admin/administradora/*"]
                // ],
                // [
                //     "text" => "Tabela Origem",
                //     "url" => "admin/tabela_origem",
                //     "icon" => "fas fa-city",
                //     'classes' => 'text-white',
                //     "active" => ['tabela_origem',"http://localhost:8000/admin/tabela_origem/*"]

                // ],
                // [
                //     'text' => 'Colaborador',
                //     'url'  => 'admin/corretores',
                //     'icon' => 'fas fa-users',
                //     'classes' => 'text-white',
                //     'active' => ['corretores',"http://localhost:8000/admin/corretores/*"]
                // ],
                // [
                //     "text" => "Planos",
                //     "url" => "admin/planos",
                //     "icon" => "fas fa-clipboard-list",
                //     'classes' => 'text-white',
                //     "active" => ['planos',"http://localhost:8000/admin/planos/*"]

                // ],
                // [
                //     'text' => 'Tabela de Preços',
                //     'url'  => 'admin/tabela',
                //     'icon' => 'fas fa-money-bill',
                //     'classes' => 'text-white',
                //     'active' => ['tabela',"http://localhost:8000/admin/tabela/*"]
                // ],




            ],
        ],

        */



        // ['header' => 'account_settings'],

        // ['header' => 'labels'],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/datatables/datatables.min.js',
                ],

                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/datatables/moment.min.js'
                ],

                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/datatables/dataTables.dateTime.min.js',
                ],


                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '/vendor/datatables/datatables.min.css',
                ],







                // [
                //     'type' => 'js',
                //     'asset' => false,
                //     'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                // ],

            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                // [
                //     'type' => 'js',
                //     'asset' => false,
                //     'location' => '/vendor/select2/js/select2.min.js',
                // ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '/vendor/select2/css/select2.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Toastr' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '/vendor/toastr/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '/vendor/toastr/toastr.min.css',
                ],

            ]
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/sweetalert2/sweetalert2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '/vendor/sweetalert2/sweetalert2.min.css',
                ]
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'jqueryUi' => [
            'active' => false,
            'files' => [
                // Core
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-ui/jquery-ui.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-ui/datepicker-pt-BR.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/jquery-ui/jquery-ui.min.css',
                ],

            ]
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/chart.js/chart3.js',
                ],
            ],
        ],
        'ChartGoogle' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '/vendor/chart.js/loader.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
