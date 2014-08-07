derp-php-sdk
============

PHP SDK for the Derp API

```
<?php

// You can get your Facebook Access Token at http://derpapp.co/api
$facebookAccessToken = "{FACEBOOK_ACCESS_TOKEN}";

// Initialize the Derp SDK
include("derp.inc.php");
$_DERP = new DERP($facebookAccessToken);

// Get your Account SID
$accountInfo = $_DERP->getAccountInfo();
$accountSID = $accountInfo["sid"];

// Send the "Derp" sound to yourself
$soundSID = "db85f3d8-c019-4a3e-9ae0-3747d92f0919";
$response = $_DERP->sendSound($accountSID, $soundSID);

// Print out the response
print "<PRE>".print_r($response, true)."</PRE>";

?>
```
