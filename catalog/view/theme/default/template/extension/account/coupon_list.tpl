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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-coupon">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left "><b><?php echo $column_name; ?></b></a></td>
                  <td class="text-right"><b><?php echo $column_code; ?></b></a></td>
                  <td class="text-right"><b><?php echo $column_discount; ?></b></a></td>
                  <td class="text-right"><b><?php echo $column_start_date; ?></b></a></td>
                  <td class="text-right"><b><?php echo $column_end_date; ?></b></a></td>
                  <td class="text-right"><b><?php echo $column_status; ?></b></a></td>
                  <td class="text-right"><b><?php echo $column_commission; ?>(%)</b></a></td>
                  <td class="text-right"><b><?php echo $column_commission_amount; ?></b></a></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($coupons) { ?>
                <?php foreach ($coupons as $coupon) { ?>
                <tr>
                  <td class="text-left"><?php echo $coupon['name']; ?></td>
                  <td class="text-right"><?php echo $coupon['code']; ?></td>
                  <td class="text-right"><?php echo $coupon['discount']; ?></td>
                  <td class="text-right"><?php echo $coupon['date_start']; ?></td>
                  <td class="text-right"><?php echo $coupon['date_end']; ?></td>
                  <td class="text-right"><?php if ($coupon['status'] == 1) { ?><?php echo $text_enabled; ?><?php } else { ?><?php echo $text_disabled; ?><?php } ?></td>
                  <td class="text-right"><?php echo $coupon['affiliate_commission']; ?></td>
                  <td class="text-right"><?php echo abs($coupon['total']); ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
       <!--  <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div> -->
      </div>
    </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>