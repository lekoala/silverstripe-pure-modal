function resizeIframe(iframe) {
    var $obj = jQuery(iframe);
    var height = $obj.contents().height();
    if ($obj.is(":visible") || height == 0) {
        var clone = $obj.clone().attr("id", false).css({
            visibility: "hidden",
            display: "block",
            position: "absolute",
        });
        jQuery("body").append(clone);
        height = clone.height();
        clone.remove();
    }
    if (height > 0) {
        $obj.height(height);
    }
}
