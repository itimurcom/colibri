<?php
// Declarative Colibri overlay contract. This file does not alter runtime
// behavior; it makes the project overlay explicit for documentation, tooling
// and future migration steps.

if (!function_exists('skel80_project_overlay_manifest'))
	{
	function skel80_project_overlay_manifest()
		{
		static $manifest = NULL;
		if ($manifest !== NULL)
			{
			return $manifest;
			}

		$manifest = [
			'project' => [
				'id' => 'colibri',
				'entry_point' => 'public/index.php',
				'config' => 'public/config.php',
				'overlay_root' => 'public/engine',
			],
			'responsibilities' => [
				'bootstrap' => [
					'public/engine/kernel.php',
					'public/engine/kernel.customs.php',
					'public/engine/core/engine_*.php',
				],
				'project_ini' => [
					'public/engine/ini/const.*.php',
					'public/engine/ini/ini.*.php',
					'public/engine/ini/custom.*.php',
				],
				'project_hooks' => [
					'public/engine/core/events/**/*.func.php',
				],
				'project_assets' => [
					'public/engine/js/*',
					'public/themes/default/*',
				],
				'delivery' => [
					'public/mvc/controllers/*',
					'public/mvc/views/*',
					'public/themes/*',
					'public/languages/*',
				],
			],
			'extension_points' => [
				'config.resolve' => 'Project base config and runtime environment defaults.',
				'paths.user' => 'Optional path overrides before user/core defaults are finalized.',
				'engine.register' => 'Project engine bootstrap and feature wiring.',
				'const.user.pre' => 'Project pre-constants before shared post-constants.',
				'ini.user.post' => 'Project ini overrides after shared defaults.',
				'events.user' => 'Project-first hooks and feature functions.',
				'custom.user.post' => 'Project customization after router/user/language are available.',
				'kernel.postrun.overlay' => 'Final project glue before site compilation.',
			],
			'modern_aliases' => [
				'public/mvc/controllers' => 'request handlers / preprocessors / actions',
				'public/mvc/views' => 'responders / page assemblers',
				'public/themes' => 'presentation skin',
				'public/languages' => 'localization resources',
			],
			'boundary_rules' => [
				'overlay_may_depend_on_core' => true,
				'core_may_depend_on_overlay_only_via_declared_extension_points' => true,
				'delivery_may_depend_on_core_and_overlay' => true,
			],
		];

		return $manifest;
		}
	}
