<?php
include 'btcchina.php';

$account_info = json_decode(request('getAccountInfo', array()), true);
//$json_demo = '{"result":{"profile":{"username":"domnli","trade_password_enabled":true,"otp_enabled":false,"trade_fee":0,"daily_btc_limit":10,"btc_deposit_address":"14DYYjLB4XmJGvYWozamdFB5c3BWUn5XuG","btc_withdrawal_address":""},"balance":{"btc":{"currency":"BTC","symbol":"\u0e3f","amount":"0.91000000","amount_integer":"91000000","amount_decimal":8},"cny":{"currency":"CNY","symbol":"\u00a5","amount":"11.68800","amount_integer":"1168800","amount_decimal":5}},"frozen":{"btc":{"currency":"BTC","symbol":"\u0e3f","amount":"0.50000000","amount_integer":"50000000","amount_decimal":8},"cny":{"currency":"CNY","symbol":"\u00a5","amount":null,"amount_integer":"0","amount_decimal":5}}},"id":"1"}';
//$account_info = json_decode($json_demo, true);
$profile_info = $account_info['result']['profile'];
$balance_info = $account_info['result']['balance'];
$frozen_info = $account_info['result']['frozen'];
//print_r($account_info);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>BTCCHINA-董明理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.2/css/bootstrap.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- ticker begin-->
    <div style="min-height: 23px; background-color:#fafafa; border-bottom: 1px solid #D5D5D5;" class="header navbar navbar-inverse navbar-fixed-top">
      <div class="row">
        <div id="toast-container" class="toast-top-center">
          <div class="toast toast" style="background:#4A5866;">
            <div class="toast-message">
              <div class="text-center" style="margin-bottom: 0; color: white; font-size: 12px;" id="ticker">

                最新成交价：¥<span class="last" style="font-size: 16px;"></span>
                <span class="spacer" style="padding-left: 15px"></span>
                买一：¥<span class="buy" style="font-size: 16px;"></span>
                <span class="spacer" style="padding-left: 15px"></span>
                卖一：¥<span class="sell" style="font-size: 16px;"></span>
                <span class="spacer" style="padding-left: 15px"></span>
                最高：¥<span class="high" style="font-size: 16px;"></span>
                <span class="spacer" style="padding-left: 15px"></span>
                最低：¥<span class="low" style="font-size: 16px;"></span>
                <span class="spacer" style="padding-left: 15px"></span>
                成交量：฿<span class="vol" style="font-size: 16px;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ticker end-->

    <div class="container" style="margin-top: 85px;">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs">
        <li class="active"><a href="#accountpane" data-toggle="tab">账户</a></li>
        <li><a href="#buypane" data-toggle="tab">买BTC</a></li>
        <li><a href="#sellpane" data-toggle="tab">卖BTC</a></li>
        <li><a href="#orderpane" data-toggle="tab">查看挂单</a></li>
        <li><a href="#settings" data-toggle="tab"></a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <!-- accountpane begin -->
        <div class="tab-pane fade in active" id="accountpane">
          <p class="lead"><strong>用户名：<?php echo $profile_info['username']?></strong>&nbsp;</p>
          <h4>账户可用余额</h4>
          <p><?php echo $balance_info['btc']['symbol'].'<strong>'.(empty($balance_info['btc']['amount'])?'0.00':$balance_info['btc']['amount']).'</strong>' ?>&nbsp;BTC</p>
          <p><?php echo $balance_info['cny']['symbol'].'<strong>'.(empty($balance_info['cny']['amount'])?'0.00':$balance_info['cny']['amount']).'</strong>' ?>&nbsp;元</p>
          <h4>账户冻结金额</h4>
          <p><?php echo $frozen_info['btc']['symbol'].'<strong>'.(empty($frozen_info['btc']['amount'])?'0.00':$frozen_info['btc']['amount']).'</strong>' ?>&nbsp;BTC</p>
          <p><?php echo $frozen_info['cny']['symbol'].'<strong>'.(empty($frozen_info['cny']['amount'])?'0.00':$frozen_info['cny']['amount']).'</strong>' ?>&nbsp;元</p>
          <button type="button" class="btn btn-primary refresh" onclick="javascript:window.location.reload();">刷新</button>
        </div>
        <!-- accountpane end -->

        <!-- buypane begin -->
        <div class="tab-pane fade" id="buypane">
          <div id="buypane-amount" class="form-group">
            <label for="amount" class="control-label required">购买数额 (฿)：</label>
            <input type="text" id="buy-amount" size="10" class="form-control" required="">
            <p class="help-block">在这里输入您想购买的比特币的数额。</p>
          </div>
          <div id="buypane-price" class="form-group" style="display: block;">
            <label for="price" class="control-label required">出价 (¥)：</label>
            <input type="text" id="buy-price" size="10" class="form-control" required="">
            <span id="buypricecalculator"></span>
            <p class="help-block">在这里输入您的人民币出价，此出价为฿1.00个比特币的买入价格。</p>
          </div>
          <div id="buypane-buy" class="form-group">
            <input type="button" name="buy" id="buy-button" value="下买单" class="btn btn-default">
          </div>
        </div>
        <!-- buypane end -->

        <!-- sellpane begin -->
        <div class="tab-pane fade" id="sellpane">
          <div id="sellpane-amount" class="form-group">
            <label for="amount" class="control-label required">出售数额 (฿)：</label>
            <input type="text" id="sell-amount" size="10" class="form-control" required="">
            <p class="help-block">在这里输入您想出售的比特币的数额。</p>
          </div>
          <div id="sellpane-price" class="form-group">
            <label for="price" class="control-label required">售价 (¥)：</label>
            <input type="text" id="sell-price" size="10" class="form-control" required="">
            <span id="sellpricecalculator"></span>
            <p class="help-block">在这里输入您的人民币售价，此售价为฿1.00个比特币的出售价格。</p>
          </div>
          <div id="sellpane-sell" class="form-group">
            <input type="button" name="buy" id="sell-button" value="下卖单" class="btn btn-default">
          </div>
        </div>
        <!-- sellpane end -->

        <!-- orderpane begin -->
        <div class="tab-pane fade" id="orderpane">
          <button type="button" class="btn btn-primary refresh" onclick="">刷新</button>
            <input type="checkbox"  id="orderpane-openonly">&nbsp;只看未成交
          <div class="portlet-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" style="font-size:12px">
                    <thead>
                      <tr>
                        <th>委托时间</th>
                        <th>买卖标志</th>
                        <th>委托数量</th>
                        <th>委托价格</th>
                        <th>成交数量</th>
                        <th>尚未成交</th>
                        <th>操作/状态</th>
                      </tr>
                    </thead>
                    <tbody id="order_list">

                    </tbody>
                  </table>
              </div>
          </div>
        </div>
        <!-- orderpane end -->

        <!-- pane begin -->
        <div class="tab-pane fade" id="pane">.5.</div>
        <!-- pane end -->
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://cdn.bootcss.com/twitter-bootstrap/3.0.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="ticker.js"></script>
    <script type="text/javascript">
      $(function(){
          //下买单
          $('#buy-button').click(function(){
            var amount = parseFloat($('#buy-amount').val());
            var price = parseFloat($('#buy-price').val());
            if(isNaN(amount) || isNaN(price)){
              return;
            }
            if(confirm('购买:' + amount + " BTC\n" + '金额:' + price + "元\n确定么?")){
              $(this).attr('disabled','disabled');
              $.ajax({
                url:'op.php?type=buy',
                data:'price='+price+'&amount='+amount,
                type:'post',
                context:this,
                complete:function(){
                  $(this).removeAttr('disabled');
                },
                success:function(data){
                  console.log(data);
                  if(data == '1'){
                    alert('下单成功');
                    window.location.reload();
                  }
                }
              });
            }
          });

          //下卖单
          $('#sell-button').click(function(){
            var amount = parseFloat($('#sell-amount').val());
            var price = parseFloat($('#sell-price').val());
            if(isNaN(amount) || isNaN(price)){
              return;
            }
            if(confirm('售出:' + amount + " BTC\n" + '金额:' + price + "元\n确定么?")){
              $(this).attr('disabled','disabled');
              $.ajax({
                url:'op.php?type=sell',
                data:'price='+price+'&amount='+amount,
                type:'post',
                context:this,
                complete:function(){
                  $(this).removeAttr('disabled');
                },
                success:function(data){
                  console.log(data);
                  if(data == '1'){
                    alert('下单成功');
                    window.location.reload();
                  }
                }
              });
            }
          });

          //查看挂单
          $('#orderpane .refresh').click(function(){
            $(this).attr('disabled','disabled');
            var openonly = '0';
            if($('#orderpane-openonly')[0].checked){
              openonly = '1';
            }
            $.ajax({
              url:'op.php?type=getorders',
              data:'openonly='+openonly,
              type:'post',
              context:this,
              complete:function(){
                $(this).removeAttr('disabled');
              },
              success:function(data){
                data = JSON.parse(data);
                var tmpl = '<tr align="center" class="red"><td>%date%</td><td>买入</td><td>฿%amount_original%</td><td>¥%price%</td><td>฿%amount%</td><td>฿%amount_no%</td><td>%status%</td></tr>';
                var html = '';
                for(var i in data){
                    html += tmpl.replace('%date%', new Date(data[i].date * 1000).format('YYYY-MM-dd hh:mm:ss'))
                                .replace('%type%', (data[i].type == 'bid'?'卖出':'买入'))
                                .replace('%amount_original%', data[i].amount_original)
                                .replace('%price%', data[i].price)
                                .replace('%amount%', data[i].amount_original - data[i].amount)
                                .replace('%amount_no%', data[i].amount);
                    if(data[i].amount != 0 && data[i].amount < data[i].amount_original){
                      html = html.replace('%status%', '部分成交');
                    }
                    switch(data[i].status){
                      case 'open':
                        var op = '<button type="button" class="btn btn-default btn-xs" onclick="cancel_order(this,'+data[i].id+')">撤单</button>';
                        html = html.replace('%status%', op);
                        break;
                      case 'closed':
                        html = html.replace('%status%', '全部成交');
                        break;
                      case 'cancelled':
                        html = html.replace('%status%', '已撤单');
                        break;
                    }
                }
                $('#order_list').html(html);
              }
            });
          });
      });
      
      function cancel_order(dom,id){
        if(confirm('确定要撤单么？')){
          $(this).attr('disabled','disabled');
          $.ajax({
            url:'op.php?type=cancelorder',
            data:'id='+id,
            type:'post',
            context:dom,
            success:function(data){
              if(data == '1'){
                alert('撤单成功');
                $(this).text('已撤单');
              }else{
                $(this).removeAttr('disabled');
              }
            }
          });
        }
      }
      /**
       * 时间对象的格式化;
       */
      Date.prototype.format = function(format) {
          /*
           * eg:format="YYYY-MM-dd hh:mm:ss";
           */
          var o = {
              "M+" :this.getMonth() + 1, // month
              "d+" :this.getDate(), // day
              "h+" :this.getHours(), // hour
              "m+" :this.getMinutes(), // minute
              "s+" :this.getSeconds(), // second
              "q+" :Math.floor((this.getMonth() + 3) / 3), // quarter
              "S" :this.getMilliseconds()
          // millisecond
          }

          if (/(Y+)/.test(format)) {
              format = format.replace(RegExp.$1, (this.getFullYear() + "")
                      .substr(4 - RegExp.$1.length));
          }

          for ( var k in o) {
              if (new RegExp("(" + k + ")").test(format)) {
                  format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]
                          : ("00" + o[k]).substr(("" + o[k]).length));
              }
          }
          return format;
      }
    </script>
  </body>
</html>