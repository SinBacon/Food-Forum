<?php
	session_start();
	header("Content-Type:text/html; charset='utf-8'");
	//引入連線檔案
	include("../db_connect.inc.php");

	//所要選擇的「資料庫UserID」變數
	$database = "flavorfulsphere";

	//選擇資料庫，失敗的話則顯示「資料庫選擇失敗」
	$db_select = mysqli_select_db($con , $database) or die("資料庫選擇失敗");
	
	if (isset($_SESSION['UserID'])){
		$UserID = $_SESSION['UserID'];
		$sql_str = "SELECT * FROM `users` WHERE `UserID` = '$UserID'";
		$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
		//計算資料筆數
		$check = mysqli_num_rows($res);
		if ($check == 1){
			login_successful($UserID);
		}else{
			echo '您無權限觀看此頁面!';
			echo '<meta http-equiv=REFRESH CONTENT=1;url=../index.php>';
		}
	}else{
		if((!empty($_SESSION['check_word'])) && (!empty($_POST['check_word']))){
			$_SESSION['input_data'] = $_POST; // 保存輸入資料
			if($_SESSION['check_word'] == $_POST['check_word']){
				unset($_SESSION['check_word']); //比對正確後，清空將check_word值
				unset($_SESSION['input_data']);
			}else{
				echo "<script>";
					echo "alert('您輸入的驗證碼錯誤 !')";
				echo "</script>";
				header("refresh:0;url = login.php");
				exit();
			}
		}else{
			echo "<script>";
				echo "alert('您尚未輸入的驗證碼 !')";
			echo "</script>";
			header("refresh:0;url = login.php");
			exit();
		}
		//SQL語法，選擇資料表「student」撈出所有資料
		$userID = $_POST['userID'];
		$password = $_POST['password'];
		$sql_str = "select * from `users` where `UserID` = '$userID' and `Password` = '$password'";
		
		try {
			$res = mysqli_query($con, $sql_str);
			if (!$res) {
				throw new Exception("SQL語法錯誤: " . mysqli_error($con));
			}
			$res = mysqli_query($con , $sql_str) or die("SQL語法錯誤");
			$row = mysqli_fetch_assoc($res);

			//計算資料筆數
			$check = mysqli_num_rows($res);
			if ($check == 0 && $_POST['userID'] != NULL){
				echo "<script>";
					echo "alert('您輸入的帳號或密碼錯誤 !')";
				echo "</script>";
				header("refresh:0;url = login.php");
			}
			else if ($_POST['userID'] != NULL){
				echo "<script>";
					echo "alert('登入成功')";
				echo "</script>";
			
				login_successful($userID);
			}
			else{
				header("refresh:0;url = login.php");
			}
		} catch (Exception $e) {
			session_destroy();
			echo '<meta http-equiv=REFRESH CONTENT=0;url=../index.php>';
		}
	}
	function login_successful($UserID) {
		$_SESSION['UserID'] = $UserID;
		echo '<meta http-equiv=REFRESH CONTENT=0;url=../user/index.php>';
	}
	mysqli_close($con); 
?>