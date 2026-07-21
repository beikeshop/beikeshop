<?php

/**
 * mail_log.php
 *
 * @copyright  2025 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2025-07-22
 */

return [
    'mail_logs' => '메일 로그',
    'mail_log'  => '메일 로그',
    'list'      => '메일 로그 목록',
    'detail'    => '메일 상세',

    // Fields
    'to_email'      => '수신자 이메일',
    'to_name'       => '수신자 이름',
    'from_email'    => '발신자 이메일',
    'from_name'     => '발신자 이름',
    'subject'       => '제목',
    'content'       => '내용',
    'mail_type'     => '메일 유형',
    'transport'     => '전송 방법',
    'status'        => '상태',
    'error_message' => '오류 메시지',
    'sent_at'       => '발송 시간',
    'created_at'    => '생성 시간',
    'updated_at'    => '수정 시간',

    // Status
    'status_pending' => '대기 중',
    'status_sent'    => '발송됨',
    'status_failed'  => '발송 실패',

    // Mail Types
    'type_customer_registration' => '고객 등록',
    'type_customer_forgotten'    => '비밀번호 찾기',
    'type_order_new'             => '새 주문',
    'type_order_update'          => '주문 업데이트',
    'type_rma_new'               => '반품 요청',
    'type_admin_forgotten'       => '관리자 비밀번호 찾기',
    'type_other'                 => '기타',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => '로그',
    'transport_array'     => '배열',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => '알 수 없음',

    // Actions
    'view_detail'  => '상세 보기',
    'resend'       => '재발송',
    'delete'       => '삭제',
    'batch_delete' => '일괄 삭제',
    'cleanup'      => '오래된 기록 정리',
    'statistics'   => '통계',
    'back_to_list' => '목록으로 돌아가기',

    // Filters
    'filter_status'     => '상태',
    'filter_mail_type'  => '메일 유형',
    'filter_transport'  => '전송 방법',
    'filter_recipient'  => '수신자',
    'filter_start_date' => '시작 날짜',
    'filter_end_date'   => '종료 날짜',
    'filter_submit'     => '필터',
    'filter_reset'      => '재설정',
    'all_status'        => '모든 상태',
    'all_types'         => '모든 유형',
    'all_transports'    => '모든 전송 방법',

    // Statistics
    'total_count'   => '총계',
    'sent_count'    => '발송됨',
    'failed_count'  => '발송 실패',
    'pending_count' => '대기 중',
    'recent_count'  => '최근',

    // Messages
    'no_records'             => '메일 기록을 찾을 수 없습니다',
    'delete_confirm'         => '이 기록을 삭제하시겠습니까?',
    'batch_delete_confirm'   => '선택한 기록을 삭제하시겠습니까?',
    'resend_confirm'         => '이 메일을 재발송하시겠습니까?',
    'cleanup_confirm'        => '보관할 일수를 입력하세요 (그보다 오래된 기록은 삭제됩니다):',
    'cleanup_title'          => '오래된 기록 정리',
    'delete_success'         => '삭제 성공',
    'batch_delete_success'   => '일괄 삭제 성공',
    'resend_success'         => '재발송 성공',
    'cleanup_success'        => '정리 성공',
    'operation_failed'       => '작업 실패',
    'delete_failed'          => '삭제 실패',
    'resend_failed'          => '재발송 실패',
    'cleanup_failed'         => '정리 실패',
    'please_select_records'  => '작업할 기록을 선택하세요',
    'resend_not_available'   => '이 메일은 이미 성공적으로 발송되었습니다. 재발송할 필요가 없습니다',
    'resend_not_implemented' => '재발송 기능이 아직 구현되지 않았습니다. 개발자에게 문의하세요',
    'statistics_developing'  => '통계 기능 개발 중...',
    'total_records'          => '총 :count개 기록',
    'recipient_placeholder'  => '이메일 주소',

    // Controller messages
    'select_records_to_delete'       => '삭제할 기록을 선택하세요',
    'delete_success_message'         => '삭제 성공',
    'delete_failed_message'          => '삭제 실패: :error',
    'select_records_to_operate'      => '작업할 기록을 선택하세요',
    'batch_delete_success_message'   => '일괄 삭제 성공',
    'batch_mark_sent_success'        => '일괄 발송됨 표시 성공',
    'batch_mark_failed_success'      => '일괄 실패 표시 성공',
    'unsupported_operation'          => '지원되지 않는 작업',
    'operation_failed_message'       => '작업 실패: :error',
    'days_must_greater_than_zero'    => '일수는 0보다 커야 합니다',
    'cleanup_success_message'        => ':count개 기록을 성공적으로 정리했습니다',
    'cleanup_failed_message'         => '정리 실패: :error',
    'get_statistics_success'         => '통계 가져오기 성공',
    'get_statistics_failed'          => '통계 가져오기 실패: :error',
    'mail_already_sent'              => '이 메일은 이미 성공적으로 발송되었습니다. 재발송할 필요가 없습니다',
    'resend_not_implemented_message' => '재발송 기능이 아직 구현되지 않았습니다. 개발자에게 문의하세요',
    'resend_failed_message'          => '재발송 실패: :error',

    // Model translations
    'unknown_status'    => '알 수 없는 상태',
    'unknown_type'      => '알 수 없는 유형',
    'unknown_transport' => '알 수 없는 전송 방법',

    // Detail Page
    'basic_info'        => '기본 정보',
    'mail_content'      => '메일 내용',
    'attachments'       => '첨부 파일',
    'headers'           => '메일 헤더',
    'operation_history' => '작업 기록',
    'view_headers'      => '상세 헤더 보기',
    'mail_created'      => '메일 생성',
    'mail_sent'         => '메일 발송',
    'mail_failed'       => '발송 실패',
    'unknown_file'      => '알 수 없는 파일',
    'system_default'    => '시스템 기본값',
    'not_sent'          => '발송되지 않음',

    // Permissions
    'permission_index'  => '메일 로그 보기',
    'permission_show'   => '메일 상세 보기',
    'permission_delete' => '메일 로그 삭제',
    'permission_update' => '메일 로그 업데이트',

    // Permission Names (for permission system)
    'mail_logs_index'  => '메일 로그 보기',
    'mail_logs_show'   => '메일 상세 보기',
    'mail_logs_delete' => '메일 로그 삭제',
    'mail_logs_update' => '메일 로그 업데이트',

    // View translations
    'confirm_resend' => '이 메일을 재발송하시겠습니까?',
    'confirm_delete' => '이 기록을 삭제하시겠습니까?',
    'text_hint'      => '힌트',
];
