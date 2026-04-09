<?php
// Runtime contract for shared SKEL80 kernel and project overlay.
// This file is intentionally declarative: it formalizes the boot phases,
// overlay points and precedence model without changing the historical runtime.

require_once 'runtime_boundaries.php';

function skel80_runtime_contract()
	{
	static $contract = NULL;
	if ($contract !== NULL)
		{
		return $contract;
		}

	$contract = [
		'platform' => [
			'core' => 'SKEL80',
			'overlay' => 'public/engine',
			'entry_point' => 'public/index.php',
			'phase_runner' => 'SKEL80/run.php',
		],
		'phases' => [
			'core.primitives' => [
				'title' => 'Load core primitives',
				'owner' => 'core',
				'file' => 'SKEL80/kernel/core.php',
			],
			'session.bootstrap' => [
				'title' => 'Start session',
				'owner' => 'core',
				'file' => 'SKEL80/run.php',
			],
			'constants.bootstrap' => [
				'title' => 'Register bootstrap constants',
				'owner' => 'core',
				'file' => 'SKEL80/run.php',
			],
			'config.resolve' => [
				'title' => 'Resolve project config.php',
				'owner' => 'overlay',
				'file' => 'public/config.php',
			],
			'runtime.compat.settings' => [
				'title' => 'Apply runtime compatibility settings',
				'owner' => 'mixed',
				'file' => 'SKEL80/kernel/runtime_compat.php + public/config.php',
			],
			'runtime.compat.handlers' => [
				'title' => 'Install runtime logging and error handlers',
				'owner' => 'core',
				'file' => 'SKEL80/kernel/runtime_compat.php',
			],
			'paths.user' => [
				'title' => 'Resolve user/project paths',
				'owner' => 'mixed',
				'file' => 'SKEL80/run.php + public/engine/kernel.path.php',
			],
			'paths.core' => [
				'title' => 'Resolve core paths',
				'owner' => 'core',
				'file' => 'SKEL80/run.php',
			],
			'paths.runtime_defaults' => [
				'title' => 'Register runtime path defaults',
				'owner' => 'core',
				'file' => 'SKEL80/run.php',
			],
			'classes.register' => [
				'title' => 'Register class discovery and autoload',
				'owner' => 'mixed',
				'file' => 'SKEL80/run.php',
			],
			'engine.register' => [
				'title' => 'Register project engine bootstrap',
				'owner' => 'overlay',
				'file' => 'public/engine/core/engine_*.php',
			],
			'events.core.pre' => [
				'title' => 'Register core system events (pre)',
				'owner' => 'core',
				'file' => 'SKEL80/kernel/events/*',
			],
			'const.user.pre' => [
				'title' => 'Apply project pre-constants',
				'owner' => 'overlay',
				'file' => 'public/engine/ini/const.*.php',
			],
			'const.core.post' => [
				'title' => 'Apply core post-constants',
				'owner' => 'core',
				'file' => 'SKEL80/events/*/ini/const.php',
			],
			'defaults.core' => [
				'title' => 'Apply core fallback defaults',
				'owner' => 'core',
				'file' => 'SKEL80/run.php',
			],
			'ini.core' => [
				'title' => 'Apply core ini defaults',
				'owner' => 'core',
				'file' => 'SKEL80/events/*/ini/ini.php',
			],
			'ini.user.post' => [
				'title' => 'Apply project post-ini overrides',
				'owner' => 'overlay',
				'file' => 'public/engine/ini/ini.*.php',
			],
			'functions.core.compat' => [
				'title' => 'Load legacy compatibility engine functions',
				'owner' => 'core',
				'file' => 'SKEL80/kernel/engine_functions.php',
			],
			'events.user' => [
				'title' => 'Register project event functions',
				'owner' => 'overlay',
				'file' => 'public/engine/core/events/**/*.func.php',
			],
			'events.core.post' => [
				'title' => 'Register core event functions',
				'owner' => 'core',
				'file' => 'SKEL80/events/**/*.func.php',
			],
			'router.bootstrap' => [
				'title' => 'Build router and request model',
				'owner' => 'core',
				'file' => 'itRouter',
			],
			'common.core' => [
				'title' => 'Apply core common/language defaults',
				'owner' => 'core',
				'file' => 'SKEL80/events/*/ini/common.php',
			],
			'user.bootstrap' => [
				'title' => 'Build user context',
				'owner' => 'core',
				'file' => 'itUser',
			],
			'custom.user.post' => [
				'title' => 'Apply project post-router customs',
				'owner' => 'overlay',
				'file' => 'public/engine/ini/custom.*.php + public/engine/kernel.customs.php',
			],
			'prepared_arrays.finalize' => [
				'title' => 'Finalize moderator prepared arrays',
				'owner' => 'mixed',
				'file' => 'prepare_global_arrays()',
			],
			'kernel.postrun.overlay' => [
				'title' => 'Apply project post-run kernel overlay',
				'owner' => 'overlay',
				'file' => 'public/engine/kernel.php',
			],
			'site.compile' => [
				'title' => 'Compile site output',
				'owner' => 'mixed',
				'file' => 'public/index.php + itSite::compile()',
			],
		],
		'overlay_points' => [
			'config' => [
				'file' => 'public/config.php',
				'phase' => 'config.resolve',
				'purpose' => 'Project database, host, theme and base runtime constants.',
			],
			'paths' => [
				'file' => 'public/engine/kernel.path.php',
				'phase' => 'paths.user',
				'purpose' => 'Project path overrides before default user/core paths are finalized.',
			],
			'engine' => [
				'file' => 'public/engine/core/engine_*.php',
				'phase' => 'engine.register',
				'purpose' => 'Project engine bootstrap, feature wiring, boot-time glue.',
			],
			'pre_constants' => [
				'file' => 'public/engine/ini/const.*.php',
				'phase' => 'const.user.pre',
				'purpose' => 'Project constants that must exist before core post-constants.',
			],
			'post_ini' => [
				'file' => 'public/engine/ini/ini.*.php',
				'phase' => 'ini.user.post',
				'purpose' => 'Project ini overrides after core ini defaults.',
			],
			'events' => [
				'file' => 'public/engine/core/events/**/*.func.php',
				'phase' => 'events.user',
				'purpose' => 'Project functions and hooks registered before core events, allowing project-first ownership of duplicate function names.',
			],
			'customs' => [
				'file' => 'public/engine/ini/custom.*.php + public/engine/kernel.customs.php',
				'phase' => 'custom.user.post',
				'purpose' => 'Project post-router and post-user customization layer.',
			],
			'post_run_kernel' => [
				'file' => 'public/engine/kernel.php',
				'phase' => 'kernel.postrun.overlay',
				'purpose' => 'Final project-level overlay after shared run.php completes.',
			],
		],
		'boundaries' => skel80_runtime_boundaries(),
		'precedence' => [
			'config' => [
				'public/config.php',
			],
			'paths' => [
				'public/engine/kernel.path.php',
				'SKEL80 default path discovery',
			],
			'constants' => [
				'public/engine/ini/const.*.php',
				'SKEL80/events/*/ini/const.php',
				'SKEL80/run.php fallback definition([...])',
			],
			'ini' => [
				'SKEL80/events/*/ini/ini.php',
				'public/engine/ini/ini.*.php',
			],
			'functions' => [
				'public/engine/core/events/**/*.func.php',
				'SKEL80/events/**/*.func.php (skipped when function already exists)',
			],
			'custom' => [
				'public/engine/kernel.customs.php',
				'public/engine/ini/custom.*.php',
				'public/engine/kernel.php post-run block',
			],
		],
	];

	return $contract;
	}

function skel80_runtime_enter_phase($phase)
	{
	$contract = skel80_runtime_contract();
	$meta = isset($contract['phases'][$phase]) ? $contract['phases'][$phase] : [
		'title' => $phase,
		'owner' => 'unknown',
		'file' => NULL,
	];

	$GLOBALS['SKEL80_RUNTIME_CONTEXT'] = [
		'phase' => $phase,
		'title' => $meta['title'],
		'owner' => $meta['owner'],
		'file' => $meta['file'],
		'entered_at' => microtime(true),
	];

	return $GLOBALS['SKEL80_RUNTIME_CONTEXT'];
	}

function skel80_runtime_get_phase($default=NULL)
	{
	if (isset($GLOBALS['SKEL80_RUNTIME_CONTEXT']['phase']))
		{
		return $GLOBALS['SKEL80_RUNTIME_CONTEXT']['phase'];
		}

	return $default;
	}
