var now_comment_index = 0; 
var max_comment_count = 100;
var stop_add_comment = false;

function getMoreCommentsData() {
    // 創建XMLHttpRequest物件
    var xhr = new XMLHttpRequest();
    // 將參數附加到 URL 上
	var url = 'getMoreCommentsData.php?comment_index=' + encodeURIComponent(now_comment_index) + '&post_id=' + encodeURIComponent(PostID);
	xhr.open('GET', url, true);
    // 定義當請求完成後要執行的函數
    xhr.onload = function() {
        if (xhr.status == 200) {
			// 使用 DOMParser 將字符串轉換為 DOM 對象
			var parser = new DOMParser();
			var htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
			// 提取純文本內容
			var plainText = htmlDoc.body.textContent.trim();
			//alert(plainText);
			if (plainText == "null") {
				if (stop_add_comment == false){
					stop_add_comment = true;
					addMoreComments(null, null, false);
				}
			}else {
				try {
					stop_add_comment = false;
					// 將 JSON 字串解析為 JavaScript 對象
					var jsonObject = JSON.parse(plainText);
					var Comments = Object.values(jsonObject.Comments);
					var comment_index = jsonObject.comment_index;
					addMoreComments(comment_index, Comments, true);
				} catch (error) {
					showErrorBlock();
				}
			}
        }
    };
    // 發送請求
    xhr.send();
}

function addMoreComments(comment_index, Comments, Check) {
	
	let commentBlock = document.querySelector('.comment');
	let contentViewBlock = document.querySelector('.content_view_block');
	
	if (Check == true){
		if (comment_index < now_comment_index) {
			comment_index = now_comment_index;
			return;
		}
		
		for (let i = 0; i < Comments.length; i++) {
			
			let newComment = document.createElement('div');
			let CommentID = escapeHtml(Comments[i].CommentID);
			
			newComment.classList.add('comment');
			newComment.setAttribute('onclick', 'handleCommentClick(' + (CommentID) + ')');
			//newComment.setAttribute('onmouseover', 'handleCommentHover(' + (CommentID) + ')');
		
			let ContentText = truncateText(escapeHtml(Comments[i].Content), 300);
			
			newComment.innerHTML = `
				<div class="comment_content">
					<div  class="comment_content_title">
						<img src="../image/profile-user.png" alt="">
						<p>${escapeHtml(Comments[i].Username)}</p>
					</div>
					<div  class="comment_content_text">
						<p>${ContentText}</p>
					</div>
					<div  class="comment_content_time">
						<p>${escapeHtml(Comments[i].Timestamp)}</p>
					</div>
				</div>
			`;
			commentBlock.appendChild(newComment);
			now_comment_index += 1;

			if (contentViewBlock.scrollHeight <= contentViewBlock.clientHeight) {
				getMoreCommentsData();
			}
		}
	}else{
		let newComment = document.createElement('div');
		newComment.classList.add('.content_view_block');
		let existingParagraph = contentViewBlock.querySelector('.tip');
		if (existingParagraph) {
			existingParagraph.remove();
		}
		let oldChild = contentViewBlock.querySelectorAll('.comment');
		if (oldChild.length == 1) {
			newComment.innerHTML = `<p class="tip">--- 搶先成為第一位留言者吧 ! ---</p>`;
		}else {
			newComment.innerHTML = `<p class="tip">--- 留言區已經到底囉 ! ---</p>`;
		}
		contentViewBlock.appendChild(newComment);
	}
}

let commentBlock = document.querySelector('.comment');
let contentViewBlock = document.querySelector('.content_view_block');

var lastScrollTime = 0;
var cooldownTime = 1; 

contentViewBlock.addEventListener('scroll', function () {
    var currentTime = Date.now();

    if (currentTime - lastScrollTime >= cooldownTime) {
        if (contentViewBlock.scrollTop + contentViewBlock.clientHeight >= contentViewBlock.scrollHeight - 5) {
			startVibration();
            getMoreCommentsData();
			let oldComments = commentBlock.querySelectorAll('.comment');
			 if (oldComments.length >= max_comment_count) {
				for (let i = 0; i < Math.min(oldComments.length, 10); i++) {
					commentBlock.removeChild(oldComments[i]);
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
    }
	
function handleCommentClick(commentId) {
	//alert(commentId);
}

window.addEventListener('resize', function() {
    if (contentViewBlock.scrollHeight <= contentViewBlock.clientHeight) {
		getMoreCommentsData();
	}    
});

document.addEventListener('DOMContentLoaded', function () {
	
	getMoreCommentsData();
	
    var submitButton = document.querySelector('.submit_button');

    submitButton.addEventListener('click', function () {
		sendComment();
	});
	
	var likeButton = document.querySelector('.like_button');
    likeButton.addEventListener('click', function () {
		setPostLike(PostID);
	});
});

// 顯示 loading_block
function showLoadingBlock() {
  var loadingBlock = document.getElementById('loading_block');
  loadingBlock.style.display = 'flex';
}

// 隱藏 loading_block
function hideLoadingBlock() {
  var loadingBlock = document.getElementById('loading_block');
  loadingBlock.style.display = 'none';
}

function showLikeBlock() {
  var LikeBlock = document.getElementById('like_block');
  LikeBlock.style.display = 'flex';
}

function hideLikeBlock() {
  var LikeBlock = document.getElementById('like_block');
  LikeBlock.style.display = 'none';
}

function refreshCommentsData() {
	let oldComments = commentBlock.querySelectorAll('.comment');
	for (let i = 0; i < oldComments.length; i++) {
		commentBlock.removeChild(oldComments[i]);
	}
	now_comment_index = 0; 
	getMoreCommentsData();
	hideLoadingBlock()
}

function truncateText(text, maxLength) {
  if (text.length > maxLength) {
    return text.substring(0, maxLength) + ' ...';
  }
  return text;
}

function startVibration() {
	let textElement = document.querySelector('.tip');
	
	if (textElement) {
		textElement.classList.add('shake-animation');
		// 等待震動動畫結束後，移除震動效果的 class
		setTimeout(function() {
			textElement.classList.remove('shake-animation');
		}, 500); // 0.5 秒後移除震動效果
	}
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
				let textElement = document.getElementById('post_LikeCount');
				if (textElement) {
					if (LikeState == 'F') {
						textElement.textContent = (parseInt(textElement.textContent, 10)-1).toString() + ' likes';
					} else if (LikeState == 'T') {
						showLikeBlock();
						setTimeout(function() {
							hideLikeBlock();
						}, 800); 
						textElement.textContent = (parseInt(textElement.textContent, 10)+1).toString() + ' likes';
					}
				}
			}
        }
    };
    // 發送請求
    xhr.send();
}

function detectEnterKey(event) {
    if (event.key === "Enter" || event.keyCode === 13) {
        sendComment();
    }
}

function sendComment() {
	var inputTextValue = document.getElementById('input_text').value;
	var post_id = document.getElementById('post_id').value;
	var user_id = document.getElementById('user_id').value;
	
	if (inputTextValue.trim()) {
		showLoadingBlock();
		var xhr = new XMLHttpRequest();
		var url = 'addComment.php';

		xhr.open('POST', url, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200) {
				// 使用 DOMParser 將字符串轉換為 DOM 對象
				let parser = new DOMParser();
				let htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
				// 提取純文本內容
				let plainText = htmlDoc.body.textContent.trim();
				let jsonObject = JSON.parse(plainText);
				let TotalComments = jsonObject.TotalComments;
					
				document.getElementById('post_TotalComments').innerHTML = TotalComments+' comments';
					
				setTimeout(function() {
					refreshCommentsData();
				}, 750);
			}
		};
		var data = 'input_text=' + encodeURIComponent(inputTextValue) +
              '&post_id=' + encodeURIComponent(post_id) +
              '&user_id=' + encodeURIComponent(user_id);

		xhr.send(data);
	}
	document.getElementById('input_text').value = '';
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

function showErrorBlock() {
  let ErrorBlock = document.getElementById('error_block');
  ErrorBlock.style.display = 'flex';
  startCountdown(6);
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
            window.location.href = '../user/index.php';
        } else {
            countdownElement.textContent = '發生錯誤，將於 ' + seconds + ' 秒後回到主頁 !';
        }
    }, 1000); 
}

function convertToLinks(elementId) {
	let contentElement = document.getElementById(elementId);
    // 检查元素是否存在
    if (contentElement) {
      var contentHtml = contentElement.innerHTML;
      var urlRegex = /(\b(?:https?|ftp):\/\/[^\s]+)/g;
      var linkedText = contentHtml.replace(urlRegex, function(url) {
        return '<a href="' + url + '" target="_blank">' + url + '</a>';
      });

      // 将替换后的 HTML 再次赋值给元素
      contentElement.innerHTML = linkedText;
    } else {
      console.error('Element with ID ' + elementId + ' not found.');
    }
}