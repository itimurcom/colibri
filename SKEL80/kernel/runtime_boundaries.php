<?php
// Explicit shared-core / project-overlay boundary map for the historical
// SKEL80 platform model. This file stays declarative and side-effect free so
// it can be reused by docs, tooling and later runtime checks.

if (!function_exists('skel80_runtime_project_root'))
	{
	function skel80_runtime_project_root()
		{
		return dirname(__DIR__, 2);
		}
	}

if (!function_exists('skel80_runtime_normalize_path'))
	{
	function skel80_runtime_normalize_path($path)
		{
		$path = str_replace('\\', '/', (string) $path);
		$path = preg_replace('~/+~', '/', $path);

		$project_root = str_replace('\\', '/', skel80_runtime_project_root());
		if (strpos($path, $project_root.'/') === 0)
			{
			$path = substr($path, strlen($project_root) + 1);
			}

		return ltrim($path, '/');
		}
	}

if (!function_exists('skel80_runtime_overlay_contract_file'))
	{
	function skel80_runtime_overlay_contract_file()
		{
		$project_root = skel80_runtime_project_root();
		$candidates = [
			$project_root.'/public/engine/overlay_contract.php',
			$project_root.'/engine/overlay_contract.php',
		];

		foreach ($candidates as $candidate)
			{
			if (is_file($candidate))
				{
				return $candidate;
				}
			}

		return NULL;
		}
	}

if (!function_exists('skel80_runtime_overlay_manifest'))
	{
	function skel80_runtime_overlay_manifest()
		{
		static $manifest = NULL;
		if ($manifest !== NULL)
			{
			return $manifest;
			}

		$manifest = [];
		$contract_file = skel80_runtime_overlay_contract_file();
		if ($contract_file !== NULL)
			{
			include_once $contract_file;
			if (function_exists('skel80_project_overlay_manifest'))
				{
				$loaded = skel80_project_overlay_manifest();
				if (is_array($loaded))
					{
					$manifest = $loaded;
					}
				}
			}

		return $manifest;
		}
	}

if (!function_exists('skel80_runtime_boundaries'))
	{
	function skel80_runtime_boundaries()
		{
		static $boundaries = NULL;
		if ($boundaries !== NULL)
			{
			return $boundaries;
			}

		$boundaries = [
			'principles' => [
				'shared_core' => 'SKEL80 may own runtime primitives, shared classes, shared events, shared assets and shared defaults.',
				'project_overlay' => 'The project overlay may define config, path overrides, engine bootstrap, project functions, project ini/customs and final post-run glue.',
				'delivery_surface' => 'The deployable public surface may depend on both shared core and overlay, but it is not part of the shared kernel contract.',
				'dependency_rule' => 'Shared core must not take direct hard dependencies on project delivery files; project code may depend on core through declared extension points.',
			],
			'owners' => [
				'shared.core' => [
					'paths' => [
						'SKEL80/kernel',
						'SKEL80/classes',
						'SKEL80/events',
						'SKEL80/css',
						'SKEL80/js',
						'SKEL80/sql',
						'SKEL80/ver',
					],
					'role' => 'Platform kernel, shared lifecycle, shared class/event library and shared resources reused across projects.',
				],
				'project.overlay' => [
					'paths' => [
						'public/config.php',
						'public/config.secrets.example.php',
						'public/engine',
						'public/logs',
					],
					'role' => 'Project-specific bootstrap, config precedence, feature wiring, project functions, project ini/customs and post-run overlay.',
				],
				'project.delivery' => [
					'paths' => [
						'public/index.php',
						'public/mvc',
						'public/themes',
						'public/languages',
						'public/login.php',
						'public/logout.php',
						'public/404.php',
						'public/more.php',
					],
					'role' => 'Deployable request entry points, responders, presentation skin and localization resources of the project.',
				],
				'mixed.hotspot' => [
					'paths' => [
						'SKEL80/run.php',
						'SKEL80/kernel/core.php',
						'SKEL80/kernel/runtime_contract.php',
						'public/engine/kernel.php',
						'public/config.php',
					],
					'role' => 'Historical glue where shared kernel and project overlay meet and where future decoupling should stay explicit and controlled.',
				],
			],
			'modern_aliases' => [
				'public/mvc/controllers' => 'request handlers / preprocessors / actions',
				'public/mvc/views' => 'responders / page assemblers',
				'public/themes' => 'presentation skin',
				'public/languages' => 'localization resources',
				'public/engine/core/engine_*.php' => 'project bootstrap modules',
				'public/engine/core/events/**/*.func.php' => 'project hooks and feature functions',
			],
			'extension_points' => [
				'config.resolve' => 'public/config.php',
				'paths.user' => 'public/engine/kernel.path.php',
				'engine.register' => 'public/engine/core/engine_*.php',
				'const.user.pre' => 'public/engine/ini/const.*.php',
				'ini.user.post' => 'public/engine/ini/ini.*.php',
				'events.user' => 'public/engine/core/events/**/*.func.php',
				'custom.user.post' => 'public/engine/ini/custom.*.php + public/engine/kernel.customs.php',
				'kernel.postrun.overlay' => 'public/engine/kernel.php',
			],
			'mixed_hotspots' => [
				'SKEL80/run.php' => 'Shared phase runner with controlled calls into project extension points.',
				'SKEL80/kernel/core.php' => 'Historical discovery primitives and include-time conventions used by both core and project layers.',
				'public/config.php' => 'Project config resolved early by the shared kernel.',
				'public/engine/kernel.php' => 'Final project glue after the shared runner hands control back to the project.',
			],
			'overlay_manifest' => skel80_runtime_overlay_manifest(),
		];

		return $boundaries;
		}
	}

if (!function_exists('skel80_runtime_path_owner'))
	{
	function skel80_runtime_path_owner($path)
		{
		$path = skel80_runtime_normalize_path($path);
		$boundaries = skel80_runtime_boundaries();

		foreach ($boundaries['owners'] as $owner => $definition)
			{
			foreach ($definition['paths'] as $prefix)
				{
				$prefix = rtrim($prefix, '/');
				if ($path === $prefix || strpos($path, $prefix.'/') === 0)
					{
					return $owner;
					}
				}
			}

		return 'unknown';
		}
	}
