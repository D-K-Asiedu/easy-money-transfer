
import { Utilities } from '../../../js/custom/utilities.js';
let utils = Utilities;
let vFactory = utils.ValidationFactory;
let color = '#052498';
$(document).ready(function () {
    // $('select').selectpicker("destroy");    
    // $('select').selectpicker();    
    // $.getScript("../js/custom/utilities.js").done(function(e) {
    // let utils = Utilities;


    utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });

    function getUserRoles(perm, adminId) {
        utils.postItems(JSON.stringify([adminId, perm]), function (res) {
            let parsedRes = JSON.parse(res);
            let roleData = [];
            for (let i = 0; i < parsedRes.length; i++) {
                let id = parsedRes[i].id;
                roleData.push([
                    parsedRes[i].role,
                    parsedRes[i].custom_id,
                    '<i class="fas fa-trash text-danger delUserRole" data-id="' + id + '"></i>'
                ]);
            }
            $("#tblUserRoles").DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "full_numbers",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Role" },
                    { title: "CustomID" },
                    { title: "Del" }
                ],
                data: roleData
            });
            $("#userRolesModal").modal("show");
        });
    }

    function getUserEntity(perm, adminId) {
        utils.postItems(JSON.stringify([adminId, perm]), function (res) {
            let parsedRes = JSON.parse(res);
            let data = [];
            for (let i = 0; i < parsedRes.length; i++) {
                let id = parsedRes[i].id;
                data.push([
                    parsedRes[i].name,
                    '<i class="fas fa-trash text-danger delUserToEntity" data-id="' + id + '"></i>'
                ]);
            }
            $("#tblUserToEntity").DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "full_numbers",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Unit/Dept Name" },
                    { title: "Del" }
                ],
                data: data
            });
            $("#userToEntityModal").modal("show");
        });
    }

    $("#tblUserRoles").on("click", ".delUserRole", function () {
        let rowId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_user_role", (perm) => { del(perm, rowId, $(this)) });
    });

    $("#tblUserToEntity").on("click", ".delUserToEntity", function () {
        let rowId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_user_to_entity", (perm) => { del(perm, rowId, $(this)) });
    });


    $("#tblUsers").on("click", ".user_to_entity", function () {
        let adminId = $(this).data("id");
        sessionStorage.setItem("userToEntityId", adminId);
        utils.getPerms(utils, "get_user_entity", (perm) => { getUserEntity(perm, adminId) });
    });

    $("#tblUsers").on("click", ".user_roles", function () {
        let adminId = $(this).data("id");
        sessionStorage.setItem("userId", adminId);
        utils.getPerms(utils, "get_user_roles", (perm) => { getUserRoles(perm, adminId) });
    });

    $('#tblUsers').on("click", ".del_user", function () {
        let userId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_user", (perm) => { del(perm, userId, $(this)) });
    });

    $('#tblUsers').on("click", ".get_edittable_user", function () {
        let userId = $(this).data("id");
        sessionStorage.setItem("userToEditId", userId);
        utils.getPerms(utils, "get_user_to_edit", (perm) => {
            getEdittableEntity(perm, userId, "#userDetails", "#userEditModal");
        });
    });

    $('#tblFiles').on("click", ".get_edittable_file", function () {
        let fileId = $(this).data("id");
        sessionStorage.setItem("fileToEditId", fileId);
        utils.getPerms(utils, "get_file_to_edit", (perm) => {
            getEdittableEntity(perm, fileId, "#fileDetails", "#fileEditModal");
        });
    });

    $("#edit_user").on("click", () => {
        utils.getPerms(utils, "edit_user", (perm) => {
            edit(perm, "userToEditId", "#userDetails", "#userEditModal");
        });
    });


    function edit(perm, sessionStorageId, parentElem, modal) {
        return new Promise((resolve, reject) => {
            let rowId = null;
            let data = null;
            if (sessionStorageId != null)
                rowId = sessionStorage.getItem(sessionStorageId);
            if (parentElem != null)
                data = validateData(utils.extractData(parentElem));
            if (!data.formValid) return;

            utils.postItems(JSON.stringify([data.values, rowId, perm]), function (res) {
                let parsedRes = JSON.parse(res);
                if (parsedRes == "Ok") {
                    sessionStorage.removeItem(sessionStorageId);
                    utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });
                    // $.notify("Operation successful", "success");
                    utils.toastMsg("Operation successful", "green", "green");
                    if (modal != null)
                        $(modal).modal("hide");
                    resolve(true);
                } else {
                    reject(parsedRes);
                    // $.notify(parsedRes, "danger");
                    utils.toastMsg(parsedRes, "red", "red");
                }
            });
        });
    }


    $("#upload").on("click", function () {
        let target = $("#file_to_upload")[0];
        let files = target.files;
        let ufileDescription = $("#ufile_description").val();
        let toAll = $("#to_all").val();
        if ($.trim(ufileDescription) == "" || toAll == null) {
            // $.notify("Please provide file description and determine if file should be made global or not");
            utils.toastMsg("Please provide file description and determine if file should be made global or not", "red", "red");
            return;
        }
        utils.getPerms(utils, "save_uploaded_files", (perm) => {
            utils.tobase64Handler(files).then((base64array) => {
                utils.saveDataWithFiles(base64array, {
                    token: sessionStorage.getItem("token"),
                    description: ufileDescription,
                    toAll: toAll
                }, ["pdf", "msword", "vnd.openxmlformats-officedocument.wordprocessingml.document",
                    "vnd.openxmlformats-officedocument.presentationml.presentation",
                    "vnd.ms-powerpoint", "vnd.ms-excel", "vnd.openxmlformats-officedocument.spreadsheetml.sheet", "jpg", "jpeg", "png"
                ],
                    2048, perm).then(() => {
                        $("#file_to_upload").val("");
                        // $.notify("Operation successful", "success");
                        utils.toastMsg("Operation successful", "green", "green");
                        utils.getPerms(utils, "get_uploaded_files", (perm) => {
                            getUploadedFiles(perm);
                        });
                        $("#clear_ufile_details").trigger("click");
                    }).catch((err) => {
                        // $.notify(err, "danger");
                        utils.toastMsg(err, "red", "red");
                    });
            });
        });
    });



    $("#uploadFileModal").on('shown.bs.modal', function (event) {
        utils.getPerms(utils, "get_uploaded_files", (perm) => {
            getUploadedFiles(perm);
        });
    });

    function getUploadedFiles(perm) {
        utils.postItems(JSON.stringify([sessionStorage.getItem('token'), perm]), function (res) {
            let parsedRes = JSON.parse(res);
            if ($.fn.DataTable.isDataTable("#tblUploadedFiles")) {
                $("#tblUploadedFiles").DataTable().destroy();
            }
            let data = [];
            for (let i = 0; i < parsedRes.length; i++) {
                let id = parsedRes[i].id;
                let path = `<a href="${parsedRes[i].path}" target="_blank" class="text-primary">Get File</a>`;
                let description = parsedRes[i].description;
                let date_inserted = parsedRes[i].date_inserted;
                let blocked = `<input type="checkbox" checked class="uploaded_file_block" data-id="${id}"/>`;
                let unblocked = `<input type="checkbox" class="uploaded_file_block" data-id="${id}"/>`;
                let restricted = parsedRes[i].restricted == 1 ? blocked : unblocked;
                let to_all = parsedRes[i].to_all == 1 ? "Yes" : "No";
                let file_size = parsedRes[i].file_size;
                let map = `<i class="fas fa-school text-primary map_to_entity" data-id="${id}"></i>`;
                let edit = `<i class="fas fa-edit text-success get_edittable_uploaded_file" data-id="${id}"></i>`;
                let del = `<i class="fas fa-trash text-danger del_uploaded_file" data-id="${id}"></i>`;
                data.push([description, date_inserted, restricted, to_all, file_size, path, map, edit, del])
            }
            $('#tblUploadedFiles').DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Description" },
                    { title: "Date" },
                    { title: "Blocked" },
                    { title: "Global" },
                    { title: "Size" },
                    { title: "Download" },
                    { title: "Assign" },
                    { title: "Edit" },
                    { title: "Del" }
                ],
                data: data
            });
        });
    } //end of function 

    $("#tblUploadedFiles").on("click", ".get_edittable_uploaded_file", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        sessionStorage.setItem("uploaded_file_id", rowId);
        utils.getPerms(utils, "get_uploaded_file_details", (perm) => { getUploadedFileDetails(perm, rowId); });
    });

    function getUploadedFileDetails(perm, rowId) {
        utils.postItems(JSON.stringify([rowId, sessionStorage.getItem('token'), perm]), function (res) {
            let parsedRes = JSON.parse(res);
            let description = parsedRes[0].description;
            let to_all = parsedRes[0].to_all;
            $("#ufile_description").val(description).change();
            $("#to_all").val(to_all);
            $("#edit_ufile_details").removeClass("d-none");
            $("#upload").addClass("d-none");
            utils.animateToElement("#uploadFileModal", "#fileUploadInpParent");
        });
    }

    $("#edit_ufile_details").on("click", () => {
        utils.getPerms(utils, "edit_uploaded_file_details", (perm) => {
            edit(perm, "uploaded_file_id", "#fileUploadInpParent", null).then(() => {
                utils.getPerms(utils, "get_uploaded_files", (perm) => {
                    getUploadedFiles(perm);
                    $("#clear_ufile_details").trigger("click");
                });
            }).catch(() => {

            });
        });
    });

    $("#clear_ufile_details").on("click", () => {
        $("#ufile_description").val("");
        $("#to_all").val(2); //To All Sections and Departments?
        $("#edit_ufile_details").addClass("d-none");
        $("#upload").removeClass("d-none");
        $("#fileList").empty();
    });

    $("#tblUploadedFiles").on("click", ".map_to_entity", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        sessionStorage.setItem("uploaded_file_to_map_id", rowId);
        $("#fileEntityModal").modal("show");
    });

    $("#fileEntityModal").on('shown.bs.modal', function (event) {
        utils.getPerms(utils, "get_uploaded_files_to_entity", (perm) => {
            getUploadedFilesToEntity(perm);
        });
        utils.getPerms(utils, "get_sections_depts", (perm) => {
            getSectionAndDepts(perm).then((res) => {
                $("#enities_to_map_to_file").empty();
                for (let i = 0; i < res.length; i++) {
                    $("#enities_to_map_to_file").append(`<option value="${res[i].id}">${res[i].name}</option>`);
                }
            });
        });
    });

    function getSectionAndDepts(perm) {
        return new Promise((resolve, reject) => {
            utils.postItems(JSON.stringify([sessionStorage.getItem('token'), perm]), function (res) {
                let parsedRes = JSON.parse(res);
                if (parsedRes.status = "Ok") {
                    resolve(parsedRes.res);
                } else {
                    reject();
                }
            });
        });
    }

    function getUploadedFilesToEntity(perm) {
        utils.postItems(JSON.stringify([sessionStorage.getItem("uploaded_file_to_map_id"), sessionStorage.getItem('token'), perm]), function (res) {
            let parsedRes = JSON.parse(res);
            let data = [];
            for (let i = 0; i < parsedRes.length; i++) {
                let file_to_entity_id = parsedRes[i].id;
                let blocked = '<input type="checkbox" checked class="file_to_entity_block" data-id="' + file_to_entity_id + '"/>';
                let unblocked = '<input type="checkbox" class="file_to_entity_block" data-id="' + file_to_entity_id + '"/>';
                let entityName = parsedRes[i].name;
                let fileDescriptin = parsedRes[i].description;
                let restricted = parsedRes[i].restricted == 1 ? blocked : unblocked;
                let del = `<i class="fas fa-trash text-danger del_file_to_entity" data-id="${file_to_entity_id}"></i>`;
                data.push([entityName, fileDescriptin, restricted, del]);
            }
            if ($.fn.DataTable.isDataTable("#tblFilesToEntity")) {
                $("#tblFilesToEntity").DataTable().destroy();
            }
            $('#tblFilesToEntity').DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "EntityName" },
                    { title: "FileDescription" },
                    { title: "Blocked" },
                    { title: "Del" }
                ],
                data: data
            });
        });
    } //end function 

    $("#map_file_to_entity").on("click", () => {
        utils.getPerms(utils, "map_uploaded_file_to_entity", (perm) => {
            map(perm, "uploaded_file_to_map_id", "#enities_to_map_to_file", "#fileEntityModal");
        });
    });

    $("#tblFilesToEntity").on("click", ".file_to_entity_block", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        let blocked = 0;
        if (elem.prop("checked")) {
            blocked = 1;
        }
        utils.getPerms(utils, "file_to_entity_block", (perm) => { block(perm, rowId, blocked); });
    });

    $("#tblFilesToEntity").on("click", ".del_file_to_entity", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        utils.getPerms(utils, "del_file_to_entity", (perm) => { del(perm, rowId, $(this)) });
    });

    $("#tblUploadedFiles").on("click", ".uploaded_file_block", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        let blocked = 0;
        if (elem.prop("checked")) {
            blocked = 1;
        }
        utils.getPerms(utils, "block_uploaded_file", (perm) => { block(perm, rowId, blocked); });
    });

    $("#tblUploadedFiles").on("click", ".del_uploaded_file", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        let bool = confirm("Click Ok if you want to delete the file or Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_uploaded_file", (perm) => { del(perm, rowId, $(this)) });
    });

    $("#file_to_upload").on("change", (e) => {
        let target = e.target;
        let files = target.files;
        $("#fileList").empty();
        for (let i = 0; i < files.length; i++) {
            $("#fileList").append(`<li>${files[i].name}</li>`);
        }
    });


    $("#edit_file").on("click", () => {
        utils.getPerms(utils, "edit_file", (perm) => {
            edit(perm, "fileToEditId", "#fileDetails", "#fileEditModal").then(() => {
                utils.getPerms(utils, "get_all_files", (perm) => { getAllFiles(perm) });
            }).catch(() => {

            });
        });
    });


    $("#addFileModal").on('shown.bs.modal', function (event) {
        utils.getPerms(utils, "get_file_params_from_db", (perm) => {
            getFields(perm, "#addFileContainer", ["id", "added_by", "date_inserted"]);
        });
    });




    $("#addUserModal").on('shown.bs.modal', function (event) {
        utils.getPerms(utils, "get_user_params_from_db", (perm) => {
            getFields(perm, "#addUserContainer", ["id", "added_by", "date_inserted","prof_pic"]);
        });
    });

    function getFields(perm, parentElem, excludedList) {
        utils.postItems(JSON.stringify([perm]), function (res) {
            let parsedRes = JSON.parse(res);
            let html = '';
            for (let i = 0; i < parsedRes.length; i++) {
                if (!excludedList.includes(parsedRes[i])) {
                    html += `<div class="md-form">  
                    <label for="${parsedRes[i]}">${parsedRes[i]}</label>                  
                    <input type="text" id="${parsedRes[i]}" class="form-control my_edittable_input">                    
                </div>`;
                    $(parentElem).empty().append(html);
                }
            }
        });
    }

    $("#addRoleModal").on('shown.bs.modal', function (event) {
        utils.getPerms(utils, "get_role_params_from_db", (perm) => {
            getFields(perm, "#addRoleContainer", ["id", "added_by", "date_inserted"]);
        });
    });

    $("#add_file").on("click", () => {
        utils.getPerms(utils, "add_new_file", (perm) => {
            add(perm, "#addFileContainer", "#addFileModal").then(() => {
                utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });
                utils.getPerms(utils, "get_all_files", (perm) => { getAllFiles(perm) });
            });;
        });
    });


    $("#add_user").on("click", () => {
        utils.getPerms(utils, "add_new_user", (perm) => {
            add(perm, "#addUserContainer", "#addUserModal").then(() => {
                utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });
            });
        });
    });

    $("#add_object_type").on("click", () => {
        utils.getPerms(utils, "add_object_type", (perm) => {
            add(perm, "#object_type_inp_container", null).then(() => {
                utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });
                utils.getPerms(utils, "get_all_objects", (perm) => { getAllObjects(perm) });
                utils.getPerms(utils, "get_all_entities", (perm) => { getAllEntities(perm) });
            });
        });
    });

    function add(perm, parentElem, modal) {
        return new Promise((resolve, reject) => {
            if (parentElem == null) return;
            let data = validateData(utils.extractData(parentElem));
            if (!data.formValid) return;

            utils.postItems(JSON.stringify([data.values, sessionStorage.getItem("token"), perm]), function (res) {
                let parsedRes = JSON.parse(res);
                if (parsedRes == "Ok") {
                    resolve(true);
                    // $.notify("Operation successful", "success");
                    utils.toastMsg("Operation successful", "green", "green");
                    if (modal != null)
                        $(modal).modal("hide");
                } else {
                    reject(parsedRes);
                    // $.notify(parsedRes, "danger");
                    utils.toastMsg(parsedRes, "red", "red");
                }
            });
        });
    }

    $("#add_role").on("click", () => {
        utils.getPerms(utils, "add_new_role", (perm) => {
            add(perm, "#addRoleContainer", "#addRoleModal").then(() => {
                utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });
                utils.getPerms(utils, "get_all_user_roles", (perm) => { getAllRoles(perm) });
            });;
        });
    });

    function validateData(data) {
        let rules = [];
        for (let i = 0; i < data.length; i++) {
            let rule = {
                id: data[i].id,
                rules: [
                    (val) => {
                        let valid = vFactory.validateEmptyString(val);
                        if (!valid) {
                            utils.notifyMsgRelativeToElem(data[i].id, 'Invalid');
                            // utils.toastMsg('Title is required', settings.toastErrorBg, settings.toastLoaderBg);
                            utils.applyEffectToElem(data[i].id, 'highlight');
                        }
                        return valid;
                    } //FUNCTION 1
                ]
            } //end rule
            rules.push(rule);
        }
        return utils.validateForm(rules);
    } //end function


    $("#clear_object_type").on("click", () => {
        utils.clearData("#object_type_inp_container");
        $("#add_object_type").removeClass("d-none");
        $("#edit_object_type").addClass("d-none");
        utils.getPerms(utils, "get_all_objects", (perm) => { getAllObjects(perm) });
    });

    $("#clear_object").on("click", () => {
        utils.clearData("#object_inp_container");
        $("#add_object").removeClass("d-none");
        $("#edit_object").addClass("d-none");
    });

    function getEdittableEntity(perm, rowId, parentElem, modal) {
        utils.postItems(JSON.stringify([rowId, perm]), function (res) {
            let parsedRes = JSON.parse(res)[0];
            let entries = Object.entries(parsedRes);
            let html = '';
            for (let i = 0; i < entries.length; i++) {
                html += `<div class="md-form">   
                    <label for="${entries[i][0]}">${entries[i][0]}</label>                 
                    <input type="text" id="${entries[i][0]}" value="${entries[i][1]}" class="form-control edittable_input">
                </div>`;
            }
            $(parentElem).empty().append(html);
            $(modal).modal("show");
            $(".edittable_input").change();
        });
    }

    function getAllRoles(perm) {
        utils.postItems(JSON.stringify([perm]), function (res) {
            let parsedRes = JSON.parse(res);
            $("#user_roles_to_add").empty().append('<option selected disabled>Roles</option>');
            for (let i = 0; i < parsedRes.length; i++) {
                $("#user_roles_to_add").append('<option value="' + parsedRes[i].id + '">' + parsedRes[i].role + '</option>');
            }
        });
    }

    utils.getPerms(utils, "get_all_user_roles", (perm) => { getAllRoles(perm) });

    $("#map_role_to_user").on("click", function () {
        utils.getPerms(utils, "map_role_to_user", (perm) => {
            map(perm, "userId", "#user_roles_to_add", "#userRolesModal");
        });
    });

    $("#map_entity_to_user").on("click", function () {
        utils.getPerms(utils, "map_entity_to_user", (perm) => {
            map(perm, "userToEntityId", "#user_entity_to_add", "#userToEntityModal");
        });
    });

    $("#tblUsers").on("click", ".user_psd_rst", function () {
        let id = $(this).data("id");
        utils.getPerms(utils, "reset_user_psd", (perm) => {
            resetPsd(perm, id);
        });
    });

    function resetPsd(perm, id) {
        utils.postItems(JSON.stringify([id, sessionStorage.getItem('token'), perm]), function (res) {
            let parsedRes = JSON.parse(res);
            if (parsedRes == "Ok") {
                // $.notify("New password has been mailed to the user", "success");
                utils.toastMsg("New password has been mailed to the user", "green", "green");
            } else {
                // $.notify(parsedRes);
                utils.toastMsg(parsedRes, "red", "red");
            }
        });
    }

    function getEntities(perm) {
        utils.postItems(JSON.stringify([perm]), function (res) {
            let parsedRes = JSON.parse(res);
            // console.log(parsedRes);
            let admins = parsedRes.admins;
            let roles = parsedRes.roles;
            let perms = parsedRes.perms;
            let files = parsedRes.files;
            let objects = parsedRes.objects;
            let objectTypes = parsedRes.object_types;;
            let adminData = [];
            let rolesData = [];
            let permsData = [];
            let filesData = [];
            let objectsData = [];
            let objectTypesData = [];

            for (let i = 0; i < objectTypes.length; i++) {
                let id = objectTypes[i].id;
                objectTypesData.push([
                    objectTypes[i].entity_name,
                    objectTypes[i].object_name,
                    objectTypes[i].parent_name,
                    '<i class="fas fa-edit text-success get_edittable_obtype" data-id="' + id + '"></i>',
                    '<i class="fas fa-trash text-danger del_obtype" data-id="' + id + '"></i>'
                ]);
            }

            for (let i = 0; i < objects.length; i++) {
                let id = objects[i].id;
                objectsData.push([
                    objects[i].name,
                    '<i class="fas fa-edit text-success get_edittable_object" data-id="' + id + '"></i>',
                    '<i class="fas fa-trash text-danger del_object" data-id="' + id + '"></i>'
                ]);
            }

            for (let i = 0; i < files.length; i++) {
                let id = files[i].id;
                let blocked = '<input type="checkbox" checked class="file_block" data-id="' + id + '"/>';
                let unblocked = '<input type="checkbox" class="file_block" data-id="' + id + '"/>';
                filesData.push([
                    files[i].path,
                    files[i].description,
                    files[i].restricted == 1 ? blocked : unblocked,
                    '<i class="fas fa-edit text-success get_edittable_file" data-id="' + id + '"></i>',
                    '<i class="fas fa-trash text-danger del_file" data-id="' + id + '"></i>'
                ]);
            }
            for (let i = 0; i < admins.length; i++) {
                let id = admins[i].id;
                adminData.push([
                    admins[i].name,
                    admins[i].username,
                    admins[i].staff_id,
                    admins[i].email,
                    '<i class="fas fa-cog text-primary user_roles" data-id="' + id + '"></i>',
                    '<i class="fas fa-school text-primary user_to_entity" data-id="' + id + '"></i>',
                    '<i class="fas fa-undo text-primary user_psd_rst" data-id="' + id + '"></i>',
                    '<i class="fas fa-edit text-success get_edittable_user" data-id="' + id + '"></i>',
                    '<i class="fas fa-trash text-danger del_user" data-id="' + id + '"></i>'
                ]);
            }
            for (let i = 0; i < roles.length; i++) {
                let id = roles[i].id;
                rolesData.push([
                    roles[i].custom_id,
                    roles[i].role,
                    '<i class="fas fa-cog text-primary role_perms" data-id="' + id + '"></i>',
                    '<i class="fas fa-file text-warning role_files" data-id="' + id + '"></i>',
                    '<i class="fas fa-edit text-success get_edittable_role" data-id="' + id + '"></i>',
                    '<i class="fas fa-trash text-danger del_role" data-id="' + id + '"></i>'
                ]);
            }
            for (let i = 0; i < perms.length; i++) {
                let id = perms[i].id;
                let blocked = '<input type="checkbox" checked class="perm_block" data-id="' + id + '"/>';
                let unblocked = '<input type="checkbox" class="perm_block" data-id="' + id + '"/>';
                permsData.push([
                    perms[i].permission,
                    perms[i].description,
                    perms[i].restricted == 1 ? blocked : unblocked,
                    '<i class="fas fa-edit text-success edit_perm" data-id="' + id + '"></i>',
                    '<i class="fas fa-trash text-danger del_perm" data-id="' + id + '"></i>'
                ]);
            }

            $('#tblObjectType').DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Name" },
                    { title: "Type" },
                    { title: "Is Under" },
                    { title: "Edit" },
                    { title: "Del" }
                ],
                data: objectTypesData
            });

            $('#tblFiles').DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Path" },
                    { title: "Description" },
                    { title: "Blocked" },
                    { title: "Edit" },
                    { title: "Del" }
                ],
                data: filesData
            });


            $('#tblUsers').DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Name" },
                    { title: "Username" },
                    { title: "StaffID" },
                    { title: "Email" },
                    { title: "Roles" },
                    { title: "Unit/Dept" },
                    { title: "Reset Psd" },
                    { title: "Edit" },
                    { title: "Del" }
                ],
                data: adminData
            });

            $('#tblPerms').DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                "lengthMenu": [
                    [1, 2, 3, 5, 10, 25, 50, 100, -1],
                    [1, 2, 3, 5, 10, 25, 50, 100, "All"]
                ],
                columns: [
                    { title: "Permission" },
                    { title: "Description" },
                    { title: "Blocked" },
                    { title: "Edit" },
                    { title: "Del" }
                ],
                data: permsData
            });

            $('#tblRoles').DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "ID" },
                    { title: "Role" },
                    { title: "Perms" },
                    { title: "Files" },
                    { title: "Edit" },
                    { title: "Del" }
                ],
                data: rolesData
            });

            $("#tblObjects").DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                "searching": false,
                columns: [
                    { title: "Name" },
                    { title: "Edit" },
                    { title: "Del" }
                ],
                data: objectsData
            });
        }); //end utils
    } //end function 


    $('#tblObjects').on("click", ".get_edittable_object", function () {
        let objId = $(this).data("id");
        sessionStorage.setItem("objectToEditId", objId);
        utils.getPerms(utils, "get_object_to_edit", (perm) => { getObjectToEdit(perm, objId) });
    });

    $("#tblObjectType").on("click", ".get_edittable_obtype", function () {
        let objId = $(this).data("id");
        sessionStorage.setItem("obTypeToEditId", objId);
        utils.getPerms(utils, "get_obtype_to_edit", (perm) => { getObTypeToEdit(perm, objId) });
        // utils.animateToElement("obj_type_inp_container");
    });

    function getObjectToEdit(perm, objId) {
        utils.postItems(JSON.stringify([objId, perm]), function (res) {
            let parsedRes = JSON.parse(res);
            $("#obj_name").val(parsedRes[0].name).change();
            $("#add_object").addClass("d-none");
            $("#edit_object").removeClass("d-none");
        });
    } //end function

    function getObTypeToEdit(perm, objId) {
        utils.postItems(JSON.stringify([objId, perm]), function (res) {
            let parsedRes = JSON.parse(res);
            $("#obj_type_name").val(parsedRes[0].name).change();
            $("#object_type").val(parsedRes[0].object_id);
            $("#object_parent").val(parsedRes[0].parent_id);
            $("#add_object_type").addClass("d-none");
            $("#edit_object_type").removeClass("d-none");
            utils.animateToElement("#objectModal", "#object_type_inp_container");
        });
    } //end function

    $("#objectModal").on('shown.bs.modal', function (event) {
        $("#obj_name").val("");
        sessionStorage.removeItem("objectToEditId");
        sessionStorage.removeItem("obTypeToEditId");
        $("#edit_object").addClass("d-none");
        $("#add_object").removeClass("d-none");
        $("#add_object_type").removeClass("d-none");
        $("#edit_object_type").addClass("d-none");
    });



    $("#add_object").on("click", function () {
        utils.getPerms(utils, "add_object", (perm) => {
            add(perm, "#object_inp_container", null).then((bool) => {
                if (bool) {
                    $("#obj_name").val("");
                    utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });
                    utils.getPerms(utils, "get_all_objects", (perm) => { getAllObjects(perm) });
                }
            }).catch(() => {

            });
        });
    });


    $("#tblObjectType").on("click", ".del_obtype", function () {
        let rowId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_object_type", (perm) => { del(perm, rowId, $(this)) });
        utils.getPerms(utils, "get_all_objects", (perm) => { getAllObjects(perm) });
    });

    $("#tblObjects").on("click", ".del_object", function () {
        let rowId = $(this).data("id");
        let bool = confirm("Click on Ok if you want to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_object", (perm) => { del(perm, rowId, $(this)) });
    });

    function del(perm, rowId, elem) {
        return new Promise((resolve, reject) => {
            utils.postItems(JSON.stringify([rowId, sessionStorage.getItem('token'), perm]), function (res) {
                let parsedRes = JSON.parse(res);
                if (parsedRes == "Ok") {
                    elem.closest("tr").remove();
                    utils.getPerms(utils, "get_all_objects", (perm) => { getAllObjects(perm) });
                    // $.notify("Operation successful", "success");
                    utils.toastMsg("Operation successful", "green", "green");
                    resolve();
                } else {
                    // $.notify(parsedRes, "danger");
                    utils.toastMsg(parsedRes, "red", "red");
                    reject();
                }
            });
        });
    }


    $("#edit_object").on("click", function () {
        utils.getPerms(utils, "edit_object", (perm) => {
            edit(perm, "objectToEditId", "#object_inp_container", null).then((bool) => {
                if (bool) {
                    $("#obj_name").val("");
                    $("#edit_object").addClass("d-none");
                    $("#add_object").removeClass("d-none");
                }
            }).catch((err) => {
                console.log(err);
            });
        });
    });


    $("#tblRoles").on("click", ".role_perms", function () {
        let roleId = $(this).data("id");
        sessionStorage.setItem("roleId", roleId);
        utils.getPerms(utils, "get_role_perms", (perm) => { getRolePerm(perm, roleId) });
    });

    $("#tblRoles").on("click", ".role_files", function () {
        let roleId = $(this).data("id");
        sessionStorage.setItem("roleFId", roleId);
        utils.getPerms(utils, "get_role_files", (perm) => { getRoleFile(perm, roleId) });
    });

    function getRoleFile(perm, roleId) {
        utils.postItems(JSON.stringify([roleId, perm]), function (res) {
            let parsedRes = JSON.parse(res);
            let roleData = [];
            for (let i = 0; i < parsedRes.length; i++) {
                let id = parsedRes[i].role_file_id;
                let blocked = '<input type="checkbox" checked class="arf_link_block" data-id="' + id + '"/>';
                let unblocked = '<input type="checkbox" class="arf_link_block" data-id="' + id + '"/>';
                roleData.push([
                    parsedRes[i].path,
                    parsedRes[i].description,
                    parsedRes[i].arf_link_restricted == 1 ? blocked : unblocked,
                    '<i class="fas fa-trash text-danger delRoleFile" data-id="' + id + '"></i>'
                ]);
            }

            $("#tblRoleFiles").DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Path" },
                    { title: "Description" },
                    { title: "Blocked" },
                    { title: "Del" }
                ],
                data: roleData
            });
            $("#roleFileModal").modal("show");
        });
    } //end function 

    $("#tblRolePerms").on("click", ".ar_link_block", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        let blocked = 0;
        if (elem.prop("checked")) {
            blocked = 1;
        }
        utils.getPerms(utils, "block_role_perm", (perm) => {
            block(perm, rowId, blocked);
        });
    });

    function block(perm, rowId, blocked) {
        utils.postItems(JSON.stringify([rowId, blocked, perm]), function (res) {
            let parsedRes = JSON.parse(res);
            if (parsedRes == "Ok") {
                // $.notify("Operation successful", "success");
                utils.toastMsg("Operation successful", "green", "green");
            } else {
                // $.notify(parsedRes, "danger");
                utils.toastMsg(parsedRes, "red", "red");
            }
        });
    }

    $("#tblRoleFiles").on("click", ".arf_link_block", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        let blocked = 0;
        if (elem.prop("checked")) {
            blocked = 1;
        }
        utils.getPerms(utils, "block_role_file", (perm) => { block(perm, rowId, blocked) });
    });


    $("#tblFiles").on("click", ".file_block", function () {
        let elem = $(this);
        let rowId = elem.data("id");
        let blocked = 0;
        if (elem.prop("checked")) {
            blocked = 1;
        }
        utils.getPerms(utils, "block_file", (perm) => { block(perm, rowId, blocked); });
    });


    function getRolePerm(perm, roleId) {
        utils.postItems(JSON.stringify([roleId, perm]), function (res) {
            let parsedRes = JSON.parse(res);
            let roleData = [];
            for (let i = 0; i < parsedRes.length; i++) {
                let id = parsedRes[i].role_perm_id;
                let blocked = '<input type="checkbox" checked class="ar_link_block" data-id="' + id + '"/>';
                let unblocked = '<input type="checkbox" class="ar_link_block" data-id="' + id + '"/>';
                roleData.push([
                    parsedRes[i].permission,
                    parsedRes[i].description,
                    parsedRes[i].ar_link_restricted == 1 ? blocked : unblocked,
                    '<i class="fas fa-trash text-danger delRolePerm" data-id="' + id + '"></i>'
                ]);
            }

            $("#tblRolePerms").DataTable({
                "processing": false,
                "serverSide": false,
                "pagingType": "simple",
                "responsive": true,
                "bDestroy": true,
                columns: [
                    { title: "Permission" },
                    { title: "Description" },
                    { title: "Blocked" },
                    { title: "Del" }
                ],
                data: roleData
            });
            $("#rolePermModal").modal("show");
        });
    } //end function 

    function getAllObjects(perm) {
        utils.postItems(JSON.stringify([perm]), function (res) {
            let parsedRes = JSON.parse(res);
            $("#object_type").empty().append('<option selected disabled>Object Type</option>');
            let objects = parsedRes.objects;
            let entities = parsedRes.entities;
            $("#object_type").empty();
            for (let i = 0; i < objects.length; i++) {
                $("#object_type").append('<option value="' + objects[i].id + '">' + objects[i].name + '</option>');
            }

            $("#object_parent").empty();
            $("#object_parent").append('<option value="0">Not Under Any Unit/Department</option>');
            for (let i = 0; i < entities.length; i++) {
                $("#object_parent").append('<option value="' + entities[i].id + '">' + entities[i].name + '</option>');
            }
        });
    }
    utils.getPerms(utils, "get_all_objects", (perm) => { getAllObjects(perm) });

    function getAllEntities(perm) {
        utils.postItems(JSON.stringify([perm]), function (res) {
            let parsedRes = JSON.parse(res);
            $("#user_entity_to_add").empty().append('<option selected disabled>Entities</option>');
            for (let i = 0; i < parsedRes.length; i++) {
                $("#user_entity_to_add").append('<option value="' + parsedRes[i].id + '">' + parsedRes[i].name + '</option>');
            }
        });
    }
    utils.getPerms(utils, "get_all_entities", (perm) => { getAllEntities(perm) });


    function getAllPerms(perm) {
        utils.postItems(JSON.stringify([perm]), function (res) {
            let parsedRes = JSON.parse(res);
            $("#role_permission_to_add").empty().append('<option selected disabled>Permissions</option>');
            for (let i = 0; i < parsedRes.length; i++) {
                $("#role_permission_to_add").append('<option value="' + parsedRes[i].id + '">' + parsedRes[i].permission + '</option>');
            }
        });
    }
    utils.getPerms(utils, "get_all_perms", (perm) => { getAllPerms(perm) });

    function getAllFiles(perm) {
        utils.postItems(JSON.stringify([perm]), function (res) {
            let parsedRes = JSON.parse(res);
            $("#role_files_to_add").empty().append('<option selected disabled>Files</option>');
            for (let i = 0; i < parsedRes.length; i++) {
                $("#role_files_to_add").append('<option value="' + parsedRes[i].id + '">' + parsedRes[i].description + " (" + parsedRes[i].path + ')</option>');
            }
        });
    }
    utils.getPerms(utils, "get_all_files", (perm) => { getAllFiles(perm) });

    function map(perm, entityId, selectBox, modal) {
        return new Promise((resolve, reject) => {
            let id = sessionStorage.getItem(entityId);
            let values = $(selectBox).val();
            utils.postItems(JSON.stringify([id, values, perm]), function (res) {
                let parsedRes = JSON.parse(res);
                if (parsedRes == "Ok") {
                    resolve(true);
                    sessionStorage.removeItem(entityId);
                    // $.notify("Operation successful", "success");
                    utils.toastMsg("Operation successful", "green", "green");
                    if (modal != null)
                        $(modal).modal("hide");
                } else {
                    reject(parsedRes);
                    // $.notify(parsedRes, "danger")
                    utils.toastMsg(parsedRes, "red", "red");
                }
            });
        });
    }

    $("#map_perm_to_role").on("click", function () {
        utils.getPerms(utils, "map_perm_to_role", (perm) => {
            map(perm, "roleId", "#role_permission_to_add", "#rolePermModal");
        });
    });

    $("#map_file_to_role").on("click", function () {
        utils.getPerms(utils, "map_files_to_role", (perm) => {
            map(perm, "roleFId", "#role_files_to_add", "#roleFileModal");
        });
    });

    $("#tblRolePerms").on("click", ".delRolePerm", function () {
        let rowId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_role_perm", (perm) => { del(perm, rowId, $(this)) });
    });


    $("#tblRoleFiles").on("click", ".delRoleFile", function () {
        let rowId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_role_file", (perm) => { del(perm, rowId, $(this)) });
    });


    $('#tblRoles').on("click", ".del_role", function () {
        let userId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_role", (perm) => { del(perm, userId, $(this)) });
        utils.getPerms(utils, "get_all_user_roles", (perm) => { getAllRoles(perm) });
    });



    $('#tblFiles').on("click", ".del_file", function () {
        let fileId = $(this).data("id");
        let bool = confirm("Click on Ok if you wnat to proceed with the action else Click on Cancel");
        if (!bool) return;
        utils.getPerms(utils, "del_file", (perm) => {
            del(perm, fileId, $(this)).then(() => {
                utils.getPerms(utils, "get_all_files", (perm) => { getAllFiles(perm) });
            }).catch(() => { });
        });
    });


    $('#tblRoles').on("click", ".get_edittable_role", function () {
        let roleId = $(this).data("id");
        sessionStorage.setItem("roleToEditId", roleId);
        utils.getPerms(utils, "get_role_to_edit", (perm) => {
            getEdittableEntity(perm, roleId, "#roleDetails", "#roleEditModal");
        });
    });

    $("#edit_role").on("click", () => {
        utils.getPerms(utils, "edit_role", (perm) => {
            edit(perm, "roleToEditId", "#roleDetails", "#roleEditModal").then(() => {
                utils.getPerms(utils, "get_all_user_roles", (perm) => { getAllRoles(perm) });
            }).catch(() => {

            });
        });
    });

    $("#edit_object_type").on("click", () => {
        utils.getPerms(utils, "edit_obtype", (perm) => {
            edit(perm, "obTypeToEditId", "#object_type_inp_container", null).then(() => {
                $(".obj_inp").val("");
                $("#add_object_type").removeClass("d-none");
                $("#edit_object_type").addClass("d-none");
                $("#object_type").val("Object Type");
                $("#object_parent").val("Is Under");
                utils.getPerms(utils, "get_all_objects", (perm) => { getAllObjects(perm) });
                utils.getPerms(utils, "get_entities", (perm) => { getEntities(perm) });
            });
        });
    });

    // }); //getScript
}); //end jquery