<PRE>
<?php
###############################################################################
#                                                                             #
#   Copyright 2014 Derp, LLC                                                  #
#                                                                             #
#   Licensed under the Apache License, Version 2.0 (the "License");           #
#   you may not use this file except in compliance with the License.          #
#   You may obtain a copy of the License at                                   #
#                                                                             #
#       http://www.apache.org/licenses/LICENSE-2.0                            #
#                                                                             #
#   Unless required by applicable law or agreed to in writing, software       #
#   distributed under the License is distributed on an "AS IS" BASIS,         #
#   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  #
#   See the License for the specific language governing permissions and       #
#   limitations under the License.                                            #
#                                                                             #
###############################################################################


$facebookAccessToken = ""; // <----- Visit http://derpapp.co/api/ to get your Facebook Access Token


include ("derp.inc.php");
try {
	$_DERP = new DERP($facebookAccessToken);
} catch (Exception $e) {
    printError($e->getMessage());
}


if (strlen($_GET["sound_sid"]) > 0) {
	sendSoundToSelf($_GET["sound_sid"]);
} else {
	$rawResponse = false; 				// Change this to TRUE if you want to see the raw response from the API
	printAccountInfo($rawResponse);
	printFriends($rawResponse);
	printSounds($rawResponse);
}



/* ------------------------------------------------------------------- */
function printAccountInfo($rawResponse) {
	/*
	
	Print out the users Account information
	
	*/
	
	global $_DERP;
	
	try {
		$accountInfo = $_DERP->getAccountInfo();
	} catch (Exception $e) {
    	printError($e->getMessage());
	}
	
	print "<h2>Account Info</h2>";
	
	if ($rawResponse) {
		print_r($accountInfo);
	} else {
		$gender = $accountInfo["identity"]["gender"] == 2 ? "female" : "male";
		print <<<HTML
<TABLE BORDER="1" CELLPADDING="3">
	<TR>
		<TD><B>Photo</B></TD>
		<TD><B>First Name</B></TD>
		<TD><B>Last Name</B></TD>
		<TD><B>Gender</B></TD>
		<TD><B>Account SID</B></TD>
	</TR>
	<TR>
		<TD><A HREF="{$accountInfo["identity"]["photo_url"]}"><IMG SRC="{$accountInfo["identity"]["photo_url"]}" WIDTH="100" HEIGHT="100"></A></TD>
		<TD>{$accountInfo["identity"]["first_name"]}</TD>
		<TD>{$accountInfo["identity"]["last_name"]}</TD>
		<TD>{$gender}</TD>
		<TD>{$accountInfo["sid"]}</TD>
	</TR>
</TABLE>
HTML;
	}
}




/* ------------------------------------------------------------------- */
function printFriends($rawResponse) {
	/*
	
	Print out the users Friends
	
	*/
	
	global $_DERP;
	
	try {
		$friends = $_DERP->getFriends();
	} catch (Exception $e) {
    	printError($e->getMessage());
	}
	
	print "<h2>Friends</h2>";

	if ($rawResponse) {
		print_r($friends);
	} else {
		foreach ($friends["friends"] as $friend) {
			$gender = $friend["identity"]["gender"] == 2 ? "female" : "male";
			$htmlFriends .= <<<HTML
	<TR>
		<TD><A HREF="{$friend["identity"]["photo_url"]}"><IMG SRC="{$friend["identity"]["photo_url"]}" WIDTH="50" HEIGHT="50"></A></TD>
		<TD>{$friend["identity"]["first_name"]}</TD>
		<TD>{$friend["identity"]["last_name"]}</TD>
		<TD>{$gender}</TD>
		<TD>{$friend["account_sid"]}</TD>
	</TR>
HTML;
		}
		
		print <<<HTML
<TABLE BORDER="1" CELLPADDING="3">
	<TR>
		<TD><B>Photo</B></TD>
		<TD><B>First Name</B></TD>
		<TD><B>Last Name</B></TD>
		<TD><B>Gender</B></TD>
		<TD><B>Account SID</B></TD>
	</TR>
	{$htmlFriends}
</TABLE>
HTML;
	}
}




/* ------------------------------------------------------------------- */
function printSounds($rawResponse) {
	/*
	
	Print out all the Sounds
	
	*/
	
	global $_DERP;
	
	try {
		$sounds = $_DERP->getSounds();
	} catch (Exception $e) {
    	printError($e->getMessage());
	}
	
	print "<h2>Sounds</h2>";

	if ($rawResponse) {
		print_r($sounds);
	} else {
		foreach ($sounds["sounds"] as $sound) {
			$encodedSID = urlencode($sound["sid"]);
			$htmlSounds .= <<<HTML
	<TR>
		<TD>{$sound["label"]}</TD>
		<TD>{$sound["sid"]}</TD>
		<TD><A HREF="?sound_sid={$encodedSID}" target="_blank">Send To Self</A></TD>
	</TR>
HTML;
		}
		
		print <<<HTML
<TABLE BORDER="1" CELLPADDING="3">
	<TR>
		<TD><B>Label</B></TD>
		<TD><B>Sound SID</B></TD>
		<TD></TD>
	</TR>
	{$htmlSounds}
</TABLE>
HTML;
	}
}




/* ------------------------------------------------------------------- */
function sendSoundToSelf($soundSID) {
	/*
	
	Send a sound to yourself
	
	*/
	
	// Get your Account SID
	global $_DERP;
	
	try {
		$accountInfo = $_DERP->getAccountInfo();
		print_r($_DERP->sendSound($accountInfo["sid"], $soundSID));
	} catch (Exception $e) {
    	printError($e->getMessage());
	}
}




/* ------------------------------------------------------------------- */
function printError($message) {
	/*

	Print out an error then abort execution
	
	*/
	
	echo "<B>Error</B><BR><BR><I>".$message."</I>";
	exit;
}

?>
