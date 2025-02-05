<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public static $permissions = [
        // Contenuti
        'crt-cnt' => 'create-content',
        'edt-cnt' => 'edit-content',
        'dlt-cnt' => 'delete-content',
        'pub-cnt' => 'publish-content',
        
        // Layout
        'crt-lyt' => 'create-layout',
        'edt-lyt' => 'edit-layout',
        'dlt-lyt' => 'delete-layout',
        'set-lyt' => 'set-layout',
        
        // Temi
        'crt-thm' => 'create-theme',
        'edt-thm' => 'edit-theme',
        'dlt-thm' => 'delete-theme',
        'set-thm' => 'set-theme',
        
        // Utenti e ruoli
        'mng-usr' => 'manage-users',
        'mng-rol' => 'manage-roles',
        
        // Impostazioni e sicurezza
        'mng-settings' => 'manage-site-settings',
        'mng-sec' => 'manage-security',
        'mng-media' => 'manage-media',
        'view-rep' => 'view-reports',
        
        // Moderazione commenti
        'mng-com' => 'manage-comments',
        'mod-com' => 'moderate-comments',
        'del-com' => 'delete-comments',
        
        // Dashboard e statistiche
        'view-dsh' => 'view-dashboard',
        'view-stats' => 'view-statistics',
        'mng-wdgt' => 'manage-widgets',
        
        // Backup e sicurezza avanzata
        'crt-bkp' => 'create-backup',
        'rstr-bkp' => 'restore-backup',
        'del-bkp' => 'delete-backup',
        'mng-firewall' => 'manage-firewall',
        'scan-vuln' => 'scan-vulnerabilities',
        
        // SEO
        'mng-seo' => 'manage-seo',
        'crt-sitemap' => 'create-sitemap',
        'edt-meta' => 'edit-metadata',
        'anl-seo' => 'analyze-seo',
        
        // API e integrazioni
        'mng-api' => 'manage-api',
        'mng-webhooks' => 'manage-webhooks',
        'view-api-log' => 'view-api-logs',
        
        // Categorie e tag
        'crt-cat' => 'create-category',
        'edt-cat' => 'edit-category',
        'dlt-cat' => 'delete-category',
        'crt-tag' => 'create-tag',
        'edt-tag' => 'edit-tag',
        'dlt-tag' => 'delete-tag',
        
        // Email e notifiche
        'mng-mlc' => 'manage-mailing-list',
        'snd-mlc' => 'send-mailing-list',
        'edt-tpl' => 'edit-email-template',
        'mng-notify' => 'manage-notifications',
        
        // E-commerce
        'crt-prod' => 'create-product',
        'edt-prod' => 'edit-product',
        'dlt-prod' => 'delete-product',
        'mng-orders' => 'manage-orders',
        'view-orders' => 'view-orders',
        'mng-coupon' => 'manage-coupons',
        
        // Moderazione avanzata
        'mng-flags' => 'manage-flags',
        'ban-user' => 'ban-user',
        'mng-reviews' => 'manage-reviews',
        
        // Configurazioni avanzate
        'mng-cust' => 'manage-customization',
        'mng-integrations' => 'manage-integrations',
        'edt-env' => 'edit-environment',
        
        // Supporto utente
        'mng-support' => 'manage-support',
        'view-tickets' => 'view-support-tickets',
        'reply-tickets' => 'reply-to-support-tickets'
    ];
    

    public function run()
    {
        foreach (self::$permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}



