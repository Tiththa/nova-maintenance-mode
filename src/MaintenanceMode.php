<?php

namespace Davidpiesse\NovaMaintenanceMode;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

/**
 * Class to wrap Artisan Maintenance mode logic
 */
class MaintenanceMode
{
    /**
     * Bring Application out of Maintenance Mode
     *
     * @return void
     */
    public static function up()
    {
        Artisan::call('up');
    }

    /**
     * Put Application into Maintenance Mode
     *
     * @param Request $request
     * @return void
     */
    public static function down($request)
    {
        $secret = (string) Str::uuid();

        Artisan::call('down', [
            '--refresh' => 15,
            '--secret' => $secret
        ]);

        return [
            'secret' => $secret
        ];
    }
}
