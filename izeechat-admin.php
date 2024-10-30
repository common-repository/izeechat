<?php
global $wpdb;
$tableName = $wpdb->prefix . 'izeechat';
$dt = new DateTime();

if (isset($_POST) && !empty($_POST)) {
	$wpdb->insert($tableName, array(
		'apiKey' => $_POST['izt-apikey'],
		'siteKey' => $_POST['izt-sitekey'],
		'serverDomain' => $_POST['izt-serverdomain'],
		'updated_at' => $dt->format('Y-m-d H:i:s')
	));
}

$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}izeechat") ;
$data = array();
if (count($results) > 0) $data = end($results);
?>

<div class="wrap">
	<h1>IzeeChat Configuration</h1>
	<h3><?= __('About Izeechat', 'izeechat') ?></h4>
    <?= __('IzeeChat is a video conferencing solution with text messaging, allowing you to communicate directly with your visitors. So, you can offer your help and your advice instantly to your visitors when they need it...', 'izeechat') ?><br>
    <?= __('If you do not have an account:', 'izeechat') ?> <a href="//cloud.apizee.com/index.php/sfApply/register" target="_blank"><?= __('Create an account', 'izeechat') ?></a> | <a href="<?= __('//doc.apizee.com/izeechat-on-wordpress/', 'izeechat') ?>" target="_blank"><?= __('Documentation', 'izeechat') ?></a><br>
	
	<form action="<?= esc_url($_SERVER['REQUEST_URI']) ?>" method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="izeechat-sitekey"><?= __("Site key", "izeechat") ?></label>
					</th>
					<td>
						<input type="text" class="regular-text ltr protected" id="izeechat-sitekey" name="izt-sitekey" value="<?= (isset($data->siteKey) && !empty($data->siteKey)) ? $data->siteKey : '' ?>" placeholder="<?= __('Your key...', 'izeechat') ?>">
						<p id="izeechat-sitekey-description" class="description"><?= __('This is the key associated to your website, configured in your apizee account.', 'izeechat') ?> <?= __('Check the documentation for more informations', 'izeechat') ?></p>
					</td>
					<th scope="row">
						<label for="izeechat-sitekey"><?= __("Server domain [Optional]", "izeechat") ?></label>
					</th>
					<td>
						<input type="text" class="regular-text ltr protected" id="izeechat-serverdomain" name="izt-serverdomain" value="<?= (isset($data->serverDomain) && !empty($data->serverDomain)) ? $data->serverDomain : '' ?>" placeholder="<?= __('Apizee server domain...', 'izeechat') ?>">
						<p id="izeechat-serverdomain-description" class="description"><?= __('eg: cloud.apizee.com', 'izeechat') ?></p>
					</td>
				</tr>
			</tbody>	
		</table>
		<p class="submit">
			<button name="submit" id="submit" class="button button-primary" type="submit"><?= __("Update configuration", 'izeechat') ?></button>
			<a href="<?= (isset($data->serverDomain) && !empty($data->serverDomain)) ? '//' . $data->serverDomain : '//cloud.apizee.com' ?>" title="<?= __("Click to access to Apizee dashboard", 'izeechat') ?>" class="button" target="_BLANK"><?= __("Access to Apizee dashboard", 'izeechat') ?></a>
		</p>
	</form>
</div>