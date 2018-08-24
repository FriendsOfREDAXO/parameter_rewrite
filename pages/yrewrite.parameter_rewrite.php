<?php

/**
 * parameter_rewrite Addon.
 *
 * @author Peter Thiel
 *
 * @var rex_addon
 */

$content = '';
$addon = rex_addon::get('parameter_rewrite');

if (rex_post('config-submit', 'boolean')) {
    $addon->setConfig(rex_post('config', [
        ['params-starter', 'string'],
    ]));

    $content .= rex_view::info($addon->i18n('config_saved'));
}

$content .= '
<div class="rex-form">
<form action="'.rex_url::currentBackendPage().'" method="post">
<fieldset>';

$formElements = [];

// set tags
$formElements = [];
$elements = array();
$elements['label'] = '<label for="params-starter">'.$addon->i18n('params-starter').'</label>';
$elements['field'] = '<input class="form-control" type="text" id="params-starter" name="config[params-starter]" value="'.$addon->getConfig('params-starter').'"/>';
$formElements[] = $elements;
$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/form.php');

$content .= '
<div class="example"><p><i>Beispiel: '.rex::getServer().'<span class="example-params-starter">'.rex_config::get('parameter_rewrite', 'params-starter').'</span><span class="example-params"></span></i></p></div>
</fieldset>

<fieldset class="rex-form-action">';

$formElements = [];
$n = [];
$n['field'] = '<input class="btn btn-save rex-form-aligned" type="submit" name="config-submit" value="'.$addon->i18n('config_save').'" '.rex::getAccesskey($addon->i18n('config_save'), 'save').' />';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/submit.php');

$content .= '
</fieldset>

</form>
</div>';

$content .= '
<script>
	$(document).ready(function(){
		var setParameter = function(){
			if($("#params-starter").val() != "" && $("#params-starter").val() != "?"){
				$(".example-params").html("/foo/bar/");
			}
			else{
				$(".example-params").html("?foo=bar");
			}
		}
		
		$("#params-starter").keyup(function(){
			$(".example-params-starter").html($("#params-starter").val());
			setParameter();
		});
		setParameter();
	});
</script>
';

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $addon->i18n('config'));
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');
