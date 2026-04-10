<?php

return [
	'project' => [
		'name' => 'Colibri',
		'type' => 'project overlay',
		'model' => 'project-specific overlay on top of shared SKEL80 runtime',
	],
	'responsibilities' => [
		'configure project constants and ini precedence',
		'register project engine parts under public/engine/core',
		'register project classes and events under public/engine/core',
		'provide late project customs through kernel.customs.php and custom.*.php',
		'define delivery assets and project-level presentation hooks',
	],
	'extension_points' => [
		['path' => 'public/engine/kernel.path.php', 'phase' => 'bootstrap.paths', 'purpose' => 'Project path overrides before class/event discovery.'],
		['path' => 'public/engine/ini/const.*.php', 'phase' => 'bootstrap.const', 'purpose' => 'Project pre-constants before shared defaults are finalized.'],
		['path' => 'public/engine/ini/ini.*.php', 'phase' => 'bootstrap.ini', 'purpose' => 'Project runtime and configuration overrides.'],
		['path' => 'public/engine/core/engine_*.php', 'phase' => 'bootstrap.engine', 'purpose' => 'Project engine registration and cross-module orchestration.'],
		['path' => 'public/engine/core/events/**/*.func.php', 'phase' => 'bootstrap.functions', 'purpose' => 'Project function/event extensions loaded through event discovery.'],
		['path' => 'public/engine/ini/custom.*.php', 'phase' => 'bootstrap.customs', 'purpose' => 'Late customizations after router and user context are ready.'],
		['path' => 'public/engine/kernel.customs.php', 'phase' => 'bootstrap.customs', 'purpose' => 'Legacy late customization hook kept as a legal overlay point.'],
	],
	'legal_override_points' => [
		'public/config.php',
		'public/config.local.php',
		'public/config.secrets.php',
		'public/config.secrets.local.php',
		'public/engine/*',
		'public/mvc/*',
		'public/themes/*',
		'public/languages/*',
	],
	'forbidden_project_locations' => [
		'SKEL80/kernel/* for project-specific business rules',
		'SKEL80/classes/* for project-only delivery behavior',
		'SKEL80/events/* for project-only theme logic',
	],
	'mixed_hotspots' => [
		['path' => 'public/ed_field.php', 'reason' => 'Legacy editor endpoint crosses overlay, auth, forms and content mutation.'],
		['path' => 'public/more.php', 'reason' => 'Legacy feed endpoint crosses transport, block assembly and HTML rendering.'],
		['path' => 'public/mvc/controllers/', 'reason' => 'Historical preprocessors still own request decisions plus some delivery branching.'],
		['path' => 'public/mvc/views/', 'reason' => 'Historical responders still mix data preparation with markup.'],
	],
];
