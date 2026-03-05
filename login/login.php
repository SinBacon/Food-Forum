<!DOCTPYE html>
<?php session_start();?>
<html>
<head>
	<meta charset="utf-8">
		<title>FlavorfulSphere</title>
		<link rel = "stylesheet" href="style.css">
	<script>
		function refresh_code(){ 
			document.getElementById("imgcode").src="captcha.php"; 
		} 
	</script>
</head>
<?php
	if (!extension_loaded('gd') || !function_exists('gd_info')) {
			echo "<script>";
				echo "alert('GD庫未啟用，無法產生驗證碼 !')";
			echo "</script>";
		} 

	include("../db_connect.inc.php");
	if(isset($_SESSION['UserID'])){
		if($_SESSION['UserID'] != null)
		{
			$database = "flavorfulsphere";
			$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
		
			$UserID = $_SESSION['UserID'];
			$sql_str = "SELECT * FROM `users` WHERE `UserID` = '$UserID'";
			$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");

			$check = mysqli_num_rows($res);
			if ($check == 1){
				echo '<meta http-equiv=REFRESH CONTENT=0;url=db_login.php>';
			}
			else{
				unset($_SESSION['UserID']);
			}
		}
	}
?>
<body>
    <br/>
    <div class="top-container">
		<div class="left-element">
		<h1  class="left-caption" onclick="javascript:location.href = '../index.php' ">FlavorfulSphere</h1>
		<script src="script.js"></script>
		</div>
		<div class="right-element">
        <input type="button" value="Home" class="home_button" onclick="javascript:location.href = '../index.php' ">
		<input type="button" value="Sing In" class="singin_button" onclick="javascript:location.href = '../signin/signin.php' ">
		</div>
    </div>
	<br/>
	<center><div class="login_block">
    <h1 class="title-text">Login</h1>
	<p class="content-text"></p>
    <form method="post" action="db_login.php" align="center">
			<div class="write_container">
				<input type="text" name = "userID" id="userID" placeholder="帳號 (ID)" onfocus="this.placeholder=''" onblur="this.placeholder='帳號 (ID)'">
				<input type="password" name = "password" id="password"  placeholder="密碼 (Password)" onfocus="this.placeholder=''" onblur="this.placeholder='密碼 (Password)'">
			</div>
			<p>
				<span class="captcha-container">
					<img id="imgcode" src="captcha.php" onclick="refresh_code()" alt="Captcha Image" /></br>
					點擊圖片更換驗證碼
				</span>
				<input type="text" name = "check_word" id="check_word" placeholder="驗證碼" onfocus="this.placeholder=''" onblur="this.placeholder='驗證碼'">
			</p>
			<br/>
			<center><input type = "submit" class="login_button" value = "Log In" align="center" class="button"></center>
    </form>
  </div></center>
  <br/>
<? mysqli_close($con); ?>
</body>
</html>