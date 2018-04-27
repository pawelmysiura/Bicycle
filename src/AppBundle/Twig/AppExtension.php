<?php

namespace AppBundle\Twig;

use Symfony\Component\Translation\Translator;
class AppExtension extends \Twig_Extension
{

    /**
     * @var Translator
     */
    private $translator;


    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('print_panel_menu', [
                $this, 'printPanelMenu'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html']
                    ]),
            new \Twig_SimpleFunction('print_admin_menu', [
                $this, 'printAdminMenu'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html']
                ]),
            new \Twig_SimpleFunction('print_front_menu', [
                $this, 'printFrontMenu'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html']
                ]),
            new \Twig_SimpleFunction('print_front_footer_menu', [
                $this, 'printFrontFooterMenu'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html']
                ]),
            new \Twig_SimpleFunction('print_panel_footer_menu', [
                $this, 'printPanelFooterMenu'],
                [
                    'needs_environment' => true,
                    'is_safe' => ['html']
                ])
        ];
    }

    public function printPanelMenu(\Twig_Environment $environment)
    {
        $panelMenu = [
            $this->translator->trans('panel.menu.user_properties',[] , 'controller') => 'fos_user_profile_show',
            $this->translator->trans('panel.menu.logout',[] , 'controller') => 'fos_user_security_logout',
            $this->translator->trans('panel.menu.post',[] , 'controller') => 'panel',
            $this->translator->trans('panel.menu.bike_paths',[] , 'controller') => 'panel_maps',
            $this->translator->trans('panel.menu.add_path',[] , 'controller') => 'panel_create_map',
            $this->translator->trans('panel.menu.favourite',[] , 'controller') => 'panel_favourite_maps'
        ];
        try {
            return $environment->render('template/panelMenu.html.twig', [
                'menu' => $panelMenu
            ]);
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function printAdminMenu(\Twig_Environment $environment)
    {
        $adminMenu = [
            $this->translator->trans('admin.title.admin_panel',[] , 'controller') => 'admin',
            $this->translator->trans('admin.title.posts',[] , 'controller') => 'admin_posts',
            $this->translator->trans('admin.title.bike_paths',[] , 'controller') => 'admin_maps',
            $this->translator->trans('admin.title.post_categories',[] , 'controller') => 'admin_categories',
            $this->translator->trans('admin.title.post_tags',[] , 'controller') => 'admin_tags',
            $this->translator->trans('admin.title.users',[] , 'controller') => 'admin_users'
        ];
        try {
            return $environment->render('template/adminMenu.html.twig', [
                'menu' => $adminMenu
            ]);
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function printFrontMenu(\Twig_Environment $environment)
    {
        $adminMenu = [
            $this->translator->trans('front.title.index',[] , 'controller') => 'front_index',
            $this->translator->trans('front.title.about',[] , 'controller') => 'front_about',
            $this->translator->trans('front.title.contact',[] , 'controller') => 'front_contact'
            ];
        try {
            return $environment->render('template/frontMenu.html.twig', [
                'menu' => $adminMenu
            ]);
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function printFrontFooterMenu(\Twig_Environment $environment)
    {
        $menu = [
            $this->translator->trans('front.title.index',[] , 'controller') => 'front_index',
            $this->translator->trans('front.title.about',[] , 'controller') => 'front_about',
            $this->translator->trans('front.title.contact',[] , 'controller') => 'front_contact'
        ];
        try {
            return $environment->render('template/frontFooterMenu.html.twig', [
                'menu' => $menu
            ]);
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

    public function printPanelFooterMenu(\Twig_Environment $environment)
    {
        $menu = [
            $this->translator->trans('panel.menu.post',[] , 'controller') => 'panel',
            $this->translator->trans('panel.menu.bike_paths',[] , 'controller') => 'panel_maps',
            $this->translator->trans('panel.menu.favourite',[] , 'controller') => 'panel_favourite_maps'
        ];
        try {
            return $environment->render('template/frontFooterMenu.html.twig', [
                'menu' => $menu
            ]);
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }

}