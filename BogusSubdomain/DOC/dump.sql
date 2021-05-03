-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.19 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table bogus.ips
CREATE TABLE IF NOT EXISTS `ips` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` int unsigned DEFAULT NULL COMMENT 'IP адрес',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='IP адреса черного списка';

-- Dumping data for table bogus.ips: ~300,810 rows (approximately)
/*!40000 ALTER TABLE `ips` DISABLE KEYS */;
/*!40000 ALTER TABLE `ips` ENABLE KEYS */;

-- Dumping structure for table bogus.useragents
CREATE TABLE IF NOT EXISTS `useragents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `useragent` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Юзерагент',
  PRIMARY KEY (`id`),
  UNIQUE KEY `useragent_UNIQUE` (`useragent`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Юзерагенты поисковых роботов';

-- Dumping data for table bogus.useragents: ~47 rows (approximately)
/*!40000 ALTER TABLE `useragents` DISABLE KEYS */;
INSERT INTO `useragents` (`id`, `useragent`) VALUES
	(20, '(compatible; Mediapartners-Google/2.1; +http://www.google.com/bot.html)'),
	(15, 'AdsBot-Google (+http://www.google.com/adsbot.html)'),
	(21, 'AdsBot-Google-Mobile-Apps'),
	(16, 'Googlebot-Image/1.0'),
	(17, 'Googlebot-News'),
	(18, 'Googlebot-Video/1.0'),
	(12, 'Mediapartners-Google'),
	(90, 'Mozilla/5.0 (compatible; AhrefsBot/6.1; +http://ahrefs.com/robot/'),
	(40, 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)'),
	(41, 'Mozilla/5.0 (compatible; DuckDuckGo-Favicons-Bot/1.0; +http://duckduckgo.com)'),
	(19, 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
	(91, 'Mozilla/5.0 (compatible; SemrushBot/6~bl; +http://www.semrush.com/bot.html'),
	(10, 'Mozilla/5.0 (compatible; YandexBlogs/0.99; robot; +http://yandex.com/bots)'),
	(4, 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)'),
	(31, 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106'),
	(5, 'Mozilla/5.0 (compatible; YandexBot/3.0; MirrorDetector; +http://yandex.com/bots)'),
	(6, 'Mozilla/5.0 (compatible; YandexImages/3.0; +http://yandex.com/bots)'),
	(9, 'Mozilla/5.0 (compatible; YandexMedia/3.0; +http://yandex.com/bots)'),
	(30, 'Mozilla/5.0 (compatible; YandexMetrika/2.0; +http://yandex.com/bots yabs01)'),
	(26, 'Mozilla/5.0 (compatible; YandexMetrika/2.0; +http://yandex.com/bots)'),
	(34, 'Mozilla/5.0 (compatible; YandexMetrika/4.0; +http://yandex.com/bots)'),
	(7, 'Mozilla/5.0 (compatible; YandexVideo/3.0; +http://yandex.com/bots)'),
	(8, 'Mozilla/5.0 (compatible; YandexWebmaster/2.0; +http://yandex.com/bots)'),
	(29, 'Mozilla/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B411 Safari/600.1.4 (compatible; YandexMobileBot/3.0; +http://yandex.com/bots)'),
	(14, 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 (compatible; AdsBot-Google-Mobile; +http://www.google.com/mobile/adsbot.html)'),
	(88, 'Mozilla/5.0 (Linux; Android 10) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/79.0.3945.116 Mobile DuckDuckGo/5 Safari/537.36'),
	(74, 'Mozilla/5.0 (Linux; Android 4.4.2; HUAWEI P7-L10 Build/HuaweiP7-L10) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36 YandexSearch/7.16'),
	(13, 'Mozilla/5.0 (Linux; Android 5.0; SM-G920A) AppleWebKit (KHTML, like Gecko) Chrome Mobile Safari (compatible; AdsBot-Google-Mobile; +http://www.google.com/mobile/adsbot.html)'),
	(39, 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.92 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
	(36, 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.122 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
	(83, 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.98 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
	(72, 'Mozilla/5.0 (Linux; Android 6.0.1; SM-J710F Build/MMB29K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36 YandexSearch/6.45'),
	(71, 'Mozilla/5.0 (Linux; Android 7.1.2; ZTE BLADE A330 Build/N2G47H; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36 YandexSearch/7.53 YandexSearchBrowser/7.53'),
	(70, 'Mozilla/5.0 (Linux; Android 8.1.0; Lenovo TB-7104I Build/O11019; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/70.0.3538.110 Safari/537.36 YandexSearch/7.52/apad YandexSearchBrowser/7.52'),
	(69, 'Mozilla/5.0 (Linux; Android 8.1.0; Redmi Note 5 Build/OPM1.171019.011; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/72.0.3626.121 Mobile Safari/537.36 YandexSearch/7.45 YandexSearchBrowser/7.45'),
	(67, 'Mozilla/5.0 (Linux; Android 8.1.0; SM-J415FN Build/M1AJQ; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/67.0.3396.87 Mobile Safari/537.36 YandexSearch/7.90 YandexSearchBrowser/7.90'),
	(87, 'Mozilla/5.0 (Linux; Android 9) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/74.0.3729.136 Mobile DuckDuckGo/5 Safari/537.36'),
	(64, 'Mozilla/5.0 (Linux; Android 9; LLD-L31 Build/HONORLLD-L31; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/74.0.3729.136 Mobile Safari/537.36 YandexSearch/7.80 YandexSearchBrowser/7.80'),
	(66, 'Mozilla/5.0 (Linux; Android 9; YAL-L41 Build/HUAWEIYAL-L41; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/77.0.3865.92 Mobile Safari/537.36 YandexSearch/7.80 YandexSearchBrowser/7.80'),
	(89, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Safari/605.1.15 (Applebot/0.1; +http://www.apple.com/go/applebot)'),
	(81, 'Mozilla/5.0 (Windows NT 5.1; rv:11.0) Gecko Firefox/11.0 (via ggpht.com GoogleImageProxy)'),
	(85, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534+ (KHTML, like Gecko) BingPreview/1.0b'),
	(57, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36 Google Favicon'),
	(80, 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/83.0.4103.122 Safari/537.36'),
	(82, 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Chrome/84.0.4147.98 Safari/537.36'),
	(11, 'PIs-Google (+https://developers.google.com/webmasters/APIs-Google.html)'),
	(45, 'Twitterbot/1.0');
/*!40000 ALTER TABLE `useragents` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
