<?php

/**
 * address.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-22 18:18:59
 * @modified   2022-08-22 18:18:59
 */

return [
    'order_success'           => '주문제출 성공',
    'order_update'            => '주문 상태 업데이트',
    'order_success_info'      => '당신의 주문은 성공적으로 제출되었으며 아래는 주문 내역입니다',
    'order_success'           => '귀하의 주문이 성공적으로 제출되었습니다',
    'not_oneself'             => '본인이 아닌 작업은 무시할 수 있습니다.',
    'customer_name'           => '존경하는:name 사용자, 안녕하세요!',
    'sincerely'               => '이치',
    'team'                    => '팀',
    'order_update_status'     => '주문: number 상태 업데이트',
    'welcome_register'        => '등록을 환영합니다',
    'new_register'            => '신규 사용자 등록',
    'customer_name_line'      => '사용자 이름',
    'register_end'            => '등록을 마쳤으니 아래 버튼을 클릭하여 쇼핑몰로 돌아가십시오.',
    'btn_buy_now'             => '즉시 구매',
    'retrieve_password_title' => '비밀번호 찾기',
    'retrieve_password_text'  => '비밀번호를 찾고 있습니다. 아래 버튼을 클릭하여 작업을 완료하십시오.',
    'retrieve_password_btn'   => '비밀번호 재설정하려면 여기를 클릭하십시오.',
    'rma_success'             => '애프터 서비스 접수가 성공적으로 제출되었습니다',
    'rma_success_admin'       => '새로운 애프터 서비스 주문이 있습니다',
    'admin_name'              => '존경하는 관리자 사용자님, 안녕하세요',
    'rma_product'             => '상품 정보',
    'new_order'               => '새 주문이 있습니다',
    'order_update_admin'      => '주문 번호 :number 상태가 업데이트되었습니다',

    // SendCloud 메일 전송 오류 메시지
    'sendcloud_invalid_message_type'         => '메시지는 Symfony\Component\Mime\Email의 인스턴스여야 합니다',
    'sendcloud_send_failed'                  => 'SendCloud 메일 전송에 실패했습니다',
    'sendcloud_from_address_empty'           => 'SendCloud 발신자 주소는 비어있을 수 없습니다',
    'sendcloud_from_address_invalid'         => 'SendCloud 발신자 주소 형식이 잘못되었습니다: :address',
    'sendcloud_example_domain_not_supported' => 'SendCloud는 예제 도메인 주소를 지원하지 않습니다: :address. 백엔드 설정에서 실제 발신자 이메일 주소를 구성하십시오.',
    'sendcloud_to_address_empty'             => 'SendCloud 수신자 주소는 비어있을 수 없습니다',
    'sendcloud_to_address_invalid'           => 'SendCloud 수신자 주소 형식이 잘못되었습니다: :address',
    'sendcloud_api_call_failed'              => 'SendCloud API 호출에 실패했습니다',
    'sendcloud_api_error'                    => 'SendCloud 오류 [:status_code]: :message',
    'sendcloud_send_success'                 => 'SendCloud 메일이 성공적으로 전송되었습니다',
];
