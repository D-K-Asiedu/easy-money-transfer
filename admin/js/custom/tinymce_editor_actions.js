$(document).ready(() => {
    $.getScript('../js/custom/utilities.js').done(function () {
        let utils = Utilities;
        let vFactory = utils.ValidationFactory;

        function reset() {
            utils.clearData('#eventParentInp');
            sessionStorage.removeItem('eventToEditId');
            tinymce.activeEditor.setContent("");
            $("#sbtn,#pbtn").prop('disabled', false);
        }
        reset();

       
        $("#sbtn,#pbtn").on('click', function (e) {
            let target = e.target;
            tinymce.activeEditor.uploadImages((success) => {
                if (success.length != 0) {
                    let img = success[0].element;
                    let path = success[0].location;
                    $(img).prop('src', path);
                }
                save(utils, target);
            });
        });

        $("#event_edit_tbl").on("click", ".event_del", async function () {
            let bool = confirm("Click Ok to proceed or Cancel");
            if (!bool) return;
            let id = $(this).data('id');
            let d = await utils.saveData('del_event', { id: id, token: sessionStorage.getItem('token') }, utils);
            if (d == "Ok") {
                utils.toastMsg('Operation successful', 'green', 'green');
                getEvents();
            } else {
                utils.toastMsg(d, 'red', 'red');
            }
        });

        $("#event_edit_tbl").on("click", ".event_edit", function () {
            let id = $(this).data('id');
            getSelectedEvent(id);
        });


        $("#event_edit_tbl").on("click", ".toggle_check", async function () {
            let checked = 0;
            if ($(this).prop('checked')) {
                checked = 1;
            }
            let id = $(this).data('id');
            let field = $(this).data('field');
            let d = await utils.saveData('toggle_field', { id: id, field: field, token: sessionStorage.getItem('token'), checked: checked }, utils);
            if (d == "Ok") {
                util.toastMsg('Operation successful', 'green', 'green');
            } else {
                util.toastMsg(d, 'red', 'red');
            }
        });

        async function getSelectedEvent(id) {
            let d = await utils.fetchDetails('get_event', sessionStorage.getItem('token'), id, utils);
            let evt = d.data;
            utils.clearData('#eventParentInp');
            $('#eventBanner').prop('src','');
            let banner = evt[0].banner_picture_path;
            let body = evt[0].body;
            let evt_type = evt[0].event_type;
            let featured = evt[0].featured;
            let is_carousel = evt[0].is_carousel;
            let is_part_of_list = evt[0].is_part_of_list;
            let link = evt[0].link;
            let ou = evt[0].ou;
            let position = evt[0].position;
            let published_save = evt[0].published_save;
            let show_hide = evt[0].show_hide;
            let title = evt[0].title;
            $('#eventBanner').prop('src', banner);
            $('#event_type').val(evt_type);
            $('#title').val(title);
            $('#ou').val(ou);
            $('#featured').val(featured);
            $('#link').val(link);
            $('#is_carousel').val(is_carousel);
            $('#is_part_of_list').val(is_part_of_list);
            $('#position').val(position);
            tinymce.activeEditor.setContent(body);
            sessionStorage.setItem('eventToEditId', id);
            $('#event_modal').modal('hide');
        }


        $("#event_banner_to_upload").change(function () {
            let files = $(this)[0].files;
            utils.tobase64Handler(files).then((base64array) => {
                $("#eventBanner").css("width", "100%");
                $("#eventBanner").attr("src", base64array[0].result);
            });
        });


        function save(utils, target) {
            let btnId = $(target).prop('id');
            $(target).prop('disabled', true);
            let savePublish = 0;
            let showHide = 0;
            if (btnId == "pbtn") {
                showHide = 1;
                savePublish = 1;
            }
            let data = utils.extractData('#eventParentInp');
            let d = utils.validateData(data, utils);
            let banner = $("#event_banner_to_upload")[0];
            let files = banner.files;
            let content = tinymce.get('event_content').getContent();
            if (!d.valid && files.length == 0 && $.trim(content) == '') {
                utils.toastMsg('Please complete the form', 'red', 'red');
                return;
            }
            utils.tobase64Handler(files).then((base64array) => {
                utils.saveDataWithFiles(base64array, {
                    data: d.values,
                    content: content,
                    token: sessionStorage.getItem('token'),
                    show: showHide,
                    save: savePublish,
                    eventToEditId: sessionStorage.getItem('eventToEditId')
                }, ["jpg", "jpeg", "png"],
                    2048, 'save_event').then(() => {
                        reset();
                        // sessionStorage.removeItem('eventToEditId');
                        // $("#event_banner_to_upload").val("");
                        utils.toastMsg("Operation successful", "green", "green");
                    }).catch((err) => {
                        utils.toastMsg(err, "red", "red");
                    });
            });
        }//end function


    }); //END OF GET SCRIPT FUNCTION

});
