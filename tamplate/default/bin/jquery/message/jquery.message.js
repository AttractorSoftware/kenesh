/*!
 * Author: Soroka Anton 
 *
 * Name: jQuery.message
 * Version: 1.0
 * Requires: jQuery 1.8+
 * Requires: jQuery.mousewheel (https://github.com/brandonaaron/jquery-mousewheel)
 */
(function($){
	
	// Итератор id для диалогового окна
	var incriment = 0;
	
	// Плагин для создания модального окна с заданным содержимым
	// Для работы в опциях следует передать 3 поля:
	//		form: text, DOMObject, jQueryObject
	//		onclose: function(callback)
	//			// Вызывается перед закрытием окна (например при нажатии
	//			// на область за пределами границ окна)
	//			// Выполняется в контексте form
	//			// По умолчанию function(callback){callback(true);}
	//			// Если функция callback вернёт в качестве первого 
	//			// параметра значение false то окно не закроется.
	//		onkeydown: function(event, form){}
	//			// Событие нажатия на клавишы
	//			// Выполняется в контексте этемента на котором произошло событие
	//			// event - Объект события
	//			// form - jQueryObject переданная форма
	// Возвращает объект с методом close, кторый выполняет onclose и закрывает форму
	$.message = function(options){
		
		// Id окна
		var id = incriment++;
		
		// Настройки по умолчанию
		var configs = $.extend({
			form: '',
			onclose: function(cb){cb(true);},
			onkeydown: function(e){}
		}, options);
		
		// Если форма не является jQuery объектом, преобразовываем
		if(!(configs.form instanceof jQuery)){
			configs.form = $(configs.form);
		}
		
		// Макет модального окна
		var $me = $(
			'<div class="jq-message">' +
				'<div class="jq-message-body">' +
					'<div class="jq-message-content">' +
					'</div>' +
				'</div>' +
				'<div class="jq-message-helper"></div>' +
			'</div>'
		);
		
		//Возвращаемый объект
		var return_object = {
		
			// Закрыть окно
			close: function(){
				
				// Выполняем метод onclose
				configs.onclose.apply(configs.form,[function(close){
					if(close){
						
						// Удаляем форму
						$me.remove();
						
						// Удаляем обработчик нажатия клавиш
						$(document).off('keydown.jq_message_' + id);
						
						
						// TODO фикс для google chrome mobile xчто-бы 
						// контекст прорисовался после удаления окна
						$('dody').css("opacity","0.99");
						setTimeout(function(){
							$('body').css('opacity', '1');
						},0);
					}
				}]);
			},
			
			// Передданная форма jQueryObject
			form: configs.form,
			
			// Уникальный идентификатор окна
			id: id
		};
		
		
		// Клик за пределами окна, останавливаем всплытие события и пытаемся закрыть окно
		$me.click(function(e){
			e.preventDefault();
			e.stopPropagation();
			return_object.close();
		});
		
		// Клик в приделах окна, останавливаем всплытие
		$me.find('.jq-message-body').click(function(e){
			e.preventDefault();
			e.stopPropagation();
		});
		
		// Нажатие на клавиши
		$(document).on('keydown.jq_message_' + id, function(e){
			
			// Передаём собыите в назначеный обработчик
			configs.onkeydown.apply(this, [e, configs.form]);
		});
		
		
		// Останавливаем всплытие событие прокрутки скролом
		// чтобы страница не прокручивалась
		$me.mousewheel(function(e){
			e.preventDefault();
		});
		
		// Прокрутка содержимого формы, если оно не помещается в заданные пределы окна
		$me.find('.jq-message-body').mousewheel(function(e){
			var $self = $(this),
				scroll_top = $self.scrollTop(),
				scroll_height = parseInt($self.prop('scrollHeight'), 10),
				outer_height = $self.outerHeight()
			;
			
			// Прокрутка вверх
			if(e.deltaY > 0 && scroll_top <= 0){
				return;
			}
			
			// Прокрутка вниз
			else if(e.deltaY < 0 && scroll_top >= scroll_height - outer_height - 1){
				return;
			}
			
			// Если фукция доработала до этой части то скролинг содержимого окна закончен,
			// после этого будит прокручиваться вся страница. Для того что-бы это
			// предотвратить необходимо вызвать метод e.stopPropagation().
			e.stopPropagation();
		});
		
		// Добавляем присланную форму в макет
		$me.find('.jq-message-content').append(configs.form);
		
		// Добавляем модальное окно в body
		$('body').append($me);
		
		// Возвращаем объект для обратной связи
		return return_object;
	}

}(jQuery));