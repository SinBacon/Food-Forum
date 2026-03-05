var now_post_index = 0; 
var max_post_count = 100;

function getMorePostsData() {
    // 創建XMLHttpRequest物件
    var xhr = new XMLHttpRequest();
    // 將參數附加到 URL 上
	var url = '/getMorePostsData.php?post_index=' + encodeURIComponent(now_post_index);
	xhr.open('GET', url, true);
    // 定義當請求完成後要執行的函數
    xhr.onload = function() {
        if (xhr.status == 200) {
			// 使用 DOMParser 將字符串轉換為 DOM 對象
			var parser = new DOMParser();
			var htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
			// 提取純文本內容
			var plainText = htmlDoc.body.textContent.trim();
			if (plainText == "null") {
				//now_post_index = 0;
			}else {
				// 將 JSON 字串解析為 JavaScript 對象
				var jsonObject = JSON.parse(plainText);
				var Posts = Object.values(jsonObject.Posts);
				var post_index = jsonObject.post_index;
				addMorePosts(post_index, Posts);
			}
        }
    };
    // 發送請求
    xhr.send();
}

function addMorePosts(post_index, Posts) {

	if (post_index < now_post_index) {
		post_index = now_post_index;
		return;
	}
    let contentRight = document.querySelector('.content-right');
		
    for (let i = 0; i < Posts.length; i++) {
        let newPost = document.createElement('div');
        newPost.classList.add('post');
        newPost.setAttribute('onclick', 'handlePostClick(' + (escapeHtml(Posts[i].PostID)) + ')');
		//newPost.setAttribute('onmouseover', 'handlePostHover(' + (Posts[i].PostID) + ')');
		
		let PostTitle = truncateText(escapeHtml(Posts[i].Title), 30);
		let PostContent = truncateText(escapeHtml(Posts[i].Content), 100);
		
         newPost.innerHTML = `
            <div class="post_content">
				<div  class="poster_info">
					<img src="../image/profile-user.png" alt="">
					<p>${escapeHtml(Posts[i].Username)}</p>
				</div>
                <div class="post-title_content">
                    <h2>${PostTitle}</h2>
                </div>
                <div class="post_content_text_area"><p class="post_content_text">${PostContent}</p></div>
				<div class="post_info">
					<div class="image-container" onclick="swapImages(this); event.stopPropagation();">
						<img src="../image/like.png" alt="">
						<img class="hidden" src="../image/like-ck.png" alt="">
					</div>
					<div class="like_area"><p id="${escapeHtml(Posts[i].PostID)}-like_count">${escapeHtml(Posts[i].LikeCount)}</p></div>
					<div class="post_time"><p>${escapeHtml(Posts[i].FormattedTimestamp)}</p></div>
            </div>
            </div>
			
        `;//<img class="pt"></img>
        contentRight.appendChild(newPost);
    }
	
	now_post_index += Posts.length;
	
	if (contentRight.scrollHeight <= contentRight.clientHeight) {
		getMorePostsData();
	}
}

let contentRight = document.querySelector('.content-right');

var lastScrollTime = 0;
var cooldownTime = 10; 

contentRight.addEventListener('scroll', function () {
    var currentTime = Date.now();

    if (currentTime - lastScrollTime >= cooldownTime) {
        if (contentRight.scrollTop + contentRight.clientHeight >= contentRight.scrollHeight - 100) {
            getMorePostsData();
			let oldPosts = contentRight.querySelectorAll('.post');
			 if (oldPosts.length >= max_post_count) {
				for (let i = 0; i < Math.min(oldPosts.length, 10); i++) {
					contentRight.removeChild(oldPosts[i]);
				}
			}
		}
        lastScrollTime = currentTime;
    }
});

function swapImages(container) {
    const images = container.getElementsByTagName('img');

    // Toggle the 'hidden' class to swap images
    for (const image of images) {
        image.classList.toggle('hidden');
    }
	var targetUrl = `../login/login.php`;
	window.location.href = targetUrl;
}
	
function handlePostClick(postId) {
    var targetUrl = `../login/login.php`;
    window.location.href = targetUrl;
}

window.addEventListener('resize', function() {
    if (contentRight.scrollHeight <= contentRight.clientHeight) {
		getMorePostsData();
	}    
});

document.addEventListener('DOMContentLoaded', () => {
    getMorePostsData();
});

function truncateText(text, maxLength) {
  if (text.length > maxLength) {
    return text.substring(0, maxLength) + ' ...';
  }
  return text;
}

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}


