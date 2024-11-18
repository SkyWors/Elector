<?php

namespace Elector;

use
	ErrorException,
	Throwable;

class ErrorHandler {

	/**
	 * Catch and store exception
	 *
	 * @param Throwable $exception Exception
	 *
	 * @author Erick Paoletti <erick.paoletti@gmail.com>
	 *
	 * @return void
	 */
	public static function handle(Throwable $exception) {
		$errorFolder = $_SERVER["DOCUMENT_ROOT"] . "/log";
		$logFile = $errorFolder . "/" . date("Y-m-d") . ".log";

		if (!file_exists($errorFolder . "/"))
			mkdir($errorFolder, 0770, true);

		$errorMessage = "[" . date('Y-m-d H:i:s') . "] ";
		$errorMessage .= "Uncaught Exception: " . $exception->getMessage() . "\n";
		$errorMessage .= "File: " . $exception->getFile() . " (Line " . $exception->getLine() . ")\n";
		$errorMessage .= "Stack trace:\n" . $exception->getTraceAsString() . "\n\n";

		file_put_contents($logFile, $errorMessage, FILE_APPEND);

		Utils::head("/error");
	}

	/**
	 * Exception event
	 *
	 * @author Erick Paoletti <erick.paoletti@gmail.com>
	 *
	 * @return void
	 */
	public static function shutdown() {
		$error = error_get_last();
		if ($error != NULL) {
			$exception = new ErrorException(
				$error['message'], 0, $error['type'], $error['file'], $error['line']
			);
			self::handle($exception);
		}
	}
}
