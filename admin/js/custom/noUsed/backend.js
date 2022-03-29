// users,roles,permissions

let i = [{
        name: "Entities",
        id: "entities",
        class: "items mb-3",
        ricon: { class: "fa-angle-down ml-3", id: "" },
        licon: { class: "fas fa-tachometer-alt ms-5 me-3", id: "" },
        style: "font-weight:500;",
        anchor: {
            class: "",
            id: "",
            spaRoute: { name: "entities", baseURL: "views/dashboard/", default: true },
            evt: (() => {}).toString(),
        },
        evt: (() => {}).toString()
    },
    {
        name: "Add User",
        id: "add_user",
        class: "items mb-3",
        ricon: { class: "fa-angle-down ml-3", id: "" },
        licon: { class: "fas fa-tachometer-alt ms-5 me-3", id: "" },
        style: "font-weight:500;",
        anchor: {
            class: "",
            id: "",
            modal: { id: "addUserModal" },
            evt: (() => {}).toString(),
        },
        evt: (() => {}).toString()
    },
    {
        name: "Add Role",
        id: "add_role",
        class: "items mb-3",
        ricon: { class: "fa-angle-down ml-3", id: "" },
        licon: { class: "fas fa-tachometer-alt ms-5 me-3", id: "" },
        style: "font-weight:500;",
        anchor: {
            class: "",
            id: "",
            modal: { id: "addRoleModal" },
            evt: (() => {}).toString(),
        },
        evt: (() => {}).toString()
    },
    {
        name: "Add File",
        id: "add_files",
        class: "items mb-3",
        ricon: { class: "fa-angle-down ml-3", id: "" },
        licon: { class: "fas fa-tachometer-alt ms-5 me-3", id: "" },
        style: "font-weight:500;",
        anchor: {
            class: "",
            id: "",
            modal: { id: "addFileModal" },
            evt: (() => {}).toString(),
        },
        evt: (() => {}).toString()
    },
    {
        name: "Object",
        id: "object",
        class: "items mb-3",
        ricon: { class: "fa-angle-down ml-3", id: "" },
        licon: { class: "fas fa-tachometer-alt ms-5 me-3", id: "" },
        style: "font-weight:500;",
        anchor: {
            class: "",
            id: "",
            modal: { id: "objectModal" },
            evt: (() => {}).toString(),
        },
        evt: (() => {}).toString()
    },
    {
        name: "Add Permission",
        id: "permission",
        class: "items mb-3",
        ricon: { class: "fa-angle-down ml-3", id: "" },
        licon: { class: "fas fa-tachometer-alt ms-5 me-3", id: "" },
        style: "font-weight:500;",
        anchor: {
            class: "",
            id: "",
            modal: { id: "permissionModal" },
            evt: (() => {}).toString(),
        },
        evt: (() => {}).toString()
    },
    {
        name: "Upload File",
        id: "upload",
        class: "items mb-3",
        ricon: { class: "fa-angle-down ml-3", id: "" },
        licon: { class: "fas fa-tachometer-alt ms-5 me-3", id: "" },
        style: "font-weight:500;",
        anchor: {
            class: "",
            id: "",
            modal: { id: "uploadFileModal" },
            evt: (() => {}).toString(),
        },
        evt: (() => {}).toString()
    }
];

let items = document.getElementById("control-options");
if (items != null)
    items.setAttribute('controls', JSON.stringify(i));

$(document).ready(() => {

});