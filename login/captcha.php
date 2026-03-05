<?php
    if(!isset($_SESSION)){ session_start(); } //檢查SESSION是否啟動
        $_SESSION['check_word'] = ''; //設置存放檢查碼的SESSION

    //設置定義為圖片
	ob_clean(); 
    header("Content-type: image/PNG");

    /*
      imgcode($nums,$width,$high)
      設置產生驗證碼圖示的參數
      $nums 生成驗證碼個數
      $width 圖片寬
      $high 圖片高
    */
    imgcode(5,200,50);

    //imgcode的function
    function imgcode($nums,$width,$high) {
       
        //去除了數字0和1 字母小寫O和L，為了避免辨識不清楚
        $str = "0123456789";
        $code = '';
        for ($i = 0; $i < $nums; $i++) {
            $code .= $str[mt_rand(0, strlen($str)-1)];
        }

        $_SESSION['check_word'] = $code;

        //建立圖示，設置寬度及高度與顏色等等條件
        $image = imagecreate($width, $high) ;
        $font_color = imagecolorallocate($image, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
        $border_color = imagecolorallocate($image, 21, 106, 235);
        $background_color = imagecolorallocate($image, 235, 236, 237);

        //建立圖示背景
        imagefilledrectangle($image, 0, 0, $width, $high, $background_color);

        //建立圖示邊框
        imagerectangle($image, 0, 0, $width-1, $high-1, $border_color);

        //在圖示布上隨機產生大量躁點
        for ($i = 0; $i < 300; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $high), $font_color);
        }
		for($i = 0; $i < 25 ; $i++){
			//設定線的顏色
			$linecolor = imagecolorallocate($image,rand(80,220), rand(80,220),rand(80,220));
			//設定線，兩點一線
			imageline($image,rand(1,$width), rand(1,$high),rand(1,$width), rand(1,$high),$linecolor);
		}

		$strx = [];
		for ($i = 0; $i < 5; $i++) {
			$min_strx = $i*($width-20)/$nums + 10;
			$max_strx = ($i+1)*($width-20)/$nums;
			while ($min_strx>=$max_strx){
				$max_strx = ($i+1)*$width/$nums;
			}
			$strx[] = mt_rand($min_strx, $max_strx); // 使用 mt_rand 生成隨機數字
		}
		// 按升序排序
		sort($strx);
		
		$font = file_get_contents("http://themes.googleusercontent.com/static/fonts/abel/v3/RpUKfqNxoyNe_ka23bzQ2A.ttf");
		file_put_contents("font.ttf", $font); //be sure to save the font in the path you have provided as font path.
		
		for ($i = 0; $i < $nums; $i++) {
			$strpos = rand(30, $high-5);
			imagettftext($image, 25, 0, $strx[$i], $strpos, $font_color, "font.ttf", substr($code, $i, 1));
		}

        imagepng($image);
        imagedestroy($image);
    }
?>
