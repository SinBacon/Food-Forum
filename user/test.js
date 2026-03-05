// Load Google Maps API with callback
function loadMapScript() {
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyARGhmvVSGZYNNjxeYq-gRYSQP7E6h5Sv8&libraries=places&callback=initMap';
    document.head.appendChild(script);
}

function initMap() {
    const autocompleteService = new google.maps.places.AutocompleteService();
    const placesService = new google.maps.places.PlacesService(document.createElement('div'));

    const location_name_text = document.getElementById('location_name_text');
    const suggestionsDiv = document.getElementById('suggestions');

    location_name_text.addEventListener('input', function() {
        const inputValue = location_name_text.value;

        // 判斷輸入內容是否空白
        if (inputValue.trim() !== '') {
            // 使用 Autocomplete 服務取得地址建議
            autocompleteService.getPlacePredictions({ input: inputValue }, function(predictions, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    // 清空建議框
                    suggestionsDiv.innerHTML = '';
                    // 更新建議框中的建議
                    predictions.forEach(function(prediction) {
                        const suggestionDiv = document.createElement('div');
                        suggestionDiv.textContent = prediction.description;
                        suggestionsDiv.appendChild(suggestionDiv);
                    });
                } else {
                    console.error('請求失敗');
                }
            });
        } else {
            suggestionsDiv.innerHTML = '';
        }
    });
}

// Call the function to load the map
loadMapScript();

