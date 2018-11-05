// Set the date we're counting down to
function runTimer(end_date, id) {
    console.log(end_date, id, add_time, factor);
    var countDownDate = end_date * 1000;
    var now = new Date().getTime();
    if (alternate_time == true) {
        countDownDate = (countDownDate + add_time) * factor;
        now = (now + add_time) * factor;
    }
    // Get todays date and time
    // Find the distance between now and the count down date
    var distance = (countDownDate - now);

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    hours = ("0" + hours).slice(-2);
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    minutes = ("0" + minutes).slice(-2);
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    seconds = ("0" + seconds).slice(-2);

    var displayTime;
    if (days == 0) {
        displayTime = hours + ":" + minutes + ":" + seconds;
    } else {
        displayTime = days + "dagen " + hours + ":" + minutes + ":" + seconds;
    }
    // Display the result in the element with id="demo"
    document.getElementById(id).innerHTML = displayTime;

    // If the count down is finished, write some text
    if (distance < 0) {
        clearInterval(runTimer);
        document.getElementById(id).innerHTML = "EXPIRED";
    }
};
