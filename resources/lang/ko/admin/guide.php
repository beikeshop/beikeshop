<?php

/**
 * order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    // 제목
    'heading_title' => '초보 가이드',

    //탭
    'tab_basic'        => '기본 설정',
    'tab_언어'           => '다양한 언어 및 통화',
    'tab_product'      => '제품 생성',
    'tab_theme'        => '상점 장식',
    'tab_pay_shipping' => '결제 및 물류',
    'tab_mail'         => '구성 메일',

    //텍스트
    'text_extension'  => '확장자',
    'text_success'    => '성공: 초보자 가이드가 수정되었습니다! ',
    'text_edit'       => '초보자를 위한 편집 안내',
    'text_view'       => '세부정보 표시...',
    'text_greeting'   => '축하합니다. 귀하의 웹사이트에 BeikeShop이 성공적으로 설치되었습니다! ',
    'text_greeting_1' => 'BeikeShop 시스템의 기능을 이해하고 빠르게 사용할 수 있도록 시스템에서 몇 가지 기본 사용자 정의 구성을 안내해 드리겠습니다! ',
    'text_basic_1'    => '먼저 시스템 설정에서 다음과 같은 중요한 정보를 구성할 수 있습니다:',
    'text_언어_1'       => 'BeikeShop 시스템은 다양한 언어와 통화를 지원합니다. 첫 번째 제품 제작을 시작하기 전에 쇼핑몰 프론트 데스크에서 기본 언어와 통화를 선택할 수 있습니다! ',
    'text_언어_2'       => '하나의 언어와 통화만 사용해야 하는 경우 다른 언어와 통화를 삭제할 수 있습니다. 제품을 만들 때 다국어로 정보를 입력해야 하는 번거로움을 피하세요. ',
    'text_product_1'  => '시스템 설치 중에 데모용으로 일부 기본 제품 데이터를 자동으로 가져옵니다. 먼저 <a href="' . admin_route('products.create') . '">제품 만들기</a>를 시도해 보세요! ',
    'text_product_2'  => 'BeikeShop은 강력한 제품 관리 기능을 제공합니다! 포함: <a href="' . admin_route('categories.index') . '">제품 분류</a>, <a href="' . admin_route('brands.index') . '">브랜드 관리</a>, 다중 사양 제품, <a href="' . admin_route('multi_filter.index') . '">고급 필터링</a>, <a href="' . admin_route('attributes.index') . '">제품 속성</a> 및 기타 기능. ',
    'text_theme_1'    => '시스템에는 기본적으로 기본 테마 템플릿 세트가 설치되어 있습니다. 기본 테마가 요구 사항을 충족하지 않는 경우 <a href="' . admin_route('marketing.index', [' ' => ' theme']) . '">플러그인 마켓</a>을 입력하여 다른 템플릿 테마를 구매하세요. ',
    'text_theme_2'    => '또한 프런트엔드 테마 템플릿의 홈 페이지는 레이아웃을 통해 모듈별로 표시됩니다. 레이아웃을 통해 일부 모듈 설정을 조정해야 할 수도 있습니다. ',
    'text_theme_3'    => 'APP을 구매하시면 <a href="' . admin_route('design_app_home.index') . '">APP 홈페이지 디자인</a>을 위한 기능도 제공됩니다. ',
    'text_pay_1'      => 'BeikeShop은 기본 PayPal, Stripe 등 일반적으로 사용되는 해외 결제 채널을 제공합니다. 공식적으로 주문하기 전에 해당 결제 방법을 활성화하고 구성해야 합니다. ',
    'text_pay_2'      => '참고: 일부 결제 인터페이스 애플리케이션은 검토하는 데 시간이 더 오래 걸리므로 미리 신청하시기 바랍니다. 중국에서 사용되는 결제 방법에는 웹사이트 도메인 이름 등록이 필요할 수 있습니다. ',
    'text_pay_3'      => '또한 고객이 선택할 수 있는 물류 배송 방법도 설정해야 합니다. 시스템은 고정 배송비 플러그인을 무료로 제공합니다. ',
    'text_pay_4'      => 'BeikeShop<a href="' . admin_route('marketing.index') . '">"플러그인 마켓"</a>으로 이동하여 더 많은 결제 방법과 실행 방법을 알아보고 다운로드할 수도 있습니다. 방법! ',
    'text_mail_1'     => '이메일 알림을 통해 고객에게 주문 상태를 계속 알릴 수 있으며 이메일을 통해 비밀번호를 등록하고 검색할 수도 있습니다. 실제 비즈니스 요구에 따라 SMTP를 구성할 수 있으며 SendCloud와 같은 이메일 엔진을 사용하여 이메일을 보냅니다. ',
    'text_mail_2'     => '알림: 이메일을 자주 보내면 스팸으로 표시될 수 있으므로 SendCloud(유료 서비스)를 사용하여 이메일을 보내는 것이 좋습니다. ',

    // 버튼
    'button_setting_general' => '웹사이트 기본 설정',
    'button_setting_store'   => '웹사이트 이름',
    'button_setting_logo'    => '로고 변경',
    'button_setting_option'  => '옵션 설정',
    'button_setting'         => '모든 시스템 설정',
    'button_언어'              => '언어 관리',
    'button_currency'        => '환전관리',
    'button_product'         => '상품보기',
    'button_product_create'  => '제품 생성',
    'button_theme_pc'        => '템플릿 설정',
    'button_theme_h5'        => '모바일 테마 설정',
    'button_theme'           => '모든 테마',
    'button_layout'          => '레이아웃 관리',
    'button_pay'             => '결제수단',
    'button_shipping'        => '배송 방법',
    'button_mail'            => '메일 설정',
    'button_sms'             => 'SMS 구성',
    'button_hide'            => '다시 표시하지 않음',
];
