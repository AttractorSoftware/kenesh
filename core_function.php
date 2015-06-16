<?php

// Критическая ошибка в скрипте
function my_error($message){
	$aDebug = debug_backtrace();
	$sDebug = array();
	$regular_expression = '#^' . preg_quote(__DIR__) . '#Uusi';
	foreach($aDebug as $oDebug){
		$file = preg_replace($regular_expression, '' , $oDebug['file']);
		$sDebug[] = $oDebug['function'] . ':' . $oDebug['line'] . ':' . $file . "\n";
	}
	$sDebug[] = "ERROR: " . $message;
	echo implode("\n", $sDebug);
	exit(100);
}

// Релокейт страницы
function my_location($url, $code = 301){
	$aDebug = debug_backtrace();
	$sDebug = array();
	$regular_expression = '#^' . preg_quote(__DIR__) . '#Uusi';
	foreach($aDebug as $oDebug){
		$file = preg_replace($regular_expression, '' , $oDebug['file']);
		$sDebug[] = $oDebug['function'] . '::' . $file . ':' . $oDebug['line'];
	}
	
	header("LocationDebug: " . implode(', ',  $sDebug));
	header("Location: " . $url, true, $code);
	exit();
}

// Страница не найдена
function my_notfound($message = ''){
	$aDebug = debug_backtrace();
	$sDebug = array();
	$regular_expression = '#^' . preg_quote(__DIR__) . '#Uusi';
	foreach($aDebug as $oDebug){
		$file = preg_replace($regular_expression, '' , $oDebug['file']);
		$sDebug[] = $oDebug['function'] . '::' . $file . ':' . $oDebug['line'];
	}
	
	header("HTTP/1.0 404 Not Found");
	header('Line: ' . implode(', ',  $sDebug));
	header('Message: ' . $message);
	echo preg_replace(
		array(
			'#\{\{url\}\}#usi',
			'#\{\{error\}\}#usi'
		),
		array(SITE_URL, preg_quote($message)),
		file_get_contents('./var/error/404.html')
	);
	exit();
}

// Записать данные в файл с логами
function my_log($message, $file = "error.log"){
	file_put_contents('./var/logs/' . $file, $message . "\n", FILE_APPEND);
}

// Автоматическое подключение классов
function __autoload($class){
	$file = 'class/' . strtolower($class) . '.php';
	if (file_exists($file)) {
		include $file;
		return true;
	}else{
		my_error('Не удалось подключить класс "' . $class . '"');
	}
	return false;
}

// Микровремя
function my_gettime(){
	$microtime = explode(' ', microtime());
	$microtime = $microtime[1] + $microtime[0];
	return $microtime;
}

function my_json_encode($s){
	//return json_encode($s);
	$a = array('а','А','б','Б','в','В','г','Г','д','Д','е','Е','ё','Ё','ж',
		'Ж','з','З','и','И','й','Й','к','К','л','Л','м','М','н','Н','о','О','п',
		'П','р','Р','с','С','т','Т','у','У','ф','Ф','х','Х','ц','Ц','ч','Ч','ш',
		'Ш','щ','Щ','ъ','Ъ','ы','Ы','ь','Ь','э','Э','ю','Ю','я','Я'
	);
	$a2 = array('#\\\\u0430#U','#\\\\u0410#U','#\\\\u0431#U','#\\\\u0411#U','#\\\\u0432#U',
		'#\\\\u0412#U','#\\\\u0433#U','#\\\\u0413#U','#\\\\u0434#U','#\\\\u0414#U','#\\\\u0435#U',
		'#\\\\u0415#U','#\\\\u0451#U','#\\\\u0401#U','#\\\\u0436#U','#\\\\u0416#U','#\\\\u0437#U',
		'#\\\\u0417#U','#\\\\u0438#U','#\\\\u0418#U','#\\\\u0439#U','#\\\\u0419#U','#\\\\u043a#U',
		'#\\\\u041a#U','#\\\\u043b#U','#\\\\u041b#U','#\\\\u043c#U','#\\\\u041c#U','#\\\\u043d#U',
		'#\\\\u041d#U','#\\\\u043e#U','#\\\\u041e#U','#\\\\u043f#U','#\\\\u041f#U','#\\\\u0440#U',
		'#\\\\u0420#U','#\\\\u0441#U','#\\\\u0421#U','#\\\\u0442#U','#\\\\u0422#U','#\\\\u0443#U',
		'#\\\\u0423#U','#\\\\u0444#U','#\\\\u0424#U','#\\\\u0445#U','#\\\\u0425#U','#\\\\u0446#U',
		'#\\\\u0426#U','#\\\\u0447#U','#\\\\u0427#U','#\\\\u0448#U','#\\\\u0428#U','#\\\\u0449#U',
		'#\\\\u0429#U','#\\\\u044a#U','#\\\\u042a#U','#\\\\u044b#U','#\\\\u042b#U','#\\\\u044c#U',
		'#\\\\u042c#U','#\\\\u044d#U','#\\\\u042d#U','#\\\\u044e#U','#\\\\u042e#U','#\\\\u044f#U',
		'#\\\\u042f#U'
	);
	
	return preg_replace($a2,$a,json_encode($s));
}

// Выполнить сомманду в коммандной строке
function my_cmd($s, $foo = null)
{
	if(is_null($foo)){
		$foo = function($str){};
	}
	
	$get_str = function($str){
		if(PHP_OS==="WINNT"){
			return iconv('CP866', 'utf-8', $str);
		}else{
			return $str;
		}
	};
	
	$f=tempnam('','cmd');
	$d=array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('file',$f,'a'));
	$p=proc_open($s,$d,$e);
	$r=false;
	if (is_resource($p)){
		$r = array();
		while(!feof($e[1])){
			$t = $get_str(fgets($e[1]));
			$r[]= $t;
			$foo($t);
		}
		fclose($e[0]);
		fclose($e[1]);
		proc_close($p);
	}
	else return $r;
	
	$fil = file($f);
	foreach($fil as $k=>$v){
		$t = $get_str($v);
		$r[] = $t;
		$foo($t);
	}
	unlink($f);
	return $r;
}


function my_array_encode($array){
	$rows = array();
	$columns = array();
	$first = true;
	foreach($array as $row){
		if($first){
			foreach($row as $key=>$elem){
				$columns[] = $key;
			}
			$first = false;
		}
		$rows[] = array_values($row);
	}
	return array(
		'columns' => $columns,
		'rows' => $rows
	);
}
function my_array_decode($data){
	if(!isset($data['columns']) || !isset($data['rows'])){
		my_error("Неверные данные для раскодирования");
	}
	$array = array();
	foreach($data['rows'] as $row){
		$temp = array();
		foreach($data['columns'] as $index => $column){
			$temp[$column] = $row[$index];
		}
		$array[] = $temp;
	}
	return $array;
}

function my_date($f,$d = false){
	$d = !$d?time():(is_int($d)?$d: strtotime($d));
	$a['n']=array("","Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря");
	$a['M']=array("","Jan"=>"Янв","Feb"=>"Фев","Mar"=>"Мар","Apr"=>"Апр","May"=>"Мая","Jun"=>"Июн","Jul"=>"Июл","Aug"=>"Авг","Sep"=>"Сен","Oct"=>"Окт","Nov"=>"Ноя","Dec"=>"Дек");
	$a['w']=array("Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота");
	return date(preg_replace_callback('#(?<!(?<!\\\)\\\)(n|w|M)#us',function($s) use ($d,$a) {
		return $a[$s[1]][date($s[1],$d)];
	},$f),$d);
}

/**
 * Слово к числу
 *
 * @i int число
 * @s1 string 0 часов, минут, сомов
 * @s2 string 1 час, минута, сом
 * @s3 string 2 часа, минуты, сома
 *
 * @return string
 */
function my_mozg($i, $s1/*0*/,$s2/*1*/, $s3/*2*/){
	$i = $i<0?-$i:$i;
	return (($m=$i%10)>=5&&$m<=9)||$m==0||(($h=$i%100)>=5&&$h<=20)?$s1:($m==1?$s2:$s3);
}

function print_table($arr, $titles = false, $title_repet = 0){
	$ret = '';
	$titles = $titles ? $titles : array();
	$get_title = function($key) use ($titles){
		if(isset($titles[$key])){
			return $titles[$key];
		}else{
			return $key;
		}
	};
	$ret .= '<table border=1 >';
	
	if(count($arr)>0){
		$max_len = 0;
		$find_th = function($row, $to) use (&$max_len, &$get_title, &$find_th){
			if($max_len < $to){
				$max_len = $to;
			}
			$temp = array();
			foreach($row as $k=>$v){
				if(is_array($v)){
					$temp[$get_title($k)] = $find_th($v, ($to+1));
				}else{
					$temp[$get_title($k)] = null;
				}
			}
			return $temp;
		};
		foreach($arr as $k=>$v){
			$th = $find_th($v, 1);
			break;
		}
		$title_str = '';
		$print_th = function($th, $to) use($max_len, &$print_th, &$title_str){
			$title_str .= '<tr>';
			$to_next = array();
			foreach($th as $k=>$next){
				if($next == null){
					$rowspan = $max_len - $to;
					$colspan = 1;
				}else{
					$rowspan = 1;
					$colspan = count($next);
					foreach($next as $k2=>$v2){
						$to_next[$k2] = $v2;
					}
				}
				$title_str .=  '<th rowspan="'.$rowspan.'" colspan="'.$colspan.'">' . $k . '</th>';
				
			}
			$title_str .=  '</tr>';
			if(count($to_next) != 0){
				$print_th($to_next, ($to+1));
			}
		};
		$print_th($th, 0);
		
		
		
		$print_td = function($row) use (&$print_td, &$ret){
			if(is_array($row)){
				foreach($row as $v){
					$print_td($v);
				}
			}else{
				$ret .= '<td>' . (in_array($row, array('', ''))? '&nbsp;' : $row )  . '</td>';
			}
		};
		
		
		$cur = 0;
		$ret .= $title_str;
		foreach($arr as $v){
			if($v === 'new_table'){
				$ret .= '</table><br><br><table border=1 >';
				$ret .= $title_str;
				continue;
			}
			if($title_repet != 0 && $cur != 0 && $cur % $title_repet == 0 || $v === 'title'){
				$ret .= $title_str;
				if($v === 'title'){
					continue;
				}
			}
			$ret .= '<tr>' . $print_td($v) . '</tr>';
			$cur++;
		}
		
	}
	$ret .= '</table>';
	return $ret;
}

















































