<?php
require_once realpath(dirname(__FILE__)."/../client/ZohoOAuthPersistenceInterface.php");
require_once realpath(dirname(__FILE__)."/../common/ZohoOAuthException.php");
require_once realpath(dirname(__FILE__)."/../common/OAuthLogger.php");

class ZohoOAuthPersistenceHandler implements ZohoOAuthPersistenceInterface
{
	public function saveOAuthData($zohoOAuthTokens)
	{
		try{
			$query="INSERT INTO oauthtokens(useridentifier,accesstoken,refreshtoken,expirytime) VALUES('".$zohoOAuthTokens->getUserEmailId()."','".$zohoOAuthTokens->getAccessToken()."','".$zohoOAuthTokens->getRefreshToken()."',".$zohoOAuthTokens->getExpiryTime().")";
			$result = \Illuminate\Support\Facades\DB::connection()
            ->getPdo()
            ->exec($query);
            if($result === false) {
            	dd($result->errorInfo());
            }
		}
		catch (Exception $ex)
		{
			dd($ex->getMessage());
		}
	}
	
	public function getOAuthTokens($userEmailId)
	{
		$db_link=null;
		$oAuthTokens=new ZohoOAuthTokens();
		try{
			$result = \Illuminate\Support\Facades\DB::table('oauthtokens')->where('useridentifier', $userEmailId)->first();
			
			if(empty($result)) {
				//TO DO HANDLE EXCEPTION
				return null;
			}else{
				$oAuthTokens->setExpiryTime($result->expirytime);
				$oAuthTokens->setRefreshToken($result->refreshtoken );
				$oAuthTokens->setAccessToken($result->accesstoken);
				$oAuthTokens->setUserEmailId($result->useridentifier);
			}
		}
		catch (Exception $ex)
		{
			//TO DO HANDLE EXCEPTION
			return null;
		}
		return $oAuthTokens;
	}
	
	public function deleteOAuthTokens($userEmailId)
	{
		try{
			$result = \Illuminate\Support\Facades\DB::table('oauthtokens')->where('useridentifier', $userEmailId)->delete();
			if(!$result) {
				//TO DO HANDLE EXCEPTION
				return null;
			}
		}
		catch (Exception $ex)
		{
			//TO DO HANDLE EXCEPTION
			return null;
		}
	}
}
?>