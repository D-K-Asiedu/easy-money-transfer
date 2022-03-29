$(document).ready(() => {
    $.getScript('../js/custom/utilities.js').done(function () {
        let utils = Utilities;
        let vFactory = utils.ValidationFactory;

        $('#gen_content').on('click', function () {
            $("#content_gen_modal").modal('show');
        });
        function getEditorContent() {
            let html = editor.getHtml();
            let css = editor.getCss();
            let js = editor.getJs();
            const htmlString = new DOMParser().parseFromString(html, "text/html");
            const imgs = [...htmlString.images].map(e => e.src);

            let img = htmlString.images.length;
            for (let i = 0; i < img; i++) {
                htmlString.images[i].src = `mike_img_${i}`;
            }
            let innerHTML = $(htmlString).find('body').html();
            return { html: innerHTML, css: css, js: js, imgs: imgs };
        }

        $("#card_img_to_upload").change(function (e) {
            e.stopImmediatePropagation();
            let files = $(this)[0].files;
            utils.tobase64Handler(files).then((base64array) => {
                $("#content_img").css("width", "100%");
                $("#content_img").attr("src", base64array[0].result);
            });
        });


        $('#save_content').on('click', function (e) {
            e.stopImmediatePropagation();
            let content = getEditorContent();
            let data = utils.extractData('#contentParentInp');
            let valid = utils.validateData(data, utils);

            if (!valid.formValid) {
                utils.animateToElement(valid.values[0]);
                return;
            }
            let content_img = $("#card_img_to_upload")[0];
            let files = content_img.files;
            utils.tobase64Handler(files).then((base64array) => {
                utils.saveDataWithFiles(base64array, {
                    data: valid.values,
                    content: content,
                    token: sessionStorage.getItem('token'),
                    contentToEditId: sessionStorage.getItem('contentToEditId')
                }, ["jpg", "jpeg", "png"],
                    2048, 'save_content').then(() => {
                        utils.clearData('#contentParentInp');
                        sessionStorage.removeItem('contentToEditId');
                        // $("#card_img_to_upload").val("");
                        utils.toastMsg("Operation successful", "green", "green");
                    }).catch((err) => {
                        utils.toastMsg(err, "red", "red");
                    });
            });
        });

        $("#content_edit_tbl").on("click", ".content_extra", function (e) {
            e.stopImmediatePropagation();
            let conf = confirm('Changing view will make you loose data. Click Ok if you that is fine else Cancel');
            if (!conf) return;
            let url = `views/user/extra.php`;
            let id = $(this).data('id');
            sessionStorage.setItem('extraContentParentId', id);
            location.href = `./#extra`;
            editor.setComponents('');//CLEAR EDITOR
            editor.setStyle('');
            fetch(url).then(response => response.text()).then(async (data) => {
                $('#app').html(data);
                $('#content_modal').modal('hide');

            });
        });

        populateExtra();

        async function populateExtra() {
            let id = sessionStorage.getItem('extraContentParentId');
            let res = await utils.fetchDetails('get_extra_parent', sessionStorage.getItem('token'), { id: id }, utils);
            let d = res.data;
            $('#extra_parent').val(id);
            $('#extra_tbl').empty();
            let html = `<thead>
                                <th>id</th>
                                <th>Parent</th>
                                <th>Name</th>
                                <th>Link</th>                                
                                <th>Position</th>
                                <th>Show</th>                                
                                <th>Content Type</th>
                                <th>Actions</th>
                            </thead><tbody>`;
            for (let i = 0; i < d.length; i++) {
                let content_id = d[i].content_id;
                let href = d[i].href;
                let id = d[i].id;
                let position = d[i].position;
                let prop = d[i].prop;
                let show = d[i].show_hide ? `<input type='checkbox' style='opacity:1;pointer-events:all' checked class='toggle_show_extra' data-field='show_hide'  data-id='${id}'>"` : `<input type='checkbox' style='opacity:1;pointer-events:all' class='toggle_show_extra' data-field='show_hide'  data-id='${id}'>`;
                let style = d[i].style;
                let content_type = d[i].content_type;
                let actions = `<i class="fas fa-edit extra_edit" data-id="${id}"></i>
                                    &nbsp;&nbsp;
                                    <i class="fas fa-trash extra_del" data-id="${id}"></i>`;
                let row = utils.createTableRow([id, content_id, prop, href, position, show, content_type, actions]);
                html += row;
            }
            if (d.length >= 1) {
                $('#extra_tbl').html(html + '</tbody>');
            }
        }

        $("#extra_tbl").on('click', '.extra_edit', async function (e) {
            e.stopImmediatePropagation();
            let id = $(this).data('id');
            let res = await utils.fetchDetails('get_extra_content', sessionStorage.getItem('token'), { id: id }, utils);
            let d = res.data;
            if (d.length <= 0) return;
            let content = d[0].content;
            let content_id = d[0].content_id;
            let content_type = d[0].content_type;
            let href = d[0].href;
            let position = d[0].position;
            let prop = d[0].prop;
            let show = d[0].show_hide;
            let style = d[0].style;
            sessionStorage.setItem('extraContentId', id);
            $('#extra_parent').val(content_id);
            $('#extra_name').val(prop);
            $('#content_type').val(content_type);
            if (content_type == 'custom') {
                editor.setComponents(content);
                editor.setStyle(style);
            } else {
                $('#content_body').val(content);
                $('#content_style').val(style);
            }
            $('#extra_show').val(show);
            $('#extra_href').val(href);
            $('#content_position').val(position);
        })

        $('#save_extra').on('click', async (e) => {
            e.stopImmediatePropagation();
            let content = getEditorContent();
            let data = utils.extractData('#extraParentInp');
            let valid = utils.validateData(data, utils);

            if (!valid.formValid) {
                utils.animateToElement(valid.values[0]);
                return;
            }
            let res = await utils.saveData('save_extra', {
                data: valid.values,
                content: content,
                token: sessionStorage.getItem('token'),
                extraToEditId: sessionStorage.getItem('extraContentId')
            }, utils);            
            if(res == 'Ok'){
                editor.setComponents('');
                editor.setStyle('');
                utils.clearData('#extraParentInp');
                utils.toastMsg('Operation successful','green','green');
                sessionStorage.removeItem('extraContentId');
                populateExtra();
            }
        });

        $("#extra_tbl").on("click", ".toggle_show_extra", async function (e) {
            e.stopImmediatePropagation();
            let checked = 0;
            if ($(this).prop('checked')) {
                checked = 1;
            }
            let id = $(this).data('id');
            let field = $(this).data('field');
            let d = await utils.saveData('toggle_extra_field', { id: id, field: field, token: sessionStorage.getItem('token'), checked: checked }, utils);            
            if (d == "Ok") {
                utils.toastMsg('Operation successful', 'green', 'green');
            } else {
                utils.toastMsg(d, 'red', 'red');
            }
        });

        $("#content_edit_tbl").on("click", ".toggle_content_check", async function (e) {
            e.stopImmediatePropagation();
            let checked = 0;
            if ($(this).prop('checked')) {
                checked = 1;
            }
            let id = $(this).data('id');
            let field = $(this).data('field');
            let d = await utils.saveData('toggle_content_field', { id: id, field: field, token: sessionStorage.getItem('token'), checked: checked }, utils);
            if (d == "Ok") {
                utils.toastMsg('Operation successful', 'green', 'green');
            } else {
                utils.toastMsg(d, 'red', 'red');
            }
        });

        $("#content_edit_tbl").on("click", ".content_del", async function (e) {
            e.stopImmediatePropagation();
            let bool = confirm("Click Ok to proceed or Cancel");
            if (!bool) return;
            let id = $(this).data('id');
            let d = await utils.saveData('del_content', { id: id, token: sessionStorage.getItem('token') }, utils);
            if (d == "Ok") {
                utils.toastMsg('Operation successful', 'green', 'green');
                getContents();
            } else {
                utils.toastMsg(d, 'red', 'red');
            }
        });

        $("#extra_tbl").on("click", ".extra_del", async function (e) {
            e.stopImmediatePropagation();
            let bool = confirm("Click Ok to proceed or Cancel");
            if (!bool) return;
            let id = $(this).data('id');
            let d = await utils.saveData('del_extra', { id: id, token: sessionStorage.getItem('token') }, utils);
            if (d == "Ok") {
                utils.toastMsg('Operation successful', 'green', 'green');
                populateExtra();
            } else {
                utils.toastMsg(d, 'red', 'red');
            }
        });

        $("#content_edit_tbl").on("click", ".content_edit", function (e) {
            e.stopImmediatePropagation();
            let id = $(this).data('id');
            getSelectedContent(id);
        });

        async function getSelectedContent(id) {
            let d = await utils.fetchDetails('get_content', sessionStorage.getItem('token'), id, utils);
            utils.clearData('#contentParentInp');
            $('#content_img').prop('src', '');
            let data = d.data[0];
            let content = data.content;
            let content_type = data.content_type;
            let css_class = data.css_class;
            let description = data.description;
            let href_or_evt_id = data.href_or_evt_id;
            let icon = data.icon;
            let img_path = data.img_path;
            let name = data.name;
            let ou = data.ou;
            let parent_classes = data.parent_classes;
            let parent_id = data.parent_id;
            let position = data.position;
            let show_hide = data.show_hide;
            let style = data.style;
            let unique_name = data.unique_name;
            if (content_type == 'custom') {
                editor.setComponents(content);
                editor.setStyle(style);
            } else {
                $('#content_body').val(content);
                $('#content_style').val(style);
            }
            $('#unique_name').val(unique_name);
            $('#html_handle').val(parent_id);
            $('#description').val(description);
            $('#ou').val(ou);
            $('#show').val(show_hide);
            $('#content_type').val(content_type);
            if ($.trim(img_path) != '')
                $('#content_img').prop('src', img_path);
            else
                $('#content_img').prop('alt', 'No Img');

            $('#content_href').val(href_or_evt_id);
            $('#css_classes').val(css_class);
            $('#content_name').val(name);
            $('#content_icon').val(icon);
            $('#content_position').val(position);
            $('#parent_classes').val(parent_classes);
            sessionStorage.setItem('contentToEditId', id);
            $('#content_modal').modal('hide');
        }

    });//getScript
});//document Ready
