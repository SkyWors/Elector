<?php

namespace Elector;

class Api {
	public static function returnMessage($message) {
		return json_encode(["answer" => $message]);
	}

	public static function returnData($data) {
		return json_encode($data);
	}
}
