<?php

namespace App\Http\Controllers\API\System;

use App\AppSetting;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

class AppSettingController extends BaseController
{

    /**
     * Get all app settings
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return $this->sendResponse([
            'settings' => AppSetting::all()
        ],
            'Successfully retrieved all app settings');
    }

}
