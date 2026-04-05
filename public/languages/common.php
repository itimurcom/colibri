<?php
/* установки для работы с административной панелью */

// кнопки
define('BUTTON_ADD', 'добавить');
define('BUTTON_OK', 'Ok');
define('BUTTON_REMOVE', 'Удалить');
define('BUTTON_NEW', 'Новый');
define('BUTTON_REPLICANT', 'Репликант');
define('BUTTON_SHOP', 'В магазине');
define('BUTTON_ECONOM', 'Эконом');
define('BUTTON_BACKGROUND', 'Обои');
define('BUTTON_SPAM', 'В спам');
define('BUTTON_SPAM_X', 'Вo входящие');
define('NODE_LOGOUT', 'Выйти');
define('BUTTON_PLUS_ITEM', '+ Товар');

// статусы
define('STATUS_MODERATE', 'на модерации');
define('STATUS_PUBLISHED', 'опубликовано');
define('STATUS_DELETED', 'в корзине');
define('STATUS_SPAM', 'СПАМ!');
define('STATUS_NOSPAM', 'вернули');

// кнопки администратора
define('BUTTON_ED_TEXT','&#8626; текст');
define('BUTTON_ED_GALLERY','&#8626; галлерея');
define('BUTTON_ED_SWITCH','&#9712; размер');
define('BUTTON_ED_AVATAR','&#9703; аватар');
define('BUTTON_ED_AVATAR_REMOVE','&#215; аватар');
define('BUTTON_ED_SWITCH_LEFT','&#9664; аватар');
define('BUTTON_ED_SWITCH_RIGHT','аватар &#9654;');
define('BUTTON_TRANSLATE','Перевести с ');
define('BUTTON_ED_AUDIO','&#8626; аудио');
define('BUTTON_ED_VIDEO','&#8626; видео');
define('BUTTON_ED_MEDIA','&#8626; медиа');
define('BUTTON_ED_IMAGE','&#8626; фото');
define('BUTTON_ED_PLAYER','&#8626; урок');
define('BUTTON_ED_TICKER','&#8626; тикер');
define('BUTTON_ED_CHANGE','&#9776; изменить');

define('BUTTON_ED_TITLE','Название');
define('BUTTON_GR','G');
define('BUTTON_MODERATE','На модерацию');
define('BUTTON_PUBLISH','Опубликовать');
define('BUTTON_N', '#');

define('BUTTON_PLUS_CATS','+раздел');
define('BUTTON_PLUS_CONTENT','+ материал');
define('BUTTON_PLUS_SLIDER','+ слайд');

define('BUTTON_USER_ADD','+ пользователь');
define('QUERY_ADD_USER', '<b>Добавить пользователя</b>');
define('ADD_USER_LABEL', 'введите логин (пароль измените на странице)');
define('USER_LOGIN_BUSY', "Пользовтель с логином <b>[VALUE]</b> уже существует, попробуйте выбрать другой логин");
define('USER_ADD_DONE', "Пользовтель c логином <b>[VALUE]</b> добавлен, не забудьте назначить пароль");

define('BUTTON_SLIDER_TITLE','Надпись');
define('BUTTON_SLIDER_HREF','Ссылка');

// запросы модальных окон
define('ITEM_TITLE_QUERY', "Измените название изделия <font color='blue'>[VALUE]</font>&nbsp;<small class='red'>( [LANG] )</small><br/> или оставьте поле пустым");
define('QUERY_ITEM_REMOVE', "Желаете <font color='red'>удалить</font> изделие <font color='blue'>[VALUE]</font>?");

define('ED_TITLE_CHANGE_QUERY','<b>Измените название контента <font color=\'blue\'>[VALUE]</font></b>');
define('ED_CATS_TITLE_CHANGE_QUERY','<b>Измените название каталога <font color=\'blue\'>[VALUE]</font></b>');
define('SLIDER_TITLE_QUERY','<b>Введите надпись для слайда <font color=\'blue\'>[VALUE]</font></b>');

define('QUERY_ED_PLAYER','<b>Выберите материал для вставки из списка загруженных <br><font color=\'blue\'>([VALUE])</font></b>');
define('QUERY_CONTENT_REMOVE',"Вы действительно хотите удалить контент <font color='blue'>[VALUE]</font>?");
define('QUERY_CATS_N',"Выберите родительскую категорию для <font color='blue'>[VALUE]</font> в дереве категорий.");
define('QUERY_CATS_GR',"Выберите группу для <font color='blue'>[VALUE]</font> в дереве категорий.");
define('QUERY_CATS_X',"Вы действительно хотите удалить категорию <font color='blue'>#[VALUE]</font>?");
define('QUERY_CONTENTS_CATS',"Выберите раздел для контента <font color='blue'>[VALUE]</font> в дереве категорий.");
define('QUERY_ADD_CATS','<b>Добавьте новый раздел</b>');
define('QUERY_ADD_CATS_GROUP', '<b>Укажите группу</b>');
define('CATS_NAME_LABEL','<b>Введите название для раздела (<font color=\'blue\'>[VALUE]</font>)</b>');
define('QUERY_ADD_CONTENT','<b>Добавьте новый материал на сайт</b>');
define('ADD_CONTENT_LABEL','<b>Введите название для материала (<font color=\'blue\'>[VALUE]</font>)</b>');
define('QUERY_ADD_CONTENT_CATEGORY','Укажите раздел');
define('QUERY_CONTENT_MODERATE','Действительно хотите отправить материал <font color=\'blue\'>[VALUE]</font> на модерацию?');
define('QUERY_CONTENT_PUBLISH','Действительно хотите опубликовать материал <font color=\'blue\'>[VALUE]</font>?');
define('QUERY_SLIDER_X',"Вы действительно хотите удалить слайд #<font color='blue'>[VALUE]</font>?");
define('QUERY_ED_CHANGE','<b>Измените данные для <font color=\'green\'>[VALUE]</font></b>');


// дополнительные надписи
define('ED_TEXT_PLACEHOLDER','введите текст');

//группы категорий
define('GR_CONTENT_TITLE', 'Контент');
define('GR_BLOCK_TITLE','Блок');

define('BLOCK_BUTTON','Блок #');
define('QUERY_BLOCK_CONTENT',"<b>Выберите контент для бока <font color='blue'>#[VALUE]</font></b>");
define('QUERY_BLOCK','Выберите контент из списка, <br/>который подготовлен для блоков');
define('HIDE_BLOCK','Cкрыть');

// даты
define('QUERY_CHANGE_DATE','Выберите дату публикации');

// killall
define('QUERY_KILLALL','<b>Желаете очистить все материалы со статусом <font color=blue>[VALUE]</font>?</b>');
define('BUTTON_KILLALL','очистить');

// панели
define('MODERATOR_PRODUCTS_LABEL','Продукты, ожидающие модерации');
define('MODERATOR_ACTION_LABEL','Операции администратора');

define('NO_PRODUCTS','Нет продуктов для модерации');
define('NO_CONTENTS','Нет новостей для модерации...');
define('ADD_BOARD_BUTTON','+ доска');
define('QUERY_ACTION_BOARD', "Добавьте в систему <font color='red'>новую</font> доску.");

define('MODERATOR_CONTENT_LABEL','Новости на модерации');
define('NO_NEWS','Нет новостей');

define('BUTTON_LATLONG','Координаты Доски');
define('BUTTON_USER', 'Хозяин Доски');

// компании
define('QUERY_PLAY_COMPAIGN',"Желаете <b><font color='red'>запустить</font></b>рекламную компанию<br/><font color='blue'>[VALUE]</font> с оплатой смартами?");

// почта
define('STATUS_PREPARED','Готово');
define('STATUS_WAIT','На отправке');
define('STATUS_SEND','Отправлено');
define('STATUS_RECIEVED','Получено');
define('STATUS_ERROR','Ошибка');

// Письма
define('ADMIN_ORDER_ACCEPT_TITLE', 'Поступил новый заказ');
define('ADMIN_CONTACT_ACCEPT_TITLE','Отправлено сообщение с сайта');
define('ADMIN_BUY_ACCEPT_TITLE','Заказ из магазина');
define('ADMIN_MEASUREMENT_ACCEPT_TITLE','Мерки тип [VALUE] для заказа');
define('FULL_ADDRESS_TITLE','Полный адрес');

// товары
define('NAME_ALL','Все');
define('NAME_AEROBICS','Аэробика');
define('NAME_SKATING','Фигурное Катание');
define('NAME_GYMNASTICS','Гимнастика');
define('NAME_UNIFORM','Различная Форма');
define('NAME_SWIMMING','Плавание');
define('NAME_ACROBATICS','Акробатика');

define('QUERY_ADD_ITEM', "Введите данные, чтобы добавить новый товар <small class='blue'>( [VALUE] )</small>");
define('ITEM_LABEL', 'Название');
define('ITEM_CATEGORY', 'Тип товара');
define('SPECIAL_ITEM_LABEL', 'Отметки');
define('ITEM_REPLICANT', "<b class='blue'>Репликант</b>");
define('ITEM_SHOP', "<b class='green'>В магазин</b>");
define('ITEM_SERIE', 'Модель');
define('ITEM_VERSION', 'Серия (номер)');
define('BUTTON_ARTICUL', 'Артикул');
define('QUERY_ARTICUL_ITEM', 'Измените данные артикула');
define('MORE_MAILING_HISTORY_TEXT', 'более ранние письма');

define('MEAS_1', 'мерки 1');
define('MEAS_2', 'мерки 2');
define('MEAS_3', 'мерки 3');
define('MEAS_4', 'мерки 4');
define('MEAS_5', 'мерки 5');

define('ITEM_PRICE_QUERY', 'Укажите базовую стоимость');
?>