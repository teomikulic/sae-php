const POPUP = document.getElementById("popup");
const NOTIFICATION = document.getElementById("notification");

function popup(){
    if(POPUP != null){
        POPUP.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
}

function closePopup(){
    if(POPUP != null){
        POPUP.style.display = "none";
        document.body.style.overflow = "";
    }
}

function move() {
    var elem = document.getElementById("notification_loading");
    if(elem != null){
        var width = 7000;
        var id = setInterval(frame, 70);
        function frame() {
            if (width <= 0) {
            clearInterval(id);
            } else {
            width -= 70; 
            elem.style.width = width / 70 + '%';
            }
        }
    }
}

function closeNotification(location) {
    if(NOTIFICATION != null){
        NOTIFICATION.innerHTML = '';
        if(location != "" && location != undefined){
            window.location = location;
        }
    }
}

function notify(message, imageName, location = ""){
    if(NOTIFICATION != null){
        NOTIFICATION.innerHTML = '<div onclick="closeNotification(\''+ location +'\')"><div id="notification_txt"><img width="35px" height="35px" src="./Imports/img/'+ imageName +'"><p>'+ message +'</p></div><div id="notification_loading"></div></div>';
        setTimeout(function() { closeNotification(location) }, 7150);
        move();
    }
}

function readURL(input, imgId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var img = document.getElementById(imgId);

        reader.onload = function (e) {
            img.setAttribute('style', 'background-image: url(\''+e.target.result+'\'); background-position: center; background-size: cover; color: transparent;');
        };

        reader.readAsDataURL(input.files[0]);
    }
}