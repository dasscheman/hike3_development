/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function() {
    //save the latest tab (http://stackoverflow.com/a/18845441)
    $('a[data-toggle="tab"]').on('click', function (e) {
        localStorage.setItem('lastTab', $(e.target).attr('href'));
    });

    //go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');

    if (lastTab) {
        $('a[href="'+lastTab+'"]').click();
    }
});

function setCookie() {
    var getUrl = window.location;
    var link = getUrl .protocol + "//" + getUrl.host + "" + getUrl.pathname.split('?')[0] + '?r=site/cookie';
      
    $.ajax({
        url: link,
        type: 'POST',
        data: {
            screen_heigth: window.screen.height
        },
        success: function (data) {
        },
        error: function(jqXHR, errMsg, data) {
            alert(errMsg + data);
        }
    });
};

document.getElementById("map-click").addEventListener("click", setCookie);

