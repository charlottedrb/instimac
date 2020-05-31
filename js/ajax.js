function postRequest(URL, body, callBackSuccess, callBackError) {
    var options = {
        method: 'post',
        body: body
    };
    sendAjaxRequest(URL, options, callBackSuccess, callBackError);
}

function getRequest(URL, body, callBackSuccess, callBackError) {

    var options = {
        method: 'get',
        headers: {"Content-Type": "application/x-www-form-urlencoded"}
    };

    if(Object.keys(body).length > 0) URL += '?' + urlencoded(body);
    delete options.body;
    sendAjaxRequest(URL, options, callBackSuccess, callBackError);
}

function sendAjaxRequest(URL, options, callBackSuccess, callBackError) {

    if (window.fetch && typeof (Promise) != 'undefined') {

        fetch(URL, options).then(function (response) {
            return response.json();
        }).then(function (success) {
            callBackSuccess(success);
        }).catch(function (error) {
            callBackError(error);
        });

    } else console.log('Method fetch not supported');
}

function arrayToFormData(body) {
    var form = new FormData();
    for (var prop in body) {
        if (body.hasOwnProperty(prop)) {
            form.append(prop, body[prop]);
        }
    }
    return form;
}

function urlencoded(object) {
    var encodedString = '';
    for (var prop in object) {
        if (object.hasOwnProperty(prop)) {
            if (encodedString.length > 0) encodedString += '&';
            encodedString += encodeURI(prop + '=' + object[prop]);
        }
    }
    return encodedString;
}
