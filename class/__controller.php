<?php
class __controller{
	public static $default_page = 'main';
	public static function page($name){
		$name = preg_replace('#-#usi', '\\', $name);
		$name = '\\view\\' . $name;
		$name();
	}
}