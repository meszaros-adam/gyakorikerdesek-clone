import axios from 'axios'

export default async function (method, url, dataObj) {
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