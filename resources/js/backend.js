console.log('hello world');

document.addEventListener("DOMContentLoaded", function (event) {
    const element = document.createElement('h1')
    element.innerHTML = "Hello backend World"
    document.body.appendChild(element)
})
