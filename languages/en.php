<?php
//установим определения для дат
setlocale(LC_ALL,'en_UK.UTF-8');

// основные установки для поисковиков и шаринга
define('CMS_NAME', 'Atelier Colibri');
define('CMS_NAME_EXTENDED','Rhythmic gymnastics leotards, ice skating, group acrobatics and aerobics');
define('EXPLAIN_BRAND',"we sew leotards for rhythmic&nbsp;gymnastics, ice&nbsp;skating, group&nbsp;acrobatics and aerobics");
define('CMS_DESCRIPTION', 'Atelier Colibri is engaged in manufacturing of leotards for gymnastics, aerobics and figure skating for over 10 years. We sew for athletes around the world. Russia, USA, Canada, Germany, Israel, Spain, Italy, the Netherlands Greece, Kazakhstan, Tajikistan, Avtsraliya, Hungary and others. We work under the order.');
define('CMS_KEYS','rhythmic gymnastics leotards,leotard,rg,rhythmic gymnastics,aerobics,ice skating,acrobatics');
define('OLDSITE', 'you can use the previous version of the site');
define('NO_TITLE','no title');
define('BUTTON_CANCEL', 'Cancel');

//пункты навигации
define ('NODE_HOME','Home');
define ('NODE_ABOUT','About');
define ('NODE_GALLERY','All Items');
define ('NODE_ORDER','Order');
define ('NODE_DELIVERY','Pay and Delivery');
define ('NODE_FRIENDS','Our Friends');
define ('NODE_GUEST','Reviews&#9654;');
define ('NODE_CONTACTS','Contacts');
define ('NODE_SHOP', 'Shop');
define ('NODE_BUY', 'Buy an item');
define ('NODE_MEASUREMENT', 'Measurements');

// пункты меню
define('NODE_ALL','All Items');
// define('NODE_AEROBICS','Leotards for Aerobics');
define('NODE_UNITARDS','Rhythmic Gymnastics Unitards');
define('NODE_SKATING','Leotards for Ice Skating');
define('NODE_GYMNASTICS','Rhythmic Gymnastics Leotards');
define('NODE_ACCESSORIES','Accessories');
// define('NODE_SWIMMING','Leotards for Synchronized Swimming');
define('NODE_WATERSPORTS',' Water Sports');
define('NODE_ACROBATICS','Leotards for Acrobatics');

// пункты сабменю
define('NODE_INFO','Info for Support');
define('NODE_SIZE','Measurment Rules');
define('NODE_FABRIC','Fabrics and Materials');
define('NODE_DESIGN','Sketches and Designs');
define('NODE_REPLICANTS','Item Replicants');
define('NODE_ECONOM','Econom Propositions');
define('NODE_NEW','New Items');

// Элементы отображения товаров ITEMS
define('PROPOSITONS_TITLE', '[VALUE] propositions');
define('ITEM_ID', 'Design');
define('ITEM_ARTICUL', 'Article');
define('ITEM_SERIE_TITLE','All proposition in serie');
define('ITEM_COLOR_TITLE','Other variants of this model');
define('COLOR_TITLE','Variant');
define('LASTSEEN_TITLE','Your last visited items');
define('NO_DATA','No information');
define('FOR_SALE', 'for sale');
define('FEWER_ITEMS_TEXT','before');
define('FEWER_ITEMS_BEGIN',"this is begin");


// надписи на кнопках
define('MORE_ITEMS_TEXT', 'more items');
define('MORE_FRIENDS_TEXT', 'more friends');
define('MORE_ABOUT_TEXT', 'more about');

// цвета для фильтра цветов товара
define('BURGUNDY_TITLE', 'burgundy');
define('RED_TITLE', 'red');
define('ORANGE_TITLE', 'orange');
define('YELLOW_TITLE', 'yellow');
define('LIMEGREEN_TITLE', 'lime green');
define('GREEN_TITLE', 'green');
define('TURQUOISE_TITLE', 'turquoise');
define('BLUE_TITLE', 'blue');
define('DARKBLUE_TITLE', 'dark blue');
define('PURPLE_TITLE', 'purple');
define('MAGENTA_TITLE', 'magenta');
define('PINK_TITLE', 'pink');
define('WHITE_TITLE', 'white');
define('GRAY_TITLE', 'gray');
define('BLACK_TITLE', 'black');

define('BRONZE_TITLE', 'bronze');
define('SILVER_TITLE', 'silver');
define('GOLD_TITLE', 'gold');

define('WITH_COLORS', 'with colors');
define('COLOR_SELECTOR', 'model colors');

// виджеты
define('WIDGET_NEW' , 'New Proposition');
define('WIDGET_SHOP', 'In the Shop');

// Form2
define('F2_SELECT_DISABLED', 'select');

define('BUY_PAGE_TITLE', "Buy an item ( [VALUE] )");

define('BUTTON_BUY', "BUY ITEM [VALUE]$");
define('BUTTON_CONTACT', "Ask");
define('BUTTON_ORDER', "Order");
define('CALC_ITEM_RESULT','Estimated price without shipping cost');
define('DECORATION_DESCRIPTION', 'If you are not sure what rhinestones and how many are needed, in the order form you can simply indicate the sum for decoration.');
define('BUTTON_CALC','Price');
define('QUERY_CALC_ITEM', "Costing model <b class='blue'>[VALUE]</b>");
define('BUTTON_CLEAR', 'Clear');
define('ORDER_MESSAGE_TITLE','I am interested of item [VALUE] from category [CAT]');
define('BUY_MESSAGE_TITLE','I want BUY an item [VALUE] from category [CAT]');
define('START_ENTER', 'start typing');
define('LOCAL_DATE_TODAY' , 'today');
define('LOCAL_DATE_YESTERDAY' , 'yesterday');


// Письма
define('USER_ORDER_ACCEPT_TITLE', 'Your order was successfuly accepted');
define('USER_CONTACT_ACCEPT_TITLE', 'Your message was successfuly sent');
define('USER_BUY_ACCEPT_TITLE', 'Your shop request was accepted');
define('USER_MEASUREMENT_ACCEPT_TITLE', 'Your measurements was sent');
define('MEASUREMENT_TITLE_DATA', "Data for order <b class='yellow'>№ [ORDER]</b>, received from <b class='yellow'>[EMAIL]</b>");
define('MEASUREMENT_ERROR', "<b class='red'>ERROR!</b> Incorrect link for order measurements");
define('USER_AGREEMENT',"Thank you for your order and information.
Please check your details again. In case you find an error, please write to our email ateliecolibri@gmail.com.
The details of your order will be sent for review and processing.
We will process your order within 24 hours.
You will be sent an invoice with payment details and a link to the measurement table (we inform you that this may take a little longer on weekends).
IF YOU HAVE NOT RECEIVED AN INVOICE WITHIN 24 HOURS, PLEASE CONTACT US.");

define('MOBILE_MENU', 'MENU');
define('SOCIAL_NETWORKS' , 'Social Networks');
define('VARIANT', 'Option');

define('THANKYOU','Thank You');

define('NEWFIRST_TITLE', "new first");
define('PRICE_UP_TITLE', "ascending price");
define('PRICE_DOWN_TITLE', "descending price");
define('NEED_CORRECT', 'need correct');

define ('PIN_DESC', "<center> Dear user, <b style = 'color: blue'> [USER] </b> </center>" .BR.BR.
"<center> <div> To enter the system, you must enter the generated PIN-code by following the <a href='//atelier-colibri.com/en/register/pin/'> link </a>. This code valid for 5 minutes after sending the message <div> <h1> [PIN] </h1> </center> ");

define ('NODE_REGISTER', "Cabinet");
define ('ENTER_EMAIL', "Enter E-Mail");
define ('ENTER_PAGE_TITLE', "Are you our client?");
define ('REGISTER_PAGE_TITLE', "Become our client :)");
define ('BUTTON_ENTER', "Login");
define ('BUTTON_REGISTER', "Register");
define ('ALREADY_HAVE', "Already in the database!");
define ('NOT_REGISTERED', "user not found");
define ('NODE_CABINET', "User Cabinet");
define ('NODE_PIN', "PIN Code");
define ('NODE_USERDATA', "User data");
define ('ITEM_WHISHLIST', "Wishlist ♡");
define ('ENTER_PIN', "Enter the PIN code from the letter");
define ('ENTER_PIN_TITLE', "PIN code");
define ('ERROR_PIN', "code not found");
define ('EXPIRED_PIN', "expired code, please try again");
define ('NODE_PIN_DESC', "A letter was sent to the email address you specified, in which the code for entering your personal account was indicated.<b class='red'>Please note that an email with a PIN code for entering your personal account may end up in your spam folder.</b>");
define ('USER_NAME', "Username");
define ('USER_ADDRESS', "Delivery Address");
define ('USER_PHONE', "Phone");
define ('QUERY_EDIT_USER', "Change user details");
define('BUTTON_EDIT', 'Edit');
define('LOG_OUT', 'Exit');
?>