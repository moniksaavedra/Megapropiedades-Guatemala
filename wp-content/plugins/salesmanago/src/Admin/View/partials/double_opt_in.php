<?php
/**
 * @var string $context from SettingsRenderer
 */
?>
<?php
if(empty($context)) {
    return;
}
$active = $this->selected('', 'double-opt-in-active', $context);
?>
<h3><?php _e('Double opt-in', 'salesmanago') ?></h3>
<table class="form-table">
    <tr valign="top">
        <th scope="row">
            <label for="double-opt-in-active">
                <?php _e('Use double opt-in', 'salesmanago') ?>
            </label>
        </th>
        <td>
            <input type="checkbox" onclick="salesmanagoToggleDoubleOptIn()" <?php echo($active) ?> name="double-opt-in[active]" id="double-opt-in-active" value="1">
            <label for="double-opt-in-active">
                <?php _e('Let contacts confirm newsletter signup with email confirmation', 'salesmanago');?>
            </label>
            <p class="description">
                <?php echo(__('Learn more on', 'salesmanago') . ' <a href="' . __('https://support.salesmanago.com/email-confirming-subscription/?utm_source=integration&utm_medium=wordpress&utm_content=tooltip', 'salesmanago') . '" target="_blank">' . __('SALESmanago support page.', 'salesmanago')) ?>
            </p>
        </td>
    </tr>
    <?php
    if($active) {
        echo("<script>document.addEventListener('DOMContentLoaded', function() {salesmanagoPreventIncompleteDoubleOptIn()});</script>");
    }
    ?>
    <tr valign="top" class="salesmanago-double-opt-in <?php echo ($active) ? '' : 'hidden' ?>">
        <th scope="row">
            <label for="double-opt-in-template-id"><?php _e('Template ID', 'salesmanago') ?></label>
        </th>
        <td>
            <input
                    type="text"
                    name="double-opt-in[template-id]"
                    value="<?php echo $this->AdminModel->getPlatformSettings()->getPluginByName($context)->getDoubleOptIn()->getTemplateId(); ?>"
                    id="double-opt-in-template-id"
                    class="regular-text"
                    onchange="salesmanagoPreventIncompleteDoubleOptIn()">
            <p class="description">
                <?php _e('Copy template ID from URL', 'salesmanago') ?>
            </p>
        </td>
    </tr>
    <tr valign="top" class="salesmanago-double-opt-in <?php echo ($active)?'':'hidden' ?>">
        <th scope="row">
            <label for="double-opt-in-account-id"><?php _e('Account ID', 'salesmanago') ?></label>
        </th>
        <td>
            <input
                    type="text"
                    name="double-opt-in[account-id]" value="<?php echo $this->AdminModel->getPlatformSettings()->getPluginByName($context)->getDoubleOptIn()->getAccountId(); ?>"
                    id="double-opt-in-account-id"
                    class="regular-text"
                    onchange="salesmanagoPreventIncompleteDoubleOptIn()">
            <p class="description">
                <?php _e('Copy account ID from URL', 'salesmanago') ?>
            </p>
        </td>
    </tr>
    <tr valign="top" class="salesmanago-double-opt-in <?php echo ($active)?'':'hidden' ?>">
        <th scope="row">
            <label for="double-opt-in-subject"><?php _e('Subject', 'salesmanago') ?></label>
        </th>
        <td>
            <input
                    type="text"
                    name="double-opt-in[subject]"
                    value="<?php echo $this->AdminModel->getPlatformSettings()->getPluginByName($context)->getDoubleOptIn()->getSubject(); ?>"
                    id="double-opt-in-subject"
                    class="regular-text"
                    onchange="salesmanagoPreventIncompleteDoubleOptIn()">
            <p class="description">
                <?php _e('Specify email subject', 'salesmanago') ?>
            </p>
        </td>
    </tr>
</table>
<div class="notice notice-warning inline hidden" id="salesmanago-double-opt-in-info">
    <?php _e('If you want to change any of Double opt-in inputs you have to fill all three.', 'salesmanago') ?>
</div>
