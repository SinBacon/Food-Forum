<!DOCTPYE html>

<?php 
	session_start();
	include('../db_connect.inc.php');
	
	
	if (isset($_SESSION['UserID'])){
		//所要選擇的「資料庫UserID」變數
		$database = "flavorfulsphere";
		//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
		$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
		$user_id = $_SESSION['UserID'];
		$sql_str = "SELECT * FROM `users` WHERE `UserID` = '$user_id'";
		$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
		$row = mysqli_fetch_assoc($res);
		$check = mysqli_num_rows($res);
		if ($check != 1){
			echo '<meta http-equiv=REFRESH CONTENT=0;url=../index.php>';
		}else{
			$UserName = $row['Username'];
		}
	} else {
		header("Location: ../index.php");
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<title>AddNewPost</title>
		<link rel = "stylesheet" href="put_up_style.css">
	</head>
	<body>
		<div id="error_block">
			<img  id="error_pt" src="/image/why-unscreen.gif" alt="">
			<p  id="error_countdown"> 發生錯誤 !</p>
		</div>
		<div id="loading_block">
			<img  id="loading_pt" src="/image/message-unscreen.gif" alt="">
		</div>
		<div class="top_block">
			<div class="top_block-child_element left">
				<h1 class= "font-fadeIn"> FlavorfulSphere </h1>
			</div>
			<div class="top_block-child_element right">
				<img class="profile-user" src="/image/profile-user.png" alt="">
				<a  href="../logout.php" id="profile-user_text"><?php echo $UserName;?></a>
			</div>
		</div>
		<div class="content">
			<div class="content-left">
			<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
			<div id="map"></div>
				<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
				<script src="https://unpkg.com/jquery"></script>
			</div>
			<div class="content-right">
				<div class ="content_view_block">
					<div class="content-section">
						<input type="text" name = "title" id="title_area" placeholder="標題" onfocus="this.placeholder=''" onblur="this.placeholder='標題'">
						<div id="food_info">
						    <input type="text" name = "food" id="food_area" placeholder="食物名稱" onfocus="this.placeholder=''" onblur="this.placeholder='食物名稱'">
							<select id="meal_type" name="meal_type">
								<option value="早餐">早餐</option>
								<option value="午餐">午餐</option>
								<option value="晚餐">晚餐</option>
								<option value="點心">點心</option>
								<option value="其他">其他</option>
							</select>
							<select id="star_type" name="star_type">
								<option value="5">美味 </option>
								<option value="4">不錯</option>
								<option value="3">一般</option>
								<option value="2">可接受</option>
								<option value="1">不喜歡</option>
							</select>
						</div>
						<input type="number" name = "price" id="price_area" placeholder="價格 ( NTD )" onfocus="this.placeholder=''" onblur="this.placeholder='價格 ( NTD )'">
						<div id="location_info">
							<div id="location_info_info">
								<img src="/image/place.png" alt="">
								&nbsp;
								<div><p id="location_text">None</p></div>
								&nbsp;&nbsp;
								<input type="hidden" id="location_data" name="location_data" value="None">
								<input type="button" id="location_clear"  value="Clear">
							</div>
						</div>
						<script src="test.js"></script>
						<div id="search_location_area">
							<input type="text" id="location_name_text" placeholder="地點名稱" onfocus="this.placeholder=''" onblur="this.placeholder='地點名稱'"></text>
							<div id="search_location_button"><img src="/image/search-location.png" alt=""></div>
						</div>
						<div id="suggestions"></div>
						<div id="content_area_parent">
							<div id="content_area_dummy"></div>
							<textarea id="content_area" oninput="document.getElementById('content_area_dummy').textContent = this.value" placeholder="輸入內容" onfocus="this.placeholder=''" onblur="this.placeholder='輸入內容'"></textarea>
						</div>
						<div id="insert_button" >
							<img src="/image/send_message-ck.png" alt="">
						</div>
						<input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ?>">
					</div>
				</div>
			</div>
		</div>
		<script src="put_up_script.js"></script>
	</body>
</html>

<?php 
	mysqli_close($con); 
?>