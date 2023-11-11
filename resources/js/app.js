import './bootstrap';
import { createToken } from './createToken';

addEventListener('DOMContentLoaded', () => {
    const submit = document.querySelector('[type="submit"]');
    submit.addEventListener('click', function (e) {
        e.preventDefault();
        const fullUrl = document.getElementById('full_url');
        createToken(fullUrl.value);
    });
});
