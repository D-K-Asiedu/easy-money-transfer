import { Utilities } from '../../../js/custom/utilities.js';
let utils = Utilities;
$(document).ready(function() {
    // $.getScript("../js/custom/utilities.js").done(function() {
        $(".direct").on("click", function(e) {
            e.preventDefault();
            let href = $(this).prop("href");
            
            let tok = sessionStorage.getItem('token');
            utils.redirectTo(href, 'token', tok);
        });
    // });
});