<?php


//require_once __DIR__.'/../../../../vendor/autoload.php';
//require_once __DIR__.'/DuskTestSuite.php';
//require_once __DIR__.'/../page/front/RegisterTest.php';

use PHPUnit\Framework\TestResult;
use Tests\DuskTestCase;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\ResultPrinter;
use PHPUnit\TextUI\DefaultResultPrinter;



    $suite = new TestSuite();
    // 向测试套件中添加测试用例
    //1.先注册一个账户
    $suite->addTestFile('E:\phpstudy_pro\WWW\autotest.test\beikeshop\tests\Browser\dusktest\page\front\RegisterFirst.php');
    //注册
    $suite->addTestFile('E:\phpstudy_pro\WWW\autotest.test\beikeshop\tests\Browser\dusktest\page\front\RegisterTest.php');
    //增加地址
    $suite->addTestFile('E:\phpstudy_pro\WWW\autotest.test\beikeshop\tests\Browser\dusktest\page\front\AddressTest.php');
    $suite->addTestFile('E:\phpstudy_pro\WWW\autotest.test\beikeshop\tests\Browser\dusktest\page\front\LoginTest.php');
    $suite->addTestFile('E:\phpstudy_pro\WWW\autotest.test\beikeshop\tests\Browser\dusktest\page\front\OrderTest.php');
    // 运行测试套件
    $result = $suite->run();
    // 输出测试结果
    $printer = new DefaultResultPrinter();
    // 输出测试结果
    $printer->printResult($result);
