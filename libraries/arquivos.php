<?php
class Arquivos {
	
	public function arqTipoCompativel($arquivo,$tipos=array()){
		$info = pathinfo($arquivo);
		if (in_array($info['extension'],$tipos) === false) {
			return false;
		}
		else{
			return true;
		}
	}
	
	public function arqTamanhoCompativel($tArquivo,$tPermitido){
		if ($tArquivo > $tPermitido) {
			return false;
		}
		else{
			return true;
		}
	}
	
	public function arqDimensao($arquivo,$dPermitido){
		$image_size = getimagesize($arquivo);
		if ($image_size[0] > $dPermitido['w'] or  $image_size[1] > $dPermitido['h'] ){ 
			return false;
		}
		else{
			return true;
		}
	}
	
	public function uploadArquivo($diretorio,$key,$nomearq,
	$validacao = array('tipo' => array(),'tamanho' => '1000000','dimensao' => array('w'=>800, 'h' => 600))){
		
		if(is_array($_FILES[$key]['name'])){
			for($i=0;$i<=count($_FILES[$key]['name']);$i++){
				$file_name[] = $_FILES[$key]['name'][$i];
				$file_type[] = $_FILES[$key]['type'][$i];
				$file_size[] = $_FILES[$key]['size'][$i];
				$file_tmp_name[] = $_FILES[$key]['tmp_name'][$i];
				$error[] = $_FILES[$key]['error'][$i];
				$file_new_name[] = date("YmdHis").rand(0,100);
			}		
		}
		else{
			$file_name[] = $_FILES[$key]['name'];
			$file_type[] = $_FILES[$key]['type'];
			$file_size[] = $_FILES[$key]['size'];
			$file_tmp_name[] = $_FILES[$key]['tmp_name'];
			$error[] = $_FILES[$key]['error'];
			$file_new_name[] = $nomearq;
		}
		
		$retorna = array();
		$msg = '';
		
		for($i=0;$i<count($file_name);$i++){
			if(is_uploaded_file($file_tmp_name[$i])){
				
				if($error[$i] == 0){
					if(!$this->arqTipoCompativel($file_name[$i],$validacao['tipo'])){
						$error[$i] = 6;
					}
					elseif(!$this->arqTamanhoCompativel($file_size[$i],$validacao['tamanho'])){
						$error[$i] = 2;
					}
					else{	
						if(is_array($validacao['dimensao'])){
							if(!$this->arqDimensao($file_tmp_name[$i],$validacao['dimensao'])){
								$error[$i] = 7;
							}
							else{			
								if(!move_uploaded_file($file_tmp_name[$i],$diretorio.$file_new_name[$i].substr($file_name[$i], -4))){
									$error[$i] = 5;
								}
							}
						}
						else{			
							if(!move_uploaded_file($file_tmp_name[$i],$diretorio.$file_new_name[$i].substr($file_name[$i], -4))){
								$error[$i] = 5;
							}
						}
					}
				}
				$msg = $this->msgErro($error[$i]);
				$retorna[] = array(
							$error[$i],
							$msg,
							$file_new_name[$i].substr($file_name[$i], -4)
							);
			}
		}
		return $retorna;
	}
	
	public function msgErro($nErro){
		switch ($nErro){
			case 0:
				return 'Upload concluido com sucesso!';
				break;
			case 1:
				return 'O tamanho do arquivo é maior que o definido nas configuraçoes do PHP!';
				break;
			case 2:
				return 'O tamanho do arquivo é maior do que o permitido!';
				break;
			case 3:
				return 'O upload não foi concluído!';
				break;
			case 4:
				return 'O upload não foi feito!';
				break;
			case 5:
				return 'Não foi possível salvar o arquivo!';
				break;	
			case 6:
				return 'Tipo de exten&ccedil;&atilde;o n&atilde;o permitido!';
				break;
			case 7:
				return 'A largura ou altura excede o permitido.';
				break;
			default:
				return 'Erro desconhecido, tente novamente mais tarde.';
				break;
					
		}
	}
}