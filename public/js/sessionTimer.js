
    // Set the one-minute timer
    var now = new Date();
    now.setMinutes(now.getMinutes() + 1);
    var countDownDate = now;
    console.log(countDownDate);

// Update the count down every 1 second
    var x = setInterval(function() {

        // Get current time
        var now = new Date().getTime();
        var interval = countDownDate - now;

        var days = Math.floor(interval / (1000 * 60 * 60 * 24));
        var hours = Math.floor((interval % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((interval % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((interval % (1000 * 60)) / 1000);

        // Output the result in an element with id="timer"
        document.getElementById("timer").innerHTML = "Session expires in " + minutes + " min "
            + seconds + " seconds ";

        // If the timer is over, notify the user with an "Session expired" text
        if (interval < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "Session expired";
            document.getElementById("timer").style.color = "#ff0000";
        }
    }, 1000);

    jQuery.ajax({
        type: "POST",
        url: '/resources/views/reservation/sessionTimoutRequest.php',
        dataType: 'json',
        data: {functionname: 'endSessionRequest', arguments: []},

        success: function (obj, textstatus) {
            if( !('error' in obj) ) {
                console.log("Success");
            }
            else {
                console.log(obj.error);
            }
        }
    });
