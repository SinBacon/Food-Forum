<!DOCTPYE html>
<html>
<head>
	<meta charset="utf-8">
	<title>會員系統</title>
	<link rel = "stylesheet" href="style.css">
</head>
<body>
<div class="top_block">
	<div class="top_block-child_element left">
		<h1 class= "font-fadeIn"> FlavorfulSphere </h1>
	</div>
</div>
<div class= "content">
	<h1 align="center">歡迎使用會員註冊系統</h1>
	<hr>
		<form method="post" action="db_signin.php" align="center">
				帳號: <input type="text" name = "userID"><br>
				密碼: <input type="password" name = "passwd"><br>
				<br/>
				<center><input type = "submit" class="button" value = "註冊"></center>
				<br/>
				<center><input type = "button" value = "回首頁" align="center" class="button" onclick="javascript:location.href = '../index.php' "></center>
		</form>
</div>
</body>
</html>