// import { Utilities } from '../../../js/custom/utilities.js';
// let utils = Utilities;
let color = '#052498';
$(document).ready(function() {    
    $.getScript("../js/custom/utilities.js").done(function() {
        let utils = Utilities;
        utils.listentoEnterKey('.inputs', '#login');
        $("#username").change();
        $("#password").change();
        $("#login").on("click", () => {
            $("#login_spinner").removeClass("d-none");
            let usr = $.trim($("#username").val());
            let psd = $.trim($("#password").val());
            
            let items = [usr, psd, "login"];
            utils.postItems(JSON.stringify(items), (res) => {
                let parsedRes = JSON.parse(res);
                let status = parsedRes.status;
                if (status == "Ok") {
                    let token = parsedRes.token;
                    sessionStorage.setItem('token', token);
                    sessionStorage.setItem("adname", parsedRes.name)
                    utils.redirectTo('./#event', 'token', token);
                } else {
                    $("#login_spinner").addClass("d-none");                    
                    utils.toastMsg(parsedRes.msg,color,color);
                }
            });
        });
    });
});