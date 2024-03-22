// When the window loads or resizes, adjust the padding-top of .post-wrapper
function adjustPostWrapperPadding() {
var header = document.querySelector('#header-bar');
if (header) {
    var headerHeight = header.offsetHeight;
    var headerTop = header.offsetTop;
    var postWrapper = document.querySelector('.post-wrapper');
    if (postWrapper) {
    postWrapper.style.paddingTop = (headerHeight + headerTop - 100)  + 'px';
    }
}
}

// Run the function on load and resize
window.onload = adjustPostWrapperPadding;
window.onresize = adjustPostWrapperPadding;