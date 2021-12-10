<?php

namespace App\Core;

use Exception;

class ExceptionsHandler extends Exception
{
    public function register(): void
    {
        // Registering custom callback functions for exception and error handlers
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'errorHandler']);
        register_shutdown_function([$this, 'fatalErrorHandler']);
    }

    public static function getErrorName($errno): string
    {
        // Error types according to their codes
        $errors = [
            0 => 'Unknown_exception', // For unspecified error codes
            1 => 'E_ERROR',
            2 => 'E_WARNING',
            4 => 'E_PARSE',
            8 => 'E_NOTICE',
            16 => 'E_CORE_ERROR',
            32 => 'E_CORE_WARNING',
            64 => 'E_COMPILE_ERROR',
            128 => 'E_COMPILE_WARNING',
            256 => 'E_USER_ERROR',
            512 => 'E_USER_WARNING',
            1024 => 'E_USER_NOTICE',
            2048 => 'E_STRICT',
            4096 => 'E_RECOVERABLE_ERROR',
            8192 => 'E_DEPRECATED',
            16384 => 'E_USER_DEPRECATED',
        ];
        if (array_key_exists($errno, $errors)) {
            return $errors[$errno] . " [$errno]";
        }
        return $errno;
    }

    public function exceptionHandler($e): void
    {
        // Logging errors
        file_put_contents(
            LOG_FILE_PATH,
            LOG_DATE . " " .
                ExceptionsHandler::getErrorName($e->getCode()) . ":  " .
                $e->getMessage() . " " .
                $e->getFile() . " on line " .
                $e->getLine() . "\nStack trace:\n" . $e->getTraceAsString() . "\n",
            FILE_APPEND
        );
        // Displaying information about the exception to the browser if enabled in the settings
        if (SHOW_ERRORS == 1) {
            ExceptionsHandler::showError(
                $e->getCode(),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine() . ' ' . $e->getTraceAsString(),
                400
            );
        }
    }

    public function errorHandler($errno, $errstr, $errfile, $errline): bool
    {
        // Logging errors
        file_put_contents(
            LOG_FILE_PATH,
            LOG_DATE . " " .
                ExceptionsHandler::getErrorName($errno) . " in " .
                $errfile . " on line " .
                $errline . "\n",
            FILE_APPEND
        );
        // Displaying an error in the browser, if enabled in the settings
        if (SHOW_ERRORS == 1) {
            ExceptionsHandler::showError($errno, $errstr, $errfile, $errline, 500);
        }
        // Return true so that error handling will NOT be passed to the inline handler.
        return true;
    }

    public function fatalErrorHandler(): void
    {
        // If a fatal error is found in the buffer,
        if (($error = error_get_last()) && ($error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR))) {
            // clean the buffer, shutdown the buffer
            ob_end_clean();
            // Logging errors
            file_put_contents(
                LOG_FILE_PATH,
                LOG_DATE . " " .
                    ExceptionsHandler::getErrorName($error['type']) . " Fatal error: " .
                    $error['message'] . " in " .
                    $error['file'] . " on line " .
                    $error['line'] . "\n",
                FILE_APPEND
            );
            // Displaying an error in the browser, if enabled in the settings
            if (SHOW_ERRORS == 1) {
                ExceptionsHandler::showError(
                    $error['type'],
                    '<b>Fatal error:</b> ' . $error['message'],
                    $error['file'],
                    $error['line'],
                    500
                );
            }
        }
    }

    public static function showError($errno, $errstr, $errfile, $errline, $status = 500): void
    {
        // Setting the response header with the appropriate status
        header("HTTP/1.1 {$status}");
        // Arbitrary formatting of the error or exception information output and sending the content to the browser.
        // Also, we can pass this data to the error page controller
        echo '
            <hr><b>Error type and number:</b> ' .
            ExceptionsHandler::getErrorName($errno) .
            '<hr><b>Message:</b> ' .
            $errstr . '<hr><b>File:</b> ' .
            $errfile . '<hr><b>Line:</b> ' .
            $errline . '<hr><b>Status:</b> ' .
            $status . '<hr><br>
        ';
    }
}
