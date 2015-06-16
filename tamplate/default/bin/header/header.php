<?php

namespace view;

function header(){
	$view = \my_view::getInstance();
	$view->addCss('./header/header.css');
	
	$sFindQuery = '';
	if(isset($_GET['page']) && $_GET['page'] == 'find' && isset($_GET['q'])){
		$sFindQuery = $_GET['q'];
	}
	
	$sContent =
		'<header class="header">' .
			'<div class="block-content">' .
				'<ul class="header-menu clearfix">' .
					'<li class="header-menu-item">' .
						'<a class="header-menu-link" href="' . SITE_URL . '">' .
							'Главная' .
						'</a>' .
					'</li>' .
					'<li class="header-menu-item">' .
						'<a class="header-menu-link" href="' . SITE_URL . '?page=days&mouth=05&year=2015">' .
							'Голосования' .
						'</a>' .
					'</li>' .
					'<li class="header-menu-item">' .
						'<a class="header-menu-link" href="' . SITE_URL . '?page=statistic">' .
							'Статистика' .
						'</a>' .
					'</li>' .
					'<li class="header-menu-item">' .
						'<a class="header-menu-link" href="' . SITE_URL . '?page=about">' .
							'О проекте' .
						'</a>' .
					'</li>' .
					'<li class="header-menu-item header-find">' .
						'<form action="' . SITE_URL . '" method="GET">' .
							'<input type="hidden" name="page" value="find">' .
							'<input class="header-find-text" type="text" name="q" value="' . ha($sFindQuery) . '" placeholder="Что искать?">' . 
							'<input class="header-find-submit" type="submit" value="поиск">' .
						'</form>' .
					'</li>' .
				'</ul>' .
			'</div>' .
		'</header>'
	;
	return $sContent;
}