async function getUserInfoAsync() {
    let response = await fetch("/socket/auth", {
        method: 'GET',
        credentials: 'include'
    })
    return await response.json();
}
window.addEventListener("DOMContentLoaded", function () {

    let socket = new WebSocket("ws://127.0.0.1:8989");
    // getUserInfoAsync().then(userInfo=>{
    //     socket.onopen = function (e) {
    //         let data=[userInfo];
    //         alert("[open] Connection established");
    //         socket.send(data);
    //     };
    // });

    let button = document.querySelector("#select");
    button.addEventListener("click", () => {
        getUserInfoAsync().then(
            userInfo=>{
                console.log(userInfo);
                socket.send(userInfo);
            }
        );
        
    })
    socket.onmessage = function (e) {
        let x = document.getElementById("messages");
        let div = document.createElement("div");
        div.innerText = e.data;
        x.appendChild(div);
    };
});