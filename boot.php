<?php

/**
 * parameter_rewrite Addon.
 *
 * @author Peter Thiel
 *
 * @var rex_addon
 */

// Addonrechte (permissions) registieren
if (rex::isBackend() && is_object(rex::getUser())) {
    rex_perm::register('parameter_rewrite[]');
}

// bessere SEO urls für den image-manager
if (!rex::isBackend()) {	
	rex_extension::register('URL_REWRITE',array('parameter_rewrite','rewrite'));
	rex_extension::register('YREWRITE_PREPARE',array('parameter_rewrite','prepare'));
}
