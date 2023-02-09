const POPUP = document.getElementById("popup");

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