export default class Helper {
    hasError(result) {
        if (result &&result.error)
            return true;
        return false;
    }
    getError(result){
        return result.error;
    }
    getErrorMessage(result){
        return result.error.message;        
    }
}