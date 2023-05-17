function validateEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isValidEmail = emailRegex.test(email);

    if (!isValidEmail) {
        emailInput.classList.add('is-invalid');
        return false;
    } else {
        emailInput.classList.remove('is-invalid');
        return true;
    }
}

function validatePassword() {
    const password1 = document.getElementById('password1').value.trim();
    const password2 = document.getElementById('password2').value.trim();
    const passwordError = document.getElementById('passwordError');

    if (password1 !== password2) {
        passwordError.innerText = 'Passwords do not match';
        return false;
    } else {
        passwordError.innerText = '';
        return true;
    }
}