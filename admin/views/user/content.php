<div class="row" id="contentParentInp">
    <div class="col-md-6">
        <div class="form-group">
            <label>Unique Name </label>
            <input type="text" class="form-control" id="unique_name" placeholder="Enter unique name" data-required="true">
        </div>
        <div class="form-group">
            <label>HTML Handle/ParentID</label>
            <select id='html_handle' class="form-control" data-required="true">
                <option value=''>Handle</option>
                <option value='card_link_container'>Card Link Container</option>
                <option value='first_navbar'>First Navbar</option>
                <option value='scrolling_notice'>Scrolling Notice</option>
                <option value='head_of_unit_picture'>Head of Unit Picture</option>
                <option value='head_msg_title'>Head Msg Title</option>
                <option value='head_msg'>Head Msg</option>
                <option value='entity_b_pic'>Fixed Top Banner Picture</option>              
            </select>
        </div>
        <div class="form-group">
            <label>Description </label>
            <input type="text" class="form-control" id="description" placeholder="Enter description" data-required="false">
        </div>

        <div class="form-group">
            <label>Organizational Type </label>
            <select id='ou' class="form-control" data-required="true"></select>
        </div>

        <div class="form-group">
            <label>Show</label>
            <select id='show' class="form-control" data-required="true">
                <option value=''>Show</option>
                <option value='1'>Yes</option>
                <option value='0'>No</option>
            </select>
        </div>

        <div class="form-group">
            <label>Content Type</label>
            <select id='content_type' class="form-control" data-required="true">
                <option value=''>Content Type</option>
                <option value='card'>Card</option>
                <option value='single_navbar_item'>Single-Navbar Item</option>
                <option value='multi_navbar_item'>Multi-Navbar Item</option>
                <option value='html'>HTML</option>
                <option value='text'>Text</option>
                <option value='src'>SRC</option>
                <option value='custom'>Custom</option>
            </select>
        </div>

        <div>
            <label class="custom-file-upload" style="cursor:pointer">
                <input class="d-none" type="file" id="card_img_to_upload" data-required="false" />
                <i class="fas fa-user"></i> Click to Upload Card Image
            </label><br>
            <img class="content_img" id="content_img" height="220" style="width:100%">
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Link </label>
            <input type="text" class="form-control" id="content_href" placeholder="Enter link" data-required="false">
        </div>
        <div class="form-group">
            <label> CSS Classes </label>
            <textarea class="form-control" id="css_classes" data-required="false"></textarea>
        </div>
        <div class="form-group">
            <label>Parent Classes </label>
            <textarea class="form-control" id="parent_classes" data-required="false"></textarea>
        </div>
        <div class="form-group">
            <label>Name </label>
            <input type="text" class="form-control" id="content_name" placeholder="Enter name" data-required="false">
        </div>
        <div class="form-group">
            <label>Icon </label>
            <textarea class="form-control" id="content_icon" data-required="false"></textarea>
        </div>

        <div class="form-group">
            <label>Position </label>
            <input type="number" class="form-control" id="content_position" value="0" placeholder="Enter position" data-required="false">
        </div>

        <div class="form-group">
            <label>Style </label>
            <textarea class="form-control" id="content_style" data-required="false"></textarea>
        </div>

        <div class="form-group">
            <label>Content </label>
            <textarea class="form-control" id="content_body" data-required="false"></textarea>
            <button class="btn btn-link btn-sm" id="gen_content">Create Content</button>
        </div>
    </div>

    <!-- <div class="col-md-12">
        <div class="form-group">
            <hr>
            <textarea class="tinymce" id="event_content" data-required="false"></textarea>
        </div>
    </div> -->
</div>

<div class="">
    <button type="button" id="save_content" class="btn btn-primary">Save</button>
    <!-- <button type="button" id="pbtn" class="btn btn-info">Publish</button> -->
</div>


<script type="text/javascript" src="js/custom/card_actions.js"></script>
<script type="text/javascript" src="js/custom/user_global.js"></script>