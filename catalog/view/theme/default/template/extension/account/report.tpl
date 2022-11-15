<?php echo $header; ?>
<div id="account-account" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> Filter Transactions </h3>
        </div>
        <div class="panel-body">
          <form action="<?php echo $filter_report_action; ?>" method="POST" >
            <select name="filter_report_month" class="form-control" id="filter">
              <option value="">Select</option>
              <?php foreach($months as $_key => $month) { ?> 
                <option value="month=<?php echo $month['month']; ?>&year=<?php echo $month['year']; ?>"><?php echo $month['label']; ?></option>
              <?php } ?>
            </select>
            <script type="text/javascript">
            $('#filter').on('change',function(){
            location.href='<?php echo $filter_report_action; ?>&'+$(this).val();
            });
            </script>
          </form>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_completed_orders; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left "><b><?php echo $column_order_id; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_added; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_modified; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_description; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_order_subtotal; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_commission; ?></b></a></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($completed_transactions)) { ?>
                  <?php foreach($completed_transactions as $_key => $completed_transaction) { ?> 
                    <tr>
                      <td class="text-left"><?php echo $completed_transaction['order_id']; ?></td>
                      <td class="text-left"><?php echo $completed_transaction['date_added']; ?></td>
                      <td class="text-left"><?php echo $completed_transaction['date_modified']; ?></td>
                      <td class="text-left"><?php echo $completed_transaction['description']; ?></td>
                      <td class="text-left"><?php echo $completed_transaction['order_total']; ?></td>
                      <td class="text-left"><?php echo $completed_transaction['amount']; ?></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td class="text-right" colspan="5"><b><?php echo $column_balance; ?></b></td>
                    <td class="text-left"><?php echo $completed_orders_commission; ?></td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_shipped_orders; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left "><b><?php echo $column_order_id; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_added; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_modified; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_description; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_order_subtotal; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_commission; ?></b></a></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($shipped_transactions)) { ?>
                  <?php foreach($shipped_transactions as $_key => $shipped_transaction) { ?> 
                    <tr>
                      <td class="text-left"><?php echo $shipped_transaction['order_id']; ?></td>
                      <td class="text-left"><?php echo $shipped_transaction['date_added']; ?></td>
                      <td class="text-left"><?php echo $shipped_transaction['date_modified']; ?></td>
                      <td class="text-left"><?php echo $shipped_transaction['description']; ?></td>
                      <td class="text-left"><?php echo $shipped_transaction['order_total']; ?></td>
                      <td class="text-left"><?php echo $shipped_transaction['amount']; ?></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td class="text-right" colspan="5"><b><?php echo $column_balance; ?></b></td>
                    <td class="text-left"><?php echo $shipped_orders_commission; ?></td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_cancelled_orders; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left "><b><?php echo $column_order_id; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_added; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_modified; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_description; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_order_subtotal; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_commission; ?></b></a></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($cancelled_transactions)) { ?>
                  <?php foreach($cancelled_transactions as $_key => $cancelled_transaction) { ?> 
                    <tr>
                      <td class="text-left"><?php echo $cancelled_transaction['order_id']; ?></td>
                      <td class="text-left"><?php echo $cancelled_transaction['date_added']; ?></td>
                      <td class="text-left"><?php echo $cancelled_transaction['date_modified']; ?></td>
                      <td class="text-left"><?php echo $cancelled_transaction['description']; ?></td>
                      <td class="text-left"><?php echo $cancelled_transaction['order_total']; ?></td>
                      <td class="text-left"><?php echo $cancelled_transaction['amount']; ?></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td class="text-right" colspan="5"><b><?php echo $column_balance; ?></b></td>
                    <td class="text-left"><?php echo $cancelled_orders_commission; ?></td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_pending_orders; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left "><b><?php echo $column_order_id; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_added; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_description; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_order_subtotal; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_commission; ?></b></a></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($pending_transactions)) { ?>
                  <?php foreach($pending_transactions as $_key => $pending_transaction) { ?> 
                    <tr>
                      <td class="text-left"><?php echo $pending_transaction['order_id']; ?></td>
                      <td class="text-left"><?php echo $pending_transaction['date_added']; ?></td>
                      <td class="text-left"><?php echo $pending_transaction['description']; ?></td>
                      <td class="text-left"><?php echo $pending_transaction['order_total']; ?></td>
                      <td class="text-left"><?php echo $pending_transaction['amount']; ?></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td class="text-right" colspan="4"><b><?php echo $column_balance; ?></b></td>
                    <td class="text-left"><?php echo $pending_orders_commission; ?></td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_processing_orders; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left "><b><?php echo $column_order_id; ?></b></a></td>
                  <td class="text-left "><b><?php echo $column_date_added; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_description; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_order_subtotal; ?></b></a></td>
                  <td class="text-left"><b><?php echo $column_commission; ?></b></a></td>
                </tr>
              </thead>
              <tbody>
                <?php if (isset($processing_transactions)) { ?>
                  <?php foreach($processing_transactions as $_key => $processing_transaction) { ?> 
                    <tr>
                      <td class="text-left"><?php echo $processing_transaction['order_id']; ?></td>
                      <td class="text-left"><?php echo $processing_transaction['date_added']; ?></td>
                      <td class="text-left"><?php echo $processing_transaction['description']; ?></td>
                      <td class="text-left"><?php echo $processing_transaction['order_total']; ?></td>
                      <td class="text-left"><?php echo $processing_transaction['amount']; ?></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <td class="text-right" colspan="4"><b><?php echo $column_balance; ?></b></td>
                    <td class="text-left"><?php echo $processing_orders_commission; ?></td>
                  </tr>
                <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>