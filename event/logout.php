<?php

use
	Elector\Utils;

session_regenerate_id();
unset($_SESSION["userUID"]);

Utils::head("/");
