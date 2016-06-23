<?php
class Gerencianet {
	
	private $token = '9bf7bc922960b4c77d7d117acac37e04';
	private $cliente = array();
	public $item = array('descricao' => '','valor' => '0','qtde' => '1','desconto' => '0');
	public $resposta = '';
	public $vencimento = '';
	
	public function enviaCombranca($tipo='boleto',$retorno='html',$cliente){
		$url = '';
		switch ($tipo) {
			case 'boleto':
				$url = "https://integracao.gerencianet.com.br/xml/boleto/emite/$retorno";
				$this->cliente = array(
					'nomeRazaoSocial' => $cliente->nome,
					'cpfcnpj' => $cliente->cpfcnpj,
					'opcionais' => array(
						'email' => $cliente->email,
						'cep' => $cliente->cep,
						'rua' => $cliente->rua,
						'numero' => $cliente->numero,
						'bairro' => $cliente->bairro,
						'complemento' => $cliente->complemento,
						'estado' => $cliente->estado,
						'cidade' => $cliente->cidade,
				));
			break;
		}
		
		$xmlArray = array(
			'token' => $this->token,
			'clientes' => array(
				'cliente' => $this->cliente
			),
			'itens' => array(
				'item' => $this->item,
			),
			'vencimento' => $this->vencimento,
			'opcionais' => array(
				'contra' => 's',
				'btaxa' => 'n',
				'enviarParaMim' => 's',
				'continuarCobrando' => '0',
				'correios' => 'n',
			),
		);
		$xml = new SimpleXMLElement('<'.$tipo.'/>');
		$this->array_to_xml($xmlArray,$xml);
		
		
		$s = $this->enviaRequisicao($url,$xml);
		if($s!==false){
			$s = $this->xml2obj($s);
			$this->resposta = $s;
		}
		
		return $s;
		
	}
	
	public function statusChave($chave=''){
		$url="https://integracao.gerencianet.com.br/xml/statusChave/xml";
		$xmlArray = array(
			'token' => $this->token,
			'chave' => $chave
		);
		$xml = new SimpleXMLElement('<statusChave/>');
		$this->array_to_xml($xmlArray,$xml);

		$s = $this->enviaRequisicao($url,$xml);
		if($s!==false){
			$s = $this->xml2obj($s);
			$this->resposta = $s;
			
		}
		
		return $s;
	}
	
	private function enviaRequisicao($url='',$xml=''){

		$ch = curl_init();
		$data = array('entrada' => $xml->asXML());
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

		$this->resposta = curl_exec($ch);
		curl_close($ch);
		
		return $this->resposta === false ? false : $this->resposta;
	}
	
	private function array_to_xml($xmlArray, &$xml) {
		foreach($xmlArray as $key => $value) {
			if(is_array($value)) {
					if(!is_numeric($key)){
							$subnode = $xml->addChild("$key");
							$this->array_to_xml($value, $subnode);
					}
					else{
							$subnode = $xml->addChild("item$key");
							$this->array_to_xml($value, $subnode);
					}
			}
			else {
					$xml->addChild("$key","$value");
			}
		}
	}
	private function xml2obj($source){
		$xml = simplexml_load_string($source);
		return $xml;
	}
	

}