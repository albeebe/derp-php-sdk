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
   

class DERP {

	protected $_apiVersion = 1;
	protected $_facebookAccessToken;
	
	
/* ------------------------------------------------------------------- */
	public function DERP($facebookAccessToken) {
		/*
		
		Initialize the class
		
		*/
		
		if (strlen($facebookAccessToken) == 0) {
			$this->throwException("No Facebook Access Token was passed. Please visit <A HREF=\"http://derpapp.co/api/\">http://derpapp.co/api/</A> to get a token.");
		} else {
			$this->_facebookAccessToken = $facebookAccessToken;
		}
	}




/* ------------------------------------------------------------------- */	
	public function getAccountInfo() {
		/*
		
		Get the users Account information
		
		*/
		
		$url = "http://api.derpapp.co/v".$this->_apiVersion."/".$this->_facebookAccessToken."/Account";
		$response = file_get_contents($url);
		
		return json_decode($response, true);
	}




/* ------------------------------------------------------------------- */	
	public function getFriends() {
		/*
		
		Get the users Friends
		
		*/
		
		$url = "http://api.derpapp.co/v".$this->_apiVersion."/".$this->_facebookAccessToken."/Friends";
		$response = file_get_contents($url);
		
		return json_decode($response, true);
	}




/* ------------------------------------------------------------------- */	
	public function getSounds() {
		/*
		
		Get all the Sounds
		
		*/
		
		$url = "http://api.derpapp.co/v".$this->_apiVersion."/".$this->_facebookAccessToken."/Sounds";
		$response = file_get_contents($url);
		
		return json_decode($response, true);
	}




/* ------------------------------------------------------------------- */	
	public function sendSound($friendSID, $soundSID) {
		/*
		
		Send a sound to a friend
		
		*/
		
		$payload = http_build_query(array("friend_sid"=>$friendSID, "sound_sid"=>$soundSID));

		$ch = curl_init("http://api.derpapp.co/v".$this->_apiVersion."/".$this->_facebookAccessToken."/Message");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		
		return json_decode($response, true);
	}
	
	
	
	
/* ------------------------------------------------------------------- */	
	private function throwException($message) {
		/*
		
			Throw an exception
			
		*/
		
		throw new Exception($message); 
	}
	
}	
?>
