<?php
/**
 *  Meebo Community IM REST API Implementation
 *
 *  This a generic PHP class that can hook-in to Meebo's Community IM Service
 * 
 *  Contributions are welcome.
 *
 *  Author: Bill Richards
 *  http://www.mixpod.com
 *  
 *  This code is free, provided AS-IS with no warranty expressed or implied.  Use at your own risk.
 *  If you find errors or bugs in this code, please contact me at bill.mff@gmail.com
 *  If you enhance this code in any way, please send me an update.  Thank you!
 *
 *  Last Updated: 4/03/2009 
 * 
 *	NOTE: ENTER YOUR USERNAME AND PASSWORD BELOW!!!
 *
 */ 
class meebo {

	var $partnerName = 'user'; //username provided by meebo
	var $partnerPass = 'pass'; //password
	
	var $debugResponse = false; //prints response from meebo for debugging
	
	const ERROR_CURL = "Request Error";
	const ERROR_FIELDS = "Missing required fields";
	
	function addFriend($uid=NULL, $frienduid=NULL, $friendname=NULL)
	{
		if($uid == NULL || $frienduid == NULL || $friendname == NULL)
		{
			throw new Exception(meebo::ERROR_FIELDS);
		} else {
			$this->doMeeboRequest('https://cim-api.meebo.com/api/communityim/v1/addfriend',array('uid'=>$uid, 'frienduid'=>$frienduid, 'friendname'=>$friendname));
		}
	}
	
	function removeFriend($uid=NULL, $frienduid=NULL)
	{
		if($uid == NULL || $frienduid == NULL)
		{
			throw new Exception(meebo::ERROR_FIELDS);
		} else {
			 $this->doMeeboRequest('https://cim-api.meebo.com/api/communityim/v1/removefriend',array('uid'=>$uid, 'frienduid'=>$frienduid));
		}
	}
	
	function updateStatus($uid=NULL, $status=NULL)
	{
		if($uid == NULL || $status == NULL)
		{
			throw new Exception(meebo::ERROR_FIELDS);
		} else {
			 $this->doMeeboRequest('https://cim-api.meebo.com/api/communityim/v1/updatestatus',array('uid'=>$uid, 'status'=>$status));
		}	
	}
	
	function updateImage($uid=NULL, $imageData=NULL, $imageURL=NULL)
	{
		if($uid == NULL || $imageURL == NULL)
		{
			throw new Exception(meebo::ERROR_FIELDS);
		} else {
			$image_type = substr(strrchr($imageURL,'.'),1);
			$this->doMeeboRequest('https://cim-api.meebo.com/api/communityim/v1/updateimage',array('uid'=>$uid, 'imagetype'=>$image_type, 'imageurl'=>$imageURL));
		}	
	}
	
	function sendNotification($uid=NULL, $type=NULL, $notificationid=NULL, $timestamp=NULL, $subjectuid=NULL, $subjectname=NULL, $predicate=NULL)
	{
		if($uid == NULL || $type == NULL || $notificationid == NULL || $timestamp == NULL || $subjectuid == NULL || $subjectname == NULL || $predicate == NULL)
		{
			throw new Exception(meebo::ERROR_FIELDS);
		} else {
		
		}	
	}
	
	function logout($uid=NULL)
	{
		if($uid == NULL)
		{
			throw new Exception(meebo::ERROR_FIELDS);
		} else {
			$this->doMeeboRequest('https://cim-api.meebo.com/api/communityim/v1/logout',array('uid'=>$uid));
		}	
	}
	
	function handleResponse($response=NULL)
	{
		if($response)
		{
			 $returned = json_decode($response, true);
			 if($returned['stat'] == 'fail'){
			 	throw new Exception('Error code: '.$returned['errorcode'].'. Message: '.$returned['msg']);
			 } else {
			 	if($this->debugResponse) print_r($returned);
			 }
		}
	}
	
	function doMeeboRequest($curl=NULL,$postData=NULL)
	{
		if($curl == NULL) throw new Exception(meebo::ERROR_FIELDS);
		$ch = curl_init($curl);
		curl_setopt($ch, CURLOPT_USERPWD, $this->partnerName.':'.$this->partnerPass);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if(is_array($postData)) curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$data = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch); 
		if($httpcode>=200 && $httpcode<300){
			$this->handleResponse($data);
		} else {
			throw new Exception(meebo::ERROR_CURL);
		}
	}
}

					/*
					Sample usage:
						try{
							$meeboIM = new meebo;
							$meeboIM->updateStatus(1234, 'Jumping Turtle');
						} catch (Exception $e) {
							$errors = $e->getMessage();
							echo $errors;
						}
					*/
?>