<?php

class __view{
	protected static $instance = false;
	private function __construct(){}
	private function __clone(){}
	
	public static function getInstance(){
		if(self::$instance === false){
			self::$instance = new __view();
		}
		return self::$instance;
	}
	
	private static $unic_id = 4744747546;
	private static function getId($id){
		if(is_null($id)){
			self::$unic_id++;
			return self::$unic_id;
		}else{
			return $id;
		}
	}
	private $css = array();
	private $js = array();
	private $meta = array();
	private $head = array();
	private $param = array();
	private $varible = array();
	
	private $name = '';
	

	/**
	 * Сохранить переменную
	 * 
	 * param string $name - имя переменной
	 * param mixed $value - переменная
	 */
	public function setVarible($name, $value){
		$this->varible[$name] = $value;
	}
	/**
	 * Забрать переменную
	 * 
	 * param string $name - имя переменной
	 */
	public function getVarible($name){
		if(!isset($this->varible[$name])){
			return null;
		}
		return $this->varible[$name];
	}
	
	
	/**
	 * Имена файлов в правильном порядке для getCss и getJs
	 * 
	 * param array $files - массив файлов из функции getOrderFilesItem
	 */
	private static function getOrderFiles($files){
		usort($files, function($a, $b){
			if($a['priority'] == $b['priority']){
				return $a['order'] == $b['order'] ? 0 : ($a['order'] > $b['order'] ? 1 : -1);
			}
			return $a['priority'] > $b['priority'] ? 1 : -1;
		});
		$names = array();
		foreach($files as $name){
			$names[] = $name['name'];
		}
		return $names;
	}
	
	/**
	 * Добавляем файл в список, если такой файл уже есть заменяем его
	 * 
	 * param array $list - список файлов
	 * param string $name - путь до js файла относительно корня папки static
	 * param string $id - идентифекатор для данного файла, что-бы можно было его заменить
	 * param int $priority - приоритет в сортировке перед выводом
	 */
	private static function setOrderFilesItem(&$list, $name, $priority = 0, $id = null){
		if(is_null($id)){
			$id = $name;
		}
		$new_file = array(
			'id' => $id,
			'name' => $name, 
			'priority' => $priority,
			'order' => self::getId(null),
		);
		foreach($list as $index => $file){
			if($file['id'] == $id){
				$list[$index] = $new_file;
				return;
			}
		}
		$list[] = $new_file;
	}
	
	/**
	 * Возвращаем html код для подключения всех CSS
	 */
	public function getCss(){
		$aCss = \cache::statics(self::getOrderFiles($this->css));
		foreach($aCss as $k=>$sCss){
			$aCss[$k] = '<link type="text/css" rel="stylesheet" href="' . $sCss . '">';
		}
		return implode('', $aCss);
	}
	/**
	 * Возвращаем html код для подключения всех JS
	 */
	public function getJs(){
		$aJs = \cache::statics(self::getOrderFiles($this->js));
		foreach($aJs as $k=>$sJs){
			$aJs[$k] = '<script type="text/javascript" src="' . $sJs . '"></script>';
		}
		return implode('', $aJs);
	}
	
	/**
	 * Возвращаем html код метатегов
	 */
	public function getMeta(){
		return implode('', $this->meta);
	}
	/**
	 * Возвращаем html подключаемый в head документа
	 */
	public function getHead(){
		return implode('', $this->head);
	}
	
	/**
	 * Возвращаем html подключаемый в head документа
	 */
	public function getParam(){
		return '<script>var $_PARAM=' . my_json_encode($this->param) . '</script>';
	}
	
	
	
	/**
	 * Устанавливаем имя страницы
	 * 
	 * param string $name
	 */
	public function setName($name){
		$this->name = $name;
	}
	/**
	 * Возвращает идентификатор текущей страницы
	 */
	public function getName(){
		return $this->name;
	}
	
	/**
	 * Добавляем в шаблон CSS
	 * 
	 * param string $name - путь до css файла относительно корня папки static
	 * param string $id - идентифекатор для данного файла, что-бы можно было его заменить
	 * param int $priority - приоритет в сортировке перед выводом
	 */
	public function addCss($name, $priority = 0, $id = null){
		self::setOrderFilesItem($this->css, $name, $priority, $id);
	}
	
	
	/**
	 * Добавляем в шаблон JS скрипты
	 * 
	 * param string $name - путь до js файла относительно корня папки static
	 * param string $id - идентифекатор для данного файла, что-бы можно было его заменить
	 * param int $priority - приоритет в сортировке перед выводом
	 */
	public function addJs($name, $priority = 0, $id = null){
		self::setOrderFilesItem($this->js, $name, $priority, $id);
	}

	/**
	 * Добавляем в шаблон META теги
	 * 
	 * param string $html - содержимое тега
	 */
	public function addMeta($html){
		$this->meta[self::getId(null)] = $html;
	}
	/**
	 * Добавляем в шаблон META теги
	 * 
	 * param string $name
	 * param string $content
	 */
	public function addMetaName($name, $content){
		$this->meta['name_' . $name] = '<meta name="' . h($name) . '" content="' . h($content) . '">';
	}
	/**
	 * Добавляем в шаблон META теги
	 * 
	 * param string $property
	 * param string $content
	 */
	public function addMetaProperty($property, $content){
		$this->meta['property_' . $property] = '<meta property="' . h($property) . '" content="' . h($content) . '">';
	}

	/**
	 * Добавляем в head произвольный HTML
	 * 
	 * param string $html - содержимое тега
	 * param string $id - идентифекатор для тега
	 */
	public function addHead($html, $id = null){
		$this->meta[self::getId($id)] = $html;
	}
	
	/**
	 * Устанавливаем заголовок для страницы
	 *
	 * param string $title
	 */
	public function setTitle($title){
		$this->setVarible('title', $title);
		$this->addMetaName('title', $title);
		$this->addMetaName('twitter:title', $title);
		$this->addMetaProperty('og:title', $title);
		$this->addHead('<title>' . h($title) . '</title>', 'title');
	}
	/**
	 * Устанавливаем ключевые слова для страницы
	 *
	 * param string $keywords
	 */
	public function setKeywords($keywords){
		$this->addMetaName('keywords', $keywords);
		$this->addMetaProperty('og:keywords', $keywords);
	}
	/**
	 * Устанавливаем краткое описание для страницы
	 *
	 * param string $description
	 */
	public function setDescription($description){
		$this->addMetaName('description', $description);
		$this->addMetaProperty('og:description', $description);
	}
	/**
	 * Устанавливаем краткое описание для страницы
	 *
	 * param string $description
	 */
	public function setImage($src){
		$this->addMetaProperty('og:image', $src);
	}
	
	/**
	 * Добавляем данные в js которые необходимы для инициализации
	 * 
	 * param string $name
	 * param mixed $obj
	 */
	public function addParam($name, $obj){
		$this->param[$name] = $obj;
	}
}








