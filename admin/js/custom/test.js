// import { Utilities } from '../../../js/custom/utilities.js';
$.getScript('../js/custom/utilities.js').done(async function () {
    let utils = Utilities;
    $(document).ready(function() {
        console.log("Test")
            $("#add-sender").on("click",async () => {
                let data = {
                    "sender_phone": "0557270470",
                    "sender_name": "Alvis Finnegan",
                    "sender_country": 2
                }
                let res = await utils.fetchDetails("add_sender",null, data, utils)
                console.log(res.data)
                });
    
            $("#remove-sender").click(async ()=>{
                console.log("remove sender")
                let data = {
                    "id": 1
                }
                let res = await utils.fetchDetails("remove_sender",null, data, utils)
                console.log(res.data)
            })
    
            $("#get-senders").click(async ()=>{
                console.log("get sender")
    
                let res = await utils.fetchDetails("get_senders",null, null, utils)
                console.log(res.data)
            })
    
            $("#get-sender").click(async ()=>{
                console.log("get sender")
    
                let data = {
                    "id": 1
                }
                let res = await utils.fetchDetails("get_sender",null, data, utils)
                console.log(res.data)
            })
    
            $("#add-transaction").click(async ()=>{
                console.log("add transaction")
                let data = {
                    "sender_phone": "0557270470",
                    "sender_name": "Alvis Finnegan",
                    "sender_country": 2,
                    "transaction_id": null,
                    "reciever_phone": "0555555555555",
                    "reciever_name": "Donald K Asiedu",
                    "reciever_country": 1,
                    "reciever_payment_mode": "Mobile Money",
                    "s_amount": 100.0,
                    "r_amount": 200.0,
                    "exchange_rate": 1,
                    "agent_commission": 1,
                    "admin_commission": 1,
                    "total_commission": 2,
                    "sender_id": 2
                }
    
                let res = await utils.fetchDetails("add_transaction",null, data, utils)
                console.log(res.data)
            })
    
            // TODO
            $("#get-transaction").click(async ()=>{
                console.log("get transaction")
            })
    
            $("#get-exchange-rate").click(async ()=>{
                console.log("get exchange rate")
                let data = {
                    "s_country": 2,
                    "r_country": 1
                }
                let res = await utils.fetchDetails("get_exchange_rate",null, data, utils)
                console.log(res.data)
            })
    
            $("#add-exchange-rate").click(async ()=>{
                console.log("add exchange rate")
                let data = {
                    "s_country": 3,
                    "r_country": 1,
                    "s_rate": 5,
                    "r_rate": 1
                }
                let res = await utils.fetchDetails("add_exchange_rate",null, data, utils)
                console.log(res.data)
            })
    
            $("#add-country").click(async ()=>{
                console.log("add country")
                // let data = {
                //     "name": "France",
                //     "code": "+33",
                //     "currency": "EURO"
                // }
    
                let countries = [
                    {
                        "name": "Ghana",
                        "code": "233",
                        "currency": "GHC"
                    },
                    {
                        "name": "United Kingdom",
                        "code": "1",
                        "currency": "GBP"
                    },
                ]
    
                countries.map(async (country)=>{
                    let res = await utils.fetchDetails("add_country",null, country, utils)
                    console.log(res.data)
                })
                // let res = await utils.fetchDetails("add_country",null, data, utils)
                // console.log(res.data)
            }
            )
            $("#get-countries").click(async ()=>{
                console.log("get countries")
                
                let res = await utils.fetchDetails("get_countries",null, null, utils)
                console.log(res.data)
            })

            $("#deposit").click(async ()=>{
                console.log("Deposit")
                let data = {
                    "admin": 1,
                    "agent": 2,
                    "amount": 200
                }
                
                let res = await utils.fetchDetails("deposit",null, data, utils)
                console.log(res.data)
            })

            $("#complete-transaction").click(async ()=>{
                console.log("Complete Transaction")
                let data = {
                    "txn_id": "EXY2112110001",
                }
                
                let res = await utils.fetchDetails("complete_transaction",null, data, utils)
                console.log(res.data)
            })

            $("#complete-transaction-bulk").click(async ()=>{
                console.log("Complete Transaction  Bulk")
                let data = ["EXY2112140002", "EXY2112140003"]
                
                let res = await utils.fetchDetails("complete_transaction_bulk",null, data, utils)
                console.log(res.data)
            })
            
    });
})
