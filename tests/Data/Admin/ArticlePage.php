<?php

namespace Tests\Data\Admin;

class ArticlePage
{
    public const  Left = [
        'url'             => '/Admin/pages',
        'mg_article'      => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(1)', //文章管理
        'catalog_article' => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(2)', //文章分类
    ];

    public const Common = [
        'add_btn'            => '#content > div.container-fluid.p-0 > div.content-info > div > div > div.d-flex.justify-content-between.mb-4 > a', //添加按钮
        'edit_btn'           => '#content > div.container-fluid.p-0 > div.content-info > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > a', //编辑按钮
        'del_btn'            => '#content > div.container-fluid.p-0 > div.content-info > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > button', //删除按钮
        'cata_title_Text'    => '#app > div > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(2) > div > a', //获取第一行文章分类标题
        'artice_title_Text'  => '#content > div.container-fluid.p-0 > div.content-info > div > div > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(2) > div > a', //获取第一行文章分类标题
        'save_btn'           => '#content > div.container-fluid.p-0 > div.page-bottom-btns > button.w-min-100.btn.btn-primary.submit-form.btn-lg', //删除-弹窗确定按钮
        'sure_btn'           => '#layui-layer1 > div.layui-layer-btn.layui-layer-btn- > a.layui-layer-btn1', //删除-弹窗确定按钮(删除分类)
        'del_sure_btn'       => '.layui-layer-btn1', //删除-弹窗确定按钮(删除文章)

    ];

    public const Top = [
        'Cn'  => '#tab-content > ul > li:nth-child(1) > button', //中文基本信息
        'En'  => '#tab-content > ul > li:nth-child(2) > button', //英文基本信息
    ];

    public const Cn_info = [
        'title'    => '#tab-zh_cn > div:nth-child(1) > div > input', //标题
        'summary'  => '#tab-zh_cn > div:nth-child(2) > div > div > textarea', //摘要
        'content'  => '#tinymce > p', //内容

    ];

    public const En_info = [
        'title'    => '#tab-en > div:nth-child(1) > div > input', //标题
        'summary'  => '#tab-en > div:nth-child(2) > div > div > textarea', //摘要
        'content'  => '#tinymce > p', //内容

    ];
}
