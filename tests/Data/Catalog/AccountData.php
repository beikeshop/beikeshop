<?php

namespace Tests\Data\Catalog;

class AccountData
{
    public const Add_Address = [
        'add_name'        => 'test', //add_name
        'add_province'    => 'v11012010086', //选择国家
        'add_code'        => '643203', //add_code
        'add_address1'    => 'test1', //add_address1
        'add_address2'    => 'test2', //add_address2
    ];

    public const User_Edit = [
        'upload_images' => '/../../data/Images/Headpicture/Headpicture.jpeg', //上传头像
        'user_name'     => 'Admin', //修改名字
        'user_email'    => 'Admin@163.com', //修改emial
    ];
}
