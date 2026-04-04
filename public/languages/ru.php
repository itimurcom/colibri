<?php
//установим определения для дат
setlocale(LC_ALL,'ru_RU.UTF-8');

// основные установки для поисковиков и шаринга
define('CMS_NAME', 'Ателье Колибри');
define('CMS_NAME_EXTENDED','Купальники для художественной гимнастики, фигурного катания, акробатики и аэробики');
define('EXPLAIN_BRAND',"мы шьем купальники для художественной&nbsp;гимнастики, фигурного катания, акробатики и аэробики");
define('CMS_DESCRIPTION','Ателье Колибри занимается изготовлением костюмов для художественной гимнастики, аэробики и фигурного катания больше 10 лет. Мы шьем для спортсменов во всем мире. Россия, США, Канада, Германия, Израиль, Испания, Италия, Голландия, Греции, Казахстан, Таджикистан, Автсралия, Венгрия и другие. Работаем под заказ.');
define('CMS_KEYS','купальники для художественной гимнастики,купальник,художественная гимнастика,аэробика,фигурное катание,акробатика');
define('OLDSITE', 'воспользуйтесь предыдущей версией сайта');
define('NO_TITLE','Без названия');
define('BUTTON_CANCEL', 'Отменить');

//пункты навигации
define ('NODE_HOME','Главная');
define ('NODE_ABOUT','О нас');
define ('NODE_GALLERY','Все предложения');
define ('NODE_ORDER','Заказ');
define ('NODE_DELIVERY','Доставка и Оплата');
define ('NODE_FRIENDS','Наши Друзья');
define ('NODE_GUEST','Отзывы&#9654;');
define ('NODE_CONTACTS','Контакты');
define ('NODE_SHOP', 'Магазин');
define ('NODE_BUY', 'Купить');
define ('NODE_MEASUREMENT', 'Мерки');

// пункты меню
define('NODE_ALL','Все предложения');
// define('NODE_AEROBICS','Костюмы для Аэробики');
define('NODE_UNITARDS','Комбинезоны для Художественной Гимнастики');
define('NODE_SKATING','Костюмы для Фигурного Катания');
define('NODE_GYMNASTICS','Купальники для Художественной Гимнастики');
define('NODE_ACCESSORIES','Аксессуары');
// define('NODE_SWIMMING','Купальники для Синхронного Плавания');
define('NODE_WATERSPORTS','Водные Виды Спорта');
define('NODE_ACROBATICS','Костюмы для Акробатики');

// пункты сабменю
define('NODE_INFO','Информация для Заказчика');
define('NODE_SIZE','Правила для снятия Мерок');
define('NODE_FABRIC','Ткани и Материалы');
define('NODE_DESIGN','Дизайн и Эскизы');
define('NODE_REPLICANTS','Копии Купальников');
define('NODE_ECONOM','Эконом Предложения');
define('NODE_NEW','Новые Предложения');

// Элементы отображения товаров
define('PROPOSITONS_TITLE', '[VALUE] предложений');
define('ITEM_ID', 'Дизайн');
define('ITEM_ARTICUL', 'Артикул');
define('ITEM_SERIE_TITLE','Все предложения в серии');
define('ITEM_COLOR_TITLE','Другие варианты данной модели');
define('COLOR_TITLE','Вариант');
define('LASTSEEN_TITLE','Недавно вы смотрели');
define('NO_DATA','Нет данных для отображения');
define('FOR_SALE','в продаже');
define('FEWER_ITEMS_TEXT','предыдущие');
define('FEWER_ITEMS_BEGIN',"это начало");

// надписи на кнопках
define('MORE_ITEMS_TEXT', 'еще предложения');
define('MORE_FRIENDS_TEXT', 'больше друзей');
define('MORE_ABOUT_TEXT', 'больше о нас');

// цвета для фильтра цветов товара
define('BURGUNDY_TITLE', 'бордовый');
define('RED_TITLE', 'красный');
define('ORANGE_TITLE', 'оранжевый');
define('YELLOW_TITLE', 'жёлтый');
define('LIMEGREEN_TITLE', 'салатовый');
define('GREEN_TITLE', 'зеленый');
define('TURQUOISE_TITLE', 'бирюза');
define('BLUE_TITLE', 'голубой');
define('DARKBLUE_TITLE', 'синий');
define('PURPLE_TITLE', 'фиолетовый');
define('MAGENTA_TITLE', 'малиновый');
define('PINK_TITLE', 'розовый');
define('WHITE_TITLE', 'белый');
define('GRAY_TITLE', 'серый');
define('BLACK_TITLE', 'черный');

define('BRONZE_TITLE', 'бронзовый');
define('SILVER_TITLE', 'серебрянный');
define('GOLD_TITLE', 'золотой');

define('WITH_COLORS', 'по цветам');
define('COLOR_SELECTOR', 'цвета модели');

// виджеты
define('WIDGET_NEW' , 'Новинка');
define('WIDGET_SHOP', 'В магазине');

// Form2
define('F2_SELECT_DISABLED', 'выберите');
define('BUY_PAGE_TITLE', 'Купить ( [VALUE] )');

define('BUTTON_BUY', "КУПИТЬ [VALUE]$");
define('BUTTON_CONTACT', "Спросить");
define('BUTTON_ORDER', "Заказать");
define('CALC_ITEM_RESULT','Ориентировочная цена без стоимости доставки');
define('DECORATION_DESCRIPTION', 'Если вы не уверены, какие стразы нужны и сколько, то в бланке заказа можно просто указать сумму для декорирования.');
define('BUTTON_CALC','Цена');
define('QUERY_CALC_ITEM', "Расчет стоимости модели <b class='blue'>[VALUE]</b>");
define('BUTTON_CLEAR', 'Очистить');
define('ORDER_MESSAGE_TITLE','Меня заинтересовало изделие [VALUE] из раздела [CAT]');
define('BUY_MESSAGE_TITLE','Хочу КУПИТЬ изделие [VALUE] из раздела [CAT]');
define('START_ENTER', 'начните ввод');
define('LOCAL_DATE_TODAY' , 'сегодня');
define('LOCAL_DATE_YESTERDAY' , 'вчера');

// Письма
define('USER_ORDER_ACCEPT_TITLE', "Ваш заказ был успешно принят");
define('USER_CONTACT_ACCEPT_TITLE', 'Ваше сообщение было успешно отправлено');
define('USER_BUY_ACCEPT_TITLE', 'Ваш запрос на покупку принят');
define('USER_MEASUREMENT_ACCEPT_TITLE', 'Ваши мерки отправлено');
define('MEASUREMENT_TITLE_DATA', "Даннные для заказа <b class='yellow'>№ [ORDER]</b>, поступившего от <b class='yellow'>[EMAIL]</b>");
define('MEASUREMENT_ERROR', "<b class='red'>ОШИБКА!</b> Неправильно указана ссылка на мерки заказа");

define('USER_AGREEMENT',"Спасибо за Ваш заказ и предоставление информации. 
Пожалуйста проверьте ваши данные ещё раз. В случае если вы обнаружили ошибку пожалуйста напишите на наш емеил ateliecolibri@gmail.com.
Детали вашего заказа будут отправлены на рассмотрение и обработку.
В течение 24 часов мы обработаем Ваш заказ.
Вам будет отправлен счёт с реквизитами на оплату и ссылкой на таблицу измерений (уведомляем Вас , что в выходные дни это может занять немного больше времени).
ЕСЛИ ВЫ НЕ ПОЛУЧИЛИ СЧЁТ В ТЕЧЕНИИ 24 ЧАСОВ, ПОЖАЛУЙСТА СВЯЖИТЕСЬ С НАМИ.");

define('MOBILE_MENU', 'МЕНЮ');

define('SOCIAL_NETWORKS' , 'Социальные сети');
define('VARIANT', 'Вариант');

define('THANKYOU','Спасибо');

define('NEWFIRST_TITLE',	'сначала новые');
define('PRICE_UP_TITLE', 	'по возвростанию цены');
define('PRICE_DOWN_TITLE',	'по убыванию цены');

define('NEED_CORRECT', 'нужно исправить');

define('NODE_REGISTER', 'Личный кабинет');
define('ENTER_EMAIL' , 'Укажите E-Mail');
define('ENTER_PAGE_TITLE', 'Вы наш клиент?');
define('REGISTER_PAGE_TITLE', 'Станьте нашим клиентом :)');

define('BUTTON_ENTER', 'Войти');
define('BUTTON_REGISTER', 'Зрегистрироваться');
define('ALREADY_HAVE', 'Уже есть в базе!');
define('NOT_REGISTERED', 'пользователь не найден');

define('PIN_DESC', "<center>Уважаемый пользователь, <b style='color:blue'>[USER]</b></center>".BR.BR.
"<center><div>Чтобы войти в систему, необходимо ввести сгенерированный PIN-код, перейдя по <a href='//atelier-colibri.com/ru/register/pin/'>ссылке</a>. Данный код действителен в течении 5 минут после отправки сообщения<div><h1>[PIN]</h1></center>");

define('NODE_CABINET', 'Кабинет пользователя');
define('NODE_PIN', 'PIN Код');
define('NODE_USERDATA', 'Данные пользователя');
define('ITEM_WHISHLIST', 'Сисок желаний ♡');
define('ENTER_PIN', 'Введите PIN код из письма');
define('ENTER_PIN_TITLE', 'PIN код');
define('ERROR_PIN', 'код не найден');
define('EXPIRED_PIN', 'код просрочен, повторите');
define('NODE_PIN_DESC', "На указанный вами email было отправлено письмо, в котором указан код для входа в личный кабинет.<b class='red'>Обратите внимание, что письмо с PIN кодом для входа в личный кабинет может попасть в ваш спам.</b>");
define('USER_NAME', 'Имя пользователя');
define('USER_ADDRESS', 'Адрес доставки');
define('USER_PHONE', 'Телефон');
define('QUERY_EDIT_USER', 'Измените данные пользователя');
define('BUTTON_EDIT', 'Изменить');
define('LOG_OUT', 'Выйти');
?>