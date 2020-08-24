export default class AuthSocket extends WebSocket {
    constructor(game_id,...args) {
        super(...args);
        //не уверен что это должно быть здесь, но шо поделать  _(-_-)-/
        this.uid=game_id;
        this._data = {};
        this._authInfo = null;
        this.onmessageAuth = null;
        this._onmessageCallbacks=[];
        this.onbeforeSend = null;
        if(this.uid)
        this.onopen=function(){
            this.send({uid:this.uid});
        }
        super.onmessage = (e) => {
            let parsedData = JSON.parse(e.data);
            if (typeof (parsedData) === "object") {
                // if (parsedData.hasOwnProperty("status code")) {
                //     //401 не удалось авторизоваться
                //     if(parsedData["status code"] === 401) {
                //       this.authInfo = this.fetchAuthInfoAsync();
                //         //пробуем снова отправить ту же информацию, но уже со свежими пользовательскими данными
                //       this.send(data);
                //     }
                // }
                // this.onmessageAuth(e, parsedData);
                this._onmessageCallbacks.forEach(callback => {
                    callback(e,parsedData);
                });
            }
        }
    }
    addMessageCallback(callback){
        this._onmessageCallbacks.push(callback);
    }
    async getauthInfo() {
        if (!this._authInfo)
            this._authInfo = await this.fetchAuthInfoAsync()
        return this._authInfo;
    }
    set authInfo(value) {
        this._authInfo = value;
    }
    async isNotGuest() {
        let authInfo = await this.getauthInfo();
        if (authInfo.id && authInfo.authKey) {
            return true;
        }
        return;
    }
    send(data = {}) {
        if (typeof data === "object") {
            this._data = data;
            // this._data.authInfo = await this.getauthInfo();
            if(this.uid)
            this._data.uid=this.uid;
            // if (await this.isNotGuest()) {
                if (this.onbeforeSend && typeof (this.onbeforeSend) !== "function")
                    throw new Error(`onbeforeSend is not a function`);
                if (this.onbeforeSend && typeof (this.onbeforeSend) === "function")
                    this.onbeforeSend({ data: this._data });
                let stringify = JSON.stringify(this._data);
                super.send(stringify);
            // }
            // else window.location.href = "/site/login";
        }
        else throw new Error("type of sended data is "+typeof(data)+" need Object");
    }
    async fetchAuthInfoAsync() {
        try {
            let response = await fetch("/socket/auth", {
                method: 'GET',
                credentials: 'include'
            });
            console.warn("getUsetInfo");
            return await response.json();
        }
        catch (error) {
            throw new Error(error);
        }
    }
}