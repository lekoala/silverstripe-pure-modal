function resizeIframe(iframe) {
    iframe.onload = function () {
        iframe.style.height =
            iframe.contentWindow.document.body.scrollHeight + "px";
    };
}
