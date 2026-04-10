<?php
// Runtime compatibility and logging baseline for modern PHP.

if (!function_exists('skel80_runtime_bool'))
    {
    function skel80_runtime_bool($value, $default = false)
        {
        if ($value === null)
            {
            return $default;
            }
        if (is_bool($value))
            {
            return $value;
            }
        $normalized = strtolower(trim((string)$value));
        if (in_array($normalized, ['1', 'true', 'yes', 'on'], true))
            {
            return true;
            }
        if (in_array($normalized, ['0', 'false', 'no', 'off', ''], true))
            {
            return false;
            }
        return $default;
        }
    }

if (!function_exists('skel80_runtime_default'))
    {
    function skel80_runtime_default($constant, $default)
        {
        return defined($constant) ? constant($constant) : $default;
        }
    }

if (!function_exists('skel80_runtime_log_file'))
    {
    function skel80_runtime_log_file()
        {
        $configured = skel80_runtime_default('CMS_RUNTIME_LOG_FILE', null);
        if (!empty($configured))
            {
            return $configured;
            }

        $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : '';
        if ($documentRoot !== '')
            {
            return $documentRoot.'/logs/php-runtime.log';
            }

        $fallbackRoot = dirname(__DIR__, 2);
        return $fallbackRoot.'/public/logs/php-runtime.log';
        }
    }

if (!function_exists('skel80_runtime_prepare_log_destination'))
    {
    function skel80_runtime_prepare_log_destination($file)
        {
        $dir = dirname($file);
        if (!is_dir($dir))
            {
            @mkdir($dir, 0775, true);
            }
        return $file;
        }
    }

if (!function_exists('skel80_runtime_configure'))
    {
    function skel80_runtime_configure()
        {
        $timezone = skel80_runtime_default('CMS_DEFAULT_TIMEZONE', 'Europe/Kyiv');
        if (!empty($timezone))
            {
            @date_default_timezone_set($timezone);
            }

        $reporting = skel80_runtime_default('CMS_ERROR_REPORTING', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        error_reporting($reporting);
        @ini_set('error_reporting', (string)$reporting);

        $displayErrors = skel80_runtime_bool(skel80_runtime_default('CMS_DISPLAY_ERRORS', false), false);
        @ini_set('display_errors', $displayErrors ? '1' : '0');
        @ini_set('display_startup_errors', $displayErrors ? '1' : '0');

        $logErrors = skel80_runtime_bool(skel80_runtime_default('CMS_LOG_ERRORS', true), true);
        @ini_set('log_errors', $logErrors ? '1' : '0');

        $logFile = skel80_runtime_prepare_log_destination(skel80_runtime_log_file());
        @ini_set('error_log', $logFile);

        return $logFile;
        }
    }

if (!function_exists('skel80_runtime_error_label'))
    {
    function skel80_runtime_error_label($severity)
        {
        $map = [
            E_ERROR => 'E_ERROR',
            E_WARNING => 'E_WARNING',
            E_PARSE => 'E_PARSE',
            E_NOTICE => 'E_NOTICE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_CORE_WARNING => 'E_CORE_WARNING',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING => 'E_COMPILE_WARNING',
            E_USER_ERROR => 'E_USER_ERROR',
            E_USER_WARNING => 'E_USER_WARNING',
            E_USER_NOTICE => 'E_USER_NOTICE',
            E_STRICT => 'E_STRICT',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED => 'E_DEPRECATED',
            E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        ];

        return isset($map[$severity]) ? $map[$severity] : 'E_UNKNOWN';
        }
    }

if (!function_exists('skel80_runtime_register_handlers'))
    {
    function skel80_runtime_register_handlers()
        {
        set_error_handler(function ($severity, $message, $file, $line)
            {
            if (!(error_reporting() & $severity))
                {
                return false;
                }

            if (in_array($severity, [E_DEPRECATED, E_USER_DEPRECATED, E_NOTICE, E_USER_NOTICE, E_STRICT], true))
                {
                error_log('[SKEL80]['.skel80_runtime_error_label($severity).'] '.$message.' in '.$file.' on line '.$line);
                return true;
                }

            return false;
            });

        register_shutdown_function(function ()
            {
            $error = error_get_last();
            if (!$error)
                {
                return;
                }

            if (in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true))
                {
                error_log('[SKEL80][FATAL] '.$error['message'].' in '.$error['file'].' on line '.$error['line']);
                }
            });
        }
    }
?>
