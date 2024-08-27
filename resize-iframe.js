// to resize the height of the given DOM object to that of its content
function resizeIframe(obj) {
    obj.style.height =
	obj.contentWindow.document.documentElement.scrollHeight + 'px';
    obj.style.width = '100%';
    obj.style.border = 'none';
}
