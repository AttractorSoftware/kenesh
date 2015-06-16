<?php 

namespace view;

function fake_vote(){
	
	$view = \my_view::getInstance();
	$view->addCss('./fake_vote/fake_vote.css');
	$view->setTitle("Какие голоса не должны быть засчитаны");
	
	$data = file_get_contents(__DIR__ . '/fake_vote1.csv');
	$data = explode("\r\n", $data);
	foreach($data as $key=>$value){
		$data[$key] = explode("\t", $value);
		$data[$key] = array(
			'Дата/ФИО' => '<span class="fake_vote-date">' . $data[$key][0] . '</span> <span class="fake_vote-user_name">' . $data[$key][1] . '</span>',
			'Законопроект' => '<a href="' . SITE_URL . '?page=paty&paty_id=' . $data[$key][2] . '">' . $data[$key][3] . '</a>',
		);
	}
	
	$data2 = file_get_contents(__DIR__ . '/fake_vote2.csv');
	$data2 = explode("\r\n", $data2);
	foreach($data2 as $key=>$value){
		$data2[$key] = explode("\t", $value);
		$data2[$key] = array(
			'Фракция' => '<span class="fake_vote-frakcia">' . $data2[$key][0] . '</span>',
			'Количество нарушений' => $data2[$key][1],
			'Количество депутатов' => $data2[$key][2],
		);
	}
	
	
	
	$sContent =
		'<div class="fake_vote">' .
			'<div class="block-content">' .
				'<div class="fake_vote-content">' .
					'<h1 class="fake_vote-title">' .
						"Какие голоса не должны быть засчитаны" .
					'</h1>' .
					'<p class="fake_vote-p">' .
						'В этом разделе выведены все голоса, для которых верны следующие условия:<br><br>' .
						'1. Депутат заранее обозначил, что его на заседании не будет<br>' .
						'2. Свой голос он не передал другому депутату<br>' .
						'3. Согласно данным, этот депутат все равно участвовал в голосовании.<br><br>' .
						'Скачать данные в формате <a href="' . SITE_STATIC_URL . 'fake_vote/fake_vote1.csv">csv</a>.' .
					'</p>' .
					// '<p class="fake_vote-p">' .
						// "В партиях не указанных в таблице нарушений не найдено." .
					// '</p>' .
				'</div>' .
				print_table($data2, false, 10) . 
				
				'<div class="fake_vote-content">' .
					'<h2 class="fake_vote-title">' .
						"Детализация" .
					'</h2>' .
				'</div>' .
				print_table($data, false, 10) . 
			'</div>' .
		'</div>'
	;
	
	echo \view\body($sContent);
}