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
    // var tracker = new Tracker();
    // tracker.setSavingInterval(30000);
    // tracker.setGPSConfiguration({
    //     enableHighAccuracy: true,
    //     timeout: 10000,
    //     maximumAge: 18000000
    // });
    //
    // tracker.onChange(ChangePosition);
    // tracker.run();
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


// function create(timeTableData) {
//   console.log('timeTableData')
//     var timetable = new Timetable();
//     timetable.setScope(timeTableData.start_eind[0], timeTableData.start_eind[1]); // optional, only whole hours between 0$
//     timetable.addLocations(timeTableData.lokaties);
//     for (var key in timeTableData.events) {
//       var starttijd = timeTableData.events[key][1].split(/-| |:/);
//       var eindtijd = timeTableData.events[key][2].split(/-| |:/);
//       timetable.addEvent(
//         timeTableData.events[key][0], //omschrijving
//         key, // event/row
//         new Date(starttijd[0],starttijd[1],starttijd[2],starttijd[3],starttijd[4]), //starttijd
//         new Date(eindtijd[0],eindtijd[1],eindtijd[2], eindtijd[3], eindtijd[4]) //eindtijd
//       );
//     }
//     var timetableRender = new Timetable.Renderer(timetable);
//     timetableRender.draw('.timetable');
// }
