<?php

namespace view;

function body($sContent){
	$view = \my_view::getInstance();
	
	$view->addJs('./jquery/jquery-2.1.1.min.js', -99);
	
	$view->addCss('./body/reset.css');
	$view->addCss('./body/font.css');
	$view->addCss('./body/body.css');
	$view->addJs('./body/body.js');
	
	$view->addParam('url', array(
		'path' => SITE_URL,
		'static' => SITE_STATIC_URL
	));
	
	$view->addMeta('<meta charset="utf-8">');
	$view->addMetaName('viewport', 'width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0');
	
	$sHeader = \view\header();
	//$sFooter = \view\block\footer();
	
	//$view->setKeywords('');
	//$view->setDescription('');
	$view->setImage(SITE_STATIC_URL . 'body/preview.jpg');
	
	$sContent =
		'<!DOCTYPE html>' .
		'<html>' .
			'<head>' .
				$view->getHead() .
				$view->getMeta() .
				$view->getCss() .
			'</head>' .
			'<body>' .
				$sHeader .
				'<div class="body">' .
					$sContent .
				'</div>' .
			'</body>' .
			$view->getParam() .
			$view->getJs() .
		'</html>' .
		"\n" . '<!--' . "\n" .
			"\t" . 'Author: uginroot@gmail.com' . "\n" . 
			"\t" . 'GenerateDate: ' . date("Y-m-d H:i:s", time()) . "\n" . 
			"\t" . 'GenerateTime: ' . (my_gettime() - GENERATE_START_TIME) . "\n" . 
			"\t" . 'GenerateMemory: ' . memory_get_usage() . "\n" . 
		'-->';
	;
	return $sContent;
}