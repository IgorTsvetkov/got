class AuthSocket extends WebSocket{
    constructor(...args) {
        super(...args);
        this._data={};
        this._userInfo=null;
        this.onmessage=null;
        super.onmessage=(e)=>{
            if(e.data["status code"]===200){            
                this.onmessage(e);
            }    
            //401 не удалось авторизоваться
            else if(e.data["status code"]===401){   
                this.userInfo=this.fetchUserInfoAsync();
                //пробуем снова отправить ту же информацию, но уже со свежими пользовательскими данными
                this.send(data);
            }
        }
    }
    async getUserInfo(){
        if(!this._userInfo)
            this._userInfo=await this.fetchUserInfoAsync()
            return this._userInfo;
    }
    set userInfo(value){
        this._userInfo=value;
    }
    async isNotGuest(){
        let userInfo=await this.getUserInfo();
        if(userInfo.id&&userInfo.authKey){
            return true;
        }
        return;
    }
    async send(data=null){
        this._data=data;
        this._data.userInfo=await this.getUserInfo();
        if(await this.isNotGuest()){
            console.log(this._data);
            console.warn("try to connect...");
            super.send(JSON.stringify(this._data));
        }
    }
    async fetchUserInfoAsync() {
        try{
            let response = await fetch("/socket/auth", {
                method: 'GET',
                credentials: 'include'
            });
            console.warn("getUsetInfo");
            return await response.json();
        }
        catch(error){
            throw new Error(error);
        }
    }
}
window.addEventListener("DOMContentLoaded",async function () {
    let socket = new AuthSocket("ws://127.0.0.1:8989");
    let button = document.querySelector("#select");
    button.addEventListener("click",async ()=> await socket.send({message:"Hello world!"}));
    socket.onmessage = e=>{
        console.log(e.data);  
    }
});
function appendSocketDataToEl(elId,message){
    let x = document.getElementById(elId);
    let div = document.createElement("div");
    div.innerText = message;
    x.appendChild(div);
}