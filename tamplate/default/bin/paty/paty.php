<?php

namespace view;

function paty(){
	
	if(!isset($_GET['paty_id'])){
		my_notfound("Not set get parameter paty_id");
	}
	
	$view = \my_view::getInstance();
	$view->addCss('./paty/paty.css');
	$view->addJs('./paty/paty.js');
	
	$paty = \Db::Query("
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
		WHERE vote.paty_id = " . ee($_GET['paty_id']) . "
		GROUP BY vote.paty_id
		ORDER BY vote.vote_date DESC
	");
	
	if(count($paty) == 0){
		my_notfound("Paty not found");
	}
	$paty = $paty[0];
	$view->setTitle($paty['paty_name']);
	
	$votes = \Db::Query("
			SELECT
			cont_vote.vote_status_num,
			cont_name.`name`,
			cont_name.`id`,
			cont_absentees.transferredVoteTo,
			cont_absentees.reason,
			cont_absentees.reasonDetail,
			cont_frakcia.frakcia_id,
			cont_frakcia.frakcia_name
		FROM
			cont_vote
				INNER JOIN cont_name ON cont_vote.user_id = cont_name.id
				LEFT OUTER JOIN cont_absentees ON cont_vote.absentees_id = cont_absentees.id
				INNER JOIN cont_frakcia ON cont_name.frakcia_id = cont_frakcia.frakcia_id
		WHERE cont_vote.paty_id = " . ee($paty['paty_id']) . "
		ORDER BY cont_frakcia.frakcia_id, cont_name.`name`
	");
	
	$frakcias = array();
	foreach($votes as $vote){
		if(!isset($frakcias[$vote['frakcia_name']])){
			$frakcias[$vote['frakcia_name']] = array();
		}
		$frakcias[$vote['frakcia_name']][] = $vote;
	}
	
	$fractia_str = array();
	foreach($frakcias as $frakcia_name => $votes){
		$votes_str = array();
		foreach($votes as $vote){
			$votes_str[] =
				'<li class="paty-vote">' .
					'<h3 class="paty-vote-user_name">' .
						'<a class="paty-vote-link" href="' . SITE_URL . '?page=user&user_id=' . $vote['id'] . '">' .
							h($vote['name']) .
						'</a>' .
					'</h3>' .
					'<div class="paty-vote-info">' .
						(is_null($vote['transferredVoteTo']) ? '' : '<div class="paty-vote-transfer">' . h($vote['reason']) . '</div>') . 
						(is_null($vote['transferredVoteTo']) || $vote['transferredVoteTo'] == '-' ? '' : 
							'<div class="paty-vote-transfer-to"> <span class="paty-transfer-info">отдал голос</span> ' . h($vote['transferredVoteTo']) . '</div>'
						) . 
					'</div>' .
					'<div class="paty-vote-status paty-vote-status-' . $vote['vote_status_num'] . '">' .
						($vote['vote_status_num'] == 0 ? 'отсутствовал' : '') .
						($vote['vote_status_num'] == 1 ? 'за' : '') .
						($vote['vote_status_num'] == -1 ? 'против' : '') .
						($vote['vote_status_num'] == 2 ? 'не голосовал' : '') .
					'</div>' .
				'</li>'
			;
		}
		
		$fractia_str[] = 
			'<div class="paty-fractia">' .
				'<h2 class="paty-fractia-name">' .
					h($frakcia_name) .
				'</h2>' .
				'<ul class="paty-votes">' .
					implode('', $votes_str) .
				'</ul>' .
			'</div>'
		;
	}
	
	$sContent =
		'<div class="paty">' .
			'<div class="block-content">' .
				'<h1 class="paty-name">' .
					h($paty['paty_name']) .
				'</h1>' .
				'<div class="paty-info">' .
					'<div class="paty-info-title">' .
						'Дата голосования' .
					'</div>' .
					'<div class="paty-info-value">' .
						h(mb_strtolower(my_date("j n Y года, H:i:s", strtotime($paty['vote_date'])))) .
					'</div>' .
				'</div>' .
				'<div class="paty-info">' .
					'<div class="paty-info-title">' .
						'Против' .
					'</div>' .
					'<div class="paty-info-value paty-info-no">' .
						h($paty['count_no']) .
					'</div>' .
				'</div>' .
				'<div class="paty-info">' .
					'<div class="paty-info-title">' .
						'За' .
					'</div>' .
					'<div class="paty-info-value paty-info-yes">' .
						h($paty['count_yes']) .
					'</div>' .
				'</div>' .
				'<div class="paty-info">' .
					'<div class="paty-info-title">' .
						'Отсутствовали' .
					'</div>' .
					'<div class="paty-info-value paty-info-abcent">' .
						h($paty['count_abcent']) .
					'</div>' .
				'</div>' .
				implode('', $fractia_str) .
			'</div>' .
		'</div>'
	;
	echo \view\body($sContent);
}
















