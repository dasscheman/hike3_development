/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function createTimeTable(timeTableData) {
    console.log('timeTableData')
    var timetable = new Timetable();
    // timetable.setScope(timeTableData.start_eind[0], timeTableData.start_eind[1]); // optional, only whole hours between 0$
    timetable.addLocations(timeTableData.lokaties);
    for (var key in timeTableData.events) {
        var starttijd = timeTableData.events[key][1].split(/-| |:/);
        var eindtijd = timeTableData.events[key][2].split(/-| |:/);
        timetable.addEvent(
            timeTableData.events[key][0], //omschrijving
            key, // event/row
            new Date(starttijd[0],starttijd[1],starttijd[2],starttijd[3],starttijd[4]), //starttijd
            new Date(eindtijd[0],eindtijd[1],eindtijd[2], eindtijd[3], eindtijd[4]) //eindtijd
        );
    }
    var timetableRender = new Timetable.Renderer(timetable);
    timetableRender.draw('.timetable');
}
