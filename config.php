<?php
// Время начала генерации страницы
$microtime = explode(' ', microtime());
$microtime = $microtime[1] + $microtime[0];
define('GENERATE_START_TIME', $microtime);
unset($microtime);

include('./core_function.php');


// Часовой пояс
date_default_timezone_set("Asia/Bishkek");

// Кодировки
ini_set('mbstring.internal_encoding', 'utf-8');
ini_set('mbstring.http_output', 'utf-8');
ini_set('mbstring.http_input', 'utf-8');
ini_set('mbstring.internal_encoding', 'utf-8');

// Заголовки
header('Content-type:text/html;charset=utf-8');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // дата в прошлом
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT'); // всегда модифицируется
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: post-check=0, pre-check=0', false);
header("Pragma: no-cache"); // HTTP/1.0

// Куки
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.save_path', './var/session/');

// Показывать все предупреждения и ошибки
error_reporting(E_ALL);
ini_set('html_errors', 'on');
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

// Очистка файлов сессий
ini_set('session.gc_divisor', 100);
ini_set('session.gc_probability', 100);

// База данных
define('MYSQLI_HOST', 'localhost');
define('MYSQLI_USER', 'root');
define('MYSQLI_PASSWORD', '');
define('MYSQLI_CHARSET', 'utf8');
define('MYSQLI_BASE_NAME', 'kenesh');

define('CACHE_STATIC', false);

define('SITE_TEMPLATE', 'default');


//====================================================

// Текущая деректория (она является корневой для сайта, скажем это PHP)
chdir(__DIR__);

// Текущее время для всего скрипта
define('NOW', date('Y-m-d H:i:s',time()));

// Если вдруг взапросе нет хоста
if(!isset($_SERVER['HTTP_HOST'])){
	$_SERVER['HTTP_HOST'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "";
}

// Если вдруг это не апач
if(!isset($_SERVER['SCRIPT_NAME'])){
	$aDebug = debug_backtrace();
	$_SERVER['SCRIPT_NAME'] = $aDebug[0]['file'];
	unset($aDebug);
}

// Имя файла который пытается выполнить PHP
$pref = $_SERVER['SCRIPT_NAME'];
// Костыль для Windows, меняем слеши на правильные, юниксовые
$__dir__ = rtrim(preg_replace('#\\\#usi','/', __DIR__), '/');
// Сопоставляем путь до скрипта с запрошеным из браузера скриптом,
// что-бы определить URL до сайта. И попутно отрываем по дериктории
// из префикса
while($pref = preg_replace('#^(.*)/[^/]*$#usi', '$1', $pref)){
	$p = preg_match('#' . preg_quote($pref) . '$#usi', $__dir__);
	if($p){
		// Если совпало то вот он наш преикс
		break;
	}
}
unset($p, $__dir__);
// Префикс дериктории сайта относительно дериктории хоста
define('SITE_PREFIX', $pref . '/');
// URL до корневой дериктории сайта
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . SITE_PREFIX);
// Адрес оригинального сайта, если это копия
define('SITE_URL_CANONICAL' , SITE_URL);
unset($pref);

Db::$cache_dir = './var/mysql_cache';
Db::Connect();

define('SITE_TEMPLATE_PATH_FULL', __DIR__ . '/tamplate/' . SITE_TEMPLATE . '/');
define('SITE_TEMPLATE_PATH', './tamplate/' . SITE_TEMPLATE . '/');
define('SITE_STATIC_PATH', SITE_TEMPLATE_PATH . 'bin/');
define('SITE_STATIC_CACHE_PATH', SITE_TEMPLATE_PATH . 'cache/');

define('SITE_TMPLATE_URL', SITE_URL . 'tamplate/' . SITE_TEMPLATE . '/');
define('SITE_STATIC_URL', SITE_TMPLATE_URL . 'bin/');
define('SITE_STATIC_CACHE_URL', SITE_TMPLATE_URL . 'cache/');


Cache::$path_input = SITE_STATIC_PATH;
Cache::$path_output = SITE_STATIC_CACHE_PATH;
Cache::$url_input = SITE_STATIC_URL;
Cache::$url_output = SITE_STATIC_CACHE_URL;
Cache::$cache = CACHE_STATIC;



require_once(SITE_TEMPLATE_PATH . 'bin/init.php');







