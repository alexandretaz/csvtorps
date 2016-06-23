<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funcoes {
	
	protected $_google_url;
	protected $_ci;

	public function __construct()
	{
		//key=AIzaSyCZ9259ejhEBt34e2EVm2z0gwrm0KH4wE4
		$this->_google_url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=';
		$this->_ci = & get_instance();
	}

	public function googleSearch($address)
	{
		$sucesso = false;
		$erroCode = 'e000';
		$dadosRetorna = array();
		
		$this->_ci->load->helper('language');
		
		if((is_numeric($address))){
			$this->cep = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($address).'&formato=query_string');  
			parse_str($this->cep, $this->endereco_cep);   
	
			$address = $this->endereco_cep['tipo_logradouro']." ".$this->endereco_cep['logradouro']." - ".$this->endereco_cep['bairro']." ".$this->endereco_cep['cidade']." - ".$this->endereco_cep['uf'];
		} 		
		$address = $this->removeAcentos($address);
		$address_url = $this->_google_url.$address;
		$geocode = file_get_contents($address_url);
//		echo $address_url;
//		var_dump($geocode);exit();
		$output = json_decode($geocode);

		
		if(isset($output->results[0]))
		{
			$lat = $output->results[0]->geometry->location->lat;
			$long = $output->results[0]->geometry->location->lng;	
			$dadosRetorna = array(
					'latitude'  => $lat,
					'longitude' => $long
				);
			$sucesso = true;
			$erroCode = 's000';
		}
		else{
			$sucesso = false;
			$erroCode = '901';
			$mensagem = array('msg'=>'Erro na busca por '.$address_url);
			$this->email_model->enviar($mensagem);
		}
		
		return array($sucesso,$erroCode,$dadosRetorna);
		
	}
	
	public function googleSearchReverse($lat,$log)
	{
		$sucesso = false;
		$erroCode = 'e000';
		$dadosRetorna = array();
		
		$this->_ci->load->helper('language');
		//key=AIzaSyCZ9259ejhEBt34e2EVm2z0gwrm0KH4wE4		
		$address_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$log&sensor=false";
		$geocode = file_get_contents($address_url);
		$output = json_decode($geocode);
		var_dump($output); exit();
		
		if(isset($output->results[0]))
		{
			$lat = $output->results[0]->geometry->location->lat;
			$long = $output->results[0]->geometry->location->lng;	
			$dadosRetorna = array(
					'latitude'  => $lat,
					'longitude' => $long
				);
			$sucesso = true;
			$erroCode = 's000';
		}
		else{
			$sucesso = false;
			$erroCode = '901';
		}
		
		return array($sucesso,$erroCode,$dadosRetorna);
		
	}
	
	public function horario($int){ //recebe horario em INTEIRO (minutos) e converte para array['hora','minuto']
		$h = $int/60; //Pega horário
		$h = floor($h);
		$m = $int%60;
		$horario = array('hora'=>$h,'minuto'=>$m);
		return $horario;
	}

	#Função retorna a distância em metros entre 2 pontos
	#Usando latitude e longitude como parâmetro
	public function calcDistancia($coordenada1, $coordenada2, $distancia){

		#Define quantas vezes o loop vai rodar
		$i = 0;

		#Cria um array para receber os valores de distancia
		#para comparar entre o ponto de partida e os saloes do banco
		$this->proximidades = array();

		foreach($coordenada2 as $c2){

			$this->distLa1 = $coordenada1['latitude'] * pi() / 180.0;
			$this->distLo1 = $coordenada1['longitude'] * pi() / 180.0;
			$this->distLa2 = $c2->latitude * pi() / 180.0;
			$this->distLo2 = $c2->longitude * pi() / 180.0;
			
			#Calcula distancia entre as Latitudes e Longitudes
			$this->dist_lat = $this->distLa2 - $this->distLa1;
			$this->dist_long = $this->distLo2 - $this->distLo1;

			$r = 6371.0;
			$a = sin($this->dist_lat / 2) * sin($this->dist_lat / 2) + cos($this->distLa1) * cos($this->distLa2) * sin($this->dist_long / 2) * sin($this->dist_long / 2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			
			#Arredonda o numero retornado na conta para obter a distancia
			$this->distancia = round($r * $c * 1000);
	
			#Verifica se a distancia esta dentro do intervalo desejado
			#pedido através da variável $distancia
			#Se estiver dentro do intervalo ele guarda dentro do array
			if($this->distancia < $distancia) {				
				$this->proximidades[$i] = $c2;
				$i++;
			}
		}

		#Ordena o array em ordem crescente
		sort($this->proximidades);

		return $this->proximidades;
	}
	public function removeAcentos($texto){
				
		$texto = strtolower($texto);
			$array1 = array( " ","á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" 
	, "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
		$array2 = array( "+", "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
		, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" ); 
	return str_replace( $array1, $array2, $texto); 

	}

}