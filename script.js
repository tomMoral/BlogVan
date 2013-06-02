/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
    $('textarea').autosize();
    $('.submit_comment').hide();
    set_text_area_background_color();
});


set_text_area_background_color = function() {
    var textareas = document.getElementsByTagName('textarea');

    for (i = 0; i < textareas.length; i++) {
        // you can omit the 'if' if you want to style the parent node regardless of its
        // element type

        textareas[i].onfocus = function() {
            this.parentNode.style.border = '1px solid #FD5400';
            this.parentNode.className += ' shadow';
            for (var i = 0; i < this.parentNode.childNodes.length; i++) {
                if (this.parentNode.childNodes[i].className === "submit_comment") {
                    $(this).parent().children().show();
                }
            }
        };
        textareas[i].onblur = function() {
            this.parentNode.style.border = '1px solid #A1A4A3';
            this.parentNode.className = 'fake_textarea';
            for (var i = 0; i < this.parentNode.childNodes.length; i++) {
                if (this.parentNode.childNodes[i].className === "submit_comment") {
                    $(this).parent().children().eq(1).hide();
                }
            }
        };
    }
};