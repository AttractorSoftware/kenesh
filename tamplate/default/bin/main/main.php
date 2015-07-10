<?php

namespace view;

function main(){
	$view = \my_view::getInstance();
	$view->addCss('./main/main.css');
	
	$data = \Db::Query("
		SELECT
			cont_vote.user_id,
			cont_vote.vote_date2,
			cont_frakcia.frakcia_id,
			IF(cont_vote.vote_date2 >= cont_absentees.date_start AND cont_vote.vote_date2 <= cont_absentees.date_stop, 1, 0) AS hooky_official,
			IF(cont_vote.vote_date2 >= cont_absentees.date_start AND cont_vote.vote_date2 <= cont_absentees.date_stop, cont_absentees.reason, '') AS hooky_str,
			IF(cont_vote.vote_date2 >= cont_absentees.date_start AND cont_vote.vote_date2 <= cont_absentees.date_stop, cont_absentees.transferredVoteTo, '') AS hooky_str2,
			IF(MAX(cont_vote.vote_status_num) IN (1,2) OR MIN(cont_vote.vote_status_num) = -1, 0, 1) AS hooky,
			IF(MAX(cont_vote.vote_status_num) IN (1,2) OR MIN(cont_vote.vote_status_num) = -1, 1, 0) AS is_vote
		FROM
			cont_vote
				LEFT OUTER JOIN cont_absentees ON cont_vote.absentees_id = cont_absentees.id
				INNER JOIN cont_name ON cont_vote.user_id = cont_name.id
				INNER JOIN cont_frakcia ON cont_name.frakcia_id = cont_frakcia.frakcia_id
		GROUP BY
			cont_vote.user_id,
			cont_vote.vote_date2
	");
	
	$dates = array();
	foreach($data as $row){
		if(!in_array($row['vote_date2'], $dates)){
			$dates[] = $row['vote_date2'];
		}
	}
	sort($dates);
	
	$users = array();
	foreach($data as $row){
		if(!isset($users[$row['user_id']])){
			$users[$row['user_id']] = array(
				'dates'=>array(),
				'user_id' => $row['user_id'],
				'frakcia_id' => $row['frakcia_id'],
				'hooky' => 0,
				'hooky_official' => 0,
				'hooky_all' => 0,
			);
		}
	}
	
	foreach($users as $user_id=>$value){
		foreach($dates as $date){
			$users[$user_id]['dates'][$date] = false;
		}
	}
	
	foreach($data as $row){
		$users[$row['user_id']]['dates'][$row['vote_date2']] = $row;
		if($row['hooky_official'] == 1){
			$users[$row['user_id']]['hooky_official'] += 1;
			$users[$row['user_id']]['hooky_all'] += 1;
		}else if($row['hooky'] == 1){
			$users[$row['user_id']]['hooky'] += 1;
			$users[$row['user_id']]['hooky_all'] += 1;
		}
	}
	
	$temp = \Db::Query("
		SELECT
			cont_name.id,
			cont_name.name2,
			cont_frakcia.frakcia_id,
			cont_frakcia.frakcia_name
		FROM
			cont_name
				INNER JOIN cont_frakcia ON cont_name.frakcia_id = cont_frakcia.frakcia_id
		GROUP BY id

	");
	$users2 = array();
	foreach($temp as $user){
		$users2[$user['id']] = $user;
	}
	
	$order = 'hooky_all';
	
	if(isset($_GET['order']) && in_array($_GET['order'], array('hooky', 'hooky_official', 'hooky_all', 'fio', 'frakcia'))){
		$order = $_GET['order'];
	}
	
	usort($users, function($a, $b) use ($users2, $order){
		if($order == 'fio'){
			return strcasecmp ($users2[$a['user_id']]['name2'], $users2[$b['user_id']]['name2']);
		}else if($order == 'frakcia'){
			$ret = $a['frakcia_id'] - $b['frakcia_id'];
			if($ret == 0){
				//$ret = strcasecmp($users2[$a['user_id']]['name2'], $users2[$b['user_id']]['name2']);
				$ret = $b['hooky_all'] - $a['hooky_all'];
			}
			return $ret;
		}else if($order == 'hooky'){
			return $b['hooky'] - $a['hooky'];
		}else if($order == 'hooky_official'){
			return $b['hooky_official'] - $a['hooky_official'];
		}else if($order == 'hooky_all'){
			return $b['hooky_all'] - $a['hooky_all'];
		}
	});
	
	
	
	$users_str = array();
	$temp_frakcia_id = false;
	foreach($users as $user){
		$user_dates = $user['dates'];
		$user_dates_str = array();
		foreach($user_dates as $date=>$info){
			$user_dates_str[] =
				'<div ' . 
					'title="' . 
						$date .
						($info['hooky_official'] == 1 
							? 
								'  Официально отсутствовал: ' . $info['hooky_str'] . 
								($info['hooky_str2'] != '-' ? ', передал голос ' . $info['hooky_str2'] : '') 
							: ''
						) .
						($info['hooky_official'] == 0 && $info['hooky'] == 1 ? '  Прогулял' : '') .
					'" ' .
					'class="users_stat-user-date' .
						($info['hooky'] == 1 ? ' users_stat-status-hooky' : '') .
						($info['hooky_official'] == 1 ? ' users_stat-status-hooky_official' : '') .
						($info['is_vote'] == 1 ? ' users_stat-status-is_vote' : '') .
					'"' .
				'></div>'
			;
		}
		if($order == 'frakcia' && $user['frakcia_id'] != $temp_frakcia_id){
			$users_str[] =
				'<li class="users_stat-users-frakcia">' .
					h($users2[$user['user_id']]['frakcia_name']) . 
				'</li>'
			;
			$temp_frakcia_id = $user['frakcia_id'];
		}
		$users_str[] =
			'<li class="users_stat-user">' .
				'<div class="users_stat-user_info">' .
					'<img class="users_stat-user-img" src="' . SITE_STATIC_URL . 'main/img/' . $user['user_id'] . '.jpg">' .
					'<span class="users_stat-user-info_line">Всего прогулов: <span class="users_stat-user-info-hooky_all">' . $user['hooky_all'] . '</span></span>' . 
					'<span class="users_stat-user-info_line">Официальные прогулы: <span class="users_stat-user-info-hooky_official">' . $user['hooky_official'] . '</span></span>' . 
					'<span class="users_stat-user-info_line">Прогулы: <span class="users_stat-user-info-hooky">' . $user['hooky'] . '</span></span>' . 
					'<h2 class="users_stat-user-title">' .
						'<a class="users_stat-user-link" href="' . SITE_URL . '?page=user&user_id=' . $user['user_id'] . '">' . h($users2[$user['user_id']]['name2']) . '</a>' .
						($order != 'frakcia' ? 
							'<span class="users_stat-user-frakcia-before"> / </span>' .
							'<span class="users_stat-user-frakcia">' . h($users2[$user['user_id']]['frakcia_name']) . '</span>' 
						:'') .
					'</h2>' .
				'</div>' .
				'<ul class="users_stat-user-dates clearfix">' .
					implode('', $user_dates_str) .
				'</ul>' .
			'</li>'
		;
	}
	
	$orders = array(
		'hooky' => 'прогулы', 
		'hooky_official' => 'официальные прогулы', 
		'hooky_all' => 'все прогулы', 
		'fio' => 'фио', 
		'frakcia' => 'по фракциям'
	);
	$orders_str = array();
	foreach($orders as $k=>$v){
		$orders_str[] = '<li class="users_stat-order' . ($k == $order ? ' current' : '') . '"><a class="users_stat-order-link" href="' . SITE_URL . '?page=main&order=' . $k . '">' . h($v) . '</a></li>';
	}
	
	
	$view->setTitle('График посещения депутатами сессий парламента');

	
	$sContent =
		'<div class="users_stat">' .
			'<div class="block-content">' .
				'<div class="users_stat-content">' .
					'<h1 class="users_stat-title">' .
						'График посещения депутатами сессий парламента' .
					'</h1>' .
					'<div class="users_stat-info">' .
						'<p class="users_stat-p">Каждый цветной квадрат - это один день пленарного заседания парламента (день голосования за законопроекты).</p>' .
						'<p class="users_stat-p">Ниже представлены дни с 11 сентября 2014 года по 28 мая 2015 года. При наведении на квадрат показывается информация о конкретной дате.</p>' .
						'<p class="users_stat-p">Более полную информацию о данных вы можете узнать в разделе "<a href="' . SITE_URL . '?page=about">О проекте</a>".</p>' .
					'</div>' . 
					'<div class="users_stat-legend">' .
						'Легенда:<br><br>' .
						'<div class="users_stat-user-date users_stat-legend-date users_stat-status-is_vote"></div>' .
							'<span class="users_stat-legend-info">депутат присутствовал на голосовании</span>' .
						'<div class="users_stat-user-date users_stat-legend-date users_stat-status-hooky_official"></div>' .
							'<span class="users_stat-legend-info">депутат отсутсвует официально (написал заявление)</span>' .
						'<div class="users_stat-user-date users_stat-legend-date users_stat-status-hooky"></div>' .
							'<span class="users_stat-legend-info">депутат отсутствует без официальной причины</span>' .
						'<div class="users_stat-user-date users_stat-legend-date"></div>' .
							'<span class="users_stat-legend-info">данных о депутате за этот день нет (возможно, в этот период времени он не депутат)</span>' .
					'</div>' .
					'<ul class="users_stat-orders clearfix">' .
						implode('', $orders_str) .
					'</ul>' .
					'<ul class="users_stat-users">' .
						implode('', $users_str) .
					'</ul>' .
				'</div>' .
			'</div>' .
		'</div>'
	;
	echo \view\body($sContent);
}













