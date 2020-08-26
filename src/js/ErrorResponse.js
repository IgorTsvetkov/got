export default class ErrorResponse{
    constructor(result) {
        this.message=result.data.error;
    }
}