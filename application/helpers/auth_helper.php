<?php

function is_logged_in($data)
{
	$result = false;
	if(array_key_exists("username", $data) && array_key_exists("role", $data) && array_key_exists("user_id", $data)){
			$result = true;
	}
	return $result;

}