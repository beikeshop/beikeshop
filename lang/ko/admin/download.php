<?php

return [
    // Download related error messages
    'download_failed_empty_content' => '플러그인 파일 다운로드 실패: 파일 내용이 비어있습니다',
    'unrecognized_beikeshop_plugin' => '인식되지 않은 beikeshop 플러그인',
    'get_download_info_failed'      => '플러그인 다운로드 정보를 가져오는데 실패했습니다',
    'missing_download_url'          => '다운로드 정보에 다운로드 URL이 없습니다',
    'oss_download_empty_content'    => 'OSS에서 다운로드된 플러그인 파일이 비어있습니다',
    'download_plugin_failed'        => '플러그인 다운로드 실패',
    'oss_download_failed_status'    => 'OSS 다운로드 실패, HTTP 상태 코드: :status',
    'oss_download_empty_file'       => 'OSS에서 다운로드된 파일 내용이 비어있습니다',
    'oss_download_request_failed'   => 'OSS 다운로드 요청 실패',
    'oss_download_exception'        => 'OSS 다운로드 예외',

    // ZIP file validation errors
    'file_too_short'     => '파일 내용이 너무 짧습니다, 유효한 ZIP 파일이 아닙니다',
    'invalid_zip_format' => '다운로드된 파일이 유효한 ZIP 형식이 아닙니다',
    'file_too_large'     => '플러그인 파일이 너무 큽니다, :sizeMB 제한을 초과했습니다',
    'file_too_small'     => '플러그인 파일이 너무 작습니다, 손상되었을 수 있습니다',

    // Extraction related errors
    'cannot_open_zip'               => '플러그인 ZIP 파일을 열 수 없습니다',
    'plugin_dir_info_incomplete'    => '플러그인 디렉토리 정보가 불완전합니다',
    'extract_to_target_failed'      => '플러그인을 대상 디렉토리로 추출하는데 실패했습니다',
    'extract_plugin_failed'         => '플러그인 추출 실패',
    'source_dir_not_exists'         => '소스 디렉토리가 존재하지 않습니다: :path',
    'rename_plugin_dir_failed'      => '플러그인 디렉토리 이름 변경 실패',
    'create_plugin_dir_failed'      => '플러그인 디렉토리 생성 실패: :path',
    'cannot_open_zip_or_not_exists' => 'ZIP 파일을 열 수 없거나 파일이 존재하지 않습니다!',

    // Validation related errors
    'invalid_plugin_code'              => '유효하지 않은 플러그인 코드 형식',
    'invalid_download_url_format'      => '유효하지 않은 다운로드 URL 형식',
    'cannot_parse_download_url_domain' => '다운로드 URL 도메인을 구문 분석할 수 없습니다',
    'download_url_must_https'          => '다운로드 URL은 HTTPS 프로토콜을 사용해야 합니다',

    // 다운로드 잠금
    'plugin_download_in_progress' => '플러그인 :plugin이 현재 다운로드 중입니다. 나중에 다시 시도해 주세요',
];
