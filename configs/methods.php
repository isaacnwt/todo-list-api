<?php
    global $_DELETE;
    $_DELETE = array();
    global $_PUT;
    $_PUT = array();

    // if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
    //     parse_str(file_get_contents('php://input'), $_DELETE);
    // }
    if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
        parse_str(file_get_contents('php://input'), $_PUT);
    }

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	parse_str(file_get_contents("php://input"), $_DELETE);

	foreach ($_DELETE as $key => $value)
	{
		unset($_DELETE[$key]);

		$_DELETE[str_replace('amp;', '', $key)] = $value;
	}

	$_REQUEST = array_merge($_REQUEST, $_DELETE);
}