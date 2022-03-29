$(document).ready(() => {
    tinymce.init({
        selector: "textarea.tinymce",
        entity_encoding: "raw",
        paste_data_images: true,
        images_upload_url: 'controllers/postAcceptor.php',
        automatic_uploads: false,
        // forced_root_block : false,
        height: "500",        

        file_picker_callback: function(callback, value, meta) {
            if (meta.filetype == 'image') {
                let fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('multiple', 'true');
                $(fileInput).trigger('click');
                $(fileInput).on('change', () => {
                    let files = $(fileInput)[0].files;
                    let fileLength = files.length;
                    if (fileLength > 5) {
                        alert('Files cannot be more than 5');
                        return;
                    }
                    for (let i = 0; i < fileLength; i++) {
                        let file = files[i];
                        let fileSize = file.size;
                        if (fileSize > 1000000) {
                            alert('File size is too big. Please compress');
                            continue;
                        }
                        // console.log(file);
                        let reader = new FileReader();
                        reader.onloadend = (e) => {
                            let result = e.target.result;
                            callback(result, {
                                alt: ""
                            });
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        },

        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code codesample fullscreen insertdatetime media nonbreaking",
            "save table  directionality emoticons template paste imagetools",
            "help importcss noneditable quickbars tabfocus legacyoutput textpattern toc"
        ],

        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
    });

});