require('./bootstrap');

window.fallbackCopyTextToClipboard = function fallbackCopyTextToClipboard(text) {
    let textArea = document.createElement("textarea");
    textArea.value = text;

    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        let successful = document.execCommand('copy');
        // var msg = successful ? 'successful' : 'unsuccessful';
        // console.log('Fallback: Copying text command was ' + msg);
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
    }

    document.body.removeChild(textArea);
}
window.copyTextToClipboard = function (text) {
    console.log('copied')
    console.log(text)

    if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
    }
    navigator.clipboard.writeText(text).then(function() {
        // console.log('Async: Copying to clipboard was successful!');
    }, function(err) {
        console.error('Async: Could not copy text: ', err);
    });
}
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-copy]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault()

            copyTextToClipboard(this.dataset.copy)

            this.classList.add('animate-ping')

            setTimeout(() => this.classList.remove('animate-ping'), 1000)
        })
    })
});
