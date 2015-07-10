<?php

class cache{
	static $path_input = '';
	static $path_output = '';
	static $url_input = '';
	static $url_output = '';
	static $cache = false;
	
	static function clearName($name){
		$name = preg_replace('#(\/(\.\/)+)#usi', '/', $name);
		$prev_name = $name;
		while(true){
			$prev_name = preg_replace('#(^.*?\/)[^\/]*\/\.\.\/#usi', '$1', $name);
			if($prev_name == $name){
				break;
			}
			$name = $prev_name;
		}
		return $name;
	}
	
	static function statics($files){
		if(count($files) == 0){
			return array();
		}
		
		if(self::$cache == false){
			foreach($files as $k=>$file){
				$files[$k] = self::$path_input . $file;
			}
			return $files;
		}
		
		$mod_max = 0;
		$mod_file_create = false;
		$mod_info_write = false;
		$mod_exit = array();
		
		$ext = explode('.', basename(current($files)));
		$ext = array_pop($ext);
		
		
		
		$mad_file_name = self::$path_output . 'last_modified.txt';
		if(file_exists($mad_file_name)){
			$mod_info = unserialize(file_get_contents($mad_file_name));
		}else{
			$mod_info = array();
			$mod_file_create = true;
		}
		
		foreach($files as $file){
			$name = self::$path_input . $file;
			//Проверка существования файла и даты его последней модификации
			if(!isset($mod_info[$file]) || $mod_info[$file][1] < time() - 5){
				//Файл не существует
				if(!file_exists($name)){
					//Файл есть в кеше дат обновления
					if(isset($mod_info[$file])){
						unset($mod_info[$file]);
						$mod_info_write = true;
					}
					$mod_exit[] = '0x03 ' . $name;
					continue;
				}
				
				//Файл существует
				else{
					$mod_info_write = true;
					$mod_time = filemtime($name);
					$mod_info[$file] = array($mod_time, time());
				}
			}
			//Дата последней модификации для всех файлов
			if($mod_max < $mod_info[$file][0]){
				$mod_max = $mod_info[$file][0];
			}
		}
		//Обновление файла с информацией
		if($mod_info_write){
			if(file_exists($mad_file_name)){
				@chmod($mad_file_name, 0666);
			}
			file_put_contents($mad_file_name, serialize($mod_info));
		}
		if($mod_exit){
			my_notfound("FileNotFound:" . implode("\n", $mod_exit));
		}
		
		$new_name = self::$path_output . md5(implode('', $files)) . '_' . $mod_max . '.' . $ext;
		
		$date_edit = @filemtime($new_name);
		
		if(!file_exists($new_name)){
			$content = '';
			foreach($files as $file){
				$name = self::$path_input . $file;
				$file_content = @file_get_contents($name);
				if($ext == 'css'){
					$dir = self::clearName(dirname($name) . '/');
					$file_content = preg_replace_callback(
						'#url\((["\']?)(?!["\'])(?!data:|http:|https:|\/\/)(.*)\\1\)#Uusi'
						,function($e) use ($dir){
							$old_url = $e[2];
							$temp_url = preg_replace('#^' . preg_quote(cache::$path_input) . '#Uusi', '', $dir);
							$new_url = cache::clearName(cache::$url_input . $temp_url . $old_url);
							return 'url("' . $new_url . '")';
						}
						,$file_content
					);
				}
				$content .= 
					//"\r\n" . '/* ' . $file . ' */' . "\r\n" . 
					$file_content . "\r\n"
				;
			}
			if(in_array($ext, array('js','css'))){
				$temp = "\xEF\xBB\xBF";
				$content = preg_replace("/".preg_quote($temp)."/","", $content);
				$content = "\xEF\xBB\xBF" . $content;
			}
			@file_put_contents($new_name, $content);
		}
		return array(self::$url_output . preg_replace('#^' . preg_quote(self::$path_output) . '#Uusi', '', $new_name));
	}
}