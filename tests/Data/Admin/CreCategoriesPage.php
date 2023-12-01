<?php

namespace Tests\Data\Admin;

class CreCategoriesPage
{
    public const Cate_Page = [
        'ch_name'        => 'descriptions[zh_cn][name]',
        'en_name'        => 'descriptions[en][name]',
        'ch_content'     => 'descriptions[zh_cn][content]',
        'en_content'     => 'descriptions[en][content]',
        'parent_cate'    => 'parent_id',
        'ch_title'       => 'descriptions[zh_cn][meta_title]',
        'en_title'       => 'descriptions[en][meta_title]',
        'ch_keywords'    => 'descriptions[zh_cn][meta_keywords]',
        'en_keywords'    => 'descriptions[en][meta_keywords]',
        'ch_description' => 'descriptions[zh_cn][meta_description]',
        'en_description' => 'descriptions[en][meta_description]',
        'status_enable'  => '#active-1',
        'status_disable' => '#active-0',
        'save_btn'       => '.btn.btn-primary.mt-3',

    ];
}
