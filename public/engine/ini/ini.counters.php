<?
global $counter_cat;
//------------------------------------------------------------------------------
// массив счетчиков сайта
//------------------------------------------------------------------------------
$counter_cat['GOOLGE'] = [
	'name'	=> 'Google Analytics',
	'show'	=> 0,
	'code'	=> <<<EOF
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-59727697-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-59727697-1');
</script>
EOF
];
?>