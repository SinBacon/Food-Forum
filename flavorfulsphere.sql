-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-12-23 13:14:35
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `flavorfulsphere`
--

-- --------------------------------------------------------

--
-- 資料表結構 `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Content` text NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `comment`
--

INSERT INTO `comment` (`CommentID`, `PostID`, `UserID`, `Content`, `Timestamp`) VALUES
(1, 204, 1, 'XD', '2023-12-23 12:10:38');

-- --------------------------------------------------------

--
-- 資料表結構 `food`
--

CREATE TABLE `food` (
  `FoodID` int(11) NOT NULL,
  `Ingredients` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Foodname` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Price` int(11) NOT NULL DEFAULT 0,
  `PostID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- 傾印資料表的資料 `food`
--

INSERT INTO `food` (`FoodID`, `Ingredients`, `Foodname`, `Description`, `Price`, `PostID`) VALUES
(18, '鵝肉', '鵝肉', '5', 200, 201),
(19, 'g', 'g', '5', 66, 202),
(20, '鵝肉', '鵝肉', '5', 200, 203),
(21, '鵝肉', '鵝肉', '5', 200, 204),
(22, 'gggg', 'gggg', '5', 555, 205);

-- --------------------------------------------------------

--
-- 資料表結構 `like`
--

CREATE TABLE `like` (
  `LikeID` int(11) NOT NULL,
  `PostID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `hashtag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `like`
--

INSERT INTO `like` (`LikeID`, `PostID`, `UserID`, `Timestamp`, `hashtag`) VALUES
(1, 204, 1, '2023-12-23 12:10:30', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `location`
--

CREATE TABLE `location` (
  `Locationname` text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Longitude` double NOT NULL,
  `Latitude` double NOT NULL,
  `FoodID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- 傾印資料表的資料 `location`
--

INSERT INTO `location` (`Locationname`, `Longitude`, `Latitude`, `FoodID`) VALUES
('雲林縣', 120.5245511, 23.6990775, 18),
('雲林縣', 120.5245511, 23.6990775, 20),
('雲林縣', 120.5245511, 23.6990775, 21),
('臺灣 31061 新竹市 東區 金山里 金山東街', 121.02829519874227, 24.776557240453304, 22);

-- --------------------------------------------------------

--
-- 資料表結構 `post`
--

CREATE TABLE `post` (
  `PostID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Title` varchar(32) NOT NULL,
  `Content` text NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `post`
--

INSERT INTO `post` (`PostID`, `UserID`, `Title`, `Content`, `Timestamp`) VALUES
(201, 2, '鵝肉育成率減價格上揚 業者估要到明年清明節後才回穩', '是國內重要養鵝地區，先前受到天熱、種鵝無法進口致近親繁殖等因素影響，國內種鵝育成率低，肉鵝減產逾3成，產地毛鵝每台斤從往年同期60元漲到70元，市售鵝肉每公斤150元漲到200元，中華民國水禽產業促進協會理事長吳祥斌表示，肉鵝供應量最快明年清明後回穩，短期間價格難以回穩。\n\nhttps://udn.com/news/story/7326/7660911?from=udn-catebreaknews_ch2\n2023-12-23 14:11 聯合報／ 記者\n陳雅玲\n／雲林即時報導', '2023-12-23 07:08:28'),
(202, 2, 'g', 'g\ng\ng\ng', '2023-12-23 07:16:56'),
(203, 2, '鵝肉育成率減價格上揚 業者估要到明年清明節後才回穩', '雲林縣是國內重要養鵝地區，先前受到天熱、種鵝無法進口致近親繁殖等因素影響，國內種鵝育成率低，肉鵝減產逾3成，產地毛鵝每台斤從往年同期60元漲到70元，市售鵝肉每公斤150元漲到200元，中華民國水禽產業促進協會理事長吳祥斌表示，肉鵝供應量最快明年清明後回穩，短期間價格難以回穩。\n\n中華民國水禽產業促進協會今天在古坑鄉綠色隧道行銷國產鵝肉、鴨肉產品，理事長吳祥斌表示，近半年天氣持續炎熱，造成種鵝不生蛋，加上2019年起法國因禽流感疫情影響，台灣無法從該地進口，導致種鵝近親繁殖，育成率明顯下降，目前全台肉鵝減產逾3成。\n\n吳祥斌指出，國內肉鵝正常每日交易量約2.5萬至3萬隻，目前僅剩2萬隻，明顯減少，加上日前嘉義縣有種鵝場因禽流感撲殺1.2萬隻鵝，對肉鵝供應不無影響，目前產地毛鵝（屠宰前）每台斤70元，市售鵝肉（屠宰後）每公斤從150元漲到200元，估計最快明年清明節後產量才能回穩。https://udn.com/news/story/7326/7660911?from=udn-catebreaknews_ch2\n2023-12-23 14:11 聯合報／ 記者 陳雅玲／雲林即時報導', '2023-12-23 07:24:55'),
(204, 1, '鵝肉育成率減價格上揚 業者估要到明年清明節後才回穩', '雲林縣是國內重要養鵝地區，先前受到天熱、種鵝無法進口致近親繁殖等因素影響，國內種鵝育成率低，肉鵝減產逾3成，產地毛鵝每台斤從往年同期60元漲到70元，市售鵝肉每公斤150元漲到200元，中華民國水禽產業促進協會理事長吳祥斌表示，肉鵝供應量最快明年清明後回穩，短期間價格難以回穩。\n\n中華民國水禽產業促進協會今天在古坑鄉綠色隧道行銷國產鵝肉、鴨肉產品，理事長吳祥斌表示，近半年天氣持續炎熱，造成種鵝不生蛋，加上2019年起法國因禽流感疫情影響，台灣無法從該地進口，導致種鵝近親繁殖，育成率明顯下降，目前全台肉鵝減產逾3成。\n\n吳祥斌指出，國內肉鵝正常每日交易量約2.5萬至3萬隻，目前僅剩2萬隻，明顯減少，加上日前嘉義縣有種鵝場因禽流感撲殺1.2萬隻鵝，對肉鵝供應不無影響，目前產地毛鵝（屠宰前）每台斤70元，市售鵝肉（屠宰後）每公斤從150元漲到200元，估計最快明年清明節後產量才能回穩。\n\nhttps://udn.com/news/story/7326/7660911?from=udn-catebreaknews_ch2\n2023-12-23 14:11 聯合報／ 記者 陳雅玲／雲林即時報導', '2023-12-23 12:10:16'),
(205, 1, 'gggg', 'gggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg', '2023-12-23 12:12:55');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Nickname` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `Password`, `Nickname`) VALUES
(0, 'User0', 'user0@example.com', '1234', ''),
(1, 'User1', 'user1@example.com', '1234', ''),
(2, 'User2', 'user2@example.com', '1234', ''),
(3, 'User3', 'user3@example.com', '1234', ''),
(4, 'User4', 'user4@example.com', '1234', ''),
(5, 'User5', 'user5@example.com', '1234', ''),
(11, '11', '11@gmail.com', '111', '');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- 資料表索引 `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`FoodID`),
  ADD KEY `PostID` (`PostID`);

--
-- 資料表索引 `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`LikeID`),
  ADD KEY `PostID` (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- 資料表索引 `location`
--
ALTER TABLE `location`
  ADD KEY `FoodID` (`FoodID`);

--
-- 資料表索引 `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`PostID`),
  ADD KEY `UserID` (`UserID`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `food`
--
ALTER TABLE `food`
  MODIFY `FoodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `like`
--
ALTER TABLE `like`
  MODIFY `LikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `post`
--
ALTER TABLE `post`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `post` (`PostID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- 資料表的限制式 `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `post` (`PostID`);

--
-- 資料表的限制式 `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`PostID`) REFERENCES `post` (`PostID`),
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- 資料表的限制式 `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `location_ibfk_1` FOREIGN KEY (`FoodID`) REFERENCES `food` (`FoodID`);

--
-- 資料表的限制式 `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
