export default class Helper {
    hasError(result) {
        if (result.data && result.data.error)
            return true;
        return false;
    }
    getError(result) {
        if (result.data)
            return result.data.error;
    }
    getErrorMessage(result) {
        if (result.data)
            return result.data.error.message;
    }
    getAction(result) {
        if (result.data)
            return result.data.action;
    }
    setAction(actionName){
        return {data:{action:actionName}};
    }
    isRedirect(result) {
        if (result.data.redirect)
            return true;
        return false;
    }
    Redirect(result) {
        if (result.data)
            window.location.pathname=result.data.redirect.url;
    }
    handleGameError(result,socket){
        if (this.hasError(result)) {
            let message = this.getErrorMessage(result);
            socket.send({}, message);
            return true;
          }
          return false;
    }
}