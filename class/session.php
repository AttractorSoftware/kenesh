<?php

class session{

	static function start(){
		if(session_id()){
			return true;
		}
		
		session_start();
		
		if(session_id()){
			return true;
		}else{
			return false;
		}
	}
	
	static function destroy(){
		if(!session_id()){
			self::start();
		}
		if(session_id()){
			header('SessionId: ' . session_name() . ' = ' . session_id());
			session_regenerate_id();
			setcookie(session_name(), session_id(), time() - 60*60*24);
			session_unset();
			session_destroy();
		}
	}

}