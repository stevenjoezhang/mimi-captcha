<?php
/**
 * 安全验证码
 * 安全的验证码要：验证码文字扭曲、旋转，使用不同字体，添加干扰码
 *
 * @author 流水孟春 <cmpan(at)qq.com>
 * @link http://labs.yulans.cn/YL_Security_Secoder
 * @link http://wiki.yulans.cn/docs/yl/security/secoder
 */
class Mimi_Captcha_Image {

	// 验证码中的参数
	protected static $code = ['无', '验', '证', '码'];
	protected static $bg = null;
	protected static $fonts = []; // 可用的字体
	protected static $image_L = 0; // 验证码图片长
	protected static $image_H = 0; // 验证码图片宽
	protected static $image = null; // 验证码图片实例

	// Settings: You can customize the captcha here
	protected static $fontSize = 30; // 验证码字体大小(px)
	protected static $useCurve = true; // 是否画混淆曲线
	protected static $useNoise = true; // 是否添加杂点
	protected static $distort = true; // 是否扭曲

/**
 * 构造函数
 * @var array $code
 * @var int   $flag
 */
	function __construct($code, $flag = 7) {
		// 验证码文字
		self::$code = $code;
		// 设置背景颜色
		self::$bg = [
			mt_rand(236, 244),
			mt_rand(242, 252),
			mt_rand(247, 255)
		];
		self::$useCurve = $flag >> 2 & 0x01;
		self::$useNoise = $flag >> 1 & 0x01;
		self::$distort = $flag & 0x01;
	}

/**
 * 输出验证码
 * 内容为self::$code，字体和颜色随机
 */
	public static function entry() {
		// 验证码使用随机字体
		self::$fonts = glob(dirname(__FILE__).'/fonts/*.ttf');
		if (count(self::$fonts) === 0) {
			die("Error: No fonts are available. Please upload fonts to /wp-content/plugins/mimi-captcha/fonts/ folder.");
		}
		// 图片宽(px)
		self::$image_L || self::$image_L = self::$fontSize * (count(self::$code) * 1.6 + 0.8);
		// 图片高(px)
		self::$image_H || self::$image_H = self::$fontSize * 2;
		// 建立一幅 self::$image_L x self::$image_H 的图像
		self::$image = imagecreate(self::$image_L, self::$image_H);
		imagecolorallocate(
			self::$image,
			self::$bg[0],
			self::$bg[1],
			self::$bg[2]
		);
		if (self::$useCurve) {
			self::writeCurve(); // 绘干扰线
		}
		// 绘验证码
		for ($i = 0; $i < count(self::$code); $i++) {
			$codeNX = self::$fontSize * mt_rand(16 * $i + 4, 16 * $i + 12) / 10; // 验证码第N个字符的左边距
			$ttf = self::$fonts[mt_rand(0, count(self::$fonts) - 1)];
			// 写一个验证码字符
			imagettftext(
				self::$image,
				self::$fontSize,
				mt_rand(-20, 20),
				$codeNX,
				self::$fontSize * 3 / 2,
				self::color(0, 120), // 验证码文字颜色
				$ttf,
				self::$code[$i]
			);
		}
		if (self::$distort) {
			self::distortion(); // 扭曲验证码
		}
		if (self::$useNoise) {
			self::writeNoise(); // 绘杂点
		}
		// Show captcha image in the html page
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Content-Type: image/png'); // Defining the image type to be shown in browser window

		// 输出图像
		imagepng(self::$image); // Showing the image
		imagedestroy(self::$image); // Destroying the image instance
	}

/**
 * 生成随机颜色
 * 返回值为imagecolorallocate的索引
 */
	protected static function color($low, $high) {
		// Random color
		return imagecolorallocate(
			self::$image,
			mt_rand($low, $high),
			mt_rand($low, $high),
			mt_rand($low, $high)
		);
	}

/**
 * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线
 * 可以改成更帅的曲线函数，比如Bessel函数
 *
 * 正弦型函数解析式：y=Asin(ωx+φ)+b
 * 各参数值对函数图像的影响：
 * A：振幅，决定峰值（即纵向拉伸压缩的倍数）
 * ω：圆频率，最小正周期T=2π/∣ω∣
 * φ：初相位，决定波形与X轴位置关系或横向移动距离（左加右减）
 * b：偏置，表示波形在Y轴的位置关系或纵向移动距离（上加下减）
 */
	protected static function writeCurve() {
		$A = mt_rand(1, self::$image_H / 2); // 振幅
		$T = mt_rand(self::$image_H * 3 / 2, self::$image_L * 2); // 周期
		$omega = (2 * M_PI) / $T; // 圆频率
		$phi = mt_rand(-self::$image_H / 4, self::$image_H / 4); // X轴方向偏移量
		$b = mt_rand(-self::$image_H / 4, self::$image_H / 4); // Y轴方向偏移量
		$px1 = 0; // 曲线横坐标起始位置
		$px2 = mt_rand(self::$image_L / 2, self::$image_L * 2 / 3); // 曲线横坐标结束位置
		$color = self::color(120, 160);
		for ($px = $px1; $px <= $px2; $px += 0.9) {
			$py = $A * sin($omega * $px + $phi) + $b + self::$image_H / 2; // y=Asin(ωx+φ)+b
			$i = (int) ((self::$fontSize - 6) / 4);
			while ($i > 0) {
				imagesetpixel(
					self::$image,
					(int)($px + $i),
					(int)($py + $i),
					(int)$color
				);
				$i--;
			}
		}

		$A = mt_rand(1, self::$image_H / 2);
		$T = mt_rand(self::$image_H * 3 / 2, self::$image_L * 2);
		$omega = (2 * M_PI) / $T;
		$phi = mt_rand(-self::$image_H / 4, self::$image_H / 4);
		$b = $py - $A * sin($omega * $px + $phi) - self::$image_H / 2;
		$px1 = $px2;
		$px2 = self::$image_L;
		$color = self::color(120, 160);
		for ($px = $px1; $px <= $px2; $px += 0.9) {
			$py = $A * sin($omega * $px + $phi) + $b + self::$image_H / 2;
			$i = (int) ((self::$fontSize - 8) / 4);
			while ($i > 0) {
				imagesetpixel(
					self::$image,
					(int)($px + $i),
					(int)($py + $i),
					(int)$color
				);
				$i--;
			}
		}
	}

/**
 * 扭曲图片
 * 对图片进行变换
 */
	protected static function distortion() {
		$distortion = imagecreate(self::$image_L, self::$image_H);
		imagecolorallocate(
			$distortion,
			self::$bg[0],
			self::$bg[1],
			self::$bg[2]
		);
		$phase = M_PI * mt_rand(-1, 1); // 初相位
		$offset = 0; // 偏置
		$amplitude = mt_rand(4, 6); // 振幅
		$round = 2; // 扭2个周期
		for ($i = 0; $i < self::$image_L; $i++) {
			$posY = round(sin($i * $round * 2 * M_PI / self::$image_L + $phase) * $amplitude + $offset);
			// 根据正弦曲线，计算偏移量
			imagecopy($distortion, self::$image, $i, $posY, $i, 0, 1, self::$image_H);
		}
		imagedestroy(self::$image);
		self::$image = $distortion;
	}

/**
 * 往图片上画不同颜色的杂点
 * 杂点文本为随机的字母或数字
 */
	protected static function writeNoise() {
		$noiseSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*#';
		for ($i = 0; $i < 5; $i++) {
			$noiseColor = self::color(160, 225); // 杂点颜色
			for ($j = 0; $j < 4; $j++) {
				// 绘杂点
				imagestring(
					self::$image,
					5,
					mt_rand(-10, self::$image_L),
					mt_rand(-10, self::$image_H),
					$noiseSet[mt_rand(0, strlen($noiseSet) - 1)],
					$noiseColor
				);
			}
		}
	}
}
?>
