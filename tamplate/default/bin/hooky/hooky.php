<?php 

namespace view;

function hooky(){
	
	$view = \my_view::getInstance();
	$view->addCss('./fake_vote/fake_vote.css');
	$view->setTitle("Какие голоса не должны быть засчитаны");
	
	$data = file_get_contents(__DIR__ . '/hooky1.csv');
	$data = explode("\r\n", $data);
	foreach($data as $key=>$value){
		$data[$key] = explode("\t", $value);
		$data[$key] = array(
			'ФИО' => $data[$key][1],
			'Прогулы' => $data[$key][2], 
			'Официально&nbsp;отпросился' => $data[$key][3], 
			'Всего' => $data[$key][4],
		);
	}
	
	$data2 = file_get_contents(__DIR__ . '/hooky2.csv');
	$data2 = explode("\r\n", $data2);
	foreach($data2 as $key=>$value){
		$data2[$key] = explode("\t", $value);
		$data2[$key] = array(
			'Фракция' => '<span class="fake_vote-frakcia">' . $data2[$key][0] . '</span>',
			'Прогулы' => $data2[$key][1], 
			'Официально&nbsp;отпросились' => $data2[$key][2], 
			'Всего' => $data2[$key][3],
		);
	}
	
	
	
	$sContent =
		'<div class="fake_vote">' .
			'<div class="block-content">' .
				'<div class="fake_vote-content">' .
					'<h1 class="fake_vote-title">' .
						"Прогульщики" .
					'</h1>' .
					'<p class="fake_vote-p">' .
						'В этом разделе выведенны данны о прогулах депутатов. Часть прогулов официальная и взята из <a href="http://kenesh.kg/RU/Folders/4258-Uchastie_deputatov_v_zasedaniyax_ZHogorku_Kenesha.aspx" target="_blank">открытых данных</a> Жогорку Кенеша по прогулам, часть определена из <a href="http://kenesh.kg/RU/Folders/3571-Rezultaty_golosovaniya.aspx" target="_blank">результатов голосований</a> по законапроектам в определённый день<br>' .
						'<br>' .
						'Скачать в формате <a href="' . SITE_STATIC_URL . 'hooky/hooky1.csv">csv</a>.' .
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