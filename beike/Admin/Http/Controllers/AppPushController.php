<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\SettingRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Beike\Admin\Services\UniPushService;

class AppPushController extends Controller
{
    public function index(Request $request): View
    {
        $data = [];

        return view('admin::pages.app_push.index', $data);
    }

    public function push(Request $request)
    {
        $message = $request->all();

        return UniPushService::getInstance()->pushOrderStatus($message);
    }
}
