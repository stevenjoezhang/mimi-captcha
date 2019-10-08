# Mimi Captcha

* Contributors: stevenjoezhang
* Donate link: https://zhangshuqiao.org/2018-07/WordPress中文验证码/
* Tags: captcha, captcha code, text captcha, forms captcha, security, protection, anti-spam, spam blocker
* Requires at least: 3.0
* Tested up to: 5.2
* Requires PHP: 5.2.4
* Stable tag: 0.2.1
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

简洁的中文验证码插件。在WordPress登陆、注册或评论表单中加入验证码，支持字母、数字、中文和算术形式。
Adds Captcha Code anti-spam methods to WordPress forms. Supports numbers, alphabets and Chinese characters.

## Description

在WordPress登陆、注册或评论表单中加入验证码，支持字母、数字、中文和算术形式。用户需要输入验证码才可以进行进一步操作，这可以有效阻止机器人发表垃圾评论或暴力破解密码，增加安全性。
如果需要在登陆密码输错若干次后限制该IP登陆，或拉入黑名单，可以使用插件Limit Login Attempts Reloaded，与本插件兼容且效果较好。
Adds Captcha code anti-spam methods to WordPress forms. Forms include login form, registration form, lost password form and comments form. In order to post comments or register, users will have to type in the code shown on the image. This prevents spam from automated bots, and increase security.

### Features

1. 管理员可以决定在哪里显示这些验证码。
Administrator can specify where the Captcha should be displayed.

2. 管理员可以选择验证码的字符类型：字母、数字、混合或者中文。
Administrator can select the Captcha type from the options available - Alphanumeric, Alphabets, Numbers or Chinese characters.

3. 管理员可以选择验证码的字母类型：大写、小写或大小写混合。
Administrator can select the letters type from the options available - Capital letters, Small letters or Both.

### Requirements

1. 生成验证码的'imagettftext()'函数需要gd库和FreeType支持。您可以通过执行'php -m'或'phpinfo()'检查php是否具有此拓展，以确保验证码能够正确显示。
PHP gd2 extension is required. You can run 'php -m' or use 'phpinfo()' to check if it's installed properly.

2. 虽然本插件可以在PHP 5.2.4和WordPress 3.0环境下运行，但为了避免出现不可预料的问题，建议将PHP更新至7.0以上的版本，WordPress更新至最新版本。
Upgrade your PHP and WordPress to the latest version for better performance.

### Support

感谢您下载安装这个插件。您可以通过氪金来支持我们继续开发。博客页面：
Thanks for downloading and installing this plugin. You can show your appreciation and support future development by donating. Blog page:

[WordPress中文验证码](https://zhangshuqiao.org/2018-07/WordPress中文验证码/)

### Note

1. 如果您发现了任何BUG，请通过上方的博客页面链接进行报告，这样我们才能尽快修正。
If you find any bugs, please report in the blog page above, so that it will be fixed as soon as possible.

2. 如果您认为可以增加新功能，请通过上方的博客页面链接给我们建议。
If you think any feature adding to this plugin can improve its features, please recommend it in the blog page above.

### Development

1. Active development of this plugin is handled [on GitHub](https://github.com/stevenjoezhang/mimi-captcha).

2. Translation of the plugin into different languages is on the [translation page](https://translate.wordpress.org/projects/wp-plugins/mimi-captcha).

### Known Issues

本插件使用了SESSION存储用户信息，这可能造成性能瓶颈。建议通过在php.ini中设置session.save_handler为redis或memcached（均需要安装拓展），以提升性能。
This plugin uses SESSION to save user infomation, you can configure redis or memcached server in your php.ini for better performance.

### TODO List

1. 设置黑名单（根据用户名，或者IP地址）
Blacklist

2. 允许用户选择下载字体库
Provide more user selectable fonts

## Installation

1. 下载插件
Download the plugin

2. 上传至你的博客（/wp-content/plugins/）目录
Upload to your blog (/wp-content/plugins/)

3. 激活插件
Activate it

4. 在'设置'中点击'Mimi Captcha'菜单
Click the 'Mimi Captcha' menu in 'Settings'

5. 进行配置
Fill in the options

重要提醒：初次使用时必须进行配置，并手动保存，否则验证码不会生效！
如需卸载，直接禁用并删除该插件即可。
Important Note: It is mandatory to save options in this plugin.
Uninstalling is as simple as deactivating and deleting the plugin.

## Screenshots

1. Login form with Mimi Captcha

2. Registration form with Mimi Captcha

3. Lost Password form with Mimi Captcha

4. Comments form with Mimi Captcha

5. Comments form with Mimi Captcha

6. Mimi Captcha settings page

## Change Log

### Version 0.2.0

* 修复站点健康问题
Fix Site Health issue

### Version 0.1.2

* 优化代码风格
Code style update

### Version 0.1.1

* 增加了更多提示信息
Add more alerts

### Version 0.0.7

* 优化了验证码生成算法
Optimize Captcha generation algorithm

* 增加了更多设置项
Add more options

### Version 0.0.6

* 升级了验证码生成算法
Upgrade Captcha generation algorithm

* 统一了错误信息的提示方式
Unify the error messages prompted

### Version 0.0.5

* 更新了样式表，适用更多的主题
Update stylesheet

* 增加了可用字体
Add more fonts

### Version 0.0.4

* 针对WordPress 5.0版本更新
Update for WordPress 5.0

* 新功能：白名单
New feature: whitelist

### Version 0.0.3

* 规范了多语言支持
Prepare for localization

### Version 0.0.2

* 修复了已知问题
Bug fixes

* 允许用户在注册时输入密码
Add Password and Repeat Password fields to WordPress registration

### Version 0.0.1

* 初始版本
Initial release

## Upgrade Notice

Have a nice day :)

## Credits

本项目从Vinoj Cardoza的Captcha Code，BestWebSoft的Google Captcha和Sola的User Generate Password插件中获得了灵感。这些项目的重要信息摘录如下：
This plugin is inspired by some other plugins. More infomation about them is listed below:

Plugin Name: Captcha Code
Plugin URI: http://www.vinojcardoza.com/captcha-code-authentication
Description: Adds Captcha Code anti-spam methods to User front-end WordPress forms.
Author: Vinoj Cardoza
Author URI: [http://www.vinojcardoza.com](http://www.vinojcardoza.com)
License: GPL2

Plugin Name: Google Captcha (reCAPTCHA) by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/google-captcha
Description: Protect WordPress website forms from spam entries with Google Captcha (reCaptcha).
Author: BestWebSoft
Author URI: [https://bestwebsoft.com](https://bestwebsoft.com)
License: GPLv3 or later

Plugin Name: User Generate Password
Plugin URI: http://www.solagirl.net/wordpress-user-generate-password.html
Description: Let user enter password instead of generated by WordPress when sign up. 用户注册时可以输入密码。
Author: Sola
Author URI: [http://www.solagirl.net](http://www.solagirl.net)
License: Unknown
