<?php 

namespace view;

function img(){
	error_reporting(E_ALL);
	ini_set('html_errors', 'on');
	ini_set('display_errors', 'on');
	ini_set('display_startup_errors', 'on');
	ini_set('memory_limit', '2024M');
	ignore_user_abort();
	set_time_limit(0);
	
	$json = file_get_contents(__DIR__ . '/img.json');
	$data = json_decode($json, true);
	foreach($data as $key => $row){
		$name = trim($row['name']);
		$name = mb_substr($name, 0, 1) . mb_strtolower(mb_substr($name, 1, mb_stripos($name, ' '))) . mb_substr($name, mb_stripos($name, ' ') + 1);
		$row['name'] = $name;
		$name = explode(' ', $name);
		if(isset($name[2])){
			$name = $name[0] . ' ' . mb_substr($name[1], 0, 1) . " " . mb_substr($name[2], 0, 1);
		}else{
			$name = $name[0] . ' ' . mb_substr($name[1], 0, 1);
		}
		$row['name2'] = $name;
		$data[$key] = $row;
	}
	
	$users = \Db::Query("SELECT * FROM cont_name");
	
	foreach($users as $k=>$user){
		foreach($data as $key=>$row){
			if($user['name'] == $row['name2']){
				$row['id'] = $user['id'];
				$user['name2'] = $row['name'];
				$user['src'] = $row['src'];
			}
		}
		$users[$k] = $user;
	}

	foreach($users as $k=>$user){
		\Db::Query("UPDATE cont_name SET name2 = " . ee($user['name2']) . " WHERE id=" . ee($user['id']) . "");
		$img = file_get_contents(preg_replace('# #usi', '%20', $user['src']));
		file_put_contents(__DIR__ . '/img/' . $user['id'] . '.jpg', $img);
		echo "#" . $user['id'] . ' ' . $user['name2'] . "\n";
	}
}