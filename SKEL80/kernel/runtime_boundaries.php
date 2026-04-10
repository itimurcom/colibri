<?php

if (!function_exists('skel80_runtime_build_boundaries'))
	{
	function skel80_runtime_build_boundaries()
		{
		return [
			'zones' => [
				'shared.core' => [
					'label' => 'Shared platform core',
					'roots' => [
						'SKEL80/',
					],
					'alias' => [
						'platform.kernel',
						'platform.shared_core',
					],
					'responsibility' => [
						'bootstrap lifecycle',
						'class discovery and autoload',
						'events/functions registration',
						'cross-project primitives',
					],
					'allowed_dependencies' => [
						'shared.core',
					],
				],
				'project.bootstrap' => [
					'label' => 'Project bootstrap surface',
					'roots' => [
						'public/index.php',
						'public/config.php',
						'public/config.local.php',
						'public/config.secrets.php',
						'public/config.secrets.local.php',
					],
					'alias' => [
						'project.bootstrap',
						'project.entrypoints',
					],
					'responsibility' => [
						'project entrypoint',
						'config layering',
						'environment-specific overrides',
					],
					'allowed_dependencies' => [
						'shared.core',
						'project.overlay',
					],
				],
				'project.overlay' => [
					'label' => 'Project overlay',
					'roots' => [
						'public/engine/',
					],
					'alias' => [
						'project.runtime_overlay',
						'project.wiring_layer',
					],
					'responsibility' => [
						'project constants and ini',
						'engine wiring',
						'project events and classes',
						'legal platform overrides',
					],
					'allowed_dependencies' => [
						'shared.core',
						'project.overlay',
					],
				],
				'project.delivery' => [
					'label' => 'Project delivery surface',
					'roots' => [
						'public/mvc/',
						'public/themes/',
						'public/languages/',
					],
					'alias' => [
						'project.presentation',
						'project.delivery',
					],
					'responsibility' => [
						'request preprocessors',
						'page assembly responders',
						'theme rendering',
						'localization assets',
					],
					'allowed_dependencies' => [
						'shared.core',
						'project.overlay',
						'project.delivery',
					],
				],
				'mixed.hotspot' => [
					'label' => 'Legacy mixed hotspot',
					'roots' => [],
					'alias' => [
						'transitional.legacy_mixed_zone',
					],
					'responsibility' => [
						'transitional compatibility zone',
						'legacy cross-boundary behavior pending extraction',
					],
					'allowed_dependencies' => [
						'shared.core',
						'project.bootstrap',
						'project.overlay',
						'project.delivery',
						'mixed.hotspot',
					],
				],
			],
			'path_map' => [
				'SKEL80/' => 'shared.core',
				'public/index.php' => 'project.bootstrap',
				'public/config.php' => 'project.bootstrap',
				'public/config.local.php' => 'project.bootstrap',
				'public/config.secrets.php' => 'project.bootstrap',
				'public/config.secrets.local.php' => 'project.bootstrap',
				'public/engine/' => 'project.overlay',
				'public/mvc/' => 'project.delivery',
				'public/themes/' => 'project.delivery',
				'public/languages/' => 'project.delivery',
				'public/ed_field.php' => 'mixed.hotspot',
				'public/more.php' => 'mixed.hotspot',
				'SKEL80/kernel/engine_functions.php' => 'mixed.hotspot',
			],
			'mixed_hotspots' => [
				[
					'path' => 'SKEL80/kernel/engine_functions.php',
					'why' => 'Shared helper surface also contains project-facing formatting, rendering and transport helpers.',
				],
				[
					'path' => 'public/ed_field.php',
					'why' => 'Legacy endpoint mixes editor transport, form handling, auth assumptions and business operations.',
				],
				[
					'path' => 'public/more.php',
					'why' => 'Feed transport endpoint mixes pagination, SQL-backed block assembly and HTML response generation.',
				],
				[
					'path' => 'public/mvc/controllers/',
					'why' => 'Historical controllers behave as preprocessors and may contain delivery decisions.',
				],
				[
					'path' => 'public/mvc/views/',
					'why' => 'Historical views also prepare data and perform inline assembly beyond pure rendering.',
				],
				[
					'path' => 'public/engine/kernel.customs.php',
					'why' => 'Late customization hook can reach across overlay, delivery and runtime state.',
				],
			],
			'dependency_rules' => [
				'shared.core must not depend on project.bootstrap, project.overlay or project.delivery',
				'project.bootstrap may configure shared.core and project.overlay but should not contain business delivery logic',
				'project.overlay may extend shared.core through legal hooks but should not mutate delivery templates directly',
				'project.delivery may depend on shared.core and project.overlay but should not redefine platform bootstrap rules',
				'mixed.hotspot is transitional and should be reduced over time, not expanded',
			],
		];
		}
	}

if (!function_exists('skel80_runtime_get_boundaries'))
	{
	function skel80_runtime_get_boundaries()
		{
		if (!isset($GLOBALS['SKEL80_RUNTIME_BOUNDARIES']) || !is_array($GLOBALS['SKEL80_RUNTIME_BOUNDARIES']))
			{
			$GLOBALS['SKEL80_RUNTIME_BOUNDARIES'] = skel80_runtime_build_boundaries();
			}
		return $GLOBALS['SKEL80_RUNTIME_BOUNDARIES'];
		}
	}

if (!function_exists('skel80_runtime_normalize_path'))
	{
	function skel80_runtime_normalize_path($path)
		{
		$path = str_replace('\\', '/', (string)$path);
		$path = preg_replace('~/{2,}~', '/', $path);

		$roots = [];
		if (defined('SKELETON_CORE_PATH'))
			{
			$roots[] = ['prefix' => rtrim(str_replace('\\', '/', SKELETON_CORE_PATH), '/').'/', 'replace' => 'SKEL80/'];
			}
		if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'])
			{
			$roots[] = ['prefix' => rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/').'/', 'replace' => 'public/'];
			}
		$project_root = dirname(__DIR__, 2);
		$roots[] = ['prefix' => rtrim(str_replace('\\', '/', $project_root), '/').'/', 'replace' => ''];

		foreach ($roots as $root)
			{
			if (strpos($path, $root['prefix']) === 0)
				{
				$path = $root['replace'].substr($path, strlen($root['prefix']));
				break;
				}
			}

		return ltrim($path, '/');
		}
	}

if (!function_exists('skel80_runtime_path_owner'))
	{
	function skel80_runtime_path_owner($path)
		{
		$relative = skel80_runtime_normalize_path($path);
		$boundaries = skel80_runtime_get_boundaries();

		foreach ($boundaries['path_map'] as $prefix => $owner)
			{
			if ($relative === $prefix)
				{
				return $owner;
				}
			if (substr($prefix, -1) === '/' && strpos($relative, $prefix) === 0)
				{
				return $owner;
				}
			}

		return 'mixed.hotspot';
		}
	}

if (!function_exists('skel80_runtime_path_alias'))
	{
	function skel80_runtime_path_alias($path)
		{
		$owner = skel80_runtime_path_owner($path);
		$boundaries = skel80_runtime_get_boundaries();
		return ready_val($boundaries['zones'][$owner]['alias'], []);
		}
	}

if (!function_exists('skel80_runtime_get_mixed_hotspots'))
	{
	function skel80_runtime_get_mixed_hotspots()
		{
		$boundaries = skel80_runtime_get_boundaries();
		return ready_val($boundaries['mixed_hotspots'], []);
		}
	}
