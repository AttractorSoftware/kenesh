<?php

namespace view;

function user(){
	if(!isset($_GET['user_id'])){
		my_notfound("Not set 'user_id'");
	}
	$view = \my_view::getInstance();
	$view->addCss('./paty/paty.css');
	$view->addCss('./days/days.css');
	$view->addCss('./find/find.css');
	$view->addCss('./user/user.css');
	
	$user = \Db::Query("
		SELECT * FROM cont_name INNER JOIN cont_frakcia ON cont_frakcia.frakcia_id = cont_name.frakcia_id WHERE cont_name.id = " . ee($_GET['user_id']) . "
	");
	if(count($user) == 0){
		my_notfound("User not found");
	}
	$user = $user[0];
	
	$votes = \Db::Query("
		SELECT
			paty.paty_id, 
			vote.vote_date,  
			vote.vote_date2, 
			paty.paty_name2 AS paty_name, 
			vote.vote_status_num
		FROM `cont_vote` AS vote
			INNER JOIN cont_paty AS paty ON paty.paty_id = vote.paty_id
		WHERE vote.user_id = " . ee($user['id']) . "
		GROUP BY vote.paty_id
		ORDER BY vote.vote_date DESC
		LIMIT 100
	");
	
	$votes_str = array();
	foreach($votes as $vote){
		$votes_str[] = 
			'<li class="days-paty clearfix">' .
				'<div class="days-paty-time find-paty-time">' .
					date('Y-m-d', strtotime($vote['vote_date'])) . ' <br> ' .
					date('H:i', strtotime($vote['vote_date'])) . 
				'</div>' .
				'<div class="days-paty-info user-vote-status paty-vote-status-' . $vote['vote_status_num'] . '">' .
					($vote['vote_status_num'] == 0 ? 'отсутствовал' : '') .
					($vote['vote_status_num'] == 1 ? 'за' : '') .
					($vote['vote_status_num'] == -1 ? 'против' : '') .
					($vote['vote_status_num'] == 2 ? 'не голосовал' : '') .
				'</div>' .
				'<h3 class="days-paty-name find-paty-name" data-paty_id="' . $vote['paty_id'] . '">' . h($vote['paty_name']) . '</h3>' .
				'<a class="days-paty-link" href="' . SITE_URL . '?page=paty&paty_id=' . $vote['paty_id'] . '"></a>' .
			'</li>'
		;
	}
	$view->setTitle($user['name']);
	
	$sContent = 
		'<div class="days">' .
			'<div class="block-content">' .
				'<div class="user-info">' .
					'<h1 class="user-user_name">' .
						h($user['name2']) . ' / <span class="user-frakcia_name">' . h($user['frakcia_name']) . '</span>' .
					'</h1>' .
				'</div>' .
				'<ul class="days-list">' .
					implode('', $votes_str) .
				'</ul>' .
			'</div>' .
		'</div>'
	;
	echo \view\body($sContent);
}