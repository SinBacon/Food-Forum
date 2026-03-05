# This is a sample Python script.
from googleapiclient.errors import HttpError


# Press Shift+F10 to execute it or replace it with your code.
# Press Double Shift to search everywhere for classes, files, tool windows, actions, and settings.


def print_hi(name):
    # Use a breakpoint in the code line below to debug your script.
    print(f'Hi, {name}')  # Press Ctrl+F8 to toggle the breakpoint.


# Press the green button in the gutter to run the script.
if __name__ == '__main__':
    import mysql.connector
    import re
    from googleapiclient.discovery import build
    from bs4 import BeautifulSoup
    import requests
    num = 0
    API_KEY = 'AIzaSyARGhmvVSGZYNNjxeYq-gRYSQP7E6h5Sv8'
    # 建立 YouTube API 服務
    youtube = build('youtube', 'v3', developerKey=API_KEY)
    # 取得七天前的日期
    from datetime import datetime, timedelta

    # 取得七天前的日期
    seven_days_ago = (datetime.now() - timedelta(days=7)).isoformat() + 'Z'
    today_date = datetime.today().strftime('%Y-%m-%dT%H:%M:%SZ')
    content_info = ''  # 在這裡初始化 content_info 變數
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="flavorfulsphere"
    )

    # 建立游標
    mycursor = mydb.cursor()
    def escape_html(text):
        html_escape_table = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            "\"": "&quot;",
            "'": "&#039;"
        }
        return re.sub(r'[&<>"\']', lambda m: html_escape_table[m.group()], text)


    def analyze_videos():
        content_info = ''  # 在這裡初始化 content_info 變數
        try:
            # 初始化變數
            videos = []
            next_page_token = None

            # 循環進行分頁查詢，直到沒有下一頁為止
            while True:
                # 執行搜尋影片的 API 呼叫
                response = youtube.search().list(
                    part='snippet',
                    q='美食',
                    type='video',
                    publishedAfter=seven_days_ago,
                    publishedBefore=today_date,
                    maxResults=50,
                    pageToken=next_page_token
                ).execute()

                # 獲取搜尋結果中的影片資訊
                videos.extend(response['items'])

                # 檢查是否還有下一頁
                next_page_token = response.get('nextPageToken')
                if not next_page_token:
                    break

            print(f"過去七天有 {len(videos)} 個包含 '美食' 的影片：")

            # 初始化變數
            video_data = []
            video_ids = set()

            for video in videos:
                video_title = video['snippet']['title']
                video_id = video['id']['videoId']

                if video_id in video_ids:
                    continue

                video_ids.add(video_id)

                try:
                    # 執行影片資訊的 API 呼叫，獲取觀看次數
                    video_response = youtube.videos().list(
                        part='statistics',
                        id=video_id
                    ).execute()

                    if 'items' in video_response and len(video_response['items']) > 0:
                        view_count = int(video_response['items'][0]['statistics'].get('viewCount', 0))
                    else:
                        view_count = 0
                except KeyError:
                    view_count = 0

                video_data.append({
                    'title': video_title,
                    'videoId': video_id,
                    'viewCount': view_count
                })

            # 根據觀看次數進行排序
            sorted_videos = sorted(video_data, key=lambda x: x['viewCount'], reverse=True)
            print("觀看次數前 10 名的影片：")
            for i in range(10):
                video = sorted_videos[i]
                video_title = video['title']
                video_id = video['videoId']
                view_count = video['viewCount']
                print(f"{i + 1}. 影片標題：{video_title}，影片ID：{video_id}，觀看次數：{view_count}")
        except HttpError as e:
            print(f"發生錯誤：{e}")
            content_info = '當你思考今天該煮什麼美味佳餚時，或者在追尋當下最潮流的美食風潮時，若感到困惑不已，別擔心！就讓我們一同瀏覽近期在 YouTube 上引起熱烈討論的美食影片排行榜，獲得靈感吧！讓這些精彩的影片啟發你，為你的下一頓大餐找到靈感與創意！\n\n以下是最近引起熱烈迴響的美食影片排行榜，讓你一窺最新美食趨勢\n\n'  # 儲存所有影片資訊的字串

            for i in range(10):  # 取前10名影片資訊
                video = sorted_videos[i]
                video_title = video['title']
                view_count = video['viewCount']
                # 在標題中加入日期
                post_title = f"{datetime.today().strftime('%Y/%m/%d')}的YOUTUBE美食話題流量排名前10名"

                # 將影片資訊附加到 content_info 變數中
                content_info += f"{video_title}，觀看次數：{view_count}\n\n"
        try:
            content_info += "\n來到這裡，歡迎大家盡情分享你個人鍾愛的美食或獨門食譜！無論是對這些影片的看法，或者想要分享自己獨特的美食心得，都歡迎你發佈貼文。讓我們一同探索，開啟嶄新的美食世界，將味蕾帶入全新的冒險之旅！"
            # 建立並執行 INSERT 語句
            sql = "INSERT INTO post (UserID, Title, Content) VALUES (%s, %s, %s)"
            mycursor.execute(sql, (0, post_title, escape_html(content_info)))
            mydb.commit()

            if mycursor.rowcount > 0:
                print("成功插入資料")
            else:
                print("沒有插入任何資料")

        except mysql.connector.Error as error:
            print("資料庫錯誤：", error)
        finally:
            if mydb.is_connected():
                mycursor.close()
                mydb.close()
                print("資料庫連接已關閉")

    # 在這裡放入美食影片分析的程式碼

    def analyze_shorts():
        content_info = ''  # 在這裡初始化 content_info 變數
        search_response = youtube.search().list(
            q='美食',  # Search query for Shorts related to "美食" (food)
            part='id,snippet',
            maxResults=50,  # Maximum results per page
            publishedAfter=seven_days_ago,
            publishedBefore=today_date,  # Videos published after 7 days ago
            type='video',
            videoDuration='short',  # Filter for Shorts videos
            order='viewCount',  # Order by view count
        ).execute()

        # Collect video details: title, view count, channel name, and duration
        videos = []

        for search_result in search_response.get('items', []):
            video_id = search_result['id']['videoId']
            video_title = search_result['snippet']['title']
            channel_id = search_result['snippet']['channelId']

            # Get video statistics to retrieve view count
            stats = youtube.videos().list(part='statistics,snippet,contentDetails', id=video_id).execute()
            view_count = stats['items'][0]['statistics']['viewCount']
            duration = stats['items'][0]['contentDetails']['duration']

            # Get channel name
            channel = youtube.channels().list(part='snippet', id=channel_id).execute()
            channel_name = channel['items'][0]['snippet']['title']

            videos.append({
                'title': video_title,
                'views': view_count,
                'channel': channel_name,
                'duration': duration,
                'video_id': video_id
            })

        # Sort videos by view count
        sorted_videos = sorted(videos, key=lambda x: int(x['views']), reverse=True)

        # Display top 10 videos by view count with title, view count, channel name, and duration
        top_10_shorts = sorted_videos[:10]
        for idx, video in enumerate(top_10_shorts, start=1):
            print(
                f"{idx}. Title: {video['title']} | Views: {video['views']} | Channel: {video['channel']} | Duration: {video['duration']} | Video ID: {video['video_id']}")
        content_info = '測試測試\n\n以下是最近引起熱烈迴響的美食短影片排行榜，讓你一窺最新美食趨勢\n\n'

        # 迴圈處理前 10 名影片資訊
        for i in range(10):
            video = sorted_videos[i]
            video_title = video['title']
            view_count = video['views']

            post_title = f"{datetime.today().strftime('%Y/%m/%d')}的YOUTUBE美食話題流量排名前10名"

            # 將影片資訊附加到 content_info 變數中
            content_info += f"{video_title}，觀看次數：{view_count}\n\n"

        try:
            content_info += "\n來到這裡，歡迎大家盡情分享你個人鍾愛的美食或獨門食譜！無論是對這些影片的看法，或者想要分享自己獨特的美食心得，都歡迎你發佈貼文。讓我們一同探索，開啟嶄新的美食世界，將味蕾帶入全新的冒險之旅！"

            # 建立並執行 INSERT 語句
            sql = "INSERT INTO post (UserID, Title, Content) VALUES (%s, %s, %s)"
            mycursor.execute(sql, (0, post_title, escape_html(content_info)))
            mydb.commit()

            if mycursor.rowcount > 0:
                print("成功插入資料")
            else:
                print("沒有插入任何資料")

        except mysql.connector.Error as error:
            print("資料庫錯誤：", error)
        finally:
            if mydb.is_connected():
                mycursor.close()
                mydb.close()
                print("資料庫連接已關閉")

    # 在這裡放入美食短影片分析的程式碼

    def bug1():
        url = 'https://www.nownews.com/cat/life/food-life/'

        response = requests.get(url)
        soup = BeautifulSoup(response.content, 'html.parser')

        articles = soup.find_all('a', class_='trace-click')

        # 創建一個空的列表來存儲標題和連結
        articles_list = []

        for article in articles:
            article_link = article.get('href')
            if article_link.startswith('https://www.nownews.com/news/'):
                try:
                    article_title = article.find('h2').text.strip()
                    articles_list.append({"Title": article_title, "Link": article_link})
                except AttributeError:
                    try:
                        figcaption = article.find('figcaption')
                        article_title = figcaption.text.strip()
                        articles_list.append({"Title": article_title, "Link": article_link})
                    except AttributeError:
                        try:
                            h3_title = article.find('h3').text.strip()
                            articles_list.append({"Title": h3_title, "Link": article_link})
                        except AttributeError:
                            article_title = "No title found"
                            articles_list.append({"Title": article_title, "Link": article_link})

        # 先創建一個空字串來存儲結果
        result_string = ""

        for item in articles_list:
            result_string += f"Title: {item['Title']}\nLink: {item['Link']}\n\n"

        import mysql.connector
        import re

        def escape_html(text):
            html_escape_table = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                "\"": "&quot;",
                "'": "&#039;"
            }
            # 使用正規表達式進行替換
            return re.sub(r'[&<>"\']', lambda m: html_escape_table[m.group()], text)

        from datetime import datetime

        # 取得今天的日期並轉換為指定的格式
        today_date = datetime.today().strftime('%Y/%m/%d')

        content_info = '測試測試\n\n測試測試\n\n' + result_string  # 儲存所有影片資訊的字串
        post_title = f"{today_date}的爬蟲測試"


        try:
            content_info += "爬蟲測試！"
        # 建立並執行 INSERT 語句
            sql = "INSERT INTO post (UserID, Title, Content) VALUES (%s, %s, %s)"
            mycursor.execute(sql, (0, post_title, escape_html(content_info)))
            mydb.commit()

            if mycursor.rowcount > 0:
                print("成功插入資料")
            else:
                print("沒有插入任何資料")

        except mysql.connector.Error as error:
            print("資料庫錯誤：", error)
        finally:
            if mydb.is_connected():
                mycursor.close()
                mydb.close()
                print("資料庫連接已關閉")


    def bug2():

        def escape_html(text):
            html_escape_table = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                "\"": "&quot;",
                "'": "&#039;"
            }
            return re.sub(r'[&<>"\']', lambda m: html_escape_table[m.group()], text)

        # 獲取網頁內容
        url = 'https://www.welcometw.com/?s=%E7%BE%8E%E9%A3%9F'
        response = requests.get(url)
        soup = BeautifulSoup(response.content, 'html.parser')

        # 抓取文章標題和連結
        articles = soup.find_all('h3', class_='elementor-post__title')
        articles_list = []

        for article in articles:
            article_title = article.text.strip()
            article_link = article.a.get('href')
            articles_list.append({"Title": article_title, "Link": article_link})
        # 取得今天的日期並轉換為指定的格式
        today_date = datetime.today().strftime('%Y/%m/%d')
        content_info = '測試測試\n\n測試測試\n\n'  # 初始化文章資訊字串
        for item in articles_list:
            content_info += f" Title: {item['Title']}\n Link: {item['Link']}\n\n"

        post_title = f"{today_date}的爬蟲測試"

        try:
            content_info += "爬蟲測試！"
            # 建立並執行 INSERT 語句
            sql = "INSERT INTO post (UserID, Title, Content) VALUES (%s, %s, %s)"
            mycursor.execute(sql, (0, post_title, escape_html(content_info)))
            mydb.commit()

            if mycursor.rowcount > 0:
                print("成功插入資料")
            else:
                print("沒有插入任何資料")

        except mysql.connector.Error as error:
            print("資料庫錯誤：", error)
        finally:
            if mydb.is_connected():
                mycursor.close()
                mydb.close()
                print("資料庫連接已關閉")
    if __name__ == '__main__':
        choice = input("請選擇要執行的分析（1: 美食影片分析, 2: 美食短影片分析 3: 爬蟲1 4: 爬蟲2）: ")

        if choice == '1':
            analyze_videos()
        elif choice == '2':
            analyze_shorts()
        elif choice == '3':
            bug1()
        elif choice == '4':
            bug2()
        else:
            print("88")

# See PyCharm help at https://www.jetbrains.com/help/pycharm/
