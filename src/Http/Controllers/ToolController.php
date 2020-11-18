<?php

namespace Davidpiesse\NovaMaintenanceMode\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\Controller;
use Davidpiesse\NovaMaintenanceMode\File;
use Davidpiesse\NovaMaintenanceMode\MaintenanceMode;

class ToolController extends Controller
{
    public function up(Request $request)
    {
        MaintenanceMode::up();

        return response([
            'message' =>__('Application is now live'),
            'currentlyInMaintenance' => app()->isDownForMaintenance(),
        ], 200);
    }

    public function down(Request $request)
    {
        $result = MaintenanceMode::down($request);

        Cache::set('maintenance_mode_secret', $result['secret']);

        return response([
            'message' =>__('Application is now in maintenance mode'),
            'currentlyInMaintenance' => app()->isDownForMaintenance(),
            'secret' => $result['secret'],
        ], 200);
    }
}
