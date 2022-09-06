<?php
/**
 * index.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-09-06 10:50:56
 * @modified   2022-09-06 10:50:56
 */

$currentDir = getcwd();
die("请修改 Apache 或 Nginx 配置, 将网站根目录设置为: {$currentDir}/public/");
