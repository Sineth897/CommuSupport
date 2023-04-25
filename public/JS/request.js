
async function getData(URL, method = 'get', data = {}) {
    return fetch(URL, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .catch(error => {
            console.error('Error:', error);
        });
}

async function getTextData(URL, method = 'get', data = {}) {
    return fetch(URL, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.text())
        .catch(error => {
            console.error('Error:', error);
        });
}

export {getData,getTextData} ;


