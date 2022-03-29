<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="extra_tbl" class="table table-striped table-hover table-sm" border="1"></table>
        </div>
    </div>
</div>
<div class="row" id="extraParentInp">
    <div class="col-md-6">
        <div class="form-group">
            <label>Parent Content </label>
            <input type="text" class="form-control" id="extra_parent" disabled data-required="true" data-clrignore="true">
        </div>

        <div class="form-group">
            <label>Name </label>
            <input type="text" class="form-control" id="extra_name" placeholder="Enter name" data-required="true">
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

        <div class="form-group">
            <label>Content </label>
            <textarea class="form-control" id="content_body" data-required="false"></textarea>
            <button class="btn btn-link btn-sm" id="gen_content">Create Content</button>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Show</label>
            <select id='extra_show' class="form-control" data-required="true">
                <option value=''>Show</option>
                <option value='1'>Yes</option>
                <option value='0'>No</option>
            </select>
        </div>

        <div class="form-group">
            <label>Link </label>
            <input type="text" class="form-control" id="extra_href" placeholder="Enter link" data-required="false">
        </div>


        <div class="form-group">
            <label>Position </label>
            <input type="number" class="form-control" id="content_position" value="0" placeholder="Enter position" data-required="false">
        </div>

        <div class="form-group">
            <label>Style </label>
            <textarea class="form-control" id="content_style" data-required="false"></textarea>
        </div>

    </div>

</div>

<div class="">
    <button type="button" id="save_extra" class="btn btn-primary">Save</button>
</div>


<script type="text/javascript" src="js/custom/card_actions.js"></script>
<script type="text/javascript" src="js/custom/user_global.js"></script>