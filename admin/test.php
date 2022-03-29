<!DOCTYPE html>
<html lang="en">
<?php require_once('../globals/html_head.php'); ?>

<body class="az-body">

  <button id="add-sender" class="btn btn-primary mt-2">Add Sender</button>
  <button id="remove-sender" class="btn btn-primary mt-1">Remove Sender</button>
  <button id="get-senders" class="btn btn-primary mt-1">Get Senders</button>
  <button id="get-sender" class="btn btn-primary mt-1">Get Sender</button>

  <button id="add-transaction" class="btn btn-success mt-5">Add Transaction</button>
  <button id="get-transaction" class="btn btn-success mt-1">Get Transaction</button>

  <button id="get-exchange-rate" class="btn btn-success mt-5">Get Exchange Rate</button>
  <button id="add-exchange-rate" class="btn btn-success mt-1">Add Exchange Rate</button>

  <button id="add-country" class="btn btn-success mt-5">Add Country</button>
  <button id="get-countries" class="btn btn-success mt-1">Get Countries</button>

  <button id="deposit" class="btn btn-success mt-5">Deposit</button>
  <button id="complete-transaction" class="btn btn-success mt-5">Complete Transaction</button>
  <button id="complete-transaction-bulk" class="btn btn-success mt-1">Complete Transaction Bulk</button>
  

  <?php require_once('../globals/includes.php') ?>
  <script type="module" src="js/custom/test.js"></script>
</body>

</html>