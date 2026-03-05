var map;
map = L.map('map').setView([23.973875, 120.982025], 7);
L.control.scale().addTo(map);

var marker = L.marker([23.973875, 120.982025]);
marker.addTo(map);
var locationText = document.getElementById('location_text');
var locationName = document.getElementById('location_name_text');
var locationData = document.getElementById('location_data');
var insertButton = document.getElementById('insert_button');
var searchButton = document.getElementById('search_location_button');

searchButton.addEventListener('click', function() {
  showLoadingBlock();

  let locationNameValue = locationName.value;
  
  if (!locationNameValue.trim()) {
    hideLoadingBlock();
    alert("請輸入有效的位置名稱  !");
	locationName.value = ``;
    return;
  }

  let cleanedString = locationNameValue.replace(/[\r\n]+/g, ' ');

  searchNominatim(cleanedString)
    .then(data => {
      let latitude = data[0].lat;
      let longitude = data[0].lon;
      if (isValidCoordinates(latitude, longitude)) {
        map.removeLayer(marker);
        marker = L.marker([latitude,longitude]);
        marker.addTo(map);
        marker.bindTooltip("("+Math.round(latitude * 100) / 100+","+Math.round(longitude * 100) / 100+")", {
          direction: 'bottom',
          sticky: true,
          permanent: false,
          opacity: 1.0
        }).openTooltip();
        map.setView([latitude, longitude], 15);
		locationText.innerHTML = `(${Math.round(latitude * 100) / 100}, ${Math.round(longitude * 100) / 100})`;
		locationData.value = `(${latitude}, ${longitude})`;
      } else{
		  alert("找不到輸入地點 !");
	  }
      hideLoadingBlock();
    })
    .catch(error => {
      hideLoadingBlock();
      console.error("Error:", error);
    });
});

 // 監聽按鈕的點擊事件
 insertButton.addEventListener('click', function() {
	postPost();
 });
  
  function postPost() {
	var title_area = document.getElementById('title_area').value;
	var content_area = document.getElementById('content_area').value;
	var food_area = document.getElementById('food_area').value;
	var meal_type = document.getElementById('meal_type').value;
	var star_type = document.getElementById('star_type').value;
	var food_price = document.getElementById('price_area').value;
	var location_data = document.getElementById('location_data').value;
	var location_name_text = document.getElementById('location_name_text').value;
	var user_id = document.getElementById('user_id').value;
	let lat = "none";
	let lng = "none";
	
	let matches = (locationData.value.slice(1, -1)).split(', ');
	if (matches) {
		lat = parseFloat(matches[0]);
		lng = parseFloat(matches[1]);
	}
	
	let location_format = false;
	if (!((isNaN(lat) || isNaN(lng)) && location_name_text.trim())) {
		location_format = true;
	}else {
		location_format = false;
	}
	
	if (title_area.trim() && content_area.trim() && food_area.trim() && food_price.trim() && location_format) {
		var xhr = new XMLHttpRequest();
		var url = 'addPost.php';

		xhr.open('POST', url, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		xhr.onreadystatechange = function () {
			if (xhr.readyState == 4 && xhr.status == 200) {
				// 使用 DOMParser 將字符串轉換為 DOM 對象
				let parser = new DOMParser();
				let htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
				// 提取純文本內容
				let plainText = htmlDoc.body.textContent.trim();
				//alert(plainText);
				try {
					let jsonObject = JSON.parse(plainText);
					let info = jsonObject.info;
					alert(info);
				} catch (error) {
					showErrorBlock();
					alert("內容有誤，張貼失敗 !");
				}
				window.location.href = `index.php`;
			}
		};
		var data = 
			  'title=' + encodeURIComponent(escapeHtml(title_area)) +
              '&content=' + encodeURIComponent(escapeHtml(content_area)) +
			  '&food=' + encodeURIComponent(escapeHtml(food_area)) +
			  '&meal_type=' + encodeURIComponent(escapeHtml(meal_type)) +
			  '&star_type=' + encodeURIComponent(escapeHtml(star_type)) +
			  '&food_price=' + encodeURIComponent(escapeHtml(food_price)) +
			  '&lat=' + encodeURIComponent(lat) +
			  '&lng=' + encodeURIComponent(lng) +
			  '&location_name=' + encodeURIComponent(escapeHtml(location_name_text)) +
              '&user_id=' + encodeURIComponent(escapeHtml(user_id));
			  
		xhr.send(data);
	}else{
		if (!location_format) {
			alert("張貼失敗，地名和地點須同時存在 !");
		} else{
			alert("張貼失敗，貼文內容不完整 !");
		}
	}
}

function showErrorBlock() {
  let ErrorBlock = document.getElementById('error_block');
  ErrorBlock.style.display = 'flex';
  startCountdown(3);
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

function showLoadingBlock() {
  var loadingBlock = document.getElementById('loading_block');
  loadingBlock.style.display = 'flex';
}

// 隱藏 loading_block
function hideLoadingBlock() {
  var loadingBlock = document.getElementById('loading_block');
  loadingBlock.style.display = 'none';
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

let locationClearButton = document.getElementById('location_clear');

  // 監聽按鈕的點擊事件
  locationClearButton.addEventListener('click', function() {
    locationText.innerHTML = `None`;
	locationData.value = `None`;
 });

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '<a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
    maxZoom: 18,
}).addTo(map);

map.on('click', function(e){
	let coord = e.latlng,
    lat = coord.lat,
    lng = coord.lng;
	map.removeLayer(marker);
	marker = L.marker([lat,lng]);
	marker.addTo(map);
	locationText.innerHTML = `(${Math.round(lat * 100) / 100}, ${Math.round(lng * 100) / 100})`;
	locationData.value = `(${lat}, ${lng})`;
	reverseGeocode(lat, lng)
		.then(result => {
			// Set the value of the locationName input field with the result
			locationName.value = reverseAndRemoveCommas(result);
		})
		.catch(error => {
			// Handle errors, e.g., log to console or set a default value
			console.error(error);
			locationName.value = 'Error retrieving location';
		});
	marker.bindTooltip("("+Math.round(lat * 100) / 100+","+Math.round(lng * 100) / 100+")", {
		direction: 'bottom', // right、left、top、bottom、center。default: auto
		sticky: true, // true 跟著滑鼠移動。default: false
		permanent: false, // 是滑鼠移過才出現，還是一直出現
		opacity: 1.0
	}).openTooltip();
});

function reverseGeocode(latitude, longitude) {
	showLoadingBlock();
    // Construct the API request URL for reverse geocoding
    let apiUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`;

	// Make a GET request to the Nominatim API and return the result
	return fetch(apiUrl)
		.then(response => response.json())
		.then(data => {
		// Access the address information from the API response and return it
		hideLoadingBlock();
		return data.display_name;
		})
		.catch(error => {
		console.error('Error:', error);
		// You might want to handle the error here, e.g., return an error message
		hideLoadingBlock();
		return 'Error retrieving location';
    });
}

function reverseAndRemoveCommas(address) {
  let addressArray = address.replace(/,/g, '').split(' ');
  let reversedAddressArray = addressArray.reverse();
  let reversedAddress = reversedAddressArray.join(' ');

  return reversedAddress;
}

function isValidCoordinates(latitude, longitude) {
  function isValidLatitude(lat) {
    return !isNaN(lat) && lat >= -90 && lat <= 90;
  }

  function isValidLongitude(lon) {
    return !isNaN(lon) && lon >= -180 && lon <= 180;
  }

  if (isValidLatitude(latitude) && isValidLongitude(longitude)) {
    return true;
  } else {
    return false;
  }
}

function searchNominatim(queryString) {
  var apiUrl = `https://nominatim.openstreetmap.org/search.php?q=${queryString}&format=jsonv2`;

  return fetch(apiUrl)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      return data;
    })
    .catch(error => {
      console.error("Error fetching data:", error);
    });
}


 