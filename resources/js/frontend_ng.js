import '../scss/frontend_ng.scss'

function updateLoginFormAction() {
    const loginRound = document.getElementById('loginround').value;
    if (loginRound == '') {
        alert('Du hast keine Runde ausgewählt.');
    } else {
        document.getElementById('loginform').action = loginRound;
    }
}

function rememberLoginRound() {
    const loginRound = document.getElementById('loginround').value;
    localStorage.setItem('round', loginRound);
}

function restoreLoginRound() {
    const loginRound = localStorage.getItem('round', loginRound);
    if (Object.values(document.getElementById('loginround').options).map(o => o.attributes.value.value).includes(loginRound)) {
        document.getElementById('loginround').value = loginRound;
    }
}

window.onload = function () {
    restoreLoginRound();
    updateLoginFormAction();
    document.getElementById("loginround").addEventListener('change', () => {
        updateLoginFormAction();
        rememberLoginRound();
    });
}
