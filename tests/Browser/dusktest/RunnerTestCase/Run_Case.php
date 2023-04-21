<?php


require_once __DIR__.'/../../../../vendor/autoload.php';

use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\DefaultResultPrinter;



    $suite = new TestSuite();
    // 向测试套件中添加测试用例
//后台
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\RegisterFirst.php');//先注册一个账户
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\RegisterTest.php');//场景注册
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\LoginTest.php'); //前台登录场景
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\SignOutTest.php'); //前台退出
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\AddressTest.php');//添加地址
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\AddCartTest.php');//加入购物车
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\RemoveCartTest.php');//移除购物车
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\WishlistTest.php');//加入喜欢
//    $suite->addTestFile('.\tests\Browser\dusktest\page\front\RemoveWishlistTest.php');//移除喜欢
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\EditUserInfo.php');//修改个人信息
//后台
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\AdminLoginTest.php'); //后台登录
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\AdminSignOutTest.php'); //后台退出
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\GoCatalogTest.php'); //跳转前台
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\GopLuginsTest.php'); //跳转插件市场
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\AddProductTest.php'); //添加商品
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\EditProductTest.php'); //编辑商品
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\DelProductTest.php'); //删除商品
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\GoVipTest.php'); //跳转vip界面
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\LanguageSwitchTest.php');//切换语言
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\AddCustomerTest.php');//创建用户
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\EditCustomerTest.php');//修改用户信息
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\DelCustomerTest.php');//删除用户
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\AddCusGroupTest.php');//添加用户组
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\EditCusGroupTest.php');//编辑用户组
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\DelCusGroupTest.php');//删除用户组
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\CusEmptyRecycleTest.php');//清空回收站
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\CustomerRecycleTest.php');//恢复客户信息
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\DelCusRecycleTest.php');//从回收站删除客户信息
    $suite->addTestFile('.\tests\Browser\dusktest\page\admin\AddExpressTest.php');//添加快递公司


//前后台联测
//场景1  前台下单，后台取消订单
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\OrderTest.php');//商品页下单
    $suite->addTestFile('.\tests\Browser\dusktest\page\front\CartCheckoutTest.php');//购物车下单
    // 运行测试套件
    $result = $suite->run();
    // 输出测试结果
    $printer = new DefaultResultPrinter();
    // 输出测试结果
    $printer->printResult($result);
