<?php 
	include_once 'db.php';
	$a = rand() % 6;

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>rikues</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
		<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<h1 id="header" class="mega bgcolor-<?php echo $a?>">rikues</h1>
		<div class="container" style="margin-bottom:30px">
			<div class="col-xs-12 text-center">
				<div href="#" class="btn btn-default custom-btn bgcolor-<?php echo $a?>">Create Channel</div>
			</div>
		</div>
		<div class="col-xs-12 text-center">
			<ul class="row" id="list-channel">
				<li class="col-xs-12 col-sm-6 col-md-3">
					<div class="head">
						<img src="tes.jpg" />
						<h2>bangtan boys</h2>
					</div>
					<div class="desc">
						ini adalah suatu channel dimana terjadi sesuatu playlist lalu kemudian sesuatu
						<div class="btns">
							<a href="#" class="btn btn-default custom-btn bgcolor-<?php echo $a?>">Request</a>
							<a href="#" class="btn btn-default custom-btn bgcolor-<?php echo $a?>">Play</a>
						</div>
					</div>
				</li>
				<li class="col-xs-12 col-sm-6 col-md-3">
					<div class="head">
						<img src="tes2.jpg" />
						<h2>ini yoongi</h2>
					</div>
					<div class="desc">
						ceritanya ngetes warna background channel
						<div class="btns">
							<a href="#" class="btn btn-default custom-btn bgcolor-<?php echo $a?>">Request</a>
							<a href="#" class="btn btn-default custom-btn bgcolor-<?php echo $a?>">Play</a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</body>
</html>