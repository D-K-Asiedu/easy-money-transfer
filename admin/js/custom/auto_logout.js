$(document).ready(function() {
    let url = window.location.pathname;
    let filename = url.substring(url.lastIndexOf('/') + 1);
    let logouTimeout = null;
    let min = 15 * 60 * 1000;
    $(document).on('mousemove', function() {
        if (sessionStorage.getItem("token") == null && (filename == "login" || filename == "forgot"|| filename == "signup")){
           
        }else if (sessionStorage.getItem("token") == null){            
            location.href = 'login';
        }

        clearTimeout(logouTimeout);
        logouTimeout = setTimeout(function() {
            if (filename != "login") {
                sessionStorage.removeItem('token');
                location.href = 'login';
            }
        }, min);
    });

    $("#logout").on("click", () => {
        sessionStorage.removeItem("token");
        window.location.href = "login";
    });

});