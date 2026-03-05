<?php
session_start();
include("db_connect.inc.php");

if (isset($_SESSION['UserID'])) {
    $database = "flavorfulsphere";
    $db_select = mysqli_select_db($con, $database) or die("資料庫選擇失敗");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['UserID'];
        $title = $_POST['user_input'];
        $meal_type = $_POST['meal_type'];
        $content = $_POST['user_input2'];
        $timestamp = date("Y-m-d H:i:s");

        $sql_str = "INSERT INTO post (UserID, Title, Content, Timestamp, hashtag)
            VALUES ('$user_id', '$title', '$content', '$timestamp', '$meal_type')";

        if ($con->query($sql_str) === TRUE) {
            echo "新紀錄插入成功";
        } else {
            echo "錯誤：" . $sql_str . "<br>" . $con->error;
        }
    }
}
mysqli_close($con);

?>