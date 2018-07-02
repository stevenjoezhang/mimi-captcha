=== Mimi Captcha ===

Contributors: vinoj.cardoza, simplywordpress, stevenjoezhang
Donate link: https://galaxymimi.com
Tags: captcha, captcha code, wordpress captcha, captcha for wordpress, forms captcha, captcha security, security
Requires at least: 3.0
Tested up to: 4.9.6
Stable tag: trunk
License: GPLv2 or later

Adds Captcha Code anti-spam methods to WordPress forms. Supports numbers, alphabets and Chinese characters.
在WordPress登陆、注册或评论表单中加入验证码功能，支持字母、数字、中文和算术验证码。

This plugin is inspired by Vinoj Cardoza's Captcha Code and simplywordpress's Captcha. More about them:
本项目从Vinoj Cardoza的Captcha Code和simplywordpress的Captcha插件中获得了灵感。原项目的重要信息如下：

Plugin Name: Captcha Code
Plugin URI: http://www.vinojcardoza.com/captcha-code-authentication/
Description: Adds Captcha Code anti-spam methods to User front-end WordPress forms.
Version: 2.6.6
Author: Vinoj Cardoza
Author URI: http://www.vinojcardoza.com
License: GPL2
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=vinoj%2ecardoza%40gmail%2ecom&currency_code=GBP&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8

Plugin Name: Captcha
Plugin URI: https://wordpress.org/plugins/captcha/
Description: This plugin allows you to implement super security captcha form into web forms.
Author: simplywordpress
Version: 4.4.6
Author URI: https://wordpress.org/plugins/captcha/
License: GPLv2 or later

== Description ==

Adds Captcha Code anti-spam methods to WordPress forms. Forms include comments form, registration form, lost passwordform and login form. In order to post comments or register, users will have to type in the code shown on the image. This prevents spam from automated bots. Adds security.
在WordPress登陆、注册或评论表单中加入验证码功能，支持字母、数字、中文和算术验证码。用户需要输入验证码才可以进行进一步操作，这阻止了垃圾评论和机器人，增加安全性。

= Features =

1. Administrator can specify where the captcha should be displayed i.e, comments, login, registration or lost password form.
2. Administrator select the letters type from the options available - Capital letters, Small letters or Captial & Small letters.
3. Administrator select the captcha type from the options available - Alphanumeric, Alphabets, numbers or Chinese characters.
4. Translation enabled.
1. 管理员可以决定在哪里显示这些验证码，例如评论、登陆、注册或找回密码窗口。
2. 管理员可以选择验证码的字母类型：大写、小写或大小写混合。
3. 管理员可以选择验证码的字符类型：字母、数字、混合或者中文。
4. 多语言支持。

= Requirements =

PHP gd2 extension is required. You can run 'php -m' or use 'phpinfo()' to check if it's installed properly.
生成验证码的imagettftext()函数需要gd库和FreeType支持。您可以通过执行php -m或phpinfo()检查php是否具有此拓展，若无则验证码无法正确显示。

= Note =

1. If you find any bugs, please report in the following link, so that it will be fixed as quick as possible.
2. If you think any feature adding to this plugin can improve its features, please recommend it in the following link.
1. 如果您找到了任何BUG，请通过下方的链接进行报告，这样我们才能尽快改正。
2. 如果您认为可以增加新功能，请通过下方的链接给我们建议。

= Support =

Thanks for downloading and installing my plugin. You can show your appreciation and support future development by donating.
感谢您下载安装这个插件。您可以通过氪金来支持我们继续开发。

Blog page: http://www.vinojcardoza.com/captcha-code-authentication/
博客页面：http://www.vinojcardoza.com/captcha-code-authentication/
https://zhangshuqiao.org/2018-07/WordPress中文验证码/

= Known Issues =

在Nginx服务器下，wp_die()可能无法正确显示错误信息。
SESSION的存储可能造成性能瓶颈，建议通过在php.ini中设置session.save_handler为redis或memcached（均需要拓展）等解决。

= TODO List =
设置白名单（根据用户分类，或者IP）
选择下载字体库

== Installation ==

1. Download the plugin.
2. Upload to your blog (/wp-content/plugins/).
3. Activate it.
4. Click the 'Captcha' menu.
6. Fill in the options.

Important Note: It is mandatory to save options in this plugin.
You're done!
Uninstalling is as simple as deactivating and deleting the plugin.

1. 下载插件
2. 上传至你的博客 (/wp-content/plugins/).
3. 激活插件
4. 点击 'Mimi Captcha' 菜单
5. 进行配置

重要提醒：初次使用时必须进行配置，并手动保存，否则验证码不会生效！
（本插件会自动提醒用户进行配置）
这样就完成了！
卸载只需要禁用并删除改插件即可。

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png

== Change Log ==

= Version 0.0.1 =
* 初始版本
