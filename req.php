<?php 
	include_once 'db.php';
	$a = rand() % 6;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Rikues | Request a Song</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta charset="utf-8">
        <link rel="stylesheet" href="bootstrap.min.css">
        <link rel="stylesheet" href="nanoscroller.css">
        <link rel="stylesheet" href="style.css">
		<link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<h1 id="header" class="bgcolor-<?php echo $a?>">
			rikues
			<a href="play.php" title="Open Player" target="_blank">&#9658;</a>
		</h1>
		<div id="playlist" class="col-xs-12 col-sm-4 col-md-3">
			<div class="title">
				<h2>Playlist</h2>
			</div>
			<div class="nano">
				<ul id="list" class="nano-content">
				<?php
					$result = mysqli_query($conn,"SELECT * FROM list WHERE is_played = 0");
					while($row = mysqli_fetch_array($result)) {
				?>
					<li data-id="<?php echo $row[3] ?>">
						<div class="requester color-<?php echo $a?>"><?php echo $row[1] ?></div>
						<div class="vid-title"><?php echo $row[6] ?></div>
						<?php if($row[4] != ""){?>
							<div style="font-style:italic">"<?php echo $row[4]?>"</div>
						<?php }else{?>
							<div style="font-style:italic"></div>
						<?php }?>
						<div class="invisible"><?php echo $row[0]?></div>
						<div class="invisible"><?php echo $row[7]?></div>
						<div class="invisible"><?php echo $row[2]?></div>
					</li>
				<?php } ?>
				</ul>
			</div>
		</div>
		<div class="col-xs-12 col-sm-8 col-md-9">
			<h1 style="margin:50px 0" class="color-<?php echo $a?>">Request</h1>
			<form id="request-form" action="query.php?action=add" method="POST" role="form" class="col-sm-12 col-md-10 col-md-offset-1">
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
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="nanoscroller.js"></script>
	<script type="text/javascript" src="bootstrap.js"></script>
	<script>
		var rand = <?php echo $a?>;
		var page = "req";
		var pre_title = "";
	</script>
	<!-- <script type="text/javascript" src="script.js"></script> -->
	<script>
		$(function() {
			refreshAjax();

			$(".nano").nanoScroller();

			if($("#list").children().length > 0){
				$('.title').css('background-image', 'url(' + $("#list").children().eq(0).children().eq(4).html() + ')');
				document.title = pre_title+"Rikues | #np " + $("#list").children().eq(0).children().eq(1).html();
			}

			$("#request-form").submit(function(e){
				e.preventDefault();
				var valid = true;
				$(this).find("input[type='text']").each(function(){
					if ($(this).val().length < 1){
						$(this).parents(".form-group").addClass("has-warning");
						valid = false;
					}
				})
				if(valid){
					$("#request-submit").val("loading...");
					$("#request-submit").attr("disabled","disabled");
					$("#request-submit").addClass("disabled");

					var url = getURL();
					console.log(url);
					
					getSongInfo(url);
				}
			})
		})

		function refreshAjax() {
			var last_id = 0;
			if($("#list").children().length > 0){
				last_id = $("#list").children().eq($("#list").children().length - 1).children().eq(3).html();
			}
			getPlaylist(last_id);
			setTimeout(function () { refreshAjax() }, 10000);
		}

		function getPlaylist(last_id) {
			var _url = "query.php?action=get&source="+page;
			if(page == "play"){
				_url += "&get="+last_id;
			}
			
			$.ajax({
				type: "GET",
				url: _url,
				success: function( response ) {
					if(page == "req") { // jika halaman req, kosongkan list
						$("#list").html("");
					}
					var result = JSON.parse(response);
					if(result.status == "success"){
						// insert request
						for(var j=0;j<result.result.length;j++) {
							console.dir(result);
							insertReqRow(result.result[j]);
						}
						// change  title and cover image
						if($("#list").children().length > 0) {
							$('.title').css('background-image', 'url(' + $("#list").children().eq(0).children().eq(4).html() + ')');
							document.title = pre_title+"Rikues | #np " + $("#list").children().eq(0).children().eq(1).html();
						}
						// play
						if(page == "play") {
							if(player.getPlayerState() <= 0 || $("#player").hasClass("invisible")) { // state idle
								playFromIdle();
							}
						}
						
					} else {
						if(page == "play")
							document.title = "Rikues | Player";
						else
							document.title = "Rikues | Request a Song";
					}
				}
			})
		}

		function insertReqRow(result) {
			var s = "";
			s += '<li data-id="'+result["song_id"]+'">';
			s += '<div class="requester color-'+rand+'">'+result["nama"]+'</div>';
			s += '<div class="vid-title">'+result["title"]+'</div>';
			if(result["salam"] != "")
				s += '<div style="font-style:italic">"'+result["salam"]+'"</div>';
			else
				s += '<div style="font-style:italic"></div>';
			s += '<div class="invisible">'+result["id"]+'</div>';
			s += '<div class="invisible">'+result["thumbnail"]+'</div>';
			s += '<div class="invisible">'+result["type"]+'</div>';
			s += '</li>';
			$("#list").append(s);
		}

		function playFromIdle() {
			$("#error").addClass("invisible")
			$("#player").removeClass("invisible")
			player.loadVideoById($("#list").children().eq(0).data("id"))
			player.playVideo();
			
			$("#title").html($("#list").children().eq(0).children().eq(1).html());
			$("#sender").html($("#list").children().eq(0).children().eq(0).html());
			$("#greet").html($("#list").children().eq(0).children().eq(2).html());
		}

		function getURL(){
			var url = "";
			var query = $('#request-form').find('input[name="video_query"]').val();
			if(query.indexOf("youtu.be") > -1) {
				var par = query.split("/"); 
				var id = par[par.length-1];
				url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id="+ id +"&key=AIzaSyCiiqy3dP4ktR2TNybQOr3M8z1Bz_0Nz3E"
			} else if(query.indexOf("youtube") > -1) {
				var id = getParameterByName(query, "v");
				url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id="+ id +"&key=AIzaSyCiiqy3dP4ktR2TNybQOr3M8z1Bz_0Nz3E"
			} else {
				url = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=" + $('#request-form').find('input[name="video_query"]').val() + "&type=video&key=AIzaSyCiiqy3dP4ktR2TNybQOr3M8z1Bz_0Nz3E";
			}
			return url;
		}

		function getParameterByName(url, name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(url);
			return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}

		function getSongInfo(url) {
			$.ajax({
				type: "GET",
				url: url,
				success: function( response ) {
					console.log(response)
					if((response.items[0].id.videoId == "" || response.items[0].id.videoId == null) &&
							(response.items[0].id == "" || response.items[0].id == null)) {
						$("#alert span").text("Request gagal :( Mari mencoba lagi!");
						$("#request-submit").val("Go!");
						$("#request-submit").removeAttr("disabled");
						$("#request-submit").removeClass("disabled");
						$("#alert").fadeIn();	
					} else {
						if (response.items[0].id.videoId == "" || response.items[0].id.videoId == null)
							$('#request-form').find('input[name="video_id"]').val(response.items[0].id);
						else 
							$('#request-form').find('input[name="video_id"]').val(response.items[0].id.videoId);
						$('#request-form').find('input[name="video_title"]').val(response.items[0].snippet.title);
						$('#request-form').find('input[name="video_thumbnail"]').val(response.items[0].snippet.thumbnails.medium.url);

						saveRequest();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$("#alert span").text("Failed to submit your request :( Let's try again!");
					$("#request-submit").val("Go!");
					$("#request-submit").removeAttr("disabled");
					$("#request-submit").removeClass("disabled");
					$("#alert").fadeIn();
				}
			})
		}

		function saveRequest(){
			$.ajax({
				type: "POST",
				url: $("#request-form").attr('action'),
				data: $("#request-form").serialize(),
				success: function( response ) {
					var result = JSON.parse(response);
					if(result.status == "success"){
						if(page == "req")
							getPlaylist();
						$("#alert span").text("Request successfully submitted!");
						$("#request-form").find(".clear-onsubmit").val("");
					}
					else{
						$("#alert span").text("Failed to submit your request :( Let's try again!");
					}
					$("#request-submit").val("Go!");
					$("#request-submit").removeAttr("disabled");
					$("#request-submit").removeClass("disabled");
					$("#alert").fadeIn();
				}
			})
		}
	</script>
	</body>
</html>