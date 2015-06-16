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
			my_notfound("Not supported get parameters");
		}
		$date_start = date("Y-m-01", $date_temp_timestamp);
		$date_stop = date("Y-m-t", $date_temp_timestamp);
	}else if(isset($_GET['year']) || isset($_GET['mouth'])){
		my_notfound("Not set year or mouth");
	}
	
	$view = \my_view::getInstance();
	$view->addCss('./days/days.css');
	$view->addJs('./days/days.js');
	$view->setTitle("Список рассмотренных законопроектов с " . $date_start . " по " . $date_stop);
	
	$data = \Db::Query("
		SELECT
			paty.paty_id, 
			vote.vote_date,  
			vote.vote_date2, 
			paty.paty_name2 AS paty_name, 
			SUM(IF(vote.vote_status_num = -1, 1,0)) AS count_no,
			SUM(IF(vote.vote_status_num = 1, 1,0)) AS count_yes,
			SUM(IF(vote.vote_status_num = 0, 1,0)) AS count_abcent
		FROM `cont_vote` AS vote
			INNER JOIN cont_paty AS paty ON paty.paty_id = vote.paty_id
		WHERE vote.vote_date2 >= " . ee($date_start) . " AND vote.vote_date2 <= " . ee($date_stop) . "
		GROUP BY vote.paty_id
		ORDER BY vote.vote_date DESC
	");
	
	$dates = array();
	foreach($data as $row){
		if(!isset($dates[$row['vote_date2']])){
			$dates[$row['vote_date2']] = array();
		}
		$dates[$row['vote_date2']][] = $row;
	}
	
	$dates_str = array();
	foreach($dates as $date => $patys){
		$patys_str = array();
		foreach($patys as $paty){
			$patys_str[] = 
				'<li class="days-paty clearfix">' .
					'<div class="days-paty-time">' .
						date('H:i', strtotime($paty['vote_date'])) . 
					'</div>' .
					'<div class="days-paty-info">' .
						'<div class="days-paty-info-item days-paty-no">' . h($paty['count_no']) . '</div>/' .
						'<div class="days-paty-info-item days-paty-yes">' . h($paty['count_yes']) . '</div>/' .
						'<div class="days-paty-info-item days-paty-abcent">' . h($paty['count_abcent']) . '</div>' .
					'</div>' .
					'<h3 class="days-paty-name" data-paty_id="' . $paty['paty_id'] . '">' . h($paty['paty_name']) . '</h3>' .
					'<a class="days-paty-link" href="' . SITE_URL . '?page=paty&paty_id=' . $paty['paty_id'] . '"></a>' .
				'</li>'
			;
		}
		$dates_str[] =
			'<li class="days-day">' .
				'<h2 class="days-day-name">' . h(mb_strtolower(my_date("j n Y", $date))) . ' года</h2>' .
				'<ul class="days-day-patys">' .
					implode('', $patys_str) .
				'</ul>' .
			'</li>'
		;
	}
	
	$sContent = 
		'<div class="days">' .
			'<div class="block-content">' .
				'<ul class="days-list">' .
					implode('', $dates_str) .
				'</ul>' .
			'</div>' .
		'</div>'
	;
	echo \view\body($sContent);
}