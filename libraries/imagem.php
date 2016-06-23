<?php
class Imagem {
	
	public $path;
	public $infos = array();
	public $infosCalcs = array();
	
	
	public function getImagem($caminho){
		
		if(is_file($caminho)){
			$this->path = $caminho;

			$this->infos = $this->getInfosImagem($caminho);

			return true;
		}
		else{
			exit('nao');
			return false;
		}
	}
	
	private function getInfosImagem($caminho){
		if(is_file($caminho)){
			return getimagesize($caminho);	
		}
		else{
			return array(0,0);
		}
	}
	
	public function setTamanho($w,$h){
		$this->infosCalcs[0] = $w;
		$this->infosCalcs[1] = $h;
	}
	
	public function calcTamanho($v,$tipo='w'){
		switch ($tipo) {
			case 'w':
				$this->infosCalcs[0] = $v;
				$this->infosCalcs[1] = ($this->infos[1] * $v) / $this->infos[0];
			
			break;
			case 'h':
				$this->infosCalcs[1] = $v;
				$this->infosCalcs[0] = ($this->infos[0] * $v) / $this->infos[1];
			
			break;
			case 'p':
				$this->infosCalcs[0] = $this->infos[0] * $v / 100;
				$this->infosCalcs[1] = $this->infos[1] * $v / 100;
			break;
		}
	}
	
	public function headerImage(){
		header('Content-type: image/jpeg');//header("Content-type: {$this->infos['mime']}");
		$imageCreate = imagecreatetruecolor($this->infos[0], $this->infos[1]);
		switch ($this->infos['mime']) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($this->path);
				imagecopyresampled($imageCreate, $image, 0, 0, 0, 0, $this->infosCalcs[0], $this->infosCalcs[1], $this->infos[0], $this->infos[1]);
				// Output the image
				imagejpeg($image, null, 100);
				break;
			case 'image/bmp':
				$image = imagecreatefromwbmp($this->path);
				imagecopyresampled($imageCreate, $image, 0, 0, 0, 0, $this->infosCalcs[0], $this->infosCalcs[1], $this->infos[0], $this->infos[1]);
				// Output the image
				imagewbmp($imageCreate, null, 100);
				break;
			case 'image/gif':
				$image = imagecreatefromgif($this->path);
				imagecopyresampled($imageCreate, $image, 0, 0, 0, 0, $this->infosCalcs[0], $this->infosCalcs[1], $this->infos[0], $this->infos[1]);
				// Output the image
				imagegif($imageCreate, null, 100);
				break;
			case 'image/png':
				imagealphablending($imageCreate, false);
				imagesavealpha($imageCreate, true);  
				
				$image = imagecreatefrompng($this->path);
				imagealphablending($image, true);
				
				imagecopyresampled($imageCreate, $image, 0, 0, 0, 0, $this->infosCalcs[0], $this->infosCalcs[1], $this->infos[0],$this->infos[1]);
				// Output the image
				imagepng($imageCreate);
				break;	
		}
		exit();
	}
	
}
?>