<?php 
	include_once 'db.php';
	$a = rand() % 6;

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Rikues | Player</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="nanoscroller.css">
        <link rel="stylesheet" href="style.css">
		<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		
		<script src="//connect.soundcloud.com/sdk.js"></script>
	</head>
	<body>
		<h1 id="header" class="bgcolor-<?php echo $a?>">rikues</h1>
		<div id="playlist" class="col-xs-12 col-sm-4 col-md-3 play-page">
			<div class="title">
				<h2>Playlist</h2>
				<div id="next-btn" class="color-<?php echo $a?>">
					<span class="glyphicon glyphicon-forward" title="Next Song" onclick="next()	"></span>
				</div>
			</div>
			<div class="nano">
				<ul id="list" class="nano-content">
				<?php
					$result = mysqli_query($conn,"SELECT * FROM list WHERE is_played = 0 LIMIT 10");
					while($row = mysqli_fetch_array($result)) {
				?>
					<li data-id="<?php echo $row[2] ?>">
						<div class="requester color-<?php echo $a?>"><?php echo $row[1] ?></div>
						<div class="vid-title"><?php echo $row[5] ?></div>
						<div class="invisible"><?php echo $row[3]?></div>
						<div class="invisible"><?php echo $row[0]?></div>
						<div class="invisible"><?php echo $row[6]?></div>
					</li>
				<?php } ?>
				</ul>
			</div>
			<div class="request" id="player-request">
				<div class="req-button bgcolor-<?php echo $a?>" data-target="player-request">
					<span>+</span>
				</div>
				<div class="req-form">
					<h3 style="margin:0 0 20px" class="color-<?php echo $a?>">Request</h3>
					<form id="request-form" action="query.php?action=add" method="POST" role="form">
						<div class="alert alert-warning alert-dismissable" id="alert" style="display:none;">
						  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						  <span></span>
						</div>
						<div class="form-group">
							<label>Name</label><br />
							<input type="text" name="nama" class="form-control" />
						</div>
						<div class="form-group">
							<label>Youtube link/keyword(s)</label><br />
							<input type="hidden" name="video_id" class="form-control" />
							<input type="text" name="video_query" class="form-control clear-onsubmit" />
							<input type="hidden" name="video_title" class="form-control" />
							<input type="hidden" name="video_thumbnail" class="form-control" />
						</div>
						<div class="form-group">
							<label>Message</label><br />
							<textarea name="pesan" class="form-control clear-onsubmit"></textarea>
						</div>
						<div class="form-group">
							<input type="submit" value="Go!" class="col-xs-4 col-xs-offset-4 btn btn-default custom-btn bgcolor-<?php echo $a?>" id="request-submit" />
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-8 col-md-9">
			<div class="col-sm-12">
				<h2 style="margin-bottom:20px;" class="color-<?php echo $a?>">#nowplaying: <span id="title"></span></h2>
				<!-- <iframe class="col-xs-12 col-md-11" style="padding-left:0" height="450" src="//www.youtube.com/embed/xtSye1rio40" frameborder="0" allowfullscreen></iframe> -->
				<div id="error" class="invisible" style="height:390px;width:80%;margin:0 10%;background:#fff;text-align:center;padding:80px 40px">
					<p style="color:#919191;font-weight:bold;font-size:1.2em;margin-bottom:40px">Oops! There was a problem playing your song :(<br /><br />Your video will be played in a new tab. Click on the button below to continue to the next song.</p>
					<button onclick="next()" type="button" class="btn btn-default btn-lg" style="color:#919191">Next</button>
				</div>
				<div id="player"></div>
				<iframe id="sc-widget" src="https://w.soundcloud.com/player/?url=http://api.soundcloud.com/users/1539950/favorites" width="80%" height="390" heightscrolling="no" frameborder="no"></iframe>
			</div>			
			<div class="col-sm-12" id="pesan">
				<h3 id="sender">Request a song <a href='req.php' target='_blank'>here</a></h3>
				<p id="greet"></p>
			</div>
		</div>
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="nanoscroller.js"></script>
		<script type="text/javascript" src="bootstrap.js"></script>
		<script src="https://w.soundcloud.com/player/api.js" type="text/javascript"></script>
		<script>
			var rand = <?php echo $a?>;
			var page = "play";
			if($("#list").children().length > 0){
				$("#title").html($("#list").children().eq(0).children().eq(1).html());
				$("#sender").html($("#list").children().eq(0).children().eq(0).html());
				$("#greet").html($("#list").children().eq(0).children().eq(2).html());
				$('.title').css('background-image', 'url(' + $("#list").children().eq(0).children().eq(4).html() + ')');
			}
		</script>
		<!-- <script type="text/javascript" src="script.js"></script> -->
		<script>
			pre_title = "[\u25b6] ";
			
		</script>
	</body>
</html>