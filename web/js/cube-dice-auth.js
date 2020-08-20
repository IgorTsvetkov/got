window.addEventListener("DOMContentLoaded",()=>{
    let loginBtn=document.getElementById("login");
    login.addEventListener("click",showAuth);
    // showAuth();
});
function showAuth(e){
    let cube=document.getElementsByClassName("cube")[0];
    cube.classList.toggle("auth-animation");
    let elementsToHide=document.querySelectorAll(".auth-front,#login-form-wrapper");
    console.log('elementsToHide :>> ', elementsToHide);
    elementsToHide.forEach(el=>el.classList.toggle("d-none"));
}
