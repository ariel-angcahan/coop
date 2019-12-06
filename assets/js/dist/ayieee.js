$(document).ready(function(){
    $("input").attr("autocomplete", "off");
});

(function(){

    const print = (param) => {
        const { size: { x, y = 50}, price = 0 } = param;
        // console.log(`Size: ${y} and Price: ${price}`);
    }

    this.size = 10;
    this.price = 100;
    this.x = 100;
    this.y = 75;

    print({
        size: {
            x: 50,
            y: 300
        },
        price: this.price
    });


})();

function request(url, data, global_ = true, dataType = "json"){
    return $.ajax({
        type: "POST",
        url: url,
        dataType: dataType,
        data: data,
        global: global_,
        error: function(jqXHR, lsStatus, ex){
            switch(jqXHR.status){
                case 404:
                    swal({
                        title: "System Location", 
                        text: "Page not found!", 
                        type: "warning"
                    }, function(){
                        // location.href = location.origin;                   
                    });
                break;
                case 403:
                    swal({
                        title: "Timeout", 
                        text: "Session not found, you will redirecting to main page!", 
                        type: "warning"
                    }, function(){
                        location.reload();
                        // location.href = location.origin;                   
                    });
                break;
                case 500: 
                    swal({
                        title: "System Error!", 
                        text: "Please contact your administrator!", 
                        type: "warning"
                    }, function(){
                    });
                break;
            }
        }
    });
}

function request_with_file(url, data, global_ = true){
    return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        global: global_,
        error: function(jqXHR, lsStatus, ex){
            swal("System Error!", "Please contact your administrator!", "warning");
        }
    });
}

$( document ).ajaxStart(function() {
  $(".page-loader-wrapper").css('background', '#eee6').show();
});

$( document ).ajaxStop(function(e) {
    // console.log(JSON.stringify(e));
    // console.log(JSON.stringify(e.target["location"].href));
  $(".page-loader-wrapper").hide();
});

$(document).ready(function(){
    $('.ayieee-active > a > span').css('color', '#2196F3');
    $('.ayieee-active > a').css('color', '#2196F3');
    // $('.menu-toggle').click();
    $('.ml-menu').show();
});

//ayieee made  this because jquery dblclick has a bug on mobile version and it will not work
var touchtime = 0;
function dblClick(target, callback, outputFormat){
    $(document).on('click', target, function(e){
        if (touchtime == 0) {
            // set first click
            touchtime = new Date().getTime();
        } else {
            // compare first click to this click and see if they occurred within double click threshold
            if (((new Date().getTime()) - touchtime) < 800) {
                // double click occurred
                callback($(this));
                touchtime = 0;
            } else {
                // not a double click so set as a new first click
                touchtime = new Date().getTime();
            }
        }
    });
}

// jQuery validation override methods
$.validator.addMethod("not-zero", function(value, element) {
  return parseInt(value) != 0;
}, "This field must not be zero!");

$.validator.addMethod("loanable-amount", function(value, element) {
  return numeral(value).format("0.00") <= numeral(window.atob($("#max-loanable-amount").val())).format("0.00");
}, function(param, element){
    return `Loanable amount must not be greater than ${window.atob($("#max-loanable-amount").val())}`
});

jQuery.validator.addMethod("noSpace", function(value, element) { 
    value = value.trimLeft();
    return value != ""; 
}, "Space is not allowed.");

jQuery.validator.addMethod("ayieee_email", function(value, element) { 
    value = value.split("@");
    return value[1].indexOf('.') > -1 && value[1].trimRight() != '.';
}, "Please enter a valid email address.");

jQuery.validator.addMethod("ayieee_mobile", function(value, element) { 
    return value.search('_') < 0;
}, "Please enter a valid mobile no.");

jQuery.validator.addMethod("money", function(value, element) { 
    return numeral(value).format('$0,0.00');
}, "Please enter a valid amount no.");

jQuery.validator.addMethod("notZero", function(value, element) { 
    return value != 0;
}, "This field is required.");

jQuery.validator.addMethod("notHigherToSubscriptionAmount", function(value, element) { 
    return numeral(value).value() < numeral($('#subscription_amount').val()).value();
}, "Payment must not higher or equal than subscription amount!.");

jQuery.validator.addMethod("notDecimalPaymentPerMode", function(value, element) {
    return Number.isInteger(numeral($('#subscription_amount').val()).value() / numeral(value).value());
}, "Quotient of the subscription amount and payment per mode must not be decimal.");

































//phut tai namu tai namuka





// (function(){
//     this.size = 10;
//     this.price = 100;

//     const print = (param) => {
//         const { size, price = 0 } = param;
//         console.log(`Size: ${size} and Price: ${price}`);
//     }

//     print({
//         size: this.size,
//         price: this.price
//     });
// })();

// AYIEEE SECURITY FOR TRAPPING SCRIPT REQUEST
// window.onclick = function(e){
//     console.log(e);
//     var fd = e.path[0].outerHTML.toString().replace("<", "");
//     var gg = ["button", "a", "input"];
//     var fg = fd.split(" ", 1);
//     if(gg.indexOf(fd.split(" ", 1)[0]) >= 0){
//         var mps = gp(e);
//         xax = mps['0xG3'];
//         yax = mps['0xF1'];
//         eda = e.layerX;
//         ht = e.layerY;
//         wlo = gwl(window);
//         console.log("you found me!");
//     }else{
//         console.log("sad!");
//     }
// }

function se(nameKey, myArray){
    for (var i=0; i < myArray.length; i++) {
        if (myArray[i].name === nameKey) {
            return myArray[i];
        }
    }
}

function gp(e){
    return { '0xG3' : e.pageX.toString().sth(), '0xF1' : e.pageY.toString().sth()};
}

function gwl(qwe){
    return qwe.location.origin;
}

String.prototype.hts = function(){
    var hex  = this.toString();
    var str = '';
    for (var n = 0; n < hex.length; n += 2) {
        str += String.fromCharCode(parseInt(hex.substr(n, 2), 16));
    }
    return str;
}

String.prototype.sth = function(){
    var arr1 = [];
    for (var n = 0, l = this.length; n < l; n ++) 
     {
        var hex = Number(this.charCodeAt(n)).toString(16);
        arr1.push(hex);
     }
    return arr1.join('');
}

// window.console.log = function(){
//   console.error('Sorry , developers tools are blocked here....');
//   window.console.log = function() {
//       return false;
//   }
// }

// console.log('test');    

var _z = console;
Object.defineProperty( window, "console", {
    get : function(){if( _z._commandLineAPI ){ throw "Script execution not permitted" } return _z; },
    set : function(val){ _z = val }
});