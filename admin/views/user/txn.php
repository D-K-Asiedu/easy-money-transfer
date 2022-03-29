<div class="row" id="txnParentInp">
    <div class="col-md-6">
        <h5 class="text-danger">Sender Details</h5>
        <hr>
        <div class="form-group">
            <label>Sender's Phone Number Without Country Code </label>
            <input type="text" class="form-control" id="s_phone" placeholder="Enter sender's phone number without country code" data-required="true">
        </div>
        <div class="form-group">
            <label>Sender Name</label>
            <input type="text" class="form-control" id="sender_name" placeholder="Enter sender's name" data-required="true">
        </div>
        <div class="form-group">
            <label>Sender's Country</label>
            <select id='s_country' class="form-control country" data-required="true">
                <option value=''>Sender's Country</option>
            </select>
        </div>


        <h5 class="text-danger">Amount to Send</h5>
        <hr>
        <div class="form-group">
            <label>Amount in Sender's Country Currency </label>
            &nbsp;(<label class="s_currency text-danger"></label>)
            <input type="number" class="form-control" id="s_amount" placeholder="Enter amount in sender's country currency" data-required="true">
        </div>
        <div class="form-group">
            <label>Amount in Receiver's Country Currency </label>
            &nbsp;(<label class="r_currency text-danger"></label>)
            <input disabled type="number" class="form-control" id="r_amount" placeholder="Enter amount in receiver's country currency" data-required="true">
        </div>

    </div>

    <div class="col-md-6">
        <h5 class="text-danger">Receiver Details</h5>
        <hr>
        <div class="form-group">
            <label>Receiver's Phone Number Without Country Code </label>
            <input type="text" class="form-control" id="r_phone" placeholder="Enter receiver phone number without country code" data-required="true">
        </div>
        <div class="form-group">
            <label>Receiver's Name</label>
            <input type="text" class="form-control" id="receiver_name" placeholder="Enter receiver's name" data-required="true">
        </div>
        <div class="form-group">
            <label>Receiver's Country</label>
            <select id='r_country' class="form-control country" data-required="true">
                <option value=''>Receiver's Country</option>
            </select>
        </div>
        <div class="form-group">
            <label>Payment Mode</label>
            <select id='pay_mode' class="form-control" data-required="true">
                <option value=''>Payment Mode</option>
                <option value='mobile'>Mobile</option>
                <option value='cash'>Cash</option>
                <option value='bank'>Bank form</option>
            </select>
        </div>
        <h5 class="text-danger">Exchange Rate</h5>
        <hr>
        <div class="form-group">
            <label>Exchange Rate </label>
            <input disabled type="text" class="form-control" id="exchange_rate" data-required="true">
        </div>
    </div>

    <div class="col-md-12">
        <h5 class="text-danger" style="display:inline-block">Commission in</h5>
        <h5 class="text-danger s_currency" style="display:inline-block"></h5>
        <hr>
        <div class="form-group">
            <label>Agent Commission </label>
            <input disabled type="text" class="form-control" id="agent_comm" data-required="true">
        </div>
        <div class="form-group">
            <label>Admin Commission </label>
            <input disabled type="text" class="form-control" id="admin_comm" data-required="true">
        </div>
        <div class="form-group">
            <label>Total Amount With Commission </label>
            <!-- &nbsp;(<label class="s_currency text-danger"></label>) -->
            <input disabled type="text" class="form-control" id="total_amt_with_comm" data-required="true">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select id='status' class="form-control" data-required="true">
                <option>pending</option>
                <option>pay</option>
            </select>
        </div>
    </div>
</div>

<div class="">
    <button type="button" id="save_txn" class="btn btn-primary">Create</button>
    <!-- <button type="button" id="pbtn" class="btn btn-info">Publish</button> -->
</div>


<script type="text/javascript" src="js/custom/easy.js"></script>