<?php

    // F U N C T I O N S
	
	function sanitize($string,$mysqli){
		
		$sanitizedString = $mysqli -> real_escape_string(trim(strip_tags(addslashes($string))));
		return $sanitizedString;

	}

?>