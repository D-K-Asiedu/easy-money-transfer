// import { Utilities } from '../../../js/custom/utilities.js';
// import { Settings } from '../../../js/custom/settings.js';
$(document).ready(function() {
    $.getScript("../js/custom/utilities.js").done(function(e) {

        let utils = Utilities;
        // let settings = Settings;
        let vFactory = utils.ValidationFactory;
        $("#btn_change").on("click", () => {
            utils.getPerms(utils, "change_credentials", (perm) => {
                let ou = $.trim($("#old_username").val());
                let op = $.trim($("#old_password").val());
                let nu = $.trim($("#new_username").val());
                let np = $.trim($("#new_password").val());
                let cp = $.trim($("#c_password").val());
                if (np != cp) { utils.toastMsg("New password mistmatch", "red", "red"); return; }
                utils.postItems(JSON.stringify([ou, op, nu, np, cp, sessionStorage.getItem('token'), perm]), function(res) {
                    let parsedRes = JSON.parse(res);
                    if (parsedRes.status == "failed") {
                        utils.toastMsg(parsedRes.msg, "green", "green");
                    } else if (parsedRes.status == "Ok") {
                        utils.toastMsg("Changes made successfully", "green", "green");
                        setTimeout(() => {
                            $("#logout").trigger("click");
                        }, 5000);

                    }
                });
            });
        });
    });

});