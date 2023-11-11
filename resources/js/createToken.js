export function createToken(fullUrl) {
    fetch('/api/v1/create', {
        body: JSON.stringify({ full_url: fullUrl }),
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((data) => {
            result = document.getElementById('result');
            result.innerText = `token = ${data.token}`;
        });
}
