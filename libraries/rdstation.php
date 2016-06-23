<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rdstation {

	public function insert($tipo,$data){
            
            $data['token_rdstation']='b461aeccdc0233546c8b06b27be24ae9';
            
            switch($tipo){
                //http://ajuda.rdstation.com.br/hc/pt-br/articles/200310589-Integrar-formul&aacute;rio-no-site-ou-sistema-pr&oacute;prio-para-Cria&ccedil;&atilde;o-de-Lead-API-
                case 'insert': $url='https://www.rdstation.com.br/api/1.2/conversions'; break; 
                //http://ajuda.rdstation.com.br/hc/pt-br/articles/200310699-Alterar-estado-do-Lead-no-funil-do-RD-Station-API-
                case 'update': $url='https://www.rdstation.com.br/api/1.2/leads/:lead_email'; break;
                //http://ajuda.rdstation.com.br/hc/pt-br/articles/202640385-Marcar-venda-e-lost-via-formul%C3%A1rio-pr%C3%B3prio-ou-sistema-API-
                case 'denificao': $url='https://www.rdstation.com.br/api/1.2/services/PRIVATE_TOKEN/generic'; break;
            }
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resultado = curl_exec($ch);
            curl_close($ch);
            return json_decode($resultado);
	}
        
        
}
