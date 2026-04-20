<?php

namespace App\Menu\Platform;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    public function __construct(
        private Security     $security,
        private RequestStack $requestStack,
    ) {}

    public function build(): array
    {
        $currentRoute = $this->requestStack->getCurrentRequest()?->attributes->get('_route');
        return $this->filter($this->getMenuDefinition(), $currentRoute);
    }

    public function buildBreadcrumbs(): array
    {
        $currentRoute = $this->requestStack->getCurrentRequest()?->attributes->get('_route');
        $breadcrumbs  = [];

        // Always start with Home
        $breadcrumbs[] = [
            'label' => 'Home',
            'route' => 'admin_v1_shop_webshop_index',
            'url'   => null, // resolved in Twig via path()
            'active' => false, // ← add this
        ];

        $this->findBreadcrumbTrail(
            $this->getMenuDefinition(),
            $currentRoute,
            $breadcrumbs
        );

        // Mark last item as active (no link)
        if (!empty($breadcrumbs)) {
            $breadcrumbs[array_key_last($breadcrumbs)]['active'] = true;
        }

        return $breadcrumbs;
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Recursively walks the menu tree looking for the current route.
     * Builds the trail on the way back up (backtracking).
     */
    private function findBreadcrumbTrail(
        array   $items,
        ?string $currentRoute,
        array   &$trail
    ): bool {
        foreach ($items as $item) {
            // Direct match on this item
            if (isset($item['route']) && $item['route'] === $currentRoute) {
                $trail[] = [
                    'label'  => $item['label'],
                    'route'  => $item['route'],
                    'active' => false, // set after loop
                ];
                return true;
            }

            // Recurse into children
            if (!empty($item['children'])) {
                // Tentatively add parent to trail
                $parentCrumb = [
                    'label'  => $item['label'],
                    'route'  => $item['route'] ?? null,
                    'active' => false,
                ];
                $trail[] = $parentCrumb;

                if ($this->findBreadcrumbTrail($item['children'], $currentRoute, $trail)) {
                    return true; // trail is complete, bubble up
                }

                // Not found in this branch — remove the tentative parent
                array_pop($trail);
            }
        }

        return false;
    }

    private function filter(array $items, ?string $currentRoute): array
    {
        $result = [];

        foreach ($items as $item) {
            if (!empty($item['roles'])) {
                $allowed = array_filter(
                    $item['roles'],
                    fn($role) => $this->security->isGranted($role)
                );
                if (empty($allowed)) {
                    continue;
                }
            }

            if (!empty($item['children'])) {
                $item['children'] = $this->filter($item['children'], $currentRoute);
            }

            $item['active'] = isset($item['route']) && $item['route'] === $currentRoute;
            $item['open'] = !empty($item['children']) && !empty(
                array_filter($item['children'], fn($c) => $c['active'])
                );

            $result[] = $item;
        }

        return $result;
    }

    private function getMenuDefinition(): array
    {
        $user = $this->security->getUser();
        $roles = $user ? $user->getRoles() : [];
        $favourites = [];

        return [
            [
                'label' => 'Dashboard',
                'subtitle' => 'Vezérlőpult',
                'icon'  => 'bi bi-speedometer2',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => [
                    [
                        'label' => 'admin | vezérlőpult',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'áttekintés',
                                'icon' => 'bi bi-house',
                                'route' => 'homepage'
                            ],
                            [
                                'label' => 'intranet',
                                'icon' => 'bi bi-info-square',
                                'route' => null
                            ],
                        ]
                    ],
                    [
                        'label' => 'kedvencek',
                        'route' => null,
                        'children' => $favourites,
                    ],
                    [
                        'label' => 'Szolgáltatások',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'Domain',
                                'icon'  => 'bi bi-link-45deg',
                                'route' => 'admin_v1_domains'
                            ],
                            [
                                'label' => 'Honlap',
                                'icon'  => 'bi bi-globe',
                                'route' => 'admin_v1_website_index'
                            ],
                            [
                                'label' => 'Webáruház',
                                'icon'  => 'bi bi-cart',
                                'route' => 'admin_v1_shop_webshop_index'
                            ],
                            [
                                'label' => 'Webalkalmazás',
                                'icon'  => 'bi bi-window',
                                'route' => null
                            ],
                            [
                                'label' => 'Mobilalkalmazás',
                                'icon'  => 'bi bi-phone',
                                'route' => null
                            ],
                            [
                                'label' => 'API',
                                'icon'  => 'bi bi-code-slash',
                                'route' => 'admin_v1_api_index'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'label' => 'CMS',
                'subtitle' => 'Tartalomkezelés',
                'icon'  => 'bi bi-globe',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => [
                    [
                        'label' => 'Tartalomkezelés',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'Blokk',
                                'icon'  => 'bi bi-bricks',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Összes blokk',
                                        'route' => 'admin_v1_block_index'
                                    ],
                                    [
                                        'label' => 'új blokk hozzáadása',
                                        'route' => 'admin_v1_block_new'
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Bejegyzések',
                                'icon'  => 'bi bi-pin-angle-fill',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Összes bejegyzés',
                                        'route' => 'admin_v1_website_posts'
                                    ],
                                    [
                                        'label' => 'bejegyzés hozzáadása',
                                        'route' => 'admin_v1_website_post_new'
                                    ],
                                    [
                                        'label' => 'bejegyzés kategóriák',
                                        'route' => 'admin_v1_website_categories'
                                    ],
                                    [
                                        'label' => 'címkék',
                                        'route' => null
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Események',
                                'icon'  => 'bi bi-calendar-event',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Összes esemény',
                                        'route' => 'admin_event_index'
                                    ],
                                    [
                                        'label' => 'esemény hozzáadása',
                                        'route' => 'admin_event_new'
                                    ],
                                    [
                                        'label' => 'események importálása',
                                        'route' => 'admin_event_import'
                                    ],
                                    [
                                        'label' => 'esemény kategóriák',
                                        'route' => null
                                    ],
                                    [
                                        'label' => 'esemény címkék',
                                        'route' => null
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Média',
                                'icon'  => 'bi bi-hdd',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Összes média',
                                        'route' => 'admin_v1_media'
                                    ],
                                    [
                                        'label' => 'média hozzáadása',
                                        'route' => 'admin_v1_media_new'
                                    ],
                                    [
                                        'label' => 'média kategóriák',
                                        'route' => null
                                    ],
                                    [
                                        'label' => 'média beállítások',
                                        'route' => null
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Oldalak',
                                'icon'  => 'bi bi-file-earmark-fill',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Összes oldal',
                                        'route' => 'admin_v1_website_pages'
                                    ],
                                    [
                                        'label' => 'új oldal hozzáadása',
                                        'route' => 'admin_v1_website_page_new'
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Hozzászólások',
                                'icon'  => 'bi bi-chat-square-fill',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Összes hozzászólás',
                                        'route' => null
                                    ],
                                ]
                            ],
                        ]
                    ],
                    [
                        'label' => 'Beállítások',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'Megjelenés',
                                'icon'  => 'bi bi-brush-fill',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Menük',
                                        'route' => 'admin_v1_website_menus'
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Eszközök',
                                'icon'  => 'bi bi-wrench',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'export',
                                        'route' => null
                                    ],
                                    [
                                        'label' => 'import',
                                        'route' => null
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Beállítások',
                                'icon'  => 'bi bi-sliders2-vertical',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'Összes beállítás',
                                        'route' => null
                                    ],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
            [
                'label' => 'SHOP',
                'subtitle' => 'Kereskedelem',
                'icon'  => 'bi bi-cart',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => [
                    [
                        'label' => 'Értékesítés',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'Termékek',
                                'icon'  => 'bi bi-basket',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'minden termék',
                                        'route' => 'ecom_v1_products'
                                    ],
                                    [
                                        'label' => 'új termék hozzáadás',
                                        'route' => 'ecom_v1_products_new'
                                    ],
                                    [
                                        'label' => 'termék kategóriák',
                                        'route' => 'ecom_v1_product_categories'
                                    ],
                                ]
                            ],
                            [
                                'label' => 'Rendelések',
                                'icon'  => 'bi bi-cart-check',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'összes rendelés',
                                        'route' => 'ecom_order_index'
                                    ],
                                ]
                            ],
                        ],
                    ],
                    [
                        'label' => 'Beállítások',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'Beállítások',
                                'icon'  => 'bi bi-gear-fill',
                                'route' => null,
                                'children' => [
                                    [
                                        'label' => 'fizetési módok',
                                        'route' => 'admin_v1_webshop_paymentmethod_index'
                                    ],
                                    [
                                        'label' => 'szállítási módok',
                                        'route' => 'admin_v1_webshop_shippingmethod_index'
                                    ],
                                    [
                                        'label' => 'rendelés e-mail sablonok',
                                        'route' => 'admin_v1_order_email_index',
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'CRM',
                'subtitle' => 'Ügyfélkapcsolat-kezelés',
                'icon'  => 'bi bi-people',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => []
            ],
            [
                'label' => 'ERP',
                'subtitle' => 'Vállalatirányítás',
                'icon'  => 'bi bi-briefcase',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => []
            ],
            [
                'label' => 'analytics',
                'subtitle' => 'Elemzések',
                'icon'  => 'bi bi-graph-up',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => []
            ],
            [
                'label' => 'tools',
                'subtitle' => 'Eszközök',
                'icon'  => 'bi bi-tools',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => [
                    [
                        'label' => 'Import',
                        'icon'  => 'bi bi-calendar-event',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'események importálása',
                                'route' => 'admin_event_import',
                                'icon' => 'bi bi-calendar-event',
                            ],
                        ]
                    ],
                ]
            ],
            [
                'label' => 'profile',
                'subtitle' => 'Profil',
                'icon'  => 'bi bi-person',
                'route' => null,
                'roles' => ['ROLE_USER'],
                'children' => [
                    [
                        'label' => 'Személyes',
                        'route' => null,
                        'icon' => 'bi bi-person',
                        'children' => [
                            [
                                'label' => 'Személyes tárhely',
                                'route' => 'admin_v1_media_user_index',
                                'icon' => 'bi bi-hdd',

                            ],
                            [
                                'label' => 'Értesítések',
                                'route' => null,
                                'icon' => 'bi bi-bell',
                            ],
                            [
                                'label' => 'Naptár',
                                'route' => null,
                                'icon' => 'bi bi-calendar',
                            ],
                            [
                                'label' => 'Jegyzet',
                                'route' => null,
                                'icon' => 'bi bi-card-text',
                            ],
                            [
                                'label' => 'Munkaidő-nyilvántartás',
                                'route' => null,
                                'icon' => 'bi bi-alarm',
                            ],
                            [
                                'label' => 'Rendszerüzenet',
                                'route' => null,
                                'icon' => 'bi bi-envelope-arrow-up',
                            ],
                        ]
                    ],
                    [
                        'label' => 'Beállítások',
                        'route' => null,
                        'icon' => 'bi bi-person',
                        'children' => [
                            [
                                'label' => 'Profil szerkesztése',
                                'route' => 'admin_v1_account_edit',
                                'icon' => 'bi bi-person',

                            ],
                            [
                                'label' => 'Jelszó módosítása',
                                'route' => 'admin_v1_account_password_change',
                                'icon' => 'bi bi-calendar',
                            ],
                            [
                                'label' => 'Elérhetőségek szerkesztése',
                                'route' => null,
                                'icon' => 'bi bi-telephone',
                            ],
                            [
                                'label' => 'Szinkronizált e-mail fiókok',
                                'route' => null,
                                'icon' => 'bi bi-envelope',
                            ],
                            [
                                'label' => 'Szinkronizált naptárak',
                                'route' => null,
                                'icon' => 'bi bi-calendar',
                            ],
                        ]
                    ],
                    [
                        'label' => 'Támogatás',
                        'route' => null,
                        'icon' => 'bi bi-person',
                        'children' => [
                            [
                                'label' => 'Felhasználói kézikönyv',
                                'route' => 'admin_v1_support',
                                'icon' => 'bi bi-person-badge',

                            ],
                            [
                                'label' => 'Súgó',
                                'route' => null,
                                'icon' => 'bi bi-question-circle',
                            ],
                            [
                                'label' => 'Hibajegy',
                                'route' => null,
                                'icon' => 'bi bi-bug',
                            ],
                            [
                                'label' => 'Fejlesztői dokumentáció',
                                'route' => null,
                                'icon' => 'bi bi-file-code',
                            ],
                            [
                                'label' => 'Üzemeltetői specifikáció',
                                'route' => null,
                                'icon' => 'bi bi-file-earmark-code',
                            ],
                        ]
                    ],
                ]
            ],
            [
                'roles'    => ['ROLE_SUPERADMIN'],
                'label' => 'superadmin',
                'subtitle' => 'Rendszergazda',
                'icon'  => 'bi bi-stars',
                'route' => null,
                'children' => [
                    [
                        'label' => 'Superadmin',
                        'route' => null,
                        'children' => [
                            [
                                'label' => 'Instance-ek',
                                'icon' => 'bi bi-diagram-3',
                                'route' => 'admin_v1_superadmin_instances',
                            ],
                            [
                                'label' => 'Felhasználók',
                                'icon' => 'bi bi-people',
                                'route' => 'admin_v1_superadmin_users',
                            ],
                            [
                                'label' => 'Számlázási fiókok',
                                'icon' => 'bi bi-receipt',
                                'route' => 'admin_v1_superadmin_billing_profiles',
                            ],
                            [
                                'label' => 'Szolgáltatások',
                                'icon' => 'bi bi-boxes',
                                'route' => 'admin_v1_superadmin_services',
                            ],
                            [
                                'label' => 'Rendelések',
                                'icon' => 'bi bi-cart',
                                'route' => 'admin_v1_superadmin_orders',
                            ],
                            [
                                'label' => 'Sablonok',
                                'icon' => 'bi bi-palette',
                                'route' => 'admin_v1_superadmin_templates_index',
                            ],
                        ]
                    ]
                ]
            ],
        ];
    }
}
