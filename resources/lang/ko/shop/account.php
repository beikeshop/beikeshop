<?php
/**
 * account.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-04 10:59:15
 * @modified   2022-08-04 10:59:15
 */

return [
    'index' => '개인중심',
    'revise_info' => '정보 수정',
    'collect' => '소장',
    '쿠폰' => '쿠폰',
    'my_order' => '내 주문',
    'orders' => '전체주문',
    'pending_payment' => '미지급금',
    'pending_send' => '출하 대기 중',
    'pending_receipt' => '수령대기',
    'after_sales' => 'A/S',
    'no_order' => '아직 주문이 없습니다!',
    'to_buy' => '주문하러 가기',
    'order_number' => '주문번호',
    'order_time' => '주문시간',
    'state' => '상태',
    'amount' => '금액',
    'check_details' => '상세보기',
    'all' => '공',
    'items' => '상품',
    'verify_code_expired' => '인증번호가 만료되었습니다(10분). 다시 가져오십시오.',
    'verify_code_error' => '인증번호 오류',
    'account_not_exist' => '계정 없음',

    'edit' => [
        'index' => '개인정보 수정',
        'modify_avatar' => '프로필 수정',
        'suggest' => 'JPG 또는 PNG 이미지를 업로드합니다.300 x 300을 권장합니다.',
        'name' => '이름',
        'email' => '이메일',
        'crop' => '커팅',
        'password_edit_success' => '비밀번호 수정 성공',
        'origin_password_fail' => '원래 비밀번호 오류',
    ],

    'wishlist' => [
        'index' => '나의 컬렉션',
        'product' => '상품',
        'price' => '가격',
        'check_details' => '상세보기',
    ],

    'order' => [
        'index' => '나의 주문',
        'completed' => '수령확인 완료',
        'cancelled' => '주문이 취소되었습니다',
        'order_details' => '주문내역',
        'amount' => '금액',
        'state' => '상태',
        'order_number' => '주문번호',
        'check' => '보기',

        'order_info' => [
            'index' => '주문내역',
            'order_details' => '주문내역',
            'to_pay' => '지불하러 가다',
            'cancel' => '주문취소',
            'confirm_receipt' => '수령확인',
            'order_number' => '주문번호',
            'order_date' => '주문일자',
            'state' => '상태',
            'order_amount' => '주문금액',
            'order_items' => '주문상품',
            'apply_after_sales' => 'A/S 신청하기',
            'order_total' => '합계주문',
            'logistics_status' => '물류 상태',
            'order_status' => '주문상태',
            'remark' => '비고',
            'update_time' => '업데이트 시간',
        ],

        'order_success' => [
            'order_success' => '축하드립니다, 주문 생성 성공!',
            'order_number' => '주문번호',
            'amounts_payable' => '미지급금액',
            'payment_method' => '지불방식',
            'view_order' => '주문내역보기',
            'pay_now' => '바로 지불',
            'kind_tips' => '따뜻한 힌트:주문 생성 완료, 빠른 결제 부탁드립니다~',
            'also' => '괜찮으십니다',
            'continue_purchase' => '계속 구매',
            'contact_customer_service' => '주문 과정에서 문제가 있으시면 언제든지 저희 고객 서비스 직원에게 연락하시면 됩니다',
            'emaill' => '메일함',
            'service_hotline' => '서비스 핫라인',
        ],

    ],

    'addresses' => [
        'index' => '내 주소',
        'add_address' => '새 주소 추가',
        'default_address' => '기본 주소',
        'delete' => '삭제',
        'edit' => '편집',
        'enter_name' => '이름 입력하세요',
        'enter_phone' => '연락처를 입력하세요',
        'enter_address' => '상세주소 1을 입력하세요',
        'select_province' => '성을 선택하십시오',
        'enter_city' => '도시를 적어주세요',
        'confirm_delete' => '주소를 삭제하시겠습니까?',
        'hint' => '힌트',
        'check_form' => '양식이 올바르게 작성되었는지 확인하십시오',
    ],

    'rma' => [
        'index' => '나의 A/S',
        'commodity' => '상품',
        'quantity' => '수량',
        'service_type' => '서비스 유형',
        'return_reason' => '교체 사유',
        'creation_time' => '만들기 시간',
        'check' => '보기',

        'rma_info' => [
            'index' => 'A/S 내역',
        ],

        'rma_form' => [
            'index' => 'A/S 정보 제출하기',
            'service_type' => '서비스 유형',
            'return_quantity' => '반환수량',
            'unpacked' => '포장 개봉 완료',
            'return_reason' => '교체 사유',
            'remark' => '비고',
        ],
    ],
];
