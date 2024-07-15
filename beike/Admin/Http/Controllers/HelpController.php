<?php

namespace Beike\Admin\Http\Controllers;

class HelpController extends Controller
{
    public function index()
    {
        $data['top_links'] = [
            [
                'title' => trans('admin/help.mall_config'),
                'icon'  => '<i class="iconfont">&#xe6b6;</i>',
                'url'   => 'https://docs.beikeshop.com/config/home.html',
            ],
            [
                'title' => trans('admin/help.dev_doc'),
                'icon'  => '<i class="iconfont">&#xe68d;</i>',
                'url'   => 'https://docs.beikeshop.com/dev/1_quick_start.html',
            ],
            [
                'title' => trans('admin/help.technical_services'),
                'icon'  => '<i class="iconfont">&#xe72e;</i>',
                'url'   => 'https://beikeshop.cn/service',
            ],
            [
                'title' => trans('admin/help.system_upgrade'),
                'icon'  => '<i class="bi bi-cloud-download"></i>',
                'url'   => 'https://beikeshop.cn/download',
            ],
        ];

        $data['links'] = [
            [
                'title'       => trans('admin/help.upgrade_services'),
                'description' => trans('admin/help.upgrade_services_text'),
                'url'         => 'https://beikeshop.cn/vip/subscription?type=tab-upgrade',
            ],
            [
                'title'       => trans('admin/help.install_ssl'),
                'description' => trans('admin/help.install_ssl_text'),
                'url'         => 'https://beikeshop.cn/vip/subscription?type=tab-ssl',
            ],
            [
                'title'       => trans('admin/help.work_order_service'),
                'description' => trans('admin/help.work_order_service_text_1'),
                'url'         => 'https://beikeshop.cn/account/tickets',
            ],
            [
                'title'       => trans('admin/help.auxiliary_station'),
                'description' => trans('admin/help.auxiliary_station_text_1'),
                'url'         => 'https://beikeshop.cn/vip/subscription?type=tab-package',
            ],
            [
                'title'       => trans('admin/help.cloud_hosting'),
                'description' => trans('admin/help.cloud_hosting_text_1'),
                'url'         => 'https://beikeshop.cn/vip/subscription?type=tab-hosting',
            ],
            [
                'title'       => trans('admin/help.vip_service'),
                'description' => trans('admin/help.vip_service_sub_title'),
                'url'         => 'https://wpa.qq.com/msgrd?v=3&uin=9358972&site=qq&menu=yes',
            ],
        ];

        return view('admin::pages.help.index', $data);
    }
}
