<?

########################################################################################################
#
# Name:    TP110-Addon
# Version: 0.1 (26.08.2017)
# Autor:   Jens Gericke (IPS Foren AccountName: Nisbo
# License: GNU General Public License v3.0
#
# Based on 
# the "TPLink HS100/HS110 WiFi Smart Plug API" by RobertShippey (GNU General Public License v3.0)
# - https://github.com/RobertShippey/hs100-php-api
#
# and the information from the Reverse Engineering the TP-Link HS110 
# by Lubomir Stroetmann, Consultant and Tobias Esser, Consultant
# - https://www.softscheck.com/en/reverse-engineering-tp-link-hs110/
#
########################################################################################################

// Config
$host  = "192.168.178.77";	// The adress of the HS110 device

$varV  = 57949;				// ObjektID for Volatage --> Type: Float
$varA  = 22493;				// ObjektID for Current  --> Type: Float
$varP  = 22956;				// ObjektID for Power    --> Type: Float
$varS  = 12405;				// ObjektID for Switch   --> Type: Boolean

$debug = false;				// true = debugging is enabled / false = debugging is disabled


// don't change anything below this line
if($debug) IPS_LogMessage("TestScript","Start");

function decrypt($cypher_text, $first_key = 0xAB){
	$header        = substr($cypher_text, 0, 4);
	$header_length = unpack('N*', $header)[1];
	$cypher_text   = substr($cypher_text, 4);
	$buf           = unpack('c*', $cypher_text );
	$key           = $first_key;
	$nextKey;
	for ($i = 1; $i < count($buf)+1; $i++) {
		$nextKey = $buf[$i];
		$buf[$i] = $buf[$i] ^ $key;
		$key     = $nextKey;
	}
	$array_map     = array_map('chr', $buf);
	$clear_text    = implode('', $array_map);
	$cypher_length = strlen($clear_text);

	if ($header_length !== $cypher_length) {
		trigger_error("Length in header ({$header_length}) doesn't match actual message length ({$cypher_length}).");
	}

	return $clear_text;
}

function encrypt ( $clear_text , $first_key = 0xAB ) {
	$buf = unpack('c*', $clear_text );
	$key = $first_key;

	for ($i = 1; $i < count($buf)+1; $i++) {
		$buf[$i] = $buf[$i] ^ $key;
		$key = $buf[$i];
	}

	$array_map  = array_map('chr', $buf);
	$clear_text = implode('', $array_map);
	$length     = strlen($clear_text);
	$header     = pack('N*', $length);

	return $header . $clear_text;
}

function connectToSocket(){
	global $debug, $host;

	if(!($sock1 = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))){
		$errorcode = socket_last_error();
		$errormsg  = socket_strerror($errorcode);
		die("Couldn't create socket: [$errorcode] $errormsg \n");
	}
	 
	if($debug) echo "Socket created";
	 
	//Connect socket to remote server
	if(!socket_connect($sock1 , $host ,9999)){
		$errorcode = socket_last_error();
		$errormsg  = socket_strerror($errorcode);
		die("Could not connect: [$errorcode] $errormsg \n");
	}
	 
	if($debug) echo " --> Connection established";
	return $sock1;
}

function sendToSocket($messageToSend, $sock){
	global $debug;

	$message = encrypt($messageToSend);
	 
	//Send the message to the server
	if(!socket_send ($sock , $message , strlen($message) , 0)){
		$errorcode = socket_last_error();
		$errormsg  = socket_strerror($errorcode);
		die("Could not send data: [$errorcode] $errormsg \n");
	}
	 
	if($debug) echo " --> Message send successfully"; 
}

function getResultFromSocket($sock){
	global $debug;

	//Now receive reply from server
	$buf = "";
	if(socket_recv ( $sock , $buf , 2048 , MSG_WAITALL ) === FALSE){
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);
		 
		die("Could not receive data: [$errorcode] $errormsg \n");
	}

	return $buf;
}


#########################################################################################################################################
# Command to Send: {"system":{"get_sysinfo":{"err_code":0,"sw_ver":"1.1.4 Build 170417 
# Expexted answer: Rel.145118","hw_ver":"1.0","type":"IOT.SMARTPLUGSWITCH","model":"HS110(EU)","mac":"70:4F:57:1B:DD:C1","deviceId":"8006E91F9E48110F07356B2A68FBF29018EC5159","hwId":"45E29DA8382494D2E82688B52A0B2EB5","fwId":"851E8C7225C3220531D5A3AFDACD9098","oemId":"3D341ECE302C0642C99E31CE2430544B","alias":"Egon","dev_name":"Wi-Fi Smart Plug With Energy Monitoring","icon_hash":"","relay_state":1,"on_time":22954,"active_mode":"schedule","feature":"TIM:ENE","updating":0,"rssi":-45,"led_off":0,"latitude":48.123456,"longitude":11.123456}}}

$sock   = connectToSocket();
          sendToSocket('{"system":{"get_sysinfo":{}}}', $sock);
$buf    = getResultFromSocket($sock);
$result = json_decode(decrypt($buf));

if($debug) {
	echo " --> Message received successfully - Lenght: " . strlen($buf) . " - decrypt:\n" . decrypt($buf) . "\n";
	print_r((array) json_decode(decrypt($buf)));
	$result->system->get_sysinfo->model . " - ". $result->system->get_sysinfo->alias . " - " . $result->system->get_sysinfo->relay_state . " (1 = on / 0 = 0ff) - Values: ";
}

SetValueBoolean($varS, (bool) $result->system->get_sysinfo->relay_state);
socket_close($sock);


#########################################################################################################################################
# Command to Send: '{"emeter":{"get_realtime":{}}}'
# Expexted answer: {"emeter":{"get_realtime":{"current":0.151818,"voltage":231.747099,"power":20.172881,"total":2.597000,"err_code":0}}}

$sock   = connectToSocket();
          sendToSocket('{"emeter":{"get_realtime":{}}}', $sock);
$buf    = getResultFromSocket($sock);
$result = json_decode(decrypt($buf));

if($debug) {
	echo " --> Message received successfully - Lenght: " . strlen($buf) . " - decrypt:\n" . decrypt($buf) . "\n";; 
	print_r((array) json_decode(decrypt($buf)));
	echo $result->emeter->get_realtime->voltage . "V / " . $result->emeter->get_realtime->current . "A / " . $result->emeter->get_realtime->power . "W";
}

SetValueFloat($varV, $result->emeter->get_realtime->voltage);
SetValueFloat($varA, $result->emeter->get_realtime->current);
SetValueFloat($varP, $result->emeter->get_realtime->power);

socket_close($sock);

if($debug) IPS_LogMessage("TestScript","Fertig");

?>
