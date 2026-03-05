<!DOCTPYE html>

<?php 
	session_start();
	include("db_connect.inc.php");
	
	
	if (isset($_SESSION['UserID'])){
		$database = "flavorfulsphere";
		$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
		$UserID = $_SESSION['UserID'];
		$sql_str = "SELECT * FROM `users` WHERE `UserID` = '$UserID'";
		$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
		$check = mysqli_num_rows($res);
		if ($check == 1){
			echo '<meta http-equiv=REFRESH CONTENT=0;url=../login/db_login.php>';
		}else{
			unset($_SESSION['UserID']);
		}
	}
?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<title>FlavorfulSphere</title>
		<link rel = "stylesheet" href="style.css">
	</head>
	<body>
		<div class="top_block">
			<div class="top_block-child_element left">
				<h1 class= "font-fadeIn"> FlavorfulSphere </h1>
			</div>
			<div class="top_block-child_element right">
				<img class="profile-user" src="/image/profile-user.png" alt="">
				<a  href="../login/login.php" id="profile-user_text">Login</a>
			</div>
		</div>
		<div class="content">
			<div class="content-left"></div>
			<div class="content-right"></div>
		</div>
		
		<script src="script.js"></script>
		
	</body>
</html>

<? mysqli_close($con); ?>