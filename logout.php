<?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
//將session清空
session_destroy();
echo "<script>";
	echo "alert('登出成功')";
echo "</script>";
echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
?>