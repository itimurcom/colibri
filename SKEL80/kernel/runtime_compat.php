<?php
// Runtime compatibility helpers for the shared SKEL80 kernel.
// Keeps the historical shared-core + project-overlay model intact while
// giving the runtime explicit PHP 8+ baseline controls.

if (!function_exists('skel80_runtime_env'))
	{
	function skel80_runtime_env($name, $default = NULL)
		{
		$value = getenv($name);
		return ($value !== false) ? $value : $default;
		}
	}

if (!function_exists('skel80_runtime_bool'))
	{
	function skel80_runtime_bool($value, $default = false)
		{
		if ($value === NULL || $value === '')
			{
			return (bool) $default;
			}

		if (is_bool($value))
			{
			return $value;
			}

		$value = strtolower(trim((string) $value));
		if (in_array($value, ['1', 'true', 'yes', 'on'], true))
			{
			return true;
			}

		if (in_array($value, ['0', 'false', 'no', 'off'], true))
			{
			return false;
			}

		return (bool) $default;
		}
	}

if (!function_exists('skel80_runtime_log_file'))
	{
	function skel80_runtime_log_file()
		{
		$default_log_file = dirname(dirname(__DIR__)).'/public/logs/php-runtime.log';
		$log_file = defined('CMS_RUNTIME_LOG_FILE')
			? CMS_RUNTIME_LOG_FILE
			: skel80_runtime_env('CMS_RUNTIME_LOG_FILE', $default_log_file);

		$log_dir = dirname($log_file);
		if (!is_dir($log_dir))
			{
			@mkdir($log_dir, 0775, true);
			}

		return $log_file;
		}
	}

if (!function_exists('skel80_runtime_log'))
	{
	function skel80_runtime_log($message, $context = [])
		{
		$line = '[SKEL80] '.$message;
		if (!empty($context))
			{
			$line .= ' '.json_encode($context, defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES : 0);
			}

		error_log($line);
		}
	}

if (!function_exists('skel80_runtime_apply_bootstrap_settings'))
	{
	function skel80_runtime_apply_bootstrap_settings()
		{
		$runtime_env = defined('CMS_RUNTIME_ENV')
			? CMS_RUNTIME_ENV
			: skel80_runtime_env('CMS_RUNTIME_ENV', 'dev');
		$display_errors = defined('CMS_DISPLAY_ERRORS')
			? CMS_DISPLAY_ERRORS
			: (($runtime_env === 'prod') ? 0 : 1);
		$log_errors = defined('CMS_LOG_ERRORS') ? CMS_LOG_ERRORS : 1;
		$error_reporting = defined('CMS_ERROR_REPORTING') ? CMS_ERROR_REPORTING : E_ALL;
		$timezone = defined('CMS_DEFAULT_TIMEZONE')
			? CMS_DEFAULT_TIMEZONE
			: skel80_runtime_env('CMS_DEFAULT_TIMEZONE', NULL);

		error_reporting((int) $error_reporting);
		@ini_set('display_errors', skel80_runtime_bool($display_errors) ? '1' : '0');
		@ini_set('log_errors', skel80_runtime_bool($log_errors, true) ? '1' : '0');
		@ini_set('error_log', skel80_runtime_log_file());

		if (!empty($timezone) && function_exists('date_default_timezone_set'))
			{
			@date_default_timezone_set($timezone);
			}
		}
	}

if (!function_exists('skel80_runtime_install_error_handlers'))
	{
	function skel80_runtime_install_error_handlers()
		{
		static $installed = false;
		if ($installed)
			{
			return;
			}
		$installed = true;

		set_error_handler(function ($severity, $message, $file, $line)
			{
			if (!(error_reporting() & $severity))
				{
				return false;
				}

			$kind = in_array($severity, [E_DEPRECATED, E_USER_DEPRECATED], true)
				? 'deprecation'
				: 'php';

			skel80_runtime_log($kind, [
				'severity' => $severity,
				'message' => $message,
				'file' => $file,
				'line' => $line,
			]);

			return false;
			});

		set_exception_handler(function ($exception)
			{
			skel80_runtime_log('uncaught_exception', [
				'type' => is_object($exception) ? get_class($exception) : 'Exception',
				'message' => is_object($exception) ? $exception->getMessage() : (string) $exception,
				'file' => is_object($exception) ? $exception->getFile() : NULL,
				'line' => is_object($exception) ? $exception->getLine() : NULL,
			]);
			});

		register_shutdown_function(function ()
			{
			$error = error_get_last();
			if ($error === NULL)
				{
				return;
				}

			if (!in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR], true))
				{
				return;
				}

			skel80_runtime_log('fatal_shutdown', $error);
			});
		}
	}
