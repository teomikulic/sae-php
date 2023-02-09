const POPUP = document.getElementById("popup");
const POPUP_CONTENT = document.getElementById("popup_content");

function popup(content, tagName, width = null){
    if(POPUP != null && POPUP_CONTENT != null){
        POPUP.style.display = "flex";

        if(POPUP.getAttribute("type") != tagName){
            if(width != null)
                POPUP_CONTENT.style.width = width;

            POPUP.setAttribute("type", tagName);

            POPUP_CONTENT.innerHTML = content;
        }

        document.body.style.overflow = "hidden";
    }
}

function closePopup(){
    if(POPUP != null){
        POPUP.style.display = "none";
        document.body.style.overflow = "";
    }
}

function loginPopup(){
    popup(LOGIN_FORM, "login", "30%");
}