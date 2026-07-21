<?php

/**
 * 中文语言包
 */

return [
    // 菜单和权限
    'menu_title'         => '图片路径迁移工具',
    'permission_group'   => '图片路径迁移',
    'permission_access'  => '访问迁移工具',
    'permission_scan'    => '扫描数据库',
    'permission_preview' => '预览迁移',
    'permission_execute' => '执行迁移',

    // 页面标题
    'page_title'    => 'BeikeShop 2.0 图片路径迁移工具',
    'page_subtitle' => '自动迁移图片路径从 catalog/ 到 image/catalog/',

    // 警告提示
    'warning_title'        => '⚠️ 重要提示：使用前必读',
    'warning_scope'        => '适用范围：本插件仅适用于从 BeikeShop 1.x 版本升级到 2.x 版本的用户，全新安装的用户无需使用。',
    'warning_reason_title' => '为什么需要使用本工具？',
    'warning_reason_desc'  => '在 BeikeShop 2.0 版本中，图片目录路径由 public/catalog 调整为 public/image/catalog。升级后，您会发现图片库中的文件不见了。',
    'warning_step_title'   => '使用步骤：',
    'warning_step_1'       => '1. 【必须先做】将原有目录 public/catalog 整个文件夹移动到 public/image/ 下，并替换已有的 catalog 文件夹',
    'warning_step_2'       => '2. 【必须备份】使用本工具前，请务必备份数据库！',
    'warning_step_3'       => '3. 【建议测试】强烈建议先在测试环境验证后再在生产环境使用',
    'warning_step_4'       => '4. 【执行迁移】移动完图片目录后，再执行本插件的图片数据路径迁移',
    'warning_step_5'       => '5. 【大数据量提示】如果商品、图片等数据量很大，可能需要重复执行“扫描数据库 -> 执行迁移”，直到扫描提示没有需要迁移的数据',
    'warning_consequence'  => '⚠️ 注意：迁移操作不可逆，迁移失败可能导致数据损坏，请务必做好备份！',

    // 步骤
    'step_scan'    => '扫描数据库',
    'step_preview' => '预览迁移',
    'step_execute' => '执行迁移',
    'step_report'  => '查看报告',

    // 扫描
    'scan_button'        => '开始扫描',
    'scan_success'       => '扫描完成',
    'scan_failed'        => '扫描失败',
    'scan_info'          => '点击"开始扫描"按钮，系统将自动识别需要迁移的表和字段',
    'scan_found_tables'  => '发现表数',
    'scan_found_fields'  => '发现字段数',
    'scan_found_records' => '匹配记录数',
    'scan_no_data'       => '未发现需要迁移的数据',

    // 预览
    'preview_button'   => '预览迁移',
    'preview_success'  => '预览加载成功',
    'preview_failed'   => '预览加载失败',
    'preview_table'    => '表名',
    'preview_field'    => '字段',
    'preview_old_path' => '原路径',
    'preview_new_path' => '新路径',
    'preview_sample'   => '样本数据（最多显示 10 条）',

    // 执行
    'execute_button'          => '执行迁移',
    'execute_confirm_title'   => '确认执行迁移',
    'execute_confirm_message' => '您确定要执行迁移吗？请再次确认您已经备份了数据库！',
    'execute_confirm_yes'     => '确认执行',
    'execute_confirm_no'      => '取消',
    'execute_success'         => '迁移执行成功',
    'execute_failed'          => '迁移执行失败',
    'execute_in_progress'     => '迁移正在进行中，请勿关闭页面',

    // 报告
    'report_title'           => '迁移报告',
    'report_id'              => '报告 ID',
    'report_start_time'      => '开始时间',
    'report_end_time'        => '结束时间',
    'report_duration'        => '总耗时',
    'report_total_processed' => '处理记录',
    'report_total_updated'   => '更新记录',
    'report_total_skipped'   => '跳过记录',
    'report_total_failed'    => '失败记录',
    'report_export_json'     => '导出 JSON',
    'report_export_text'     => '导出文本',
    'report_not_found'       => '报告未找到',

    // 验证
    'verify_button'            => '验证结果',
    'verify_success'           => '验证通过：所有路径已成功迁移',
    'verify_incomplete'        => '验证未通过：仍有路径需要迁移',
    'verify_failed'            => '验证失败',
    'verify_remaining_records' => '剩余记录数',

    // 表格
    'table_name'    => '表名',
    'field_name'    => '字段名',
    'field_type'    => '类型',
    'match_count'   => '匹配数',
    'updated_count' => '更新数',
    'skipped_count' => '跳过数',
    'failed_count'  => '失败数',

    // 按钮
    'btn_next'    => '下一步',
    'btn_prev'    => '上一步',
    'btn_back'    => '返回',
    'btn_refresh' => '刷新',
    'btn_export'  => '导出',

    // 消息
    'no_fields_selected' => '请选择要迁移的字段',
    'export_failed'      => '导出失败',
    'loading'            => '加载中...',
    'processing'         => '处理中...',

    // 类型
    'type_plain'      => '纯文本',
    'type_json'       => 'JSON',
    'type_serialized' => '序列化',

    // Vue 组件消息
    'scan_no_data_found'      => '未发现需要迁移的数据',
    'confirm_execute_title'   => '确认执行迁移',
    'confirm_execute_message' => '您确定要执行迁移吗？请再次确认您已经备份了数据库！',
    'confirm_button'          => '确认执行',
    'cancel_button'           => '取消',
    'no_report_to_export'     => '没有可导出的报告',
];
