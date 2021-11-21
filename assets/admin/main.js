$(function() {
    // Remove Osclass's (stupid) select UI.
    $('.select-box-trigger').remove();
    $('.select-box').replaceWith(function() {
        return $('select', this);
    });
    $('select').addClass('uk-select').css('opacity', '1');
})