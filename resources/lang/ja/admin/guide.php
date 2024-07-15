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
    // 見出し
    'Heading_title' => '初心者ガイド',

    //タブ
    'tab_basic'            => '基本設定',
    'tab_ language'        => '複数の言語と通貨',
    'tab_product'          => '製品の作成',
    'tab_theme'            => '店舗装飾',
    'tab_payment_shipping' => '支払いと物流',
    'tab_mail'             => '設定メール',

    //文章
    'text_extension'   => '拡張子',
    'text_success'     => '成功: 初心者ガイドが変更されました。 ',
    'text_edit'        => '初心者のための編集ガイド',
    'text_view'        => '詳細を表示...',
    'text_greeting'    => 'おめでとうございます。あなたのウェブサイトに BeikeShop が正常にインストールされました。 ',
    'text_greeting_1'  => 'BeikeShop システムの機能を理解し、すぐに使い始めることができるように、システム上でいくつかの基本的なカスタム構成を作成する方法を説明します。 ',
    'text_basic_1'     => 'まず、システム設定で次の重要な情報を構成できます:',
    'text_ language_1' => 'BeikeShop システムは複数の言語と通貨をサポートしています。最初の商品の作成を開始する前に、モールのフロント デスクでデフォルトの言語と通貨を選択できます。 ',
    'text_ language_2' => '1 つの言語と通貨のみを使用する必要がある場合は、他の言語と通貨を削除できます。 商品作成時に多言語で情報を入力する手間を省きます。 ',
    'text_product_1'   => 'システムのインストール中に、デモンストレーション用に一部のデフォルトの製品データが自動的にインポートされます。 まず、<a href="' . admin_route('products.create') . '">商品の作成</a>を試してください。 ',
    'text_product_2'   => 'BeikeShop は強力な製品管理機能を提供します。 含まれるもの: <a href="' . admin_route('categories.index') . '">商品分類</a>、<a href="' . admin_route('brands.index') . '">ブランド管理</a>、複数仕様の製品、<a href="' . admin_route('multi_filter.index') . '">高度なフィルタリング</a>、<a href="' . admin_route('attributes.index') . '">製品属性</a>およびその他の機能。 ',
    'text_theme_1'     => 'システムには、デフォルトでインストールされた一連のデフォルトのテーマテンプレートがあります。デフォルトのテーマがニーズを満たさない場合は、 <a href="' . admin_route('marketing.index', [' type' => ' テーマ']) . '">プラグイン マーケット</a>で他のテンプレート テーマを購入します。 ',
    'text_theme_2'     => 'さらに、フロントエンド テーマ テンプレートのホームページは、レイアウトを通じてモジュールによって表示されます。レイアウトを通じていくつかのモジュール設定を調整する必要がある場合があります。 ',
    'text_theme_3'     => 'APP を購入すると、<a href="' . admin_route('design_app_home.index') . '">APP ホームページのデザイン</a>に特化した機能も提供されます。 ',
    'text_payment_1'   => 'BeikeShop は、デフォルトの PayPal、Stripe など、一般的に使用される海外の支払いチャネルを提供します。 正式に注文する前に、対応する支払い方法を有効にして設定する必要があります。 ',
    'text_payment_2'   => '注: 一部の支払いインターフェイス アプリケーションは審査に時間がかかるため、事前に申請してください。 中国で使用される支払い方法では、Web サイトのドメイン名の登録が必要になる場合があります。 ',
    'text_payment_3'   => 'さらに、顧客が選択できる物流配送方法を設定する必要もあります。 システムでは固定送料プラグインを無料で提供しています。 ',
    'text_payment_4'   => 'BeikeShop<a href="' . admin_route('marketing.index') . '">「プラグイン マーケット」</a> にアクセスして、その他の支払い方法とロジスティクスを確認およびダウンロードすることもできます。メソッド！ ',
    'text_mail_1'      => '電子メール通知により、顧客は注文ステータスを常に知ることができ、電子メール経由でパスワードを登録および取得することもできます。 実際のビジネス ニーズに応じて SMTP を構成でき、電子メールの送信には SendCloud などの電子メール エンジンが使用されます。 ',
    'text_mail_2'      => '注意: 頻繁にメールを送信すると、メールがスパムとしてマークされる可能性があります。メールの送信には SendCloud (有料サービス) を使用することをお勧めします。 ',

    // ボタン
    'button_setting_general' => 'Webサイトの基本設定',
    'button_setting_store'   => 'ウェブサイト名',
    'button_setting_logo'    => 'ロゴを変更',
    'button_setting_option'  => 'オプション設定',
    'button_setting'         => 'すべてのシステム設定',
    'button_ language'       => '言語管理',
    'button_currency'        => '通貨管理',
    'button_product'         => '製品を表示',
    'button_product_create'  => '製品の作成',
    'button_theme_pc'        => 'テンプレート設定',
    'button_theme_h5'        => 'モバイルテーマ設定',
    'button_theme'           => 'すべてのテーマ',
    'button_layout'          => 'レイアウト管理',
    'button_payment'         => '支払い方法',
    'button_shipping'        => '配送方法',
    'button_mail'            => 'メール設定',
    'button_sms'             => 'SMS 設定',
    'button_hide'            => '今後表示しない',
];
