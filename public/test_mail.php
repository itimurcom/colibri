<?php

$result = mail('ateliecolibri@gmail.com', 'Test', date('d.m.Y H:i:s'), 'From: robot@'.CMS_CURRENT_EMAIL_DOMAIN);

echo date('d.m.Y H.i.s');
var_dump($result);
die;
