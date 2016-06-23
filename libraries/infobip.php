<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Infobip {

	public function enviar($data){
                $data['user']='salaovip';
                $data['password']='Salao14!';
                $data['sender']='27185';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://api.infobip.com/api/v3/sendsms/plain");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$resultado = curl_exec($ch);
		$xml = new SimpleXMLElement($resultado);
		$s = $xml->result->status==0 ? 1 : -1;
		return array($s,$xml->result->status,$this->getMsgRetorno($xml->result->status));
	}
        
        public function converteDataInfobip($data){
            if($data==''){return '';}
            $data1 = new DateTime();
            $data2 = new DateTime($data);
            if($data2<$data1){return '';}
            $intervalo = $data1->diff($data2);
            return "{$intervalo->days}d{$intervalo->h}h{$intervalo->i}m"; 
        }

	public function getMsgRetorno($status){
		$retorno = "";
		switch($status){
			case '0': $retorno = "Enviado com sucesso"; break;
			case '-1': $retorno = "Erro no processamento de requisição";break;
			case '-2': $retorno = "Não á créditos suficientes";break;
			case '-3': $retorno = "Rede existe cobertura para a rede indicada";break;
			case '-5': $retorno = "Usuário ou senha inválido";break;
			case '-6': $retorno = "Número de destino ausente na requisição";break;
			case '-10': $retorno = "Nome de usuário ausente na requisição";break;
			case '-11': $retorno = "Senha ausente na requisição";break;
			case '-13': $retorno = "Número Inválido";break;
			case '-22': $retorno = "Formato XML inválido, causado por erro de syntax";break;
			case '-23': $retorno = "Erro geral, as razões podem variar";break;
			case '-26': $retorno = "API Erro geral, as razões podem variar";break;
			case '-27': $retorno = "Agendamento parametar inválido";break;
		 	case '-28': $retorno = "PushURL inválido";break;
			case '-30': $retorno = "APPID inválido";break;
			case '-33': $retorno = "MessageID duplicada no pedido";break;
			case '-34': $retorno = "Nome do remetente não é permitido";break;
			case '-99': $retorno = "Erro indefinido";break;
}
		return $retorno;
	}

}