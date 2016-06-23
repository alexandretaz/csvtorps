<?php
class Convercao {
	
	
	/**
     * Converte a data em formato brasileiro para a data mysql
     * OBS: Explode a data do parametro e reorganiza na data do mysql
     *
     * @param string $data Data que sera convertida
     * @return string
     */
	public function dataToMysql($data){
		try {
			$exdata = explode("/",$data);
			return $exdata[2]."-".$exdata[1]."-".$exdata[0];
		} catch (Exception $e) {
			return '';
		}
	}
	
	/**
     * Converte a data e hora em formato brasileiro para a data mysql
     * OBS: Explode a data da hora e utiliza o metodo dataToMysql para conveter
     *
     * @param string $data Data e hora que sera convertida
     * @return string
     */	
	public function datatimeToMysql($data){
		if($data!=''){
			$exdata = explode(" ",$data);
			return $this->dataToMysql($exdata[0])." ".$exdata[1];
		}
		else{
			return '';
		}
	}
	
	public function datatimeToMysqlArr($data){
		if($data!=''){
			$exdata = explode(" ",$data);
			return array($this->dataToMysql($exdata[0]),$exdata[1]);
		}
		else{
			return '';
		}
	}
	
	/**
     * Converte a data e hora em formato mysql para a data brasileira
     * OBS: Explode a data do parametro e reorganiza na data do brasileiro
     *
     * @param string $data Data e hora que sera convertida
     * @return string
     */	
	public function mysqlToData($data){
		if($data!=''){
			$exdata = explode("-",$data);
			$dataFim = $exdata[2]."/".$exdata[1]."/".$exdata[0];
			return $dataFim!='00/00/0000' ? $dataFim : '';
		}
		else{
			return '';
		}
	}
	
	/**
     * Converte a datae hora em formato mysql para a data brasileira
     * OBS: Explode a data da hora e utiliza o metodo mysqlToData para conveter
     *
     * @param string $data Data e hora que sera convertida
     * @return string
     */	
	public function mysqlToDatatime($data){
		if($data!=''){
			$exdata = explode(" ",$data);
			return $this->mysqlToData($exdata[0])." ".$exdata[1];
		}
		else{
			return '';
		}
	}
	
	/**
     * Converte o numero em nome do mes
     * OBS:
     *
     * @param string $mes numero do mes com duas casas
     * @return string
     */	
	public function nomeMes($mes){
		switch ($mes) {
			case "01":    $mes = 'Janeiro';     break;
			case "02":    $mes = 'Fevereiro';   break;
			case "03":    $mes = 'Março';       break;
			case "04":    $mes = 'Abril';       break;
			case "05":    $mes = 'Maio';        break;
			case "06":    $mes = 'Junho';       break;
			case "07":    $mes = 'Julho';       break;
			case "08":    $mes = 'Agosto';      break;
			case "09":    $mes = 'Setembro';    break;
			case "10":    $mes = 'Outubro';     break;
			case "11":    $mes = 'Novembro';    break;
			case "12":    $mes = 'Dezembro';    break; 
		 }
		 return $mes;
	}
	
	
	public function nomeDiaSemana($dia){
		switch ($dia) {
			case "0":    $dia = 'Segunda';  break;
			case "1":    $dia = 'Terça';   	break;
			case "2":    $dia = 'Quarta';   break;
			case "3":    $dia = 'Quinta';   break;
			case "4":    $dia = 'Sexta';    break;
			case "5":    $dia = 'Sabado';   break;
			case "6":    $dia = 'Domingo';  break;
		 }
		 return $dia;
	}
	
	function dataMysqlToString($data){
		list($ano,$mes,$dia) = explode("-",$data);
		$mes = $this->nomeMes($mes);
		return "$dia de $mes ";
	}
	
	function dataTimeMysqlToString($data){
		$exdata = explode(" ",$data);
		list($ano,$mes,$dia) = explode("-",$exdata[0]);
		$mes = $this->nomeMes($mes);
		return "$mes $ano";
	}
	

	/**
     * Converte os minutos em hora
     * OBS: O retorno em array vem com as horas no primeiro vetor e os minutos no segundo vetor
     *
     * @param int $int Data e hora que sera convertida
     * @return array
     */		
	public function minutoToHora($int){
		$h = $int/60;
		$h = round($h)>$h ? round($h)-1 : round($h);
		$m = $int%60;
		$h = $h<10 ? '0'.$h : $h;
		$m = $m<10 ? '0'.$m : $m;
		$horario = array('hora'=>$h,'minuto'=>$m);
		return $horario;
	}
	
	public function horaToMinuto($hora){
		
		$exHora = explode(':',$hora);
		$m = $exHora[0]*60;
		$m += $exHora[1];
		return $m;
	}
	
	/**
     * Converte os numeros float em moeda real
     * OBS: O retorno em array vem com as horas no primeiro vetor e os minutos no segundo vetor
     *
     * @param float $int Data e hora que sera convertida
     * @return real
     */		
	public function floatToReal($float){
		return number_format($float,2,',','.');
	}
	
	public function floatToDollar($float){
		return number_format($float,2,'.','');
	}
	public function realToFloat($real){
		return str_replace(',','.',$real);
	}
	
	public function limpaTelefone($texto){
				
		$array1 = array( "(",")","-"," " ); 
		$array2 = array(  "","","","" ); 
		return str_replace( $array1, $array2, $texto); 

	}
	
	public function statusToLabel($int){
		$status = '';
		if($int==0){
			$status = '<span class="label">Cancelado</span>';
		}
                if($int==1){
			$status = '<span class="label label-info">Agendado</span>';
		}
                if($int==1.5){
                    	$status = '<span class="label" style="background: #00BCD4;">Confirmado</span>';
                }
		if($int==2){
			$status = '<span class="label label-warning">Aguardando</span>';
		}
		if($int==3){
			$status = '<span class="label label-success">Em Atendimento</span>';
		}
		if($int==4){
			$status = '<span class="label label-important">Pago</span>';
		}
		return $status; 

	}
	
	
	public function strToAscii($texto){
				
			$array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" 
	, "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
		$array2 = array(  "&aacute;", "&agrave;", "&acirc;", "&atilde;", "a", "&eacute;", "&egrave;", "&ecirc;", "e", "&iacute;", "i", "i", "i", "o", "o", "o", "&otilde;", "o", "&uacute;", "u", "u", "u", "&ccedil;" 
		, "&Aacute;", "&Agrave;", "&Acirc;", "&Atilde;", "A", "&Eacute;", "&Egrave;", "&Ecirc;", "E", "&Iacute;", "I", "I", "I", "O", "O", "O", "&Otilde;", "O", "&Uacute;", "U", "U", "U", "&Ccedil;" ); 
	return str_replace( $array1, $array2, $texto); 

	}
	
	public function removeAcentos($texto){
				
			$array1 = array( "&", "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" 
	, "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
		$array2 = array( "e", "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
		, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" ); 
	return str_replace( $array1, $array2, $texto); 

	}

}