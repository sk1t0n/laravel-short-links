export function createToken(fullUrl) {
    fetch('/api/v1/create', {
        body: JSON.stringify({ full_url: fullUrl }),
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => {
            if (response.redirected) {
                throw 'Error: invalid url';
            }
            return response.json();
        })
        .then((data) => {
            console.log(data);
            result = document.getElementById('result');
            result.innerText = `token = ${data.token}`;
        })
        .catch((error) => console.error(error));
}
