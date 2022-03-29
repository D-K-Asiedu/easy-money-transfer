

$.getScript('../js/custom/utilities.js').done(async function () {
    let utils = Utilities;
   


    async function getCountries() {
        let res = await utils.fetchDetails('get_countries', sessionStorage.getItem('token'), null, utils);
        let d = res.data;
        let html = `<option value=''>Select Country</option>`;
        for (let i = 0; i < d.length; i++) {
            html += `<option value='${d[i].id}'>${d[i].name}</option>`;
        }
        $('.country').html(html);
    }
    getCountries();

    let timer = null;
    $('#s_amount').on('keyup', function (e) {
        let amt = $(this).val();
        e.stopImmediatePropagation();
        clearTimeout(timer);
        timer = setTimeout(() => {
            let sCountry = $('#s_country').val();
            let rCountry = $('#r_country').val();
            getExchangeRate(sCountry, rCountry, amt);
            calcCommission(amt);
        }, 1000);
    });

    async function getExchangeRate(sCountry, rCountry, amt) {
        if (sCountry == '' && rCountry == '') {
            $('#echange_rate').val('');
            $('#r_amount').val('');
            $('#agent_comm').val('');
            $('#admin_comm').val('');
            $('#total_amt_with_comm').val('');
            utils.toastMsg('Cannot fetch exchnage rate. Please set the country of the sender and the receiver', 'red', 'red');
        }
        let res = await utils.fetchDetails('get_exchange_rate', sessionStorage.getItem('token'), { s_country: sCountry, r_country: rCountry }, utils);
        let r = res.data;
        let d = r.data;
        if (r.status == 'Ok') {
            let flag = r.flag;
            let rCurrency = d.r_currency;
            let rRate = d.r_rate;
            let sCurrency = d.s_currency;
            let sRate = d.s_rate;
            sessionStorage.setItem('exchange_rate_id',d.id);
            let rate = `${sCurrency} ${sRate} = ${rCurrency} ${rRate}`;
            $('#exchange_rate').val(rate);
            $('.s_currency').html(sCurrency);
            $('.r_currency').html(rCurrency);
            let rAmount = 0;
            if (flag == 'normal') {
                rAmount = (rRate * amt).toFixed(2);
            } else if (flag == 'reversed') {
                rAmount = (amt / rRate).toFixed(2);
            }
            $('#r_amount').val(rAmount);
        } else {
            $('#exchange_rate').val('');
            $('#r_amount').val('');
            $('#agent_comm').val('');
            $('#admin_comm').val('');
            $('#total_amt_with_comm').val('');
            utils.toastMsg(r.msg, 'red', 'red');
        }
    }

    $('.country').change(function () {
        $('#s_amount').val('');
        $('#r_amount').val('');
        $('#exchange_rate').val('');
        $('#agent_comm').val('');
        $('#admin_comm').val('');
        $('#total_amt_with_comm').val('');
    });

    function calcCommission(sAmount) {
        let totalCommission = 0;
        if ($.trim(sAmount) == '') {
            totalCommission = 0;
        } else if (sAmount >= 1 && sAmount <= 50) {
            totalCommission = 3;
        } else if (sAmount >= 51 && sAmount <= 100) {
            totalCommission = 4;
        } else if (sAmount >= 101 && sAmount <= 150) {
            totalCommission = 6;
        } else {
            let a = Math.ceil(sAmount / 100);
            totalCommission = a * 4;
        }
        let dividedCommission = totalCommission / 2;
        let t = parseFloat(totalCommission) + parseFloat(sAmount);
        if (!isNaN(t)) {
            $('#total_amt_with_comm').val(t);
        } else {
            $('#total_amt_with_comm').val(0);
        }

        $('#agent_comm').val(dividedCommission);
        $('#admin_comm').val(dividedCommission);
    }

    $('#save_txn').on('click', async function (e) {
        e.stopImmediatePropagation();
        let data = utils.extractData('#txnParentInp');
        let valid = utils.validateData(data, utils);
        if (!valid.formValid) {
            utils.animateToElement(valid.values[0]);
            return;
        }
        let res = await utils.saveData('add_transaction', {
            data: valid.values,           
            token: sessionStorage.getItem('token'),
            transaction_id: sessionStorage.getItem('transaction_id'),
            exchange_rate_id: sessionStorage.getItem('exchange_rate_id')
        }, utils);  
        console.log(res); 

    });

    // Configuration Page
    // Functions
    async function get_countries(){
        let res = await utils.fetchDetails('get_countries', sessionStorage.getItem('token'), null, utils);
        let d = res.data;
        let html = "";
        for (let i = 0; i < d.length; i++) {
            html += `<tr id="country-${d[i].id}">
            <td id="country_name-${d[i].id}">${d[i].name}</td>
            <td id="country_code-${d[i].id}">${d[i].code}</td>
            <td id="country_currency-${d[i].id}">${d[i].currency}</td>
            <td>
            <button class="btn btn-primary btn-sm" id="update_country" data-id="${d[i].id}">Update</button>
            <button class="btn btn-danger btn-sm" id="delete_country" data-id="${d[i].id}">Delete</button>
            </td>
          </tr>`;
        }
        $('.countries').html(html);
        
    }

    async function getCountry(id){
        let res = await utils.fetchDetails('get_country', sessionStorage.getItem("token"),[id], utils);
        let data = res.data;

        if (data.status == "Ok"){
            return data.data[0]["name"];
        }
    }

    async function get_exchange_rate(){
        let res = await utils.fetchDetails('get_exchange_rates', sessionStorage.getItem('token'), null, utils);
        let d = res.data;
        let html = "";
        for (let i = 0; i < d.length; i++) {
            let s_country = await getCountry(d[i].s_country);
            let r_country = await getCountry(d[i].r_country);

            html += `<tr id="rate-${d[i].id}">
            <td id="s_country-${d[i].id}">${s_country}</td>
            <td id="r_country-${d[i].id}">${r_country}</td>
            <td id="s_rate-${d[i].id}">${d[i].s_rate}</td>
            <td id="r_rate-${d[i].id}">${d[i].r_rate}</td>
            <td>
            <button class="btn btn-primary btn-sm" id="update_rate" data-id="${d[i].id}">Update</button>
            <button class="btn btn-danger btn-sm" id="delete_rate" data-id="${d[i].id}">Delete</button>
            </td>
          </tr>`;
        }
        $('.exchange_rate').html(html);
        
    }

    async function getCountriesUpdating(names) {
        let res = await utils.fetchDetails('get_countries', sessionStorage.getItem('token'), null, utils);
        let d = res.data;
        let html = `<option value=''>Select Country</option>`;
        for (let i = 0; i < d.length; i++) {
            if (names[0] == d[i].name){
                html += `<option value='${d[i].id}' selected>${d[i].name}</option>`;
            }else{
                html += `<option value='${d[i].id}'>${d[i].name}</option>`;
            }
        }
        $('.s_country').html(html);

        html = `<option value=''>Select Country</option>`;
        for (let i = 0; i < d.length; i++) {
            if (names[1] == d[i].name){
                html += `<option value='${d[i].id}' selected>${d[i].name}</option>`;
            }else{
                html += `<option value='${d[i].id}'>${d[i].name}</option>`;
            }
        }
        $('.r_country').html(html);
    }

    // Countries Configuration
    get_countries();
    get_exchange_rate();
    $("#country_button").on('click', "#add_country",async function(e){
        e.stopImmediatePropagation();
        let html = `<tr id="txnParentInp">
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="country_name" placeholder="Enter Country Name" data-required="true">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="country_code" placeholder="Enter Country Code" data-required="true">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="country_currency" placeholder="Enter Country Currency" data-required="true">
            </div>
        </td>
        <td>
            <button id="save_country" class="btn btn-primary btn-sm">Save</button>
            <button id="cancel_save_country" class="btn btn-danger btn-sm">Cancel</button>
        </td>
    </tr>`;
    $(".countries").append(html);
    $('#country_button').hide();
    });

    $('.countries').on('click','#save_country',async (e)=>{
        e.stopImmediatePropagation();
        let data = utils.extractData('#txnParentInp');
        let valid = utils.validateData(data, utils);
        if (!valid.formValid) {
            return;
        }
        
        let res = await utils.saveData('add_country', {
            data: valid.values,           
            token: sessionStorage.getItem('token'),
            transaction_id: sessionStorage.getItem('transaction_id'),
            exchange_rate_id: sessionStorage.getItem('exchange_rate_id')
        }, utils);  
        get_countries();
        $('#country_button').show();
        console.log(res);
    });

    $(".countries").on("click", "#cancel_save_country", async function(){
        get_countries();
        $('#country_button').show();
    })

    $(".countries").on("click", "#update_country", function(){
        console.log("Update Button");
        let id = $(this).data("id");
        let country_name = $(`#country_name-${id}`).text();
        let country_code = $(`#country_code-${id}`).text();
        let country_currency = $(`#country_currency-${id}`).text();
        console.log(country_name);

        let html = `
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="country_name" value=${country_name} data-required="true">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="country_code" value=${country_code} data-required="true">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="country_currency" value=${country_currency} data-required="true">
            </div>
        </td>
        <td>
        <button id="save_update_country" class="btn btn-primary btn-sm" data-id=${id}>Save</button>
        <button id="cancel_save_country" class="btn btn-danger btn-sm">Cancel</button>
        </td>`;
        $(`#country-${id}`).html(html);

    });

    $(".countries").on("click", "#save_update_country", async function(){
        let id = $(this).data("id");
        let data = utils.extractData(`#country-${id}`);
        let valid = utils.validateData(data, utils);
        if (!valid.formValid) {
            return;
        }

        console.log(data);
        
        let res = await utils.saveData('update_country', {
            data: valid.values,           
            token: sessionStorage.getItem('token'),
            id: id
        }, utils);  
        get_countries();
        console.log(res);
    });

    $(".countries").on("click", "#delete_country", async function(){
        let id = $(this).data("id");
        let res = await utils.fetchDetails('delete_country', sessionStorage.getItem('token'), {"id": id }, utils);

        if (res.data.status == "Ok"){
            utils.toastMsg('Operation Successful', "green", "green");
            get_countries();
        }
        else{
            utils.toastMsg(res.data.msg, "red", "red");
        }
    });

    //Exchange Rate Configuration
    $("#exchange_rate_button").on('click', "#add_exchange_rate",async function(e){
        e.stopImmediatePropagation();
        let html = `<tr id="txnParentInp">
        <td>
            <select id='s_country' class="form-control country" data-required="true">
            </select>
        </td>
        <td>
            <select id='r_country' class="form-control country" data-required="true">
            </select>
        </td>
        
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="s_rate" placeholder="Enter Sender Rate" data-required="true">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="r_rate" placeholder="Enter Reciever Rate" data-required="true">
            </div>
        </td>
        <td>
        <button id="save_rate" class="btn btn-primary btn-sm">Save</button>
        <button id="cancel_save_rate" class="btn btn-danger btn-sm">Cancel</button>
        </td>
    </tr>`;
    $(".exchange_rate").append(html);
    getCountries();
    $('#exchange_rate_button').hide();
    });

    $('.exchange_rate').on('click','#save_rate',async ()=>{
        let data = utils.extractData('#txnParentInp');
        let valid = utils.validateData(data, utils);
        if (!valid.formValid) {
            return;
        }        
        let res = await utils.saveData('add_exchange_rate', {
            data: valid.values,           
            token: sessionStorage.getItem('token'),
            transaction_id: sessionStorage.getItem('transaction_id'),
            exchange_rate_id: sessionStorage.getItem('exchange_rate_id')
        }, utils);  
        get_exchange_rate();
        $('#exchange_rate_button').show();
        console.log(res);
    });

    $(".exchange_rate").on("click", "#cancel_save_rate",function(){
        get_exchange_rate();
        $('#exchange_rate_button').show();
    });

    $(".exchange_rate").on("click", "#update_rate",async function(){
        console.log("Update Button");
        let id = $(this).data("id");
        let s_country = $(`#s_country-${id}`).text();
        let r_country = $(`#r_country-${id}`).text();
        let s_rate = $(`#s_rate-${id}`).text();
        let r_rate = $(`#r_rate-${id}`).text();

        console.log(s_country);

        let html = `
        <td>
            <select id='s_country' class="form-control s_country" data-required="true">
            </select>
        </td>
        <td>
            <select id='r_country' class="form-control r_country" data-required="true">
            </select>
        </td>
        
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="s_rate" value=${s_rate} data-required="true">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control form-control-sm" id="r_rate" value=${r_rate} data-required="true">
            </div>
        </td>
        <td>
        <button id="save_update_rate" class="btn btn-primary btn-sm" data-id=${id}>Save</button>
        <button id="cancel_save_rate" class="btn btn-danger btn-sm">Cancel</button>
        </td>`;
        $(`#rate-${id}`).html(html);
        getCountriesUpdating([s_country, r_country]);
    });

    $(".exchange_rate").on("click", "#save_update_rate", async function(){
        console.log("Hello");
        let id = $(this).data("id");
        let data = utils.extractData(`#rate-${id}`);
        let valid = utils.validateData(data, utils);
        if (!valid.formValid) {
            return;
        }

        console.log(data);
        
        let res = await utils.saveData('update_exchange_rate', {
            data: valid.values,           
            token: sessionStorage.getItem('token'),
            id: id,
        }, utils);  
        get_exchange_rate();
        console.log(res);
    });

    $(".exchange_rate").on("click", "#delete_rate", async function(){
        let id = $(this).data("id");
        let res = await utils.fetchDetails('delete_exchange_rate', sessionStorage.getItem('token'), {"id": id }, utils);

        if (res.data.status == "Ok"){
            utils.toastMsg('Operation Successful', "green", "green");
            get_exchange_rate();
        }
        else{
            utils.toastMsg(res.data.msg, "red", "red");
        }
    });


});
