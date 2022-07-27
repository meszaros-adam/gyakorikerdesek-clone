import axios from 'axios'

async function useCallApi(method, url, dataObj) {
    try {
        return await axios({
            method: method,
            url: url,
            data: dataObj,
        })
    }
    catch (e) {
        return e.response
    }
}
function useValidateEmail(email) {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
}

export {useValidateEmail, useCallApi}