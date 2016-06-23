<?php
	require_once("settings.php");
	require_once("libraries/rps/Rps.php");
	require_once("libraries/rps/LotFile.php");
?>


<html>
	<head>
		<title> Gerador de RPS </title>
	</head>
	<body>
		<form action="/execute.php" method="post" enctype="multipart/form-data">
			<label for="upload_rps">
				<input type="file" id="upload_rps" name="upload_rps">
				<input type="submit">
			</label>
		</form>
		<h1> Lista de Arquivos de Lote Armazenados no Servidor</h1>
		<?php
		$iterator = new FilesystemIterator(DIR_STORE, FilesystemIterator::SKIP_DOTS );?>

		<ul> 
		<?php
		foreach ($iterator as $item):
			if( $item->isDir() || strcasecmp($item->getFileName(),'base.md') === 0 ):
				continue;
			endif;
		?>
			<li><a href="/lotFile/<?php echo $item->getFileName();?>">
				<?php echo $item->getFileName();?>(<?php echo date('d/m/Y H:i:s',$item->getMTime());?>)
			</a> </li>
		<?php
		endforeach;
		?>
		</ul>
	</body>
</html>