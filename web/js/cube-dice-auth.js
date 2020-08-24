window.addEventListener("DOMContentLoaded",()=>{
    login.addEventListener("click",showAuth);
    registration.addEventListener("click",showAuth);
});
function showAuth(e){
    let cube=document.getElementsByClassName("cube")[0];
    cube.classList.toggle("auth-animation");
    let elementsToHide=document.querySelectorAll(".auth-front");
    console.log('elementsToHide :>> ', elementsToHide);
    elementsToHide.forEach(el=>el.classList.toggle("d-none"));
}
