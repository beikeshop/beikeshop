<?php

/**
 * English Language Pack
 */

return [
    // Menu and Permissions
    'menu_title'         => 'Image Path Migration Tool',
    'permission_group'   => 'Image Path Migration',
    'permission_access'  => 'Access Migration Tool',
    'permission_scan'    => 'Scan Database',
    'permission_preview' => 'Preview Migration',
    'permission_execute' => 'Execute Migration',

    // Page Titles
    'page_title'    => 'BeikeShop 2.0 Image Path Migration Tool',
    'page_subtitle' => 'Automatically migrate image paths from catalog/ to image/catalog/',

    // Warning Messages
    'warning_title'        => '⚠️ Important: Please Read Before Use',
    'warning_scope'        => 'Scope: This plugin is only for users upgrading from BeikeShop 1.x to 2.x. Fresh installations do not need this tool.',
    'warning_reason_title' => 'Why do you need this tool?',
    'warning_reason_desc'  => 'In BeikeShop 2.0, the image directory path has been changed from public/catalog to public/image/catalog. After upgrading, you will find that files in the image library are missing.',
    'warning_step_title'   => 'Usage Steps:',
    'warning_step_1'       => '1. [Must Do First] Move the entire public/catalog folder to public/image/ and replace the existing catalog folder',
    'warning_step_2'       => '2. [Must Backup] Please backup your database before using this tool!',
    'warning_step_3'       => '3. [Recommended] Strongly recommend testing in a staging environment first',
    'warning_step_4'       => '4. [Execute Migration] After moving the image directory, run this plugin to migrate image path data',
    'warning_step_5'       => '5. [Large Data Notice] If products, images, or other data volumes are very large, you may need to repeat "Scan Database -> Execute Migration" until the scan reports no data needs migration',
    'warning_consequence'  => '⚠️ Warning: Migration is irreversible. Failure may cause data corruption. Please ensure you have a backup!',

    // Steps
    'step_scan'    => 'Scan Database',
    'step_preview' => 'Preview Migration',
    'step_execute' => 'Execute Migration',
    'step_report'  => 'View Report',

    // Scan
    'scan_button'        => 'Start Scan',
    'scan_success'       => 'Scan completed',
    'scan_failed'        => 'Scan failed',
    'scan_info'          => 'Click "Start Scan" to automatically identify tables and fields that need migration',
    'scan_found_tables'  => 'Tables Found',
    'scan_found_fields'  => 'Fields Found',
    'scan_found_records' => 'Records Matched',
    'scan_no_data'       => 'No data found that needs migration',

    // Preview
    'preview_button'   => 'Preview Migration',
    'preview_success'  => 'Preview loaded successfully',
    'preview_failed'   => 'Preview loading failed',
    'preview_table'    => 'Table',
    'preview_field'    => 'Field',
    'preview_old_path' => 'Old Path',
    'preview_new_path' => 'New Path',
    'preview_sample'   => 'Sample Data (Max 10 records)',

    // Execute
    'execute_button'          => 'Execute Migration',
    'execute_confirm_title'   => 'Confirm Migration',
    'execute_confirm_message' => 'Are you sure you want to execute the migration? Please confirm you have backed up your database!',
    'execute_confirm_yes'     => 'Confirm',
    'execute_confirm_no'      => 'Cancel',
    'execute_success'         => 'Migration executed successfully',
    'execute_failed'          => 'Migration execution failed',
    'execute_in_progress'     => 'Migration in progress, please do not close this page',

    // Report
    'report_title'           => 'Migration Report',
    'report_id'              => 'Report ID',
    'report_start_time'      => 'Start Time',
    'report_end_time'        => 'End Time',
    'report_duration'        => 'Duration',
    'report_total_processed' => 'Processed',
    'report_total_updated'   => 'Updated',
    'report_total_skipped'   => 'Skipped',
    'report_total_failed'    => 'Failed',
    'report_export_json'     => 'Export JSON',
    'report_export_text'     => 'Export Text',
    'report_not_found'       => 'Report not found',

    // Verify
    'verify_button'            => 'Verify Results',
    'verify_success'           => 'Verification passed: All paths migrated successfully',
    'verify_incomplete'        => 'Verification incomplete: Some paths still need migration',
    'verify_failed'            => 'Verification failed',
    'verify_remaining_records' => 'Remaining Records',

    // Table
    'table_name'    => 'Table',
    'field_name'    => 'Field',
    'field_type'    => 'Type',
    'match_count'   => 'Matches',
    'updated_count' => 'Updated',
    'skipped_count' => 'Skipped',
    'failed_count'  => 'Failed',

    // Buttons
    'btn_next'    => 'Next',
    'btn_prev'    => 'Previous',
    'btn_back'    => 'Back',
    'btn_refresh' => 'Refresh',
    'btn_export'  => 'Export',

    // Messages
    'no_fields_selected' => 'Please select fields to migrate',
    'export_failed'      => 'Export failed',
    'loading'            => 'Loading...',
    'processing'         => 'Processing...',

    // Types
    'type_plain'      => 'Plain Text',
    'type_json'       => 'JSON',
    'type_serialized' => 'Serialized',

    // Vue Component Messages
    'scan_no_data_found'      => 'No data found that needs migration',
    'confirm_execute_title'   => 'Confirm Migration',
    'confirm_execute_message' => 'Are you sure you want to execute the migration? Please confirm you have backed up your database!',
    'confirm_button'          => 'Confirm',
    'cancel_button'           => 'Cancel',
    'no_report_to_export'     => 'No report to export',
];
