function validateTitle() {
    const title = document.getElementById('post_title').value.trim();
    const titleError = document.getElementById('titleError');

    if (title === "") {
        titleError.innerText = 'Post title cannot be empty';
        return false;
    } else {
        titleError.innerText = '';
        return true;
    }  
}

function validateText() {
    const text = document.getElementById('post_text').value.trim();
    const textError = document.getElementById('textError');

    if (text === "") {
        textError.innerText = 'Post text cannot be empty';
        return false;
    } else {
        textError.innerText = '';
        return true;
    }  
}

