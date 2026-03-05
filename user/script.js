var now_post_index = 0; 
var max_post_count = 100;
const urlParams = new URLSearchParams(window.location.search);
var x = document.getElementById('myElement').getAttribute('data-x');
function getMorePostsData() {
    // 創建XMLHttpRequest物件
    let xhr = new XMLHttpRequest();
    let url = `getMorePostsData.php?post_index=${encodeURIComponent(now_post_index)}&x=${encodeURIComponent(x)}`;
	xhr.open('GET', url, true);
    // 定義當請求完成後要執行的函數
    xhr.onload = function() {
        if (xhr.status == 200) {
			// 使用 DOMParser 將字符串轉換為 DOM 對象
			let parser = new DOMParser();
			let htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
			// 提取純文本內容
			let plainText = htmlDoc.body.textContent.trim();
			//alert(plainText);
			if (plainText == "null") {
			//now_post_index = 0;
			}else {
				// 將 JSON 字串解析為 JavaScript 對象
				try {
					let jsonObject = JSON.parse(plainText);
					let Posts = Object.values(jsonObject.Posts);
					let LikeStates = Object.values(jsonObject.LikeStates);
					let post_index = jsonObject.post_index;
					addMorePosts(post_index, Posts, LikeStates);
				} catch (error) {
					showErrorBlock();
				}
			}
        }
    };
    // 發送請求
    xhr.send();
}

function isJSON(str) {
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

function addMorePosts(post_index, Posts, LikeStates) {

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
		
		let PostTitle = truncateText_Line(truncateText(escapeHtml(Posts[i].Title), 30), 1);
		let PostContent = truncateText_Line(truncateText(escapeHtml(Posts[i].Content), 100), 3);
		let like_image_innerHTML = ``;

		if (LikeStates[i] == "T") {
			like_image_innerHTML = `
			<div class="image-container" onclick="swapImages(this, ${escapeHtml(Posts[i].PostID)}); event.stopPropagation();">
				<img src="../image/like-ck.png" alt="">
				<img class="hidden" src="../image/like.png" alt="">		
			</div>
			`;
		}else {
			like_image_innerHTML = `
			<div class="image-container" onclick="swapImages(this, ${escapeHtml(Posts[i].PostID)}); event.stopPropagation();">
				<img class="hidden" src="../image/like-ck.png" alt="">
				<img src="../image/like.png" alt="">		
			</div>
			`;
		}
		
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
					${like_image_innerHTML}
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

let insertButton = document.getElementById('insert_button');

  // 監聽按鈕的點擊事件
  insertButton.addEventListener('click', function() {
    let targetUrl = `put_up.php`;
	window.location.href = targetUrl;
  });

function swapImages(container, ID) {
    const images = container.getElementsByTagName('img');

    // Toggle the 'hidden' class to swap images
    for (const image of images) {
         image.classList.toggle('hidden');
    }
	
	setPostLike(ID);
}
	
function handlePostClick(postId) {
    let targetUrl = `post.php?post_id=${postId}`;
	window.open(targetUrl, '_blank');
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

function truncateText_Line(text, maxLines) {
  // 将字符串按行分割成数组
  const lines = text.split('\n');

  // 如果行数小于等于 maxLines，返回原始字符串
  if (lines.length <= maxLines) {
    return text;
  }

  // 取前 maxLines 行，并加上省略号
  const truncatedText = lines.slice(0, maxLines).join('\n') + '...';
  return truncatedText;
}

function setPostLike(PostID) {
    // 創建XMLHttpRequest物件
    let xhr = new XMLHttpRequest();
    // 將參數附加到 URL 上
	let url = 'setPostLike.php?post_id=' + encodeURIComponent(PostID);
	xhr.open('GET', url, true);
    // 定義當請求完成後要執行的函數
    xhr.onload = function() {
        if (xhr.status == 200) {
			// 使用 DOMParser 將字符串轉換為 DOM 對象
			let parser = new DOMParser();
			let htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
			// 提取純文本內容
			let plainText = htmlDoc.body.textContent.trim();

			if (plainText == "null") {
				//now_post_index = 0;
			}else {
				let jsonObject = JSON.parse(plainText);
				let LikeState = jsonObject.LikeState;
				const elementID = PostID + '-like_count';
				let textElement = document.getElementById(elementID);
				if (textElement) {
					if (LikeState == 'F') {
						textElement.textContent = (parseInt(textElement.textContent, 10)-1).toString() ;
					} else if (LikeState == 'T') {
						textElement.textContent = (parseInt(textElement.textContent, 10)+1).toString() ;
					}
				}
			}
        }
    };
    // 發送請求
    xhr.send();
}

function showErrorBlock() {
  let ErrorBlock = document.getElementById('error_block');
  ErrorBlock.style.display = 'flex';
  startCountdown(10);
}

function hideErrorBlock() {
  let ErrorBlock = document.getElementById('error_block');
  ErrorBlock.style.display = 'none';
}

function startCountdown(seconds) {
    let countdownElement = document.getElementById('error_countdown');

    let countdownInterval = setInterval(function() {
        seconds--;

        if (seconds <= 0) {
            clearInterval(countdownInterval);
            window.location.href = '../logout.php';
        } else {
            countdownElement.textContent = '發生錯誤，將於' + seconds + '秒後登出 !';
        }
    }, 1000); 
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
// yourScriptFile.js



