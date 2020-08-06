export default class AuthSocket extends WebSocket{
    constructor(...args) {
        super(...args);
        this._data={};
        this._authInfo=null;
        this.onmessageAuth=null;
        this.onbeforeSend=null;
        super.onmessage=(e)=>{
            let parsedData=JSON.parse(e.data);
            if(typeof(parsedData)==="object"){
                if(parsedData.hasOwnProperty("status code")){
                    if(parsedData["status code"]===200){       
                        this.onmessageAuth(e,parsedData);
                    }    
                    //401 не удалось авторизоваться
                    else if(parsedData["status code"]===401){
                        this.authInfo=this.fetchauthInfoAsync();
                        //пробуем снова отправить ту же информацию, но уже со свежими пользовательскими данными
                        this.send(data);
                    }
                }
            }
        }
    }
    async getauthInfo(){
        if(!this._authInfo)
            this._authInfo=await this.fetchauthInfoAsync()
            return this._authInfo;
    }
    set authInfo(value){
        this._authInfo=value;
    }
    async isNotGuest(){
        let authInfo=await this.getauthInfo();
        if(authInfo.id&&authInfo.authKey){
            return true;
        }
        return;
    }
    async send(data={}){
        this._data=data;
        this._data.authInfo=await this.getauthInfo();
        if(await this.isNotGuest()){
            if(this.onbeforeSend&&typeof(this.onbeforeSend)!=="function")
                throw new Error(`onbeforeSend is not a function`);
            if(this.onbeforeSend&&typeof(this.onbeforeSend)==="function")
                this.onbeforeSend({data:this._data});
            let stringify=JSON.stringify(this._data);
            super.send(stringify);
        }
        else console.warn("unauthorised user");
    }
    async fetchauthInfoAsync() {
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