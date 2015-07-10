<?php

class Config
{
	static function Get($key){
		$val = Db::Query("SELECT config_value FROM cont_config WHERE config_name=" . ee($key));
		if(count($val) == 0){
			return null;
		}
		return $val[0]['config_value'];
	}
	static function Set($key, $value){
		$val = Db::Query("SELECT config_name, config_value FROM cont_config WHERE config_name=" . ee($key));
		$data = array('config_value'=>$value, 'config_name'=>$key);
		if(count($val) == 0){
			_Db::Add('cont_config', $data);
		}else{
			$data['config_name'] = $val[0]['config_name'];
			_Db::Update('cont_config', 'config_name', $data, array());
		}
		return true;
	}
}