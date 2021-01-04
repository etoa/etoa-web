function updateFormAction() {
    const loginRound = document.getElementById('loginround').value;
    if (loginRound == '') {
        alert('Du hast keine Runde ausgewählt.');
    } else {
        document.getElementById('loginform').action = loginRound;
    }
}

function rememberLoginRound() {
    const loginRound = document.getElementById('loginround').value;
    const expiryDate = new Date((new Date()).getTime() + 1000 * 60 * 60 * 24 * 365);
    document.cookie = 'round=' + loginRound + ';expires=' + expiryDate.toGMTString() + ';';
}

window.onload = function () {
    document.getElementById('loginform').addEventListener('submit', updateFormAction);
    document.getElementById("loginround").addEventListener('change', rememberLoginRound);
}
