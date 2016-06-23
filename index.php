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
		$iterator = new FilesystemIterator(FOLDER_TO_UPLOAD, FilesystemIterator::SKIP_DOTS );?>

		<ul> 
		<?php
		foreach ($iterator as $item):
			if( $item->isDir() ):
				continue;
			endif;
		?>
			<li><a href="/uploads/<?php echo $item->getFileName();?>">
				<?php $item->getFileName();?>(<?php $item->getMTime();?>)
			</a> </li>
		<?php
		endforeach;
		?>
		</ul>
	</body>
</html>