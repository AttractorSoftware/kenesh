<?php

namespace view;

function statistic(){
	
	$view = \my_view::getInstance();
	$view->addCss('./statistic/statistic.css');
	$view->setTitle('Статистика');
	
	$sContent =
		'<div class="statistic">' .
			'<div class="block-content">' .
				'<h1 class="statistic-title">Статистика</h1>' .
				'<div class="statistic-info">' . 
					'<p class="statistic-p">Все информация в этом разделе собрана по данным за период с 11 сентября 2014 года по 28 мая 2015 года. Источники указаны в разделе 	 <a class="header-menu-link" href="' . SITE_URL . '?page=about">о проекте</a></p>' .
					'<p class="statistic-p"><a class="statistic-a" href="' . SITE_URL . '?page=fake_vote">Какие голоса не должны быть засчитаны</a></p>' .
					'<p class="statistic-p"><a class="statistic-a" href="' . SITE_URL . '?page=hooky">Прогульщики</a></p>' .
					//'<p class="statistic-p"><a class="statistic-a" href="' . SITE_URL . '?page=get_vote">Переданные голоса</a></p>' .
				'</div>' .
			'</div>' .
		'</div>'
	;
	echo \view\body($sContent);
}