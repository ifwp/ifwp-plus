<?php

require_once(IFWP_PLUS_DIR . 'core/plugin-update-checker/vendor/autoload.php');
Puc_v4_Factory::buildUpdateChecker('https://github.com/ifwp/ifwp-plus', IFWP_PLUS_FILE, IFWP_PLUS_SLUG);
$tab = new _IFWP_Tab();
$tab->add_custom_html([
    'std' => '<p><img style="max-width: 150px; height: auto;" src="data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNDA0IDU3NiI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiM2Yzc1N2Q7fS5jbHMtMntmaWxsOiMzNDNhNDA7fTwvc3R5bGU+PC9kZWZzPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTI4My43MiwxNDMuODNhMzIsMzIsMCwwLDAtNTUuNDMsMzJsMTQ4LDI1Ni4zNEEzMiwzMiwwLDAsMCw0MjAsNDQzLjg4aDBhMzIsMzIsMCwwLDAsMTEuNzEtNDMuNzFaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDApIi8+PHJlY3QgY2xhc3M9ImNscy0yIiB4PSI0NDUuOTkiIHk9IjI1Ni4xOCIgd2lkdGg9IjM2MCIgaGVpZ2h0PSI2NCIgcng9IjMyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg2My40MyA2ODYuMjIpIHJvdGF0ZSgtNjApIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMzc2LjI4LDQwMC4xN0EzMiwzMiwwLDAsMCwzODgsNDQzLjg4aDBhMzIsMzIsMCwwLDAsNDMuNzItMTEuNzFMNDc4LDM1Mi4wOGwtMzYuOTUtNjRaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDApIi8+PHJlY3QgY2xhc3M9ImNscy0yIiB4PSI0NDYuMDEiIHk9IjEwOC4xOCIgd2lkdGg9IjY0IiBoZWlnaHQ9IjM2MCIgcng9IjMyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtODAuMDUgMjc3LjYxKSByb3RhdGUoLTMwKSIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTg5Miw0NDhBMTYwLDE2MCwwLDEsMCw3MzIsMjg4LDE2MCwxNjAsMCwwLDAsODkyLDQ0OFptMC0yNTZhOTYsOTYsMCwxLDEtOTYsOTZBOTYsOTYsMCwwLDEsODkyLDE5MloiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMCkiLz48cmVjdCBjbGFzcz0iY2xzLTEiIHg9IjczMiIgeT0iMjU2IiB3aWR0aD0iNjQiIGhlaWdodD0iMzIwIiByeD0iMzIiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE1MjggODMyKSByb3RhdGUoMTgwKSIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTk2LDE2MGg2NGEwLDAsMCwwLDEsMCwwVjQxNmEzMiwzMiwwLDAsMS0zMiwzMmgwYTMyLDMyLDAsMCwxLTMyLTMyVjE2MEEwLDAsMCwwLDEsOTYsMTYwWiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTE3Mi44OCwxMTJBOTYsOTYsMCwwLDEsMzA0LDc2Ljg5bDMyLTU1LjQzQTE2MCwxNjAsMCwwLDAsOTYsMTYwaDY0QTk1LjUxLDk1LjUxLDAsMCwxLDE3Mi44OCwxMTJaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDApIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTEiIGN4PSIzMjAuMDIiIGN5PSI0OS4xOCIgcj0iMzIiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xMjgsMTI3LjgySDI1NmEzMiwzMiwwLDAsMSwzMiwzMnYwYTMyLDMyLDAsMCwxLTMyLDMySDk2YTAsMCwwLDAsMSwwLDB2LTMyQTMyLDMyLDAsMCwxLDEyOCwxMjcuODJaIi8+PHJlY3QgY2xhc3M9ImNscy0xIiB5PSIxMjgiIHdpZHRoPSI2NCIgaGVpZ2h0PSIzMjAiIHJ4PSIzMiIvPjxjaXJjbGUgY2xhc3M9ImNscy0xIiBjeD0iMzIiIGN5PSIzMiIgcj0iMzIiLz48cmVjdCBjbGFzcz0iY2xzLTEiIHg9IjEwODQiIHk9IjI1NiIgd2lkdGg9IjMyMCIgaGVpZ2h0PSI2NCIgcng9IjMyIi8+PHJlY3QgY2xhc3M9ImNscy0xIiB4PSIxMjEyIiB5PSIxMjgiIHdpZHRoPSI2NCIgaGVpZ2h0PSIzMjAiIHJ4PSIzMiIvPjwvc3ZnPg==" alt="' . IFWP_PLUS_NAME . '"></p><p><a href="https://ifwp.plus" target="_blank">' . IFWP_PLUS_NAME . '</a> is proudly powered by <a href="https://vidsoe.com" target="_blank">Vidsoe</a></p>',
]);
$tab->on('wp_enqueue_scripts', function(){
    wp_enqueue_script('ifwp-functions', IFWP_PLUS_URL . 'core/functions.js', array('jquery'), IFWP_PLUS_VERSION);
});

$tab2 = new _IFWP_Tab('', 'REST API');
$tab2->add_switch([
    'id' => 'fix_shutdown',
    'name' => 'Fix shutdown?',
    'std' => true,
]);
if($tab2->get_option('fix_shutdown', true)){
    $tab2->on('wp_die_handler', function($function){
        if($function === '_default_wp_die_handler'){ // check for another plugins
            if(defined('REST_REQUEST') and REST_REQUEST){
                $function = apply_filters('wp_die_json_handler', '_json_wp_die_handler');
            }
        }
        return $function;
	});
}
