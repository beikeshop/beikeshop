<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     licy <licy@guangda.work>
 * @created    2023-06-06 17:17:04
 * @modified   2023-06-06 17:17:04
 */
require_once __DIR__ . '/../../../../vendor/autoload.php';

use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\DefaultResultPrinter;

$suite = new TestSuite();
// 向测试套件中添加测试用例
//前台
$suite->addTestFile('.\tests\Browser\Pages\front\RegisterFirst.php'); //先注册一个账户
$suite->addTestFile('.\tests\Browser\Pages\front\RegisterTest.php'); //场景注册
$suite->addTestFile('.\tests\Browser\Pages\front\LoginTest.php'); //前台登录场景
$suite->addTestFile('.\tests\Browser\Pages\front\SignOutTest.php'); //前台退出
$suite->addTestFile('.\tests\Browser\Pages\front\AddressTest.php'); //添加地址
$suite->addTestFile('.\tests\Browser\Pages\front\AddCartTest.php'); //加入购物车
$suite->addTestFile('.\tests\Browser\Pages\front\RemoveCartTest.php'); //移除购物车
$suite->addTestFile('.\tests\Browser\Pages\front\RemoveWishlistTest.php'); //移除喜欢
$suite->addTestFile('.\tests\Browser\Pages\front\WishlistTest.php'); //加入喜欢
$suite->addTestFile('.\tests\Browser\Pages\front\AscPriceTest.php'); //价格升序
$suite->addTestFile('.\tests\Browser\Pages\front\DescPriceTest.php'); //价格降序
$suite->addTestFile('.\tests\Browser\Pages\front\AscNameTest.php'); //name升序
$suite->addTestFile('.\tests\Browser\Pages\front\DescNameTest.php'); //name降序
$suite->addTestFile('.\tests\Browser\Pages\front\EditUserInfo.php'); //修改个人信息
$suite->addTestFile('.\tests\Browser\Pages\front\CartCheckoutTest.php'); //从购物车结账
$suite->addTestFile('.\tests\Browser\Pages\front\ChangePayMethodTest.php'); //下单时更改支付方式
$suite->addTestFile('.\tests\Browser\Pages\front\OrderTest.php'); //下单

//后台
$suite->addTestFile('.\tests\Browser\Pages\admin\AdminLoginTest.php'); //后台登录
$suite->addTestFile('.\tests\Browser\Pages\admin\AdminSignOutTest.php'); //后台退出
$suite->addTestFile('.\tests\Browser\Pages\admin\GoCatalogTest.php'); //跳转前台
$suite->addTestFile('.\tests\Browser\Pages\admin\GopLuginsTest.php'); //跳转插件市场
$suite->addTestFile('.\tests\Browser\Pages\admin\AddProductTest.php'); //添加商品
$suite->addTestFile('.\tests\Browser\Pages\admin\DelProductTest.php'); //删除商品
$suite->addTestFile('.\tests\Browser\Pages\admin\EditProductTest.php'); //编辑商品
$suite->addTestFile('.\tests\Browser\Pages\admin\GoCopyrightAndServiceTest.php'); //跳转版权服务
$suite->addTestFile('.\tests\Browser\Pages\admin\LanguageSwitchTest.php'); //切换语言
$suite->addTestFile('.\tests\Browser\Pages\admin\AddExpressTest.php'); //添加快递公司
$suite->addTestFile('.\tests\Browser\Pages\admin\AddProductBrandsTest.php'); //添加商品品牌
$suite->addTestFile('.\tests\Browser\Pages\admin\EditProductBrandsTest.php'); //编辑商品品牌
$suite->addTestFile('.\tests\Browser\Pages\admin\DelProductBrandsTest.php'); //删除商品品牌
$suite->addTestFile('.\tests\Browser\Pages\admin\AddArticleCataTest.php'); //添加文章分类
$suite->addTestFile('.\tests\Browser\Pages\admin\AlterArticleCataTest.php'); //编辑文章分类
$suite->addTestFile('.\tests\Browser\Pages\admin\DelArticleTest.php'); //删除文章分类
$suite->addTestFile('.\tests\Browser\Pages\admin\AddArticleTest.php'); //添加文章
$suite->addTestFile('.\tests\Browser\Pages\admin\AlterArticleTest.php'); //编辑文章
$suite->addTestFile('.\tests\Browser\Pages\admin\DelArticleTest.php'); //删除文章
$suite->addTestFile('.\tests\Browser\Pages\admin\AddRmaReasonsTest.php'); //添加售后原因
//前后台联测
$suite->addTestFile('.\tests\Browser\Pages\combine\AlterOrderStationTest.php'); //订单状态修改 已支付-已发货-一已完成OrderRmaTest
$suite->addTestFile('.\tests\Browser\Pages\combine\OrderRmaTest.php'); //商品退换测试
$suite->addTestFile('.\tests\Browser\Pages\combine\CancelOrderTest.php'); //取消商品订单
$suite->addTestFile('.\tests\Browser\Pages\combine\CloseVisiterCheckoutTest.php'); //禁用游客结账
$suite->addTestFile('.\tests\Browser\Pages\combine\OpenVisiterCheckoutTest.php'); //开启游客结账
$suite->addTestFile('.\tests\Browser\Pages\combine\DisableProductTest.php'); //禁用商品
$suite->addTestFile('.\tests\Browser\Pages\combine\EnableProductTest.php'); //启用商品
$suite->addTestFile('.\tests\Browser\Pages\combine\UnderstockOrderTest.php'); //库存不足下单
$suite->addTestFile('.\tests\Browser\Pages\combine\CustomerGroupDiscountTest.php'); //客户组折扣检验
$suite->addTestFile('.\tests\Browser\Pages\combine\CreateCategoriesTest.php'); //增加商品分类
$suite->addTestFile('.\tests\Browser\Pages\combine\AlterCategoriesTest.php'); //更改商品分类
$suite->addTestFile('.\tests\Browser\Pages\combine\DelCategoriesTest.php'); //删除商品分类

//后台删除用户操作
$suite->addTestFile('.\tests\Browser\Pages\admin\AddCustomerTest.php'); //创建用户
$suite->addTestFile('.\tests\Browser\Pages\admin\EditCustomerTest.php'); //修改用户信息
$suite->addTestFile('.\tests\Browser\Pages\admin\DelCustomerTest.php'); //删除用户
$suite->addTestFile('.\tests\Browser\Pages\admin\AddCusGroupTest.php'); //添加用户组
$suite->addTestFile('.\tests\Browser\Pages\admin\EditCusGroupTest.php'); //编辑用户组
$suite->addTestFile('.\tests\Browser\Pages\admin\DelCusGroupTest.php'); //删除用户组
$suite->addTestFile('.\tests\Browser\Pages\admin\CustomerRecycleTest.php'); //恢复客户信息
$suite->addTestFile('.\tests\Browser\Pages\admin\DelCustomerTest.php'); //删除用户
$suite->addTestFile('.\tests\Browser\Pages\admin\CusEmptyRecycleTest.php'); //清空回收站
$suite->addTestFile('.\tests\Browser\Pages\admin\DelCustomerTest.php'); //删除用户
$suite->addTestFile('.\tests\Browser\Pages\admin\DelCusRecycleTest.php'); //从回收站删除客户信息

//插件测试
//    $suite->addTestFile('.\tests\Browser\Pages\front\PaypalOrderTest.php'); //paypal插件测试  沙盒
//    $suite->addTestFile('.\tests\Browser\Pages\front\StripeOrderTest.php'); //stripe插件测试  沙盒

// 运行测试套件
$result = $suite->run();
// 输出测试结果
$printer = new DefaultResultPrinter();
// 输出测试结果
$printer->printResult($result);
