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
            console.log(errMsg + data);
        }
    });
};

document.getElementById("map-click").addEventListener("click", setCookie);

window.onload = function() {
    var tracker = new Tracker();
    tracker.setSavingInterval(300000);
    tracker.setGPSConfiguration({
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 18000000
    });

    tracker.onChange(ChangePosition);
    tracker.run();
};


function ChangePosition(pos) {
    var crd = pos.coords;
    var path = [];
}

function switchAllowTracking() {
    var getUrl = window.location;
    var link = getUrl .protocol + "//" + getUrl.host + "" + getUrl.pathname.split('?')[0] + '?r=track/switch';

    $.ajax({
        url: link,
        type: 'POST',
        success: function (data) {
            console.log('success');
            console.log(data);
        },
        error: function(jqXHR, errMsg, data) {
            alert(errMsg + data);
        }
    });
}
