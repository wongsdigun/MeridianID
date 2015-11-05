<?php 
	include_once 'db.php';
	if($_GET["action"] == "test"){
		$vid = $_GET["q"];
		$request = "https://www.googleapis.com/youtube/v3/search?part=snippet&q=" . $vid . "&type=video&key=AIzaSyCiiqy3dP4ktR2TNybQOr3M8z1Bz_0Nz3E";
		$response  = file_get_contents($request);
		$jsonobj  = json_decode($response);
		echo $jsonobj;

	} else if($_GET["action"] == "add"){
		$nama = $_POST["nama"];
		$vid = $_POST["video_id"];
		$title = mb_convert_encoding($_POST["video_title"],'HTML-ENTITIES','UTF-8');
		$thumbnail = $_POST["video_thumbnail"];
		$pesan = nl2br($_POST["pesan"]);
		
		if(strpos($vid,'youtu.be') !== false) {
			$p = explode('/',$vid);
			$vid = $p[count($p) - 1];
		}
		else if(strpos($vid,'youtube') !== false) {
			parse_str( parse_url( $vid, PHP_URL_QUERY ), $var_arr );
			$vid = $var_arr['v'];
		}
		
		$query = "INSERT INTO songlist (nama,song_id,salam,title,thumbnail) VALUES ('".addslashes($nama)."','".$vid."','".addslashes($pesan)."','".addslashes($title)."','".$thumbnail."')";
		
		$result = mysqli_query($conn,$query);
		if($result)
			echo json_encode(array("status"=>'success'));
		else
			echo json_encode(array("status"=>'failed'));
	} else if($_GET["action"] == "get"){
		
		$query = "";
		if($_GET["source"] == "req") {
			$query .= "SELECT * FROM songlist WHERE is_played = 0";
		} else {
			$id = $_GET["get"];
			if($id == -1) 
				$query .= "SELECT * FROM songlist WHERE is_played = 0 LIMIT 5";
			else
				$query .= "SELECT * FROM songlist WHERE is_played = 0 AND id > $id LIMIT 5";
		}
		
		$result = mysqli_query($conn,$query);
		$rows = array();
		while($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}
		if(count($rows) > 0)
			echo json_encode(array("status"=>'success', "result"=>$rows));
		else
			echo json_encode(array("status"=>'failed'));
	} else if($_GET["action"] == "played"){
		$id = $_GET["played"];
		$query = "UPDATE songlist SET is_played=1 WHERE id = " . $id;
		
		$result = mysqli_query($conn,$query);
		if($result)
			echo json_encode(array("status"=>'success'));
		else
			echo json_encode(array("status"=>'failed'));
	}
?>