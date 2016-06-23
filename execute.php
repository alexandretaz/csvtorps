<?php
	require_once("settings.php");
	require_once("libraries/rps/Rps.php");
	require_once("libraries/rps/LotFile.php");
	if(!isset($_FILES['upload_rps']) || empty($_FILES['upload_rps'])) {
		header('location:index.php');
	}
	$fileToWork =  FOLDER_TO_UPLOAD.DIRECTORY_SEPARATOR.$_FILES['upload_rps']['name'];
	$dataInicio  =  new \Datetime();
	$strDateRecord = $dataInicio->format('Ymd_H_i_s');

	if( isset( $_FILES['upload_rps'] ) && !empty($_FILES['upload_rps'] ) && move_uploaded_file( $_FILES['upload_rps']['tmp_name'], $fileToWork ) ) {
			$lines = file($fileToWork);
			$lotFile = new lotFile();
			$comandas = array();
			$arrSalao = array();
			foreach ($lines  as $key => $line) {
				if($key == 0) {
					continue;
				}

				$comanda = new StdClass();
				$actualDate = new \Datetime();
				$fields = explode(";", $line);


				$comanda->total = (float) str_ireplace( "," , "." , str_ireplace("R$" , "" , $fields[0]) );
				$comanda->cnpj = $fields['2'];
				$comanda->tipoEndereco = $fields['3'];
				$comanda->endereco = $fields['4'];
				$comanda->enderecoNumero = $fields['5'];
				$comanda->enderecoComplemento = $fields['6'];
				$comanda->enderecoBairro = $fields['7'];
				$comanda->enderecoCidade =  $fields['8'];
				$comanda->enderecoUF =  $fields['9'];
				$comanda->enderecoCEP =  $fields['10'];
				$comanda->enderecoEmail =  $fields['11'];
				$comanda->servicos = "Assinatura Sistema Salao ViP|valor aproximado dos tributos 6,0% - conforme Lei 12.741/2012" ;
				$comanda->data_emissao = $actualDate->format('Ymd');		
				if( !empty($fields['0']) ) {
				$salao = $fields['1'];
				$salaoId = explode(" - ", $salao);
				$arrSalao[] = str_ireplace("#", '', $salaoId['0']);
					$comandas[] = $comanda;
				}		
			}
			$lotFile->generate($comandas, $dados, $dadosEnvio);
	}
	else{
		var_dump($_FILES);
	}

	file_put_contents( DIR_STORE.'/'.'rps_emitidas_'.$strDateRecord.'.txt', $lotFile->__toString() );
	chmod (DIR_STORE.'/'.'rps_emitidas_'.$strDateRecord.'.txt', 775);

	unlink('next_rps');
	file_put_contents ( "next_rps", $lotFile->getNextNumber() );

	require_once("index.php");

?>