/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


new_comment = function() {
    $(".button").click(function() {
        var body = $(this).parent().parent().parent();
        var text = body.children(".fake_textarea").children(".write_comment").val().replace(/\r?\n/g, 'linebreak');
        var id_post = body.children(".fake_textarea").children("input").val();
        $.post("ajax/createcomment.php", {text: text, id_post: id_post})
                .done(function(data) {
            var here=$("#fake_area_"+id_post).parent();
            here.parent().append(data+'<div class="write" >'+
                    '<div class="fake_textarea" id="fake_area_'+id_post+'">'+
                        '<textarea class="write_comment" placeholder="Write something" name="body"></textarea>'+
                        '<div class="submit_comment">'+
                            '<input type="submit" value="post" class="button"/>'+
                        '</div><input type="hidden" name="id" value="'+id_post+'">'+
                    '</div>'+
                '</div>');
            here.remove();
            set_text_area_background_color();
            new_comment();
        });
    });
}

function validateEmail(email) {

    var reverse = email.split("").reverse().join("");
    var at = reverse.indexOf('@');
    var point = reverse.indexOf('.');
    return point > 0 && at > point;
}


set_text_area_background_color = function() {
    $('textarea').focus(function() {
        $(this).parent().css("border", '1px solid #FD5400');
        $(this).parent().children(".submit_comment").show();
        $(this).parent().className += ' shadow';
    });
    $(document).mouseup(function(e) {
        var textareas = document.getElementsByTagName('textarea');
        for (var i = 0; i < textareas.length; i++) {
            var that = $(textareas[i]).parent();
            if (that.has(e.target).length === 0) {
                that.css("border", '1px solid #A1A4A3');
                that.className = 'fake_textarea';
                that.children(".submit_comment").hide();
            }
        }
    });
};

