<?php

namespace view;

function about(){
	$view = \my_view::getInstance();
	$view->addCss('./about/about.css');
	
	$sContent =
		'<div class="about">' .
			'<div class="block-content">' .
				'<div class="about-data">' .
					'<p class="about-p">Проект "Как голосуют депутаты" разработан во время Центральноазиатского хакатона по открытым данным - 2015, организованном ОФ "ИТ Аттрактор" и фондом "Сорос-Кыргызстан".</p>' .
					'<p class="about-p">Цель проекта - демонстрация возможностей применения открытых данных.</p>' .
					'<p class="about-p">В качестве исходных данных взяты <a class="about-a" href="http://kenesh.kg/RU/Folders/3571-Rezultaty_golosovaniya.aspx">результаты голосований</a> депутатов с сайта Жогорку Кенеша, предоставленные организаторами в <a class="about-a" href="http://api.opendata-hackathon.com/#post_131">машиночитаемом формате</a>.</p>' .
					'<p class="about-p">Также использовались данные об <a class="about-a" href="http://kenesh.kg/RU/Folders/4258-Uchastie_deputatov_v_zasedaniyax_ZHogorku_Kenesha.aspx">посещений сессий</a> Жогорку Кенеша депутатами, <a class="about-a" href="http://opendatakosovo.org">обработанные</a> Жоржем Лабрешем.</p>' .
				'</div>' .
			'</div>' .
		'</div>'
	;
	
	echo \view\body($sContent);
}
