<?php only_admin_access(); ?>

<label class="mw-ui-label">Techprocess Merchant Code: </label>
<input type="text" class="mw-ui-field mw_option_field" name="techprocess_mrctCode"  placeholder="T3790"   data-option-group="payments"  value="<?php print get_option('techprocess_mrctCode', 'payments'); ?>" >
<label class="mw-ui-label">Techprocess Scheme Code: </label>
<input type="text" class="mw-ui-field mw_option_field" name="techprocess_schemeCode"  placeholder="CBSL"   data-option-group="payments"  value="<?php print get_option('techprocess_schemeCode', 'payments'); ?>" >
<label class="mw-ui-label">Techprocess Encryption Key: </label>
<input type="text" class="mw-ui-field mw_option_field" name="techprocess_encryption_key"    data-option-group="payments"  value="<?php print get_option('techprocess_encryption_key', 'payments'); ?>" >
<label class="mw-ui-label">Techprocess Encryption IV: </label>
<input type="text" class="mw-ui-field mw_option_field" name="techprocess_encryption_iv"    data-option-group="payments"  value="<?php print get_option('techprocess_encryption_iv', 'payments'); ?>" >
<?php
 
 $locators = array(); 
 $locators['TEST'] = 'https://www.tekprocess.co.in/PaymentGateway/TransactionDetailsNew.wsdl';
 $locators['E2EWithIP'] = 'http://10.10.60.46:8080/PaymentGateway/services/TransactionDetailsNew';

 $locators['E2EWithDomain'] = 'https://tpslvksrv6046/PaymentGateway/services/TransactionDetailsNew';

 $locators['UATWithDomain'] = 'https://www.tekprocess.co.in/PaymentGateway/services/TransactionDetailsNew';

 $locators['UATWithIP'] = 'http://10.10.102.157:8081/PaymentGateway/services/TransactionDetailsNew';

 $locators['EAP UATWithIP'] = 'http://10.10.102.158:8081/PaymentGateway/services/TransactionDetailsNew';

 $locators['TEST'] = 'https://www.tekprocess.co.in/PaymentGateway/TransactionDetailsNew.wsdl';
 $locators['LIVE'] = 'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';

  ?>
 <?php 
 $techprocess_locator_url =  get_option('techprocess_locator_url', 'payments'); 
 ?> 
<label class="mw-ui-label">Locator URL</label>
<select  class="mw-ui-field mw_option_field" name="techprocess_locator_url"    data-option-group="payments">
  <?php foreach($locators as $k=>$v){ ?>
  <option value="<?php print $v ?>" <?php if($techprocess_locator_url == $v) { ?> selected="selected" <?php } ?> ><?php print $k ?></option>
  <?php } ?>
</select>
