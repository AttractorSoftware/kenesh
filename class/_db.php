<?php

class _Db
{
	static function Add($table,$data,$exception = null){
		if(!is_array($exception)){
			$exception = array();
		}
		$field = Db::Field($table);
		$field = array_diff($field,$exception);
		$rows = array();
		foreach($field as $v){
			if(!array_key_exists($v, $data)){
				continue;
			}
			$rows[$v] = $data[$v];
		}
		$rows = ei($rows);
		return Db::Query("INSERT INTO ".en($table)." (" . $rows[0] . ") VALUES (" . $rows[1] . ")");
	}
	public static function Update($table,$id,$data,$exception = null){
		if(!is_array($exception)){
			$exception = array();
		}
		$field = Db::Field($table);
		$exception[] = $id;
		$field = array_diff($field,$exception);
		$rows = array();
		foreach($field as $v){
			if(!array_key_exists($v, $data)){
				continue;
			}
			$rows[$v] = $data[$v]; 
		}
		if(count($rows) == 0){
			return false;
		}
		$rows = eu($rows);
		return Db::Query("UPDATE `" . e($table) . "` SET " . $rows . " WHERE `" . e($id) . "`='" . e($data[$id]) . "'");
	}
	public static function Delete($table,$data){
		if(count($data)>0){
			$where = ed($data);
			return Db::Query("DELETE FROM `" . e($table) . "` WHERE " . $where);
		}else{
			return Db::Query("DELETE FROM `".e($table)."`");
		}
	}
	public static function Get($table,$data=array()){
		$other_where = '';
		if(func_num_args() > 2){
			
			$arg = func_get_arg(2);
			$g = array();
			$o = array();
			$set = function($v){
				if(is_array($v)){
					$get = (isset($v[1]) && ($v[1] == 0)) ? ' DESC' : ' ASC';
					return "`" . e($v[0]) . "`" . $get;
				}else{
					return "`" . e($v) . "`";
				}
			};

			if(isset($arg['o']) && is_array($arg['o'])){
				foreach($arg['o'] as $k=>$v){
					$o[] = $set($v);
				}
			}
			if(isset($arg['g']) && is_array($arg['g'])){
				foreach($arg['g'] as $k=>$v){
					$g[] = $set($v);
				}
			}
			if(count($g) > 0){
				$other_where .= "\nGROUP BY " . implode(',',$g);
			}else if(count($o) > 0){
				$other_where .= "\nORDER BY " . implode(',',$o);
			}
		}
		if(count($data)>0){
			$where = ed($data);
			return Db::Query("SELECT * FROM `".e($table)."` WHERE " . $where . $other_where);
		}else{
			return Db::Query("SELECT * FROM `".e($table)."`" . $other_where);
		}
	}
}