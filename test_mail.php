<?php

$result = mail('ateliecolibri@gmail.com', 'Test', date('d.m.Y H:i:s'), 'From: robot@atelier-colibri.com');

echo date('d.m.Y H.i.s');
var_dump($result);
die;
