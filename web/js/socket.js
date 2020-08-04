import AuthSocket from "./AuthSocket.js";
window.addEventListener("DOMContentLoaded",socketHandler);
async function socketHandler(){
    let socket = new AuthSocket("ws://127.0.0.1:8989");
    let button = document.querySelector("#select");
    socket.onbeforeSend=function(e){
        console.warn("onbeforeSend")
        console.log(e.data);
    }
    button.addEventListener("click",async ()=> await socket.send({message:"Hello world!"}));
    socket.onmessageAuth = (e,parsedData)=>{
        console.warn("onmessage");
        console.log(typeof(parsedData));  
        console.log(parsedData);
    }
}
function appendSocketDataToEl(elId,message){
    let x = document.getElementById(elId);
    let div = document.createElement("div");
    div.innerText = message;
    x.appendChild(div);
}