<?php
/**
 * Сласс для подключения и обращения к базе данных.
 */
class Db

{
	/**
	 * Экземпляр данного класса.
	 *
	 * var Db
	 */
	private static $instance = null;
	/**
	 * Имя базы данных.
	 *
	 * @var string
	 */
	private static $date_base_name = null;
	/**
	 * Количество выполненных запросов.
	 *
	 * @var integer
	 */
	public static $count_query = 0;

	/**
	 * Экземпляр класса mysqli.
	 *
	 * @var mysqli
	 */
	private static $db = array();
	/**
	 * Последний выполненый запрос.
	 *
	 * @var string
	 */
	public static $query = null;
	/**
	 * Время выполнения последнего запроса.
	 *
	 * @var string
	 */
	public static $time_query = 0;
	public static $all_time_query = 0;
	
	
	/**
	 * Кеширование запросов
	 *
	 * @var string
	 */
	public static $cache = false;
	/**
	 * Дериктория с файлами кеша
	 *
	 * @var string
	 */
	public static $cache_dir = './';

	/**
	 * Подключение к базе данных, устанока кодировки, выбор базы данных.
	 * Икпользуются константы DETE_BASE_NAME и MYSQL_CHARSET.
	 *
	 * param string $date_base_name Имя базы данных для подключения, если не заданно то используется значение константы DETE_BASE_NAME
	 */
	public static

	function Connect($date_base_name = null)
	{
		self::$date_base_name = MYSQLI_BASE_NAME;
		if(is_null($date_base_name)){
			$date_base_name = self::$date_base_name;
		}
		if(!is_null(self::$instance) && isset(self::$db[$date_base_name])) return;
		if(!is_null(self::$instance)){
			self::$instance = new Db;
		}
		self::$db[$date_base_name] = new mysqli(MYSQLI_HOST, MYSQLI_USER, MYSQLI_PASSWORD, $date_base_name);
		if (mysqli_connect_errno()){
			error('Подключение к серверу MySQL невозможно. Код ошибки: %s\n', mysqli_connect_error());
		}

		if (!self::$db[$date_base_name]->query('SET NAMES "' . MYSQLI_CHARSET . '"')){
			error('Ошибка при установке кодировки: \n%s\n%s\n', self::$db[$date_base_name]->errno, self::$db[$date_base_name]->error);
		}
		
		if (!self::$db[$date_base_name]->query('SET group_concat_max_len = 655350')){
			error('Ошибка при установки значения group_concat_max_len: \n%s\n%s\n', self::$db[$date_base_name]->errno, self::$db[$date_base_name]->error);
		}
		
		$max_cache_time = 10;
		
		if(file_exists(self::$cache_dir . 'last_clear')){
			$last_clear = unserialize(file_get_contents(self::$cache_dir . 'last_clear'));
			if($last_clear > time() - 2){
				return;
			}else{
				file_put_contents(self::$cache_dir . 'last_clear', serialize(time()));
			}
		}else{
			file_put_contents(self::$cache_dir . 'last_clear', serialize(time()));
		}
		
		$files = scandir(self::$cache_dir);
		foreach($files as $file){
			if(in_array($file, array('.','..','last_clear'))){
				continue;
			}
			if(file_exists(self::$cache_dir . $file)){
				if(@filemtime(self::$cache_dir . $file) < time() - $max_cache_time){
					@unlink(self::$cache_dir . $file);
				}
			}
		}
	}

	/**
	 * Конструктор класса.
	 */
	private
	function __construct()
	{
		return self::$instance;
	}

	/**
	 * Деструктор класса. Закрывает подключение к базе данных.
	 */
	public

	function __destruct()
	{
		foreach(self::$db as &$v){
			$v->close();
		}
	}

	/**
	 * Запись данных об ошибке в файл, вывод ошибки и завершение скрипта.
	 * Используется константа MYSQL_LOG_FILE.
	 */
	static private
	function writeLog($is_error = true, $date_base_name)
	{
		if ($is_error){
			$s = "\nnum:%s error:%s\n\n%s\n";
			$s = sprintf($s, self::$db[$date_base_name]->errno, self::$db[$date_base_name]->error, self::$query);
			my_log($s, 'mysql_error.log');
			my_error($s);
		}
	}

	/**
	 * Выполнить запрос к базе данных.
	 *
	 * @param string $query
	 * @return mixed массив с результатом запроса,
	 * 	количество затронутых запросом записей,
	 * 	auto_increment последнего вставленного значения,
	 * 	true если количество затронутых записей равно 0 или в таблице не используется значение auto_increment.
	 */
	public static

	function Query($query, $date_base_name = null)
	{
		if(is_null($date_base_name)){
			$date_base_name = self::$date_base_name;
		}
		self::$query = $query;
		
		if(self::$cache){
			$md5 = md5(self::$query);
			$name = self::$cache_dir . $md5;
			if(file_exists($name)){
				if(filemtime($name) > time() - 60){
					$time_start = my_gettime();
					$ret = unserialize(file_get_contents($name));
					self::$time_query = my_gettime() - $time_start;
					self::$all_time_query += self::$time_query;
					return $ret;
				}else{
					unlink($name);
				}
			}
		}
		
		$time_start = my_gettime();
		if(!isset(self::$db[$date_base_name])){
			self::Connect($date_base_name);
		}
		$r = self::$db[$date_base_name]->query(self::$query);
		self::$time_query = my_gettime() - $time_start;
		self::$all_time_query += self::$time_query;
		self::$count_query += 1;
		
		// echo "======================================================================";
		// echo "Current Query Id:" . (self::$count_query - 1) . "\n";
		// echo "Time:" . self::$time_query . "\n";
		// echo "Time all:" . self::$all_time_query . "\n";
		// echo "Query:\n" . self::$query . "\n\n\n\n";
		
		if (!$r) self::writeLog(true,$date_base_name);
		self::writeLog(false,$date_base_name);
		if (!isset($r->num_rows))
		{
			$ret = (($i = self::$db[$date_base_name]->insert_id) == 0) ? self::$db[$date_base_name]->affected_rows : $i;
			return $ret;
		}
		
		$rows = array();
		while($row = $r->fetch_array(MYSQLI_ASSOC)){
			$rows[] = $row;
			unset($row);
		} 
		$r->free();
		if(self::$cache){
			file_put_contents($name ,serialize($rows));
		}
		return $rows;
	}
	
	
	/**
	 * Не совсем понятен принцип multi_query.
	 */
	public static

	function MultiQuery($querys, $date_base_name = null)
	{
		if(is_null($date_base_name)){
			$date_base_name = self::$date_base_name;
		}
		if(is_array($querys)){
			self::$query = implode(";\n",$querys) . ";";
		}else{
			self::$query = $querys;
		}
		self::$query = self::$query . "\n#multiQuery";
		
		if(self::$cache){
			$md5 = md5(self::$query);
			$name = self::$cache_dir . $md5;
			if(file_exists($name)){
				if(filemtime($name) > time() - 60){
					$time_start = my_gettime();
					$ret = unserialize(file_get_contents($name));
					self::$time_query = my_gettime() - $time_start;
					self::$all_time_query += self::$time_query;
					return $ret;
				}else{
					unlink($name);
				}
			}
		}
		
		$time_start = my_gettime();
		$r = self::$db[$date_base_name]->multi_query(self::$query);
		self::$time_query = my_gettime() - $time_start;
		self::$all_time_query += self::$time_query;
		
		self::$count_query += 1;
		
		
		// echo "======================================================================\n";
		// echo "Current Query Id:" . (self::$count_query - 1) . "\n";
		// echo "Time:" . self::$time_query . "\n";
		// echo "Time all:" . self::$all_time_query . "\n";
		// echo "Query:\n" . self::$query . "\n\n\n\n";
		
		if($r){
			//self::writeLog(false,$date_base_name);
			$results = array();
			do{
				$result = self::$db[$date_base_name]->store_result();
				if(!$result &&  self::$db[$date_base_name]->errno != 0){
					self::writeLog(true,$date_base_name);
				}
				
				if(!$result){
					$results[] = null;
				}
				
				else if(!isset($result->num_rows)){
					$affected_rows = self::$db[$date_base_name]->affected_rows;
					$insert_id = self::$db[$date_base_name]->insert_id;
					$results[] = ($insert_id == 0 ? $affected_rows : $insert_id);
				}
				
				else{
					$rows = array();
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						$rows[] = $row;
						unset($row);
					}
					$result->free();
					$results[] = $rows;
				}
			}while(self::$db[$date_base_name]->more_results() && self::$db[$date_base_name]->next_result());
		}else{
			self::writeLog(true,$date_base_name);
		}
		
		
		if(self::$cache){
			file_put_contents($name ,serialize($results));
		}
		return $results;
	}

	public static

	function E($str, $date_base_name = null)
	{
		if(is_null($date_base_name)){
			$date_base_name = self::$date_base_name;
		}
		$str = (string)$str;
		$r = self::$db[$date_base_name]->real_escape_string($str);
		if(!is_string($r)){
			var_dump($str);
		}
		return $r;
	}
	public static function Field($table){
		$rowses = Db::Query("/*a005*/ SHOW COLUMNS FROM `".self::E($table)."`");
		$fild = array();
		foreach($rowses as $r){
			$fild[] = $r['Field'];
		}
		return $fild;
	}
}

// экранировать для БД. real_escape_string
function e($s){
	return Db::E($s);
}

// имена столбцов
function en($s){
	$s = explode('.',$s);
	foreach($s as $k=>$v){
		$s[$k] = '`' . Db::E($v) . '`';
	}
	return implode('.',$s);
}

// Экранировать с учётом типа
function ee($s){
	if($s === ''){
		return "''";
	}else if(is_null($s)){
		return 'NULL';
	}else if($s === false){
		return '0';
	}else if($s === true){
		return '1';
	}else{
		return "'" . Db::E($s) . "'";
	}
}

// Экранировать для вывода в HTML
function h($s){
	return htmlspecialchars($s);
}
// Экранировать для вывода в HTML
function ha($s){
	return preg_replace(array('#"#usi', '#&#usi'), array('&quot;', '&amp;'), $s);
}

// Экранировать для вывода в GET параметры ссылок в HTML документах
function u($t){
	return urlencode($t);
}

// Экранировать имена столбцов и их значений, через запятую (например для последующей вставки)
function ei($a){
	$f = $r = array();
	foreach($a as $k=>$v){
		$f[] = en($k);
		$r[] = ee($v);
	}
	return array(implode(',',$f), implode(',',$r));
}

// Экранировать имена столбцов и их значений, приравняв друг к другу (например для обновления столбцов в таблице)
function eu($a){
	$r = array();
	foreach($a as $k=>$v){
		$r[] = en($k) . "=" . ee($v);
	}
	return implode(',',$r);
}

// Упрощённое формирование условий для запроса
function ed($a,$w=false){
	$r = array();
	foreach($a as $k=>$v){
		$t = true;
		if(is_array($v) && isset($v[1]) && $v[1] == 0){
			$t = false;
			$v = $v[0];
		}else if(is_array($v)){
			$v = $v[0];
		}else{
			$v = $v;
		}
		if(is_array($v)){
			if(count($v)>0){
				$r[] = en($k) . ' ' . ($t?'IN':'NOT IN') . ' (' . implode(',',array_map('ee', $v)) . ')';
			}
		}else{
			$r[] = en($k) . ($t?'=':'!=') . ee($v);
		}
	}
	$r[] = $w ? '1=0':'1=1';
	return implode($w ? ' OR ' : ' AND ', $r);
}



