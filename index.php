<?php
include('./config.php');


if(isset($_GET['page'])){
	my_controller::page($_GET['page']);
}else{
	my_controller::page(my_controller::$default_page);
}
