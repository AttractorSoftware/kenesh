<?php

namespace view;

function days(){
	$date_start = date("Y-m-01", time());
	$date_stop = date("Y-m-t", time());
	
	if(isset($_GET['year']) && isset($_GET['mouth'])){
		$date_temp = date('Y-m-d', strtotime($_GET['year'] . '-' . $_GET['mouth'] . '-01'));
		$date_temp_timestamp = strtotime($date_temp);
		if(
			date('Y', $date_temp_timestamp) !== $_GET['year'] ||
			date('m', $date_temp_timestamp) !== $_GET['mouth'] ||
			$_GET['mouth'] > 12 ||
			$_GET['year'] > date("Y", time()) ||
			$_GET['year'] < 2011
			
		){
			my_notfound("Ќеверные параметры, год должен быть четырЄхзначным, мес€ц с ведущем нулЄм, мес€ц должен быть меньше 12");
		}
		$date_start = date("Y-m-01", $date_temp_timestamp);
		$date_stop = date("Y-m-t", $date_temp_timestamp);
	}else if(isset($_GET['year']) || isset($_GET['mouth'])){
		my_notfound("Ќе указан мес€ц или год");
	}
	
	$data = \Db::Query("
		SELECT vate_date, vote_status, user_id, paty_id
		FROM `cont_vote` AS vote
		WHERE vote.vote_date2 >= " . ee($date_start) . " AND vote.vote_date2 <= " . ee($date_stop) . "
		GROUP BY vote.paty_id, user_id
		ORDER BY vote.paty_id, user_id
	");
	$patys = array();
	foreach($data as $row){
		if(!in_array($row['paty_id'], $patys)){
			$patys[] = $row['paty_id'];
		}
	}
	for($i = 1, $l = count($data); $i <= $l; $i++){
		if(!in_array($data[$i]['paty_id'], $patys)){
			$patys[] = $data[$i]['paty_id'];
		}
	}
	$patys = \Db::Query("SELECT * FROM cont_paty WHERE paty_id IN (" . implode(',', $patys) . ")");
	$patys_temp = array();
	foreach($patys as $paty){
		$patys_temp[$paty['paty_id']] = array(
			'name' => $_paty['paty_name'],
			'data' => array()
		);
		foreach($data as $row){
			
		}
	}
	$patys = $patys_temp;
	unset($patys_temp);
	
	
	$patys_str = array();
	foreach($data as $row){
		$patys_str[] = 
			"<li class='days-day'>" .
				"<h2 class='days-day-name'>" . h($patys[$row['paty_id']]) . "</h2>" .
			"</li>"
		;
	}
	
	$sContent = 
		"<div class='days'>" .
			"<ul class='days-list'>" .
				"<li class='days-day'>" .
					"<h2 class='days-day-name'>" . $ . "</h2>" .
				"</li>" .
			"</ul>" .
			"" .
		"</div>"
	;
}