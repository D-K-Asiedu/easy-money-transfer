import { flexCard } from '../../../ui_component_lib/flexCard.js';
import { Utilities } from '../../../js/custom/utilities.js';
import { pairedBarChart } from '../../../ui_component_lib/barChart.js';

$(document).ready(function() {
    let utils = Utilities;
    // let bc = null;
    utils.getPerms(utils, "get_stats", (perm) => {
        setInterval(function() {
            getStats(perm, null);
        }, 1000 * 60);
    });
    $("#adname").html(sessionStorage.getItem('adname'));

    utils.getPerms(utils, "get_stats", (perm) => {
        let data = {
            dataset1: { data: [10, 11, 10, 10], title: "" },
            dataset2: { data: [3, 4, 5, 6], title: "" },
        }
        let pcb = pairedBarChart("myChart", ['Red', 'Blue', 'Yellow', 'Green'], data, "Total Sales")
        getStats(perm, pcb);
    });

    function getStats(perm, bc) {
        utils.postItems(JSON.stringify([sessionStorage.getItem('token'), perm]), function(res) {
            let parsedRes = JSON.parse(res);
            let t1 = flexCard("fas fa-pencil-alt text-info fa-3x", "Direct", parsedRes.dt.printed, parsedRes.dt.generated);
            let t2 = flexCard("far fa-life-ring text-danger fa-3x", "Matured", parsedRes.mat.printed, parsedRes.mat.generated);
            let t3 = flexCard("fas fa-rocket text-warning fa-3x", "Post-Diploma", parsedRes.pd.printed, parsedRes.pd.generated);
            let t4 = flexCard("far fa-user text-success fa-3x", "Post-Graduate", parsedRes.pg.printed, parsedRes.pg.generated);
            let p = parsedRes.dt.printed + parsedRes.mat.printed + parsedRes.pd.printed + parsedRes.pg.printed;
            let g = parsedRes.dt.generated + parsedRes.mat.generated + parsedRes.pd.generated + parsedRes.pg.generated;
            let t5 = flexCard("fas fa-pencil-alt text-info fa-3x", "Total", p, g);
            $("#mysm_card").empty().append(t1).append(t2).append(t3).append(t4).append(t5);
            // $("#all_total").empty().append(t5);
            if (bc != null) bc.destroy();
            let data = {
                dataset1: { data: [parsedRes.dt.printed, parsedRes.mat.printed, parsedRes.pd.printed, parsedRes.pg.printed], title: "Printed Vouchers" },
                dataset2: { data: [parsedRes.dt.generated, parsedRes.mat.generated, parsedRes.pd.generated, parsedRes.pg.generated], title: "Total Generated Vouchers" },
            }
            bc = pairedBarChart("myChart", ['Direct', 'Matured', 'Post-Dip', 'Post-Grad'], data);
        });
    }

    utils.getPerms(utils, "get_sales", (perm) => { getSalesDataPerUser(perm) });

    function getSalesDataPerUser(perm) {
        utils.postItems(JSON.stringify([sessionStorage.getItem('token'), perm]), function(res) {
            let parsedRes = JSON.parse(res);
            let data = [];
            for (let i = 0; i < parsedRes.length; i++) {
                let mode = parsedRes[i].name;
                let sn = parsedRes[i].serial_no;
                let ps = parsedRes[i].pin;
                let pid = parsedRes[i].id;
                let date = parsedRes[i].date_inserted;
                let hasPrt = parsedRes[i].has_printed == 0 ? "No" : "Yes";
                let prt = '<i style="cursor:pointer" class="fas fa-print text-primary print" data-id="' + pid + '"></i>';
                data.push([sn, ps, mode, hasPrt, date, prt]);
            }

            $("#salesData").DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "full_numbers",
                "responsive": true,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                dom: 'Blfrtip',
                // dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                columns: [{
                        title: "Serial Number"
                    },
                    {
                        title: "Pin"
                    },
                    {
                        title: "Mode"
                    },
                    {
                        title: "Has Printed"
                    },
                    {
                        title: "Date"
                    },
                    {
                        title: "Print"
                    }
                ],

                data: data
            }); //data table                
        });
    } //end function 


    $("#salesData").on("click", ".print", function() {
        let id = $(this).data("id");
        let tr = $(this).closest("tr");
        let td = $(tr).find("td");
        let sn = $(td[0]).text();
        let pn = $(td[1]).text();
        utils.getPerms(utils, "get_voucher_to_print", (perm) => { printVoucher(perm, sn, pn) });
    });

    function printVoucher(perm, sn, pn) {
        utils.postItems(JSON.stringify([sn, pn, sessionStorage.getItem('token'), perm]), function(res) {
            let parsedRes = JSON.parse(res);
            if (parsedRes.status == "Ok") {
                let parsedRes = JSON.parse(res);
                $("#serial").html(parsedRes.res[0].serial_no);
                $("#pin").html(parsedRes.res[0].pin);
                $("#issuer").html(parsedRes.res[0].usr);
                $("#vmode").html(parsedRes.res[0].name);
                $("#amount").html("GHC " + parsedRes.res[0].price);
                $("#pid_bcode").prop("src", parsedRes.bcode);
                $("#voucher_modal").modal("show");
            } else {
                $.toast("Printing Failed");
            }
        });
    }


    $("#prtVoucher").on("click", function() {
        let sn = $("#sn").text();
        let pn = $("#pn").text();
        utils.getPerms(utils, "get_voucher_to_print", (perm) => { printVoucher(perm, sn, pn) });
    });

    $("#genVoucher").on("click", function() {
        let mode = $("#mode").val();
        if (mode == 0) {
            $.toast("Please select the entry mode");
            return;
        }
        utils.getPerms(utils, "get_voucher", (perm) => {
            utils.postItems(JSON.stringify([mode, sessionStorage.getItem('token'), perm]), function(res) {
                let parsedRes = JSON.parse(res);
                let sn = parsedRes.sn;
                let pn = parsedRes.pn;
                $("#sn").html(sn);
                $("#pn").html(pn);
            });
        });
    });

    $("#prt_letter").on("click", () => {
        $("#letter_modal").modal('show');
    });


    $("#btn_print").on('click', () => {
        let css = '<link href="../css/bootstrap.min.css" rel="stylesheet">' +
            '<link href="../css/custom_overall/voucher_prt_css.css" rel="stylesheet">';
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            utils.renderPrintablePage($("#letter_content"), css);
        } else {
            utils.renderToPrint($("#letter_content"), css);
        }
        utils.postItems(JSON.stringify([sessionStorage.getItem('token'), "accept_offer"]), function(res) {
            let parsedRes = JSON.parse(res);
            if (parsedRes == "Ok") {
                $.toast("Thank you for accepting our offer");
            } else {
                $.toast(parsedRes);
            }
        });
    });

}); //document ready