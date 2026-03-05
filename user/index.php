<!DOCTPYE html>

<?php 
	session_start();
	include("../db_connect.inc.php");
	
	$UserName = "";
	
	if (isset($_SESSION['UserID'])){
		//所要選擇的「資料庫UserID」變數
		$database = "flavorfulsphere";
		//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
		$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
		$UserID = $_SESSION['UserID'];
		$sql_str = "SELECT * FROM `users` WHERE `UserID` = '$UserID'";
		$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
		$row = mysqli_fetch_assoc($res);
		$check = mysqli_num_rows($res);
		if ($check != 1){
			header("Location: ../index.php");
		}else{
			$UserName = htmlspecialchars($row['Username'], ENT_QUOTES);
		}
	} else {
		header("Location: ../index.php");
	}
	if(isset($_GET['x'])) {
    $x = $_GET['x'];
	}else{
		$x =1;
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
		<div  class="insert_block">
			<img id="insert_button" src="/image/insert.png" alt="">
		</div>
		<div id="error_block">
			<img  id="error_pt" src="/image/why-unscreen.gif" alt="">
			<p  id="error_countdown"> 發生錯誤 !</p>
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
			<!-- index.php -->
		<div class="content-left" style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 300px;">
    <div>
        <form action="index.php" method="GET">
            <button type="submit" name="x" value="1" style="background-color: #3498db; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer;">時間排序</button>
        </form>
    </div>
    <div style="margin-top: 100px;margin-bottom: 150px;">
        <form action="index.php" method="GET">
            <button type="submit" name="x" value="0" style="background-color: #2ecc71; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer;">喜好排序</button>
        </form>
    </div>
	 <img src="佛跳牆.gif" alt="佛跳牆" style="margin-top: 50px; max-width: 100%; height: auto;">
</div>
			<div class="content-right">
			</div>
		</div>
		<div id="myElement" data-x="<?php echo $x; ?>"></div>
		<script src="script.js"></script>
	</body>
</html>

<? mysqli_close($con); ?>