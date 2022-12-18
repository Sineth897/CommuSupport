
export default function request() {
    return {
        getData: getData
    }
}

async function getData(URL, method, data) {
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




