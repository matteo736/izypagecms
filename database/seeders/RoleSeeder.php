<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Database\Seeders\PermissionSeeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Recupero dei permessi dal PermissionSeeder
        $permissions = PermissionSeeder::$permissions;

        // Mappa dei ruoli con permessi
        $rolesPermissions = [
            'admin' => array_values($permissions), // Admin ha tutti i permessi
            'editor' => [
                //Content
                $permissions['crt-cnt'],
                $permissions['dlt-cnt'],
                $permissions['edt-cnt'],
                $permissions['pub-cnt'],

                //Layout
                $permissions['crt-lyt'],
                $permissions['dlt-lyt'],
                $permissions['edt-lyt'],
                $permissions['set-lyt'],

                //Theme
                $permissions['crt-thm'],
                $permissions['dlt-thm'],
                $permissions['edt-thm'],
                $permissions['set-thm'],

                //Categories & Tags
                $permissions['crt-cat'],
                $permissions['dlt-cat'],
                $permissions['edt-cat'],
                $permissions['crt-tag'],
                $permissions['dlt-tag'],
                $permissions['edt-tag'],

                //Media
                $permissions['mng-media'],

                //Reports
                $permissions['view-rep'],

                //SEO
                $permissions['mng-seo'],
                $permissions['edt-meta']
            ],
            'author' => [
                //Content
                $permissions['crt-cnt'],
                $permissions['edt-cnt'],
                $permissions['dlt-cnt'],
                $permissions['pub-cnt'],

                //Media
                $permissions['mng-media'],

                //Dashboard
                $permissions['view-dsh'],
            ],
            'contributor' => [
                //Content
                $permissions['crt-cnt'],
                $permissions['edt-cnt'],

                //Media
                $permissions['mng-media'],

                //Dashboard
                $permissions['view-dsh'],
            ],
            'subscriber' => [
                //Notifiche
                $permissions['mng-notify'],
            ] // Nessun permesso assegnato
        ];

        // Creazione dei ruoli e assegnazione dei permessi
        foreach ($rolesPermissions as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
