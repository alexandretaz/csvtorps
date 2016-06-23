<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pipedrive {

	public function insert($tipo,$data){
		//PIPEDRIVE ORGANIZAÇÃO		
		$ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, "https://api.pipedrive.com/v1/".$tipo."?api_token=".PIPEDRIVE_KEY);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resultado = curl_exec($ch);
		curl_close($ch);
		return json_decode($resultado);
	}

}