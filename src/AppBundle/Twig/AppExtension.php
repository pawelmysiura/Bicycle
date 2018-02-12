<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.02.18
 * Time: 17:41
 */

namespace AppBundle\Twig;


class AppExtension extends \Twig_Extension
{

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
                ])
        ];
    }

    public function printPanelMenu(\Twig_Environment $environment)
    {
        $panelMenu = [
            'Ustawienia uzytkownika' => 'fos_user_profile_show',
            'Wyloguj' => 'fos_user_security_logout',
            'Co nowego' => 'panel',
            'Ścieżki' => 'panel_maps',
            'Dodaj ścieżkę' => 'panel_create_map',
            'Ulubione' => 'panel_favourite_maps'
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
            'Admin Panel' => 'admin',
            'Posts' => 'admin_posts',
            'Maps' => 'admin_maps',
            'Post categories' => 'admin_categories',
            'Post tags' => 'admin_tags',
            'Users' => 'admin_users'
        ];
        try {
            return $environment->render('template/panelMenu.html.twig', [
                'menu' => $adminMenu
            ]);
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }
}