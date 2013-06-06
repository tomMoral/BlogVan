/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function validateEmail(email) { 

    var reverse = email.split("").reverse().join("");
    var at = reverse.indexOf('@');
    var point = reverse.indexOf('.');
    return point>0 && at>point;
} 


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
                    //$(this).parent().children().eq(1).hide();
                }
            }
        };
    }
};


//ajax scripts:

function getXMLHttpRequest() {
    var xhr = null;

    if (window.XMLHttpRequest || window.ActiveXObject) {
        if (window.ActiveXObject) {
            try {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
        } else {
            xhr = new XMLHttpRequest();
        }
    } else {
        alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
        return null;
    }

    return xhr;
}


function request(callback, page, arg) {
    //page is the page we send the arg to and wich return the value display by callback
    //cf connexion for an example
    var xhr = null;
    if (xhr && xhr.readyState != 0) {
        xhr.abort(); // On annule la requête en cours !
    }

    xhr = getXMLHttpRequest(); // plus de mot clé 'var'

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
            callback(xhr.responseText);
        }
    };

    xhr.open("GET", page + "?arg=" + arg, true);
    xhr.send(null);
}
