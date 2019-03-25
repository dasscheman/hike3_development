/**
 * A utility to tracking gps html5 with unique ID user and send to servir with ajax
 * each N time.
 *
 * @class Tracker
 * @autor @jamesjara
 */
function Tracker() {

    var getUrl = window.location;
    this.webserviceUrl = getUrl.protocol + "//" + getUrl.host + "" + getUrl.pathname.split('?')[0] + '?r=track/create';
    this.changeCallBack = null; //ChangePosition;
    this.onErrorCallBack = null;
    this.configArray = null;
    this.savingInterval = null;
    this.navigatorId = null;
    this.loadedJQ = false;
    this.data = new Array();

    this.setWebserviceUrl = function(webserviceUrl) {
        console.log('setWebserviceUrl');
        this.webserviceUrl = webserviceUrl;
    };
    this.getWebserviceUrl = function() {
        return this.webserviceUrl;
    };
    this.setGPSConfiguration = function(array) {
        console.log('setGPSConfiguration');
        this.configArray = array;
    };
    this.onChange = function(callback) {
        console.log('onChange');
        this.changeCallBack = callback;
    };
    this.onChangePosition = function(data) {
        console.log('onChangePosition');
        if (this.ProcessGpsData(data)) {
            this.changeCallBack(data);
        } else {
            alert('error saving gps data ');
        }
    };
    this.startTracking = function() {
        console.log('startTracking');
        if (this.navigatorId > 0) {
            console.log('navigator.geolocation already running');
            return;
        }
        if (navigator.geolocation) {
            console.log('navigator.geolocation works');
            this.navigatorId = navigator.geolocation.watchPosition(bind(this,
                    this.onChangePosition), this.onErrorCallBack,
                this.configArray);
        } else {
            document.write(' FATAL - ERROR navigator.geolocation need to be enabled ');
        }
        this.resetData();
    };
    this.stopTracking = function() {
        console.log('stopTracking');
        navigator.geolocation.clearWatch(this.navigatorId);
        this.setSavingInterval(20000);
        this.navigatorId = null;
        this.resetData();
    };
    this.onError = function(callback) {
        console.log('onError');
        this.onErrorCallBack = callback;
    };
    this.ProcessGpsData = function(callback) {
        return this.saveData(callback);
    };
    this.isOk = function() {
        var result = true;
        if (this.webserviceUrl == null)
            result = false;
        if (!window.jQuery)
            result = false;
        return result;
    };

    this.getStatus = function(callbackStart, callbackStop) {
        var getUrl = window.location;
        var link = getUrl.protocol + "//" + getUrl.host + "" + getUrl.pathname.split('?')[0] + '?r=track/status';
        console.log('getstatus');
        $.ajax({
            url: link,
            type: 'POST',
            success: function(data) {
                if (data === 'true') {
                    callbackStart();
                } else {
                    callbackStop();
                }

            },
            error: function(jqXHR, errMsg, data) {
                console.log(errMsg + data);
            }
        });
    };

    this.saveData = function(pos) {
        var temp = new Array();
        temp = {
            accuracy: pos.coords.accuracy,
            latitude: pos.coords.latitude,
            longitude: pos.coords.longitude,
            timestamp: pos.timestamp / 1000 | 0
        };
        this.data.push(temp);
        return true;
    };
    this.sendData = function(stopTracking) { // TODO must be sincronico , validar response
        console.log('sendData');
        if (this.isOk()) {
            if (this.data.length >= 1) {
                $.ajax({
                    type: "POST",
                    url: this.getWebserviceUrl(),
                    cache: false,
                    data: {
                        trackdata: JSON.stringify(this.getData())
                    },
                    success: function(data) {
                        if (data === 'false') {
                            stopTracking();
                        }
                    },
                    error: function(jqXHR, errMsg, data) {
                        console.log(errMsg + data);
                    }
                });
                this.resetData();
            }
        } else {
            console.log('imcomplete configuration or jquery missing ');
            this.injectJquery();
        }
    };
    this.getData = function() {
        return JSON.stringify(this.data);
    };

    this.resetData = function() {
        this.data = [];
    };
    this.setSavingInterval = function(value) {
        this.savingInterval = value.valueOf();
    };
    this.getSavingInterval = function() {
        return this.savingInterval;
    };
    this.loadedJquery = function(value) {
        this.loadedJQ = value;
    };
    // this.injectJquery = function(callback) {
    //     console.log('injectJquery');
    //     if (this.loadedJQ == false && !window.jQuery) {
    //         console.log('!window.jQuery');
    //         var script = document.createElement('script');
    //         script.type = "text/javascript";
    //         script.src = "//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js";
    //         script.onload = this.loadedJquery(true);
    //         document.getElementsByTagName('head')[0].appendChild(script);
    //     }
    // };

    this.run = function() {
        var self = this;
        console.log('run');
        // this.injectJquery();
        //set interval loopt niet vanaf de start, daarom eerst een keer sowieso runnen.
        this.getStatus(function() {self.startTracking();}, function() {self.stopTracking();});
        self.sendData(function() {self.stopTracking(); });
        setInterval(function() {
            self.getStatus(function() {self.startTracking();}, function() {self.stopTracking();});
            self.sendData(function() {self.stopTracking(); });
        }, this.getSavingInterval());
    };

    function bind(scope, fn) {
        return function() {
            fn.apply(scope, arguments);
        };
    }
}
