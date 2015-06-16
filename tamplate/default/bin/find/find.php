<?php

namespace view;

function find(){
	$view = \my_view::getInstance();
	$view->addCss('./days/days.css');
	$view->addCss('./find/find.css');
	
	if(!isset($_GET['q'])){
		my_notfound("Not set 'q' - query string");
	}
	$sQ = $_GET['q'];
	
	$patys = \Db::Query("
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
		WHERE paty.paty_name2 LIKE '%" . e($sQ) . "%'
		GROUP BY vote.paty_id
		ORDER BY vote.vote_date DESC
	");
	
	$patys_str = array();
	foreach($patys as $paty){
		$patys_str[] = 
			'<li class="days-paty clearfix">' .
				'<div class="days-paty-time find-paty-time">' .
					date('Y-m-d', strtotime($paty['vote_date'])) . ' <br> ' .
					date('H:i', strtotime($paty['vote_date'])) . 
				'</div>' .
				'<div class="days-paty-info find-paty-info">' .
					'<div class="days-paty-info-item days-paty-no">' . h($paty['count_no']) . '</div>/' .
					'<div class="days-paty-info-item days-paty-yes">' . h($paty['count_yes']) . '</div>/' .
					'<div class="days-paty-info-item days-paty-abcent">' . h($paty['count_abcent']) . '</div>' .
				'</div>' .
				'<h3 class="days-paty-name find-paty-name" data-paty_id="' . $paty['paty_id'] . '">' . h($paty['paty_name']) . '</h3>' .
				'<a class="days-paty-link" href="' . SITE_URL . '?page=paty&paty_id=' . $paty['paty_id'] . '"></a>' .
			'</li>'
		;
	}
	$view->setTitle('Результаты поиска по запросу: ' . h($sQ));
	
	$iCiuntResults = count($patys);
	$sContent =
		'<div class="find">' .
			'<div class="block-content">' .
				'<h1 class="days-day-name find-day-name">' .
					'Результаты поиска по запросу "' . h($sQ) . '"<br>' . 
					'Всего найдено: ' . $iCiuntResults . ' ' . my_mozg($iCiuntResults, 'записей', 'запись', 'записи') . 
				'</h1>' .
				'<ul class="days-day-patys">' .
					implode('', $patys_str) .
				'</ul>' .
			'</div>' .
		'</div>'
	;
	echo \view\body($sContent);
}