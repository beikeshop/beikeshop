<?php
/**
 * PagesSeeder.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-09-05 20:42:42
 * @modified   2022-09-05 20:42:42
 */

namespace Database\Seeders;

use Beike\Models\Page;
use Beike\Models\PageDescription;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = $this->getPages();

        if ($pages) {
            Page::query()->truncate();
            foreach ($pages as $item) {
                Page::query()->create($item);
            }
        }

        $descriptions = $this->getPageDescriptions();
        if ($descriptions) {
            PageDescription::query()->truncate();
            foreach ($descriptions as $item) {
                PageDescription::query()->create($item);
            }
        }
    }


    public function getPages()
    {
        return [
            [
                'id' => 12,
                'page_category_id' => 0,
                'position' => 0,
                'views' => 0,
                'author' => 'BeikeShop',
                'active' => 1
            ],
            [
                'id' => 18,
                'page_category_id' => 0,
                'position' => 0,
                'views' => 0,
                'author' => 'BeikeShop',
                'active' => 1
            ],
            [
                'id' => 20,
                'page_category_id' => 0,
                'position' => 0,
                'views' => 0,
                'author' => 'BeikeShop',
                'active' => 1
            ],
            [
                'id' => 21,
                'page_category_id' => 0,
                'position' => 0,
                'views' => 0,
                'author' => 'BeikeShop',
                'active' => 1
            ],
            [
                "id" =>22,
                "page_category_id" =>1,
                "position" =>0,
                "views" =>1,
                "image" => '/catalog/demo/blog/post-1.png',
                "author" =>"BeikeShop",
                "active" =>1,
            ],
            [
                "id" =>23,
                "page_category_id" =>1,
                "position" =>0,
                "views" =>1,
                "image" => '/catalog/demo/blog/post-2.png',
                "author" =>"BeikeShop",
                "active" =>1,
            ],
            [
                "id" =>24,
                "page_category_id" =>1,
                "position" =>0,
                "views" =>1,
                "image" => '/catalog/demo/blog/post-3.png',
                "author" =>"BeikeShop",
                "active" =>1,
            ],
            [
                "id" =>25,
                "page_category_id" =>1,
                "position" =>0,
                "views" =>1,
                "image" => '/catalog/demo/blog/post-4.png',
                "author" =>"BeikeShop",
                "active" =>1,
            ],
        ];
    }

    public function getPageDescriptions()
    {
        $version = config('beike.version');
        return [
            [
                "id" => "55",
				"page_id" => "18",
				"locale" => "en",
				"title" => "Distribution information",
                "summary" => "",
				"content" => "<p><span style=\"color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff;\">Delivery Information</span></p>",
				"meta_title" => "",
				"meta_description" => "",
				"meta_keywords" => "",
				"created_at" => "2022-08-12 01:19:03",
				"updated_at" => "2022-08-12 01:19:03"
			],
            [
                "id" => "56",
				"page_id" => "18",
				"locale" => "zh_cn",
				"title" => "配送信息",
                "summary" => "",
				"content" => "<p><span style=\"color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff;\">Delivery Information</span></p>",
				"meta_title" => "",
				"meta_description" => "",
				"meta_keywords" => "",
				"created_at" => "2022-08-12 01:19:03",
				"updated_at" => "2022-08-12 01:19:03"
			],
            [
                "id" => "63",
				"page_id" => "12",
				"locale" => "en",
				"title" => "Privacy policy",
                "summary" => "",
				"content" => "<h1 style=\"box-sizing: border-box; font-size: 24px; margin: 0px 0px 18px; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-weight: 500; line-height: 1.1; color: #333333; background-color: #ffffff; outline: none !important;\">Privacy policy</h1>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">1、 Collection and use of personal information</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">When you use Everbright mall services, we may ask you to provide personal information. Everbright mall will use this information in accordance with this privacy policy. You do not have to provide the personal information we require, but in many cases, if you choose not to provide it, we will not be able to provide you with our products or services or respond to any problems you encounter.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">The following provides some examples of the types of personal information that Everbright mall may collect and how we use such information:</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">2、 What personal information do we collect</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">When you create an account, buy products, download software updates and contact us, we may collect various information, including your name, mailing address, telephone number, e-mail address, contact information and preferences.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">When you use Everbright mall to purchase goods or gift cards, Everbright mall may collect information related to the above persons provided by you, such as name, mailing address, e-mail address and telephone number. Everbright mall will use this information to meet your requirements, provide relevant products or services, or achieve anti fraud purposes.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">In some jurisdictions, for the purpose of complying with the law, we may require users to provide identification issued by the government, but only in a few cases, such as customs declaration.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">4、 How do we use your personal information</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We will inform you of the latest product release, software update and activity forecast in time. If you don't want to be on our mailing list, you can opt out at any time by updating your preferences.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We may use your personal information (such as email address or mobile phone number) to verify and determine the user's identity.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We may use personal information to send important notices, such as order information about purchases. Since this information is very important for your communication with the mall, you cannot refuse to receive such information.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We will also use personal information for internal purposes such as audit, data analysis and research to improve Everbright mall's products, services and communication with customers.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">5、 Collection and use of non personal information</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We will also collect and use data that cannot be directly linked to any particular individual by itself. We may collect, use, transfer and disclose non personal information for any purpose. Below are some examples of non personal information we may collect and how we use it.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We will collect information such as occupation, language, zip code, area code, unique device identifier, referral URL, location and time zone when users use the product, so that we can better understand customers' behavior and improve our products, services and advertising.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We collect information about our customers' activities on our website. We will summarize such information to help us provide customers with more useful information and understand which parts of our website, products and services customers are most interested in. For the purposes of this privacy policy, aggregated data is considered non personal information.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We may collect and store detailed information about how you use our services, including search queries. Such information may be used to improve the results of our service delivery and make it more relevant. Such information usually does not involve your IP address, except for the following few cases: we need to ensure the quality of services provided through the Internet.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">6、 Cookies and other technologies</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">Everbright mall or email and advertising may use cookies and other technologies, such as pixel tags and website beacons. These technologies help us better understand user behavior, tell us what parts of our website people visit, measure and improve the effectiveness of advertising and web search. We treat information collected through cookies and other technologies as non personal information. However, if local laws treat Internet Protocol (IP) addresses or similar identification marks as personal information, we also treat such identification marks as personal information. Similarly, for the purpose of this privacy policy, in the case of combining non personal information with personal information, we treat the combined information as personal information.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">Like most Internet services, we automatically collect certain information and store it in log files. Such information includes IP address, browser type and language, operating system, date \/ time stamp, etc.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We use this information to understand and analyze trends, manage the website, understand user behavior on the website, improve our products and services, and collect information on the overall audience characteristics of the user base. Everbright mall can use this information for our marketing and advertising services.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">Pixel tags allow us to send email in a format that customers can read and tell us whether the email is open. We can use this information to reduce or eliminate email to customers.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">7、 Other</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">It may be necessary for Everbright mall to disclose your personal information according to the laws, legal procedures, litigation and or the requirements of public institutions and government departments at home and abroad. We may also disclose information about you if we determine that disclosure is necessary or appropriate for national security, law enforcement or other matters of public importance.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">We may also disclose information about you if we determine that disclosure is reasonably necessary to enforce our terms and conditions or protect our operations or users. In addition, in case of reorganization, merger or sale, we can transfer all personal information we collect to relevant third parties.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">Protection of personal information Everbright mall attaches great importance to the security of your personal information. Everbright mall will use encryption technologies such as transport layer security protocol (TLS) to protect your personal information during transmission. When storing your personal data in Everbright mall, we will use computer systems with limited access rights, which are deployed in facilities protected by physical security measures. Your account and password are stored in encrypted form, which is also true when we use third-party storage space.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">8、 Privacy issues</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">If you have any questions or questions about Everbright mall's privacy policy or data processing, or want to complain about possible violations of local privacy laws, please contact us. You can call the support number of Everbright mall to contact us at any time.</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">&nbsp;</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">When receiving privacy issues or access \/ download requests, we will screen and classify the contacts and try to solve the specific problems or questions you raise. If your question itself involves more important matters, we may ask you for more information. These contacts who ask more important questions will receive replies. If you are not satisfied with the response received, you can refer the complaint to the relevant regulatory authority in your jurisdiction. If you consult us, we will provide information on possible applicable complaint channels according to your actual situation. Everbright mall can update its privacy policy at any time. If we make significant changes to our privacy policy, we will publish announcements and updated privacy policies on the company's website.</p>",
				"meta_title" => "23",
				"meta_description" => "",
				"meta_keywords" => "213123",
				"created_at" => "2022-08-12 01:54:01",
				"updated_at" => "2022-08-12 01:54:01"
			],
            [
                "id" => "64",
				"page_id" => "12",
				"locale" => "zh_cn",
				"title" => "隐私政策",
                "summary" => "",
				"content" => "<h1 style=\"box-sizing: border-box; font-size: 24px; margin: 0px 0px 18px; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-weight: 500; line-height: 1.1; color: #333333; background-color: #ffffff; outline: none !important;\">隐私政策</h1>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">一、个人信息的收集和使用</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">当您使用《成都光大网络科技有限公司》商城服务时，我们有可能会要求你提供个人信息。《成都光大网络科技有限公司》商城将按本隐私政策使用这些信息。你不是一定要提供我们要求的个人信息，但在许多情况下，如果你选择不提供，我们将无法为你提供我们的产品或服务，也无法回应你遇到的任何问题。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">以下提供了一些成都光大网络科技有限公司商城可能收集的个人信息类型以及我们如何使用此类信息的示例：</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">二、我们收集哪些个人信息</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">当你创建账号、购买产品、下载软件更新、联系我们时，我们可能会收集各种信息，包括你的姓名、邮寄地址、电话号码、电子邮件地址、联系方式偏好等信息。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">当你使用成都光大网络科技有限公司商城购买商品或礼品卡时，成都光大网络科技有限公司商城可能会收集你提供的与上述人士有关的信息，如姓名、邮寄地址、电子邮件地址以及电话号码。成都光大网络科技有限公司商城将使用此类信息来满足你的要求，提供相关的产品或服务，或实现反欺诈目的。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">在某些司法辖区，出于遵守法律规定的目的，我们可能会要求用户提供由政府发放的身份证明，但仅限于为数不多的情形，例如海关报关。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">四、我们如何使用你的个人信息</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们将最新产品发布、软件更新及活动预告及时通知你。如果你不希望列入我们的邮寄列表，可随时通过更新偏好设置选择退出。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们可能会使用你的个人信息（例如电子邮件地址或手机号码）来验证及确定用户身份。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们可能会使用个人信息发送重要通知，例如关于购买的相关订单信息。由于这些信息对你与商城之间的沟通至关重要，你不能拒绝接收此类信息。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们还会将个人信息用于审计、数据分析和研究等内部目的，以改进成都光大网络科技有限公司商城的产品、服务和与客户之间的沟通。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们系统集成了cn.jpush.android(极光;极光推送) com.huawei.hms(华为;华为推送) com.alipay(支付宝;阿里乘车码;阿里芝麻信用实名认证;芝麻认证) com.tencent.bugly(Bugly;腾讯Bugly)等SDK，将在APP推送消息时推送给您，用于发送系统最新消息。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">五、非个人信息的收集和使用</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们还会收集利用其本身无法与任何特定个人直接建立联系的数据。我们可出于任何目的而收集、使用、转让和披露非个人信息。下文是我们可能收集的非个人信息以及我们如何使用这些信息的一些示例。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们会收集诸如职业、语言、邮编、区号、唯一设备标识符、引荐 URL、位置以及用户在使用产品时所处时区等信息，以便我们能更好地了解客户的行为，改进我们的产品、服务和广告宣传。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们会收集与客户在我们的网站上活动有关的信息。我们会将此类信息汇总，用于帮助我们向客户提供更有用的信息，了解客户对我们网站、产品和服务中的哪些部分最感兴趣。就本隐私政策而言，汇总数据被视为非个人信息。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们可能会收集和存储有关你如何使用我们的服务的详细信息，包括搜索查询。此类信息可能会用于改进我们的服务提供的结果，使其更具相关性。此类信息通常不会涉及你的 IP 地址，但以下少数情况除外：我们需要确保通过互联网提供的服务的质量。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">六、Cookie 和其他技术</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">成都光大网络科技有限公司商城或电子邮件和广告可能会使用 Cookie 和其他技术，如像素标签和网站信标。此等技术帮助我们更好地了解用户的行为，告诉我们人们浏览了我们网站的哪些部分，衡量广告和网络搜索的效果并加以改善。我们将通过 Cookie 和其他技术收集的信息视为非个人信息。但是，如果当地法律将 Internet 协议 (IP) 地址或类似识别标记视为个人信息，则我们亦将此等识别标记视为个人信息。同样，就本隐私政策而言，在将非个人信息与个人信息结合使用的情况下，我们将结合使用的信息视为个人信息。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">如同大多数互联网服务一样，我们也会自动收集某些信息，将其存储在日志文件中。这类信息包括 IP 地址、浏览器类型和语言、操作系统、日期\/时间戳等。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">我们使用此等信息来了解和分析趋势、管理网站、了解网站上的用户行为、改进我们的产品和服务，以及收集用户群的整体受众特征信息。成都光大网络科技有限公司商城可将此类信息用于我们的营销和广告服务。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">像素标签使我们可以用客户可阅读的格式发送电子邮件，并告知我们邮件是否被打开。我们可利用这些信息来减少或免除向客户发送的电子邮件。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">七、其他</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">根据你居住国境内外的法律、法律程序、诉讼和或公共机构和政府部门的要求，成都光大网络科技有限公司商城可能有必要披露你的个人信息。如果我们确定就国家安全、执法或具有公众重要性的其他事宜而言，披露是必须的或适当的，我们也可能会披露关于你的信息。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">如果我们确定为了执行我们的条款和条件或保护我们的经营或用户，披露是合理必要的，我们也可能会披露关于你的信息。此外，如果发生重组、合并或出售，则我们可将我们收集的一切个人信息转让给相关第三方。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">个人信息的保护成都光大网络科技有限公司商城非常重视你的个人信息的安全。成都光大网络科技有限公司商城会使用传输层安全协议 (TLS) 等加密技术，在传输过程中保护你的个人信息。在成都光大网络科技有限公司商城存储你的个人数据时，我们会使用具有有限访问权限的电脑系统，这些系统部署在通过物理安全措施加以保护的设施之中。您的账号密码以加密形式存储，在我们使用第三方存储空间时也是如此。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">八、隐私问题</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">如果你对成都光大网络科技有限公司商城的隐私政策或数据处理有任何问题或疑问，或者想就可能违反当地隐私权法律的情况进行投诉，请联系我们。你可以随时拨打成都光大网络科技有限公司商城支持电话号码与我们联系。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">当收到隐私问题或访问\/下载请求时，我们将对联系人进行甄别分类，并将设法解决您提出的具体问题或疑问。如果您的问题本身涉及比较重大的事项，我们可能会要求您提供更多信息。这些提出比较重大问题的联系人均将收到回复。如果您对收到的答复不满意，您可以将投诉移交给所在司法辖区的相关监管机构。如果您咨询我们，我们会根据您的实际情况，提供可能适用的相关投诉途径的信息。 成都光大网络科技有限公司商城可随时对其隐私政策加以更新。如果我们对隐私政策作出重大变更，我们将在公司网站上发布通告和经更新的隐私政策。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">权限说明：</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">&nbsp; &nbsp; &nbsp;1、&lt;android.permission.WRITE_EXTERNAL_STORAGE&gt; 获取用户sd卡文件，在用户授权的情况下用于个人中心修改头像以及商品购买前联系客服咨询相关商品信息。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">&nbsp; &nbsp; &nbsp;2、&lt;android.permission.CAMERA&gt; 调用摄像头权限，在用户授权的情况下 用于个人中心修改头像以及商品购买前联系客服咨询相关商品信息，以及首页扫描二维码帮助参加砍价、团购等商品促销&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 活动。</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">&nbsp; &nbsp; &nbsp;3、&lt;android.permission.RECORD_AUDIO&gt; 录音权限，在用户授权的情况下商品购买前联系客服发送语音咨询相关商品信息</p>\r\n<p>&nbsp;</p>",
				"meta_title" => "",
				"meta_description" => "",
				"meta_keywords" => "",
				"created_at" => "2022-08-12 01:54:01",
				"updated_at" => "2022-08-12 01:54:01"
			],
            [
                "id" => "167",
				"page_id" => "20",
				"locale" => "en",
				"title" => "Terms of use",
                "summary" => "",
				"content" => "<p><span style=\"color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff;\">Terms &amp; Conditions</span></p>",
				"meta_title" => "",
				"meta_description" => "",
				"meta_keywords" => "",
				"created_at" => "2022-08-31 16:05:49",
				"updated_at" => "2022-08-31 16:05:49"
			],
            [
                "id" => "168",
				"page_id" => "20",
				"locale" => "zh_cn",
				"title" => "使用条款",
                "summary" => "",
				"content" => "<p><span style=\"color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\">Terms &amp; Conditions</span></p>",
				"meta_title" => "",
				"meta_description" => "",
				"meta_keywords" => "",
				"created_at" => "2022-08-31 16:05:49",
				"updated_at" => "2022-08-31 16:05:49"
			],
            [
                "id" => "179",
				"page_id" => "21",
				"locale" => "en",
				"title" => "About",
                "summary" => "",
				"content" => "<div style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\">\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Chengdu Guangda Network Technology Co., Ltd.</span></span><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">&nbsp;is a high-tech enterprise mainly engaged in the development of Internet independent station systems.</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">We provide commercial technical support, secondary development, mobile app customization and template design related to cross-border open source e-commerce website construction.</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">&nbsp;</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">- Cutting-edge open source e-commerce independent station system</span></span></span></div>\r\n<ul>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">An integrated website building package from PC to mobile phone, suitable for various devices, one-stop e-commerce website building</span></span></span></li>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">It can solve the data exchange between mobile users and PC users, one core and two stores</span></span></span></li>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">100% open source system code</span></span></span></li>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Developed based on Laravel framework</span></span></span></li>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Powerful plug-in mechanism, easy function expansion</span></span></span></li>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">The mall supports multi-language\/multi-currency, various payment methods, and supports PayPal, stripe and other payments</span></span></span></li>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Strictly follow the MVC architecture</span></span></span></li>\r\n<li style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">The homepage UI layout is up to you</span></span></span></li>\r\n</ul>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">&nbsp;</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">contact details:</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">The company brings together industry elites and has a high-end, honest, professional and efficient service team and technical team to provide our customers with safe and stable system software and services. For business cooperation, please contact us!</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">&nbsp;</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Working hours: Monday to Friday (9:00 - 18:00)</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Contact: Mr. Liang</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Company Email: support@example.com</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Postal Code: 611731</span></span></span></div>\r\n<div style=\"box-sizing: border-box; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; outline: none !important;\"><span style=\"color: #000000;\"><span style=\"font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif;\"><span style=\"font-size: 14px;\">Address:&nbsp;</span></span><span style=\"font-size: 14px; white-space: pre-wrap;\">G8 Tianfu Software Park Chengdu China</span></span></div>\r\n</div>\r\n</div>",
				"meta_title" => "",
				"meta_description" => "",
				"meta_keywords" => "",
				"created_at" => "2022-09-02 11:04:30",
				"updated_at" => "2022-09-02 11:04:30"
			],
            [
                "id" => "180",
				"page_id" => "21",
				"locale" => "zh_cn",
				"title" => "关于我们",
                "summary" => "",
				"content" => "<div style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\"><span style=\"box-sizing: border-box; color: #3ca6f2; outline: none !important;\"><strong style=\"box-sizing: border-box; outline: none !important;\"><a style=\"box-sizing: border-box; background-color: transparent; color: #175199; text-decoration-line: none; outline: none !important;\" href=\"http:\/\/www.guangdawangluo.com\/\" target=\"_blank\" rel=\"noopener\">成都光大网络科技有限公司</a></strong></span>，是一家主要从事互联网独立站系统开发的高科技企业。</div>\r\n<div style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\"><hr style=\"box-sizing: content-box; height: 0px; margin-top: 20px; margin-bottom: 20px; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-image: initial; outline: none !important; border-color: #eeeeee initial initial initial;\" \/></div>\r\n<div style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">我们提供跨境开源电商建站相关的商业技术支持、二次开发、手机商城 App 定制和模板设计。</div>\r\n<div style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">&nbsp;</div>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">- 前沿的开源电商独立站系统</p>\r\n<ul>\r\n<li style=\"list-style-type: none;\">\r\n<ul>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\">从pc到手机一体化的建站套餐，适配各种设备，一站式电子商务建站</li>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\">可以解决移动端用户和PC端用户数据互通，一核双店</li>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\"><span style=\"color: #333333; font-family: Microsoft YaHei, tahoma, arial, Hiragino Sans GB, 宋体, sans-serif;\"><span style=\"font-size: 14px;\">系统代码100%开源</span></span></li>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\"><span style=\"color: #333333; font-family: Microsoft YaHei, tahoma, arial, Hiragino Sans GB, 宋体, sans-serif;\"><span style=\"font-size: 14px;\">基于Laravel框架开发</span></span></li>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\"><span style=\"color: #333333; font-family: Microsoft YaHei, tahoma, arial, Hiragino Sans GB, 宋体, sans-serif;\"><span style=\"font-size: 14px;\">强大的插件机制，轻松的功能拓展</span></span></li>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\">商城支持多语言\/多货币，支付方式多样，支持PayPal、stripe等支付</li>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\"><span style=\"color: #333333; font-family: Microsoft YaHei, tahoma, arial, Hiragino Sans GB, 宋体, sans-serif;\"><span style=\"font-size: 14px;\">严格遵循MVC架构</span></span></li>\r\n<li style=\"box-sizing: border-box; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', 宋体, sans-serif; font-size: 14px; background-color: #ffffff;\">首页UI布局由你选</li>\r\n</ul>\r\n</li>\r\n</ul>\r\n<hr style=\"box-sizing: content-box; height: 0px; margin-top: 20px; margin-bottom: 20px; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-image: initial; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important; border-color: #eeeeee initial initial initial;\" \/>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; outline: none !important;\">联系方式:</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">公司汇聚行业精英，拥有高端、诚信、专业、高效的服务团队和技术团队，为广大客户提供安全、稳定的系统软件和服务。如需商业合作，请联系我们！</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">工作时间：周一到周五（9:00 - 18:00）</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">联系人：梁先生</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">公司邮箱：support@example.com</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">邮政编码：611731</p>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 14px; color: #333333; font-family: 'Microsoft YaHei', tahoma, arial, 'Hiragino Sans GB', '\\宋体', sans-serif; font-size: 14px; background-color: #ffffff; padding-left: 30px; outline: none !important;\">地址：成都天府软软件园</p>",
				"meta_title" => "",
				"meta_description" => "",
				"meta_keywords" => "",
				"created_at" => "2022-09-02 11:04:30",
				"updated_at" => "2022-09-02 11:04:30"
			],
            [
                "id" =>199,
                "page_id" =>22,
                "locale" =>"zh_cn",
                "title" =>"Beikeshop {$version} 新版版本发布！！！",
                "summary" =>"新闻博客做为重要的文字营销工具，是电商网站必不可少的功能之一。博客正文内容支持 HTML 常用标签等.....",
                "content" =>"<p>Beikeshop {$version} 新版版本发布！！！</p>",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ],
            [
                "id" =>200,
                "page_id" =>22,
                "locale" =>"en",
                "title" =>"Beikeshop {$version} new version released! ! !",
                "summary" =>"As an important text marketing tool, news blogs are one of the essential functions of e-commerce websites. Blog body content supports HTML common tags, etc.....",
                "content" =>"<p>Beikeshop {$version} new version released! ! !</p>",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ],
            [
                "id" =>203,
                "page_id" =>23,
                "locale" =>"zh_cn",
                "title" =>"新一代开源跨境电商系统，BeikeShop重磅上线！",
                "summary" =>"新闻博客做为重要的文字营销工具，是电商网站必不可少的功能之一。博客正文内容支持 HTML 常用标签等...",
                "content" =>"随着一带一路的蓬勃发展，以及中国制造亟需出海拓展全球市场，外贸行业仍然是大国创造GDP的核心引擎.光大网络科技长期深耕跨境电商行业，经过多年的摸索与思考，开发出了一套更加符合国内出海企业需求的开源电商系统&mdash;&mdash;BeikeShop&mdash;&mdash;助力企业品牌出海。",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ],
            [
                "id" =>204,
                "page_id" =>23,
                "locale" =>"en",
                "title" =>"A new generation of open source cross-border e-commerce system, BeikeShop is launched!",
                "summary" =>"As an important text marketing tool, news blogs are one of the essential functions of e-commerce websites. Blog body content supports HTML common tags, etc.....",
                "content" =>"随着一带一路的蓬勃发展，以及中国制造亟需出海拓展全球市场，外贸行业仍然是大国创造GDP的核心引擎.光大网络科技长期深耕跨境电商行业，经过多年的摸索与思考，开发出了一套更加符合国内出海企业需求的开源电商系统&mdash;&mdash;BeikeShop&mdash;&mdash;助力企业品牌出海",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ],
            [
                "id" =>209,
                "page_id" =>24,
                "locale" =>"zh_cn",
                "title" =>"BeikeShop 多语言支持助您一臂之力",
                "summary" =>"新闻博客做为重要的文字营销工具，是电商网站必不可少的功能之一。博客正文内容支持 HTML 常用标签等.....",
                "content" =>"新闻博客做为重要的文字营销工具，是电商网站必不可少的功能之一。博客正文内容支持 HTML 常用标签等.....",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ],
            [
                "id" =>210,
                "page_id" =>24,
                "locale" =>"en",
                "title" =>"BeikeShop multi-language support helps you",
                "summary" =>"As an important text marketing tool, news blogs are one of the essential functions of e-commerce websites. Blog body content supports HTML common tags, etc.....",
                "content" =>"As an important text marketing tool, news blogs are one of the essential functions of e-commerce websites. Blog body content supports HTML common tags, etc.....",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ],
            [
                "id" =>211,
                "page_id" =>25,
                "locale" =>"zh_cn",
                "title" =>"性能提升秘籍 | 如何打造闪电般快速的 BeikeShop",
                "summary" =>"新闻博客做为重要的文字营销工具，是电商网站必不可少的功能之一。博客正文内容支持 HTML 常用标签等.....",
                "content" =>"新闻博客做为重要的文字营销工具，是电商网站必不可少的功能之一。博客正文内容支持 HTML 常用标签等.....",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ],
            [
                "id" =>212,
                "page_id" =>25,
                "locale" =>"en",
                "title" =>"Performance Tips | How to Build a Lightning-Fast BeikeShop",
                "summary" =>"As an important text marketing tool, news blogs are one of the essential functions of e-commerce websites. Blog body content supports HTML common tags, etc.....",
                "content" =>"As an important text marketing tool, news blogs are one of the essential functions of e-commerce websites. Blog body content supports HTML common tags, etc.....",
                "meta_title" =>"",
                "meta_description" =>"",
                "meta_keywords" =>"",
            ]
        ];
    }
}
