<?php



// Подключаем контроллер. Класс my_controller должен наследоваться от класса __controller
require_once(__DIR__ . '/my_controller.php');
// Подключаем представления. Класс my_view должен наследоваться от класса __view
require_once(__DIR__ . '/my_view.php');

session::start();
$temp_view = \my_view::GetInstance();
$temp_view->setVarible('admin', isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1 ? true : false);
$temp_view->addParam('admin', $temp_view->getVarible('admin'));


// Подключаем представления
require_once(__DIR__ . '/days/days.php');
require_once(__DIR__ . '/body/body.php');
require_once(__DIR__ . '/main/main.php');
require_once(__DIR__ . '/paty/paty.php');
require_once(__DIR__ . '/header/header.php');
require_once(__DIR__ . '/find/find.php');
require_once(__DIR__ . '/about/about.php');
require_once(__DIR__ . '/statistic/statistic.php');
require_once(__DIR__ . '/user/user.php');
require_once(__DIR__ . '/fake_vote/fake_vote.php');
require_once(__DIR__ . '/hooky/hooky.php');
