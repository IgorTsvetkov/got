window.addEventListener("DOMContentLoaded",function(){
    
    let socket = new WebSocket("ws://127.0.0.1:8989");
    socket.onopen = function(e) {
        // alert("[open] Connection established");
        // alert("Sending to server");
        // socket.send("My name is John");
    };
    let button=document.querySelector("#select");
    button.addEventListener("click",()=>{
        socket.send("Кнопка нажата");
    })
    socket.onmessage = function(e) {
        let x=document.getElementById("messages");
        let div=document.createElement("div");
        div.innerText=e.data;
        x.appendChild(div);
    };
});