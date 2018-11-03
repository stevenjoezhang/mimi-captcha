# Mimi Captcha

* Contributors: stevenjoezhang
* Donate link: https://zhangshuqiao.org/2018-07/WordPress中文验证码/
* Tags: captcha, captcha code, security, spam blocker, forms captcha, protection, text captcha, anti-spam
* Requires at least: 3.0
* Tested up to: 5.0
* Requires PHP: 5.2.4
* Stable tag: 0.0.5
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds Captcha Code anti-spam methods to WordPress forms. Supports numbers, alphabets and Chinese characters.
在WordPress登陆、注册或评论表单中加入验证码功能，支持字母、数字、中文和算术验证码。

## Description

Adds Captcha Code anti-spam methods to WordPress forms. Forms include comments form, registration form, lost passwordform and login form. In order to post comments or register, users will have to type in the code shown on the image. This prevents spam from automated bots. Adds security.
在WordPress登陆、注册或评论表单中加入验证码功能，支持字母、数字、中文和算术验证码。用户需要输入验证码才可以进行进一步操作，这阻止了垃圾评论和机器人，增加安全性。

### Features

1. Administrator can specify where the captcha should be displayed i.e, comments, login, registration or lost password form.
管理员可以决定在哪里显示这些验证码，例如评论、登陆、注册或找回密码窗口。
2. Administrator select the letters type from the options available - Capital letters, Small letters or Captial & Small letters.
管理员可以选择验证码的字母类型：大写、小写或大小写混合。
3. Administrator select the captcha type from the options available - Alphanumeric, Alphabets, numbers or Chinese characters.
管理员可以选择验证码的字符类型：字母、数字、混合或者中文。
4. Translation enabled.
多语言支持。

### Requirements

1. PHP gd2 extension is required. You can run 'php -m' or use 'phpinfo()' to check if it's installed properly.
生成验证码的'imagettftext()'函数需要gd库和FreeType支持。您可以通过执行'php -m'或'phpinfo()'检查php是否具有此拓展，以确保验证码能够正确显示。
2. Upgrade your PHP and WordPress to the newest version for the best performance.
虽然本插件可以在PHP 5.2.4和WordPress 3.0环境下运行，但为了避免出现不可预料的问题，建议将PHP更新至7.2以上的版本，WordPress更新至最新版本。

### Note

1. If you find any bugs, please report in the following link, so that it will be fixed as quick as possible.
如果您找到了任何BUG，请通过下方的博客页面链接进行报告，这样我们才能尽快改正。
2. If you think any feature adding to this plugin can improve its features, please recommend it in the following link.
如果您认为可以增加新功能，请通过下方的博客页面链接给我们建议。

### Support

Thanks for downloading and installing my plugin. You can show your appreciation and support future development by donating. Blog page:
感谢您下载安装这个插件。您可以通过氪金来支持我们继续开发。博客页面：

[WordPress中文验证码](https://zhangshuqiao.org/2018-07/WordPress中文验证码/)

### Development

* Active development of this plugin is handled [on GitHub](https://github.com/stevenjoezhang/mimi-captcha).
* Translation of the plugin into different languages is on the [translation page](https://translate.wordpress.org/projects/wp-plugins/mimi-captcha).

### Known Issues

If you're using Nginx with php-fpm, 'wp_die()' function may not show error page correctly.
在Nginx服务器下，'wp_die()'可能无法正确显示错误信息。
This plugin uses SESSION to save user infomation, you can configure redis or memcached server in your php.ini for better performance.
本插件使用了SESSION存储用户信息，这可能造成性能瓶颈。建议通过在php.ini中设置session.save_handler为redis或memcached（均需要安装拓展），以提高性能，解决此问题。

### TODO List

1. Blacklist
设置黑名单（根据用户分类，或者IP地址）
2. More user selectable fonts
允许用户选择下载字体库

## Installation

1. Download the plugin.
下载插件
2. Upload to your blog (/wp-content/plugins/).
上传至你的博客（/wp-content/plugins/）目录
3. Activate it.
激活插件
4. Click the 'Mimi Captcha' menu.
点击 'Mimi Captcha' 菜单
5. Fill in the options.
进行配置

Important Note: It is mandatory to save options in this plugin.
You're done!
Uninstalling is as simple as deactivating and deleting the plugin.
重要提醒：初次使用时必须进行配置，并手动保存，否则验证码不会生效！
这样就完成了！
卸载只需要禁用并删除该插件即可。

## Screenshots

1. Login form with Mimi Captcha
2. Register form with Mimi Captcha
3. Comments form with Mimi Captcha
4. Mimi Captcha Settings page

## Change Log

### Version 0.0.5

* Bug fixes
修复了已知问题

### Version 0.0.4

* Updated for WordPress 5.0
针对WordPress 5.0版本更新
* 如果需要在登陆密码输错若干次后限制该IP登陆，或拉入黑名单，可以使用插件Limit Login Attempts Reloaded，与本插件兼容且效果较好。

### Version 0.0.3

* Updated readme.txt
更新了readme.txt
* Prepared for localization
规范了多语言支持

### Version 0.0.2

* Bug fixes
修复了已知问题

* Add Password and Repeat Password fields to WordPress registration
允许用户在注册时输入密码

### Version 0.0.1

* Initial release
初始版本

## Upgrade Notice

Have a nice day :)

## Credits

This plugin is inspired by some other plugins. More infomation about them is listed below:
本项目从Vinoj Cardoza的Captcha Code，BestWebSoft的Google Captcha和Sola的User Generate Password插件中获得了灵感。这些项目的重要信息如下：

Plugin Name: Captcha Code
Plugin URI: http://www.vinojcardoza.com/captcha-code-authentication
Description: Adds Captcha Code anti-spam methods to User front-end WordPress forms.
Author: Vinoj Cardoza
Author URI: http://www.vinojcardoza.com
License: GPL2

Plugin Name: Google Captcha (reCAPTCHA) by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/google-captcha
Description: Protect WordPress website forms from spam entries with Google Captcha (reCaptcha).
Author: BestWebSoft
Author URI: https://bestwebsoft.com
License: GPLv3 or later

Plugin Name: User Generate Password
Plugin URI: http://www.solagirl.net/wordpress-user-generate-password.html
Description: Let user enter password instead of generated by WordPress when sign up. 用户注册时可以输入密码。
Author: Sola
Author URI: http://www.solagirl.net
License: Unknown
