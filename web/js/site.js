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

window.onload = function() {
    var tracker = new Tracker();
    tracker.setSavingInterval(5000);
    tracker.setGPSConfiguration({
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    });

    tracker.onChange(ChangePosition);
    tracker.run();
};


//function ChangePosition(pos) {
//    var crd = pos.coords;
//    var path = [];
//    console.log('Latitude : ' + crd.latitude);
//    console.log('Longitude: ' + crd.longitude);
//    console.log('More or less ' + crd.accuracy + ' meters.');
//    lastll = crd.latitude;
//    lastlo = crd.longitude;
//    var temp = new Array();
//    temp[0] = crd.latitude;
//    temp[1] = crd.longitude;
//    path.push(temp);
//    if (google.map) {
//        google.map.removeMarkers();
////        google.map.addMarker({
////            lat: crd.latitude,
////            lng: crd.longitude,
////            title: 'Id:' + this.getUserId(),
////            click: function(e) {
////                alert('Id:' + this.getUserId());
////            }
////        });
//
////        google.map.drawCircle({
////            lat: crd.latitude,
////            lng: crd.longitude,
////            radius: 300, //metros
////            strokeColor: '#432070',
////            strokeOpacity: 1,
////            strokeWeight: 3,
////            fillColor: '#432070',
////            fillOpacity: 0.6
////        });
//
//        google.map.drawPolyline({
//            path: path,
//            strokeColor: '#131540',
//            strokeOpacity: 0.6,
//            strokeWeight: 6
//        });
//    }
//};

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
