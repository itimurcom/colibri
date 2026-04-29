<?php

require_once __DIR__.'/runtime_boundaries.php';

if (!function_exists('skel80_runtime_build_contract'))
	{
	function skel80_runtime_build_contract()
		{
		return [
			'identity' => [
				'platform' => 'SKEL80 shared kernel',
				'model' => 'shared kernel + project overlay',
			],
			'phases' => [
				'bootstrap.core' => ['owner' => 'shared.core', 'description' => 'Load core primitives, runtime compatibility layer and basic PHP handlers.'],
				'bootstrap.config' => ['owner' => 'project.bootstrap', 'description' => 'Resolve config.php and project-level config overlays before path registration.'],
				'bootstrap.paths' => ['owner' => 'shared.core', 'description' => 'Resolve shared and user paths, then allow optional project path overrides through kernel.path.php when the file exists.'],
				'bootstrap.overlay.contract' => ['owner' => 'project.overlay', 'description' => 'Load the project overlay manifest that declares responsibilities, extension points and hotspots.'],
				'bootstrap.classes' => ['owner' => 'shared.core', 'description' => 'Register class folders and autoload order.'],
				'bootstrap.engine' => ['owner' => 'project.overlay', 'description' => 'Load project engine_*.php parts and project classes/events.'],
				'bootstrap.const' => ['owner' => 'shared.core', 'description' => 'Apply user pre-constants, then shared core constants and defaults.'],
				'bootstrap.ini' => ['owner' => 'project.overlay', 'description' => 'Apply shared ini defaults and then project ini overrides.'],
				'bootstrap.functions' => ['owner' => 'mixed.hotspot', 'description' => 'Load kernel/engine_functions.php and register function hooks from project and core.'],
				'bootstrap.router' => ['owner' => 'shared.core', 'description' => 'Build router and route context for the request.'],
				'bootstrap.common' => ['owner' => 'shared.core', 'description' => 'Load common/lang defaults after routing.'],
				'bootstrap.user' => ['owner' => 'shared.core', 'description' => 'Build user context and session-bound behavior.'],
				'bootstrap.customs' => ['owner' => 'project.overlay', 'description' => 'Load custom.*.php and project late customization hooks.'],
				'delivery.controllers' => ['owner' => 'project.delivery', 'description' => 'Historical preprocessors/controllers prepare request-level data.'],
				'delivery.views' => ['owner' => 'project.delivery', 'description' => 'Historical views act as responders/page assemblers.'],
				'delivery.theme' => ['owner' => 'project.delivery', 'description' => 'Theme assets and wrappers shape final output.'],
			],
			'legal_override_points' => [
				'public/config.php',
				'public/config.local.php',
				'public/config.secrets.php',
				'public/config.secrets.local.php',
				'public/engine/kernel.path.php',
				'public/engine/overlay_contract.php',
				'public/engine/ini/const.*.php',
				'public/engine/ini/ini.*.php',
				'public/engine/ini/custom.*.php',
				'public/engine/core/engine_*.php',
				'public/engine/core/events/**/*.func.php',
				'public/engine/kernel.customs.php',
				'public/mvc/controllers/*.php',
				'public/mvc/views/*.php',
				'public/themes/**',
				'public/languages/**',
			],
			'illegal_override_examples' => [
				'SKEL80/classes/* for project-specific behavior',
				'SKEL80/kernel/* to inject project business rules',
				'public/themes/* to redefine bootstrap constants',
				'public/mvc/* to mutate shared runtime paths',
			],
		];
		}
	}

if (!function_exists('skel80_runtime_get_contract'))
	{
	function skel80_runtime_get_contract()
		{
		if (!isset($GLOBALS['SKEL80_RUNTIME_CONTRACT']) || !is_array($GLOBALS['SKEL80_RUNTIME_CONTRACT']))
			{
			$GLOBALS['SKEL80_RUNTIME_CONTRACT'] = skel80_runtime_build_contract();
			}
		return $GLOBALS['SKEL80_RUNTIME_CONTRACT'];
		}
	}

if (!function_exists('skel80_runtime_register_overlay_contract'))
	{
	function skel80_runtime_register_overlay_contract($contract)
		{
		if (is_array($contract))
			{
			$GLOBALS['SKEL80_PROJECT_OVERLAY_CONTRACT'] = $contract;
			return true;
			}
		return false;
		}
	}

if (!function_exists('skel80_runtime_get_overlay_contract'))
	{
	function skel80_runtime_get_overlay_contract()
		{
		return ready_val($GLOBALS['SKEL80_PROJECT_OVERLAY_CONTRACT'], []);
		}
	}

if (!function_exists('skel80_runtime_load_overlay_contract'))
	{
	function skel80_runtime_load_overlay_contract($engine_path)
		{
		$engine_path = rtrim((string)$engine_path, '/').'/';
		$contract_file = $engine_path.'overlay_contract.php';
		if (is_file($contract_file))
			{
			$contract = include $contract_file;
			return skel80_runtime_register_overlay_contract($contract);
			}
		return false;
		}
	}

if (!function_exists('skel80_runtime_get_extension_points'))
	{
	function skel80_runtime_get_extension_points()
		{
		$overlay = skel80_runtime_get_overlay_contract();
		return ready_val($overlay['extension_points'], []);
		}
	}
