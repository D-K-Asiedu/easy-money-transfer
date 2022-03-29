$(document).ready(() => {
    $.getScript('../js/custom/utilities.js').done(function () {
        let utils = Utilities;
        let filename = utils.getUrlFileName();
        let queryString = utils.getUrlQueryStrings();
        let dept = queryString['dept'];
        let type = queryString['type'];
        


    }); //end getscript
}); //end document ready