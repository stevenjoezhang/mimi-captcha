# Mimi Captcha

* Contributors: stevenjoezhang
* Donate link: https://zhangshuqiao.org/2018-07/WordPress中文验证码/
* Tags: captcha, captcha code, text captcha, forms captcha, security, protection, anti-spam, spam blocker
* Requires at least: 3.7
* Tested up to: 6.4
* Requires PHP: 8.0
* Stable tag: 0.6.1
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html

简洁的中文验证码插件。在 WordPress 登陆、注册或评论表单中加入验证码，支持字母、数字、中文和算术形式。
Adds Captcha Code anti-spam methods to WordPress forms. Supports numbers, alphabets and Chinese characters.

## Description

在 WordPress 登陆、注册或评论表单中加入验证码，支持字母、数字、中文和算术形式。用户需要输入验证码才可以进行进一步操作，这可以有效阻止机器人发表垃圾评论或暴力破解密码，增加安全性。
如果需要在登陆密码输错若干次后限制该 IP 登陆，或拉入黑名单，可以将本插件配合 Limit Login Attempts Reloaded 插件使用。
Adds Captcha code anti-spam methods to WordPress forms. Forms include login form, registration form, lost password form and comments form. In order to post comments or register, users will have to type in the code shown on the image. This prevents spam from automated bots, and increase security.

### Features

1. 管理员可以设置在哪些情况下需要输入验证码。
Administrator can specify where the Captcha should be displayed.

2. 管理员可以选择验证码的字符类型：字母、数字、混合或者中文。
Administrator can select the Captcha type from the options available - Alphanumeric, Alphabets, Numbers or Chinese characters.

3. 管理员可以选择验证码的字母类型：大写、小写或大小写混合。
Administrator can select the letters type from the options available - Capital letters, Small letters or Both.

### Requirements

1. 生成验证码的 'imagettftext()' 函数需要 gd 库和 FreeType 支持。您可以通过执行 'php -m' 或 'phpinfo()' 检查 php 是否具有此拓展，以确保验证码能够正确显示。
PHP gd2 extension is required. You can run 'php -m' or use 'phpinfo()' to check if it's installed properly.

2. 为了避免出现安全问题，建议将 PHP 更新至 7.4 以上的版本，WordPress 更新至最新版本。
Upgrade your PHP and WordPress to the latest version to avoid security vulnerabilities.

### Support

感谢您下载安装这个插件。您可以通过氪金来支持我们继续开发。博客页面：
Thanks for downloading and installing this plugin. You can show your appreciation and support future development by donating. Blog page:

[WordPress中文验证码](https://zhangshuqiao.org/2018-07/WordPress中文验证码/)

### Development

1. Active development of this plugin is handled [on GitHub](https://github.com/stevenjoezhang/mimi-captcha).

2. Translation of the plugin into different languages is on the [translation page](https://translate.wordpress.org/projects/wp-plugins/mimi-captcha).

### Note

1. 如果您发现了任何 BUG，请通过上方的 GitHub 仓库页面进行报告，这样我们才能尽快修正。
If you find any bugs, please report in the GitHub repository above, so that it will be fixed as soon as possible.

2. 如果您认为可以增加新功能，请通过上方的 GitHub 仓库页面给我们建议。
If you think any feature adding to this plugin can improve its features, please recommend it in the GitHub repository above.

### Known Issues

本插件使用了 SESSION 存储用户信息，这可能造成性能瓶颈。建议通过在 php.ini 中设置 session.save_handler 为 redis 或 memcached（均需要安装拓展），以提升性能。
This plugin uses SESSION to save user information, you can configure redis or memcached server in your php.ini for better performance.

### TODO List

1. 设置黑名单（根据用户名，或者 IP 地址）
Blocklist

2. 允许用户选择下载字体库
Provide more user selectable fonts

## Installation

1. 下载插件
Download the plugin

2. 上传至你的博客（/wp-content/plugins/）目录
Upload to your blog (/wp-content/plugins/)

3. 激活插件
Activate it

4. 在 '设置' 中点击 'Mimi Captcha' 菜单
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

### Version 0.6.0

* 要求 PHP 7.4 及更新版本
Require PHP 7.4 or newer

### Version 0.5.0

* 要求 PHP 7.3 及更新版本
Require PHP 7.3 or newer

### Version 0.4.0

* 修复与 Kratos 主题的兼容性问题
Fix compatibility issue with theme Kratos

### Version 0.3.2

* 更新翻译
Update translation

### Version 0.3.1

* 要求 PHP 7.2 及更新版本
Require PHP 7.2 or newer

### Version 0.3.0

* 要求 PHP 7.1 及更新版本
Require PHP 7.1 or newer

### Version 0.2.1

* 修复了已知问题
Bug fixes

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

* 针对 WordPress 5.0 版本更新
Update for WordPress 5.0

* 新功能：白名单
New feature: allowlist

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

本项目从 Vinoj Cardoza 的 Captcha Code，BestWebSoft 的 Google Captcha 和 Sola 的 User Generate Password 插件中获得了灵感。这些项目的重要信息摘录如下：
This plugin is inspired by some other plugins. More information about them is listed below:

Plugin Name: Captcha Code
Plugin URI: https://cn.wordpress.org/plugins/captcha-code-authentication/
Description: Adds Captcha Code anti-spam methods to User front-end WordPress forms.
Author: Vinoj Cardoza
Author URI: https://www.cardozatechnologies.com
License: GPL2

Plugin Name: Google Captcha (reCAPTCHA) by BestWebSoft
Plugin URI: https://cn.wordpress.org/plugins/google-captcha/
Description: Protect WordPress website forms from spam entries with Google Captcha (reCaptcha).
Author: BestWebSoft
Author URI: https://bestwebsoft.com
License: GPLv3 or later

Plugin Name: User Generate Password
Plugin URI: https://www.solagirl.net/wordpress-user-generate-password.html
Description: Let user enter password instead of generated by WordPress when sign up. 用户注册时可以输入密码。
Author: Sola
Author URI: https://www.solagirl.net
License: Unknown
