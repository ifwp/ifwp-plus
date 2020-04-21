<?php

$tab = new _IFWP_reCAPTCHA('Google', 'reCAPTCHA');
$tab->on('init', [$tab, 'init']);
