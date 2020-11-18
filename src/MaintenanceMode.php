<?php

namespace Davidpiesse\NovaMaintenanceMode;

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
        @unlink(storage_path('framework/down'));
        return;
    }

    /**
     * Put Application into Maintenance Mode
     *
     * @param Request $request
     * @return void
     */
    public static function down($request)
    {
        $retry = $request->get('retry');

        $retry_seconds = is_numeric($retry) && $retry > 0 ? (int) $retry : null;

        $payload = [
            'time' => now()->timestamp,
            'retry' => $retry_seconds,
            'secret' => Str::uuid(),
        ];

        file_put_contents(
            storage_path('framework/down'),
            json_encode($payload, JSON_PRETTY_PRINT)
        );

        return $payload;
    }
}
