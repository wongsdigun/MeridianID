var pre_title = "";
if(page == "play")
	pre_title = "[\u25b6] ";

refreshAjax();
function refreshAjax() {
	var last_id = 0;
	if($("#list").children().length > 0){
		last_id = $("#list").children().eq($("#list").children().length - 1).children().eq(3).html();
	}
	getPlaylist(last_id);
	setTimeout(function () { refreshAjax() }, 10000);
}

function getParameterByName(url, name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(url);
	return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

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

		var url = "";
		var query = $('#request-form').find('input[name="video_query"]').val();
		if(query.indexOf("youtu.be") > -1) {
			var par = query.split("/"); 
			var id = par[par.length-1];
			url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id="+ id +"&key=AIzaSyCiiqy3dP4ktR2TNybQOr3M8z1Bz_0Nz3E"
			console.log("masuk youtu.be, id => " + id);
		} else if(query.indexOf("youtube") > -1) {
			var id = getParameterByName(query, "v");
			url = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id="+ id +"&key=AIzaSyCiiqy3dP4ktR2TNybQOr3M8z1Bz_0Nz3E"
			console.log("masuk youtube.com, id => " + id);
		} else {
			url = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=" + $('#request-form').find('input[name="video_query"]').val() + "&type=video&key=AIzaSyCiiqy3dP4ktR2TNybQOr3M8z1Bz_0Nz3E";
			console.log("masuk query, url => " + url);
		}
		
		$.ajax({
			type: "GET",
			url: url,
			success: function( response ) {
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
})

function getPlaylist(last_id) {
	var _url = "query.php?action=get&source="+page;
	if(page == "play"){
		_url += "&get="+last_id;
	}
	
	$.ajax({
		type: "GET",
		url: _url,
		success: function( response ) {
			console.log(response)
			if(page == "req")
				$("#list").html("");
			var result = JSON.parse(response);
			if(result.status == "success"){
				for(var j=0;j<result.result.length;j++){
					console.dir(result);
					var s = "";
					s += '<li data-id="'+result.result[j]["song_id"]+'">';
					s += '<div class="requester color-'+rand+'">'+result.result[j]["nama"]+'</div>';
					s += '<div class="vid-title">'+result.result[j]["title"]+'</div>';
					if(result.result[j]["salam"] != "")
						s += '<div style="font-style:italic">"'+result.result[j]["salam"]+'"</div>';
					else
						s += '<div style="font-style:italic"></div>';
					s += '<div class="invisible">'+result.result[j]["id"]+'</div>';
					s += '<div class="invisible">'+result.result[j]["thumbnail"]+'</div>';
					s += '</li>';
					$("#list").append(s);
				}
				
				if($("#list").children().length > 0){
					$('.title').css('background-image', 'url(' + $("#list").children().eq(0).children().eq(4).html() + ')');
					document.title = pre_title+"Rikues | #np " + $("#list").children().eq(0).children().eq(1).html();
				}
				
				if(page == "play"){
					if(player.getPlayerState() <= 0 || $("#player").hasClass("invisible")){
						$("#error").addClass("invisible")
						$("#player").removeClass("invisible")
						player.loadVideoById($("#list").children().eq(0).data("id"))
						player.playVideo();
						
						$("#title").html($("#list").children().eq(0).children().eq(1).html());
						$("#sender").html($("#list").children().eq(0).children().eq(0).html());
						$("#greet").html($("#list").children().eq(0).children().eq(2).html());
					}
				}
				
			}
			else{
				if(page == "play")
					document.title = "Rikues | Player";
				else
					document.title = "Rikues | Request a Song";
			}
		}
	})
}

SC.initialize({
  client_id: '69177104f29d85e04a6e7b1586370164'
});

$(document).ready(function() {
	var widgetIframe = document.getElementById('sc-widget')
	var widget       = SC.Widget(widgetIframe);

	widget.load("https://soundcloud.com/chadborghini/earned-it-the-weeknd", {
      auto_play : true
    });
    widget.bind(SC.Widget.Events.READY, function() {

      	widget.bind(SC.Widget.Events.PLAY, function() {
        	// get information about currently playing sound
	        widget.getCurrentSound(function(currentSound) {
          		console.log('sound ' + currentSound.get('') + 'began to play');
	        });
      	});
    });
});

// if(page == "play"){
// 	var tag = document.createElement('script');
// 	tag.src = "https://www.youtube.com/iframe_api";
// 	var firstScriptTag = document.getElementsByTagName('script')[0];
// 	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 	var player;
// 	var _h = '390'
// 	var _w = "80%";
// 	if(window.innerWidth <= 480){
// 		_h = '180';
// 		_w = '100%';
// 	}

// 	function onYouTubeIframeAPIReady() {
// 		player = new YT.Player('player', {
// 				height: _h,
// 				width: _w,
// 				videoId: '64f2tQNOkkg',
// 				events: {
// 				'onReady': onPlayerReady,
// 				'onStateChange': onPlayerStateChange,
// 				'onError': onErrorOccured
// 			}
// 		});
// 	}

// 	function next() {
// 		$("#error").addClass("invisible")
// 		$("#player").removeClass("invisible")
// 		updatePlaylist(function() {
// 			player.loadVideoById($("#list").children().eq(0).data("id"))
// 			player.playVideo();	
// 		});
// 	}

// 	if($("#list").children().length > 0 && $("#list").children().eq(0).data("id") == "") next();

// 	function onErrorOccured(event) {
// 		// updatePlaylist(function() {
// 		$("#error").removeClass("invisible")
// 		$("#player").addClass("invisible")
// 		openNewBackgroundTab($("#list").children().eq(0).data("id"));
// 		// });
// 	}

// 	function openNewBackgroundTab(id){
// 		var a = document.createElement("a");
// 		a.href = "http://www.youtube.com/watch?v=" + id;
// 		var evt = document.createEvent("MouseEvents");
// 		//the tenth parameter of initMouseEvent sets ctrl key
// 		evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0,
// 									true, false, false, false, 0, null);
// 		a.dispatchEvent(evt);
// 	}

// 	function onPlayerReady(event) {
// 		console.log("masuk ready" + $("#list").children().eq(0).data("id"))
// 		if($("#list").children().length > 0) {
// 			player.loadVideoById($("#list").children().eq(0).data("id"))
// 			player.playVideo();
// 		} else {
// 			// player.stopVideo();
// 			$("#error").removeClass("invisible")
// 			$("#player").addClass("invisible")
// 		}
// 	}

// 	var done = false;
// 	function onPlayerStateChange(event) {
// 		if (event.data == YT.PlayerState.ENDED ){
// 			updatePlaylist(function() {
// 				player.loadVideoById($("#list").children().eq(0).data("id"))
// 				player.playVideo();
// 				// openNewBackgroundTab($("#list").children().eq(0).data("id"));		
// 			});
// 		}
// 	}
// 	function updatePlaylist(callback) {
// 		$("#list").children().eq(0).slideUp(400, function(){
// 			$("#list").children().eq(5).slideDown();
			
// 			$.ajax({
// 				type: "GET",
// 				url: "query.php?action=played&played=" + $("#list").children().eq(0).children().eq(3).html(),
// 				success: function( response ) {
// 					var last_id = $("#list").children().eq($("#list").children().length - 1).children().eq(3).html();
// 					var result = JSON.parse(response);
// 					$("#list").children().eq(0).remove();
// 					if(result.status == "success")
// 						getPlaylist(last_id);
// 					if($("#list").children().length > 0) {
// 						if($("#list").children().eq(0).data("id") == "") next();
// 						else {
// 							document.title = "[\u25b6] Rikues | #np " + $("#list").children().eq(0).children().eq(1).html();
// 							$("#title").html($("#list").children().eq(0).children().eq(1).html());
// 							$("#sender").html($("#list").children().eq(0).children().eq(0).html());
// 							$("#greet").html($("#list").children().eq(0).children().eq(2).html());
// 							$('.title').css('background-image', 'url(' + $("#list").children().eq(0).children().eq(4).html() + ')');
// 							callback();
// 						}		
// 					}
// 					else{
// 						document.title = "Rikues | Player";
// 						$("#title").html("");
// 						$("#sender").html("Request a song <a href='req.php' target='_blank'>here</a>");
// 						$("#greet").html("");
// 						$('.title').css('background-image', 'url(' + $("#list").children().eq(0).children().eq(4).html() + ')');
// 					}
// 				}
// 			})
// 		});
// 	}
// 	function stopVideo() {
// 		player.stopVideo();
// 	}

// 	$(".req-button").click(function(){
// 		var _tgt = $(this).data("target");
		
// 		$("#"+_tgt).stop().toggleClass("opened");
// 		$("#"+_tgt).find(".req-form").stop().slideToggle();
		
// 		$("#request-form").find(".clear-onsubmit").val("");
// 	})
// }