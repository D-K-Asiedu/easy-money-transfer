<div class="row" id="eventParentInp">

    <div class="col-md-6">
        <div class="form-group">
            <label>Event Type</label>
            <select id='event_type' class="form-control" data-required="true">
                <option value=''>Event Type</option>
                <option value='0'>Announcement</option>
                <option value='1'>News</option>
            </select>
        </div>
        <div class="form-group">
            <label>Title </label>
            <input type="text" class="form-control" id="title" placeholder="Enter title" data-required="true">
        </div>        
        <div class="form-group">
            <label>Organizational Type </label>
            <select id='ou' class="form-control" data-required="true">
                <option value=''>Organizational Type</option>
                <option>AMU</option>
                <option>FTE</option>
            </select>
        </div>
        <div class="form-group">
            <label>Featured </label>
            <select id='featured' class="form-control" data-required="true">
                <option value=''>Featured</option>
                <option value='1'>Yes</option>
                <option value='0'>No</option>
            </select>
        </div>

        <div class="form-group">
            <label>Link </label>
            <input type="text" class="form-control" id="link" placeholder="Enter link" data-required="false">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Is Carousel </label>
            <select id='is_carousel' class="form-control" data-required="true">
                <option value=''>Is Carousel</option>
                <option value='1'>Yes</option>
                <option value='0'>No</option>
            </select>
        </div>
        <div class="form-group">
            <label>Is Part of List </label>
            <select id='is_part_of_list' class="form-control" data-required="true">
                <option value=''>Is Part of List</option>
                <option value='1'>Yes</option>
                <option value='0'>No</option>
            </select>
        </div>
        <div class="form-group">
            <label>Position </label>
            <input type="number" class="form-control" id="position" value="0" placeholder="Enter position" data-required="true">
        </div>
        <div>
            <label class="custom-file-upload" style="cursor:pointer">
                <input class="d-none" type="file" id="event_banner_to_upload" data-required="false" />
                <i class="fas fa-user"></i> Click to Upload Banner Picture
            </label><br>
            <img class="eventBanner" id="eventBanner" height="220" style="width:100%">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">            
            <hr>
            <textarea class="tinymce" id="event_content" data-required="false"></textarea>
        </div>
    </div>
</div>

<div class="">
    <button type="button" id="sbtn" class="btn btn-primary">Save</button>
    <button type="button" id="pbtn" class="btn btn-info">Publish</button>
</div>


<script type="text/javascript" src="vendors/js-vendors/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="js/custom/tinymce_editor.js"></script>
<script type="text/javascript" src="js/custom/tinymce_editor_actions.js"></script>
<script type="text/javascript" src="js/custom/user_global.js"></script>