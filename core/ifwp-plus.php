<?php

require_once(IFWP_PLUS_DIR . 'core/plugin-update-checker/vendor/autoload.php');
Puc_v4_Factory::buildUpdateChecker('https://github.com/ifwp/ifwp-plus', IFWP_PLUS_FILE, IFWP_PLUS_SLUG);
$tab = new _IFWP_Tab();
$tab->add_custom_html([
    'std' => '<p><img style="max-width: 150px; height: auto;" src="data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMzcyIDU3NiI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMwMTAxMDE7fTwvc3R5bGU+PC9kZWZzPjxwYXRoIGQ9Ik0yODMuNzIsMTQzLjgzYTMyLDMyLDAsMCwwLTU1LjQzLDMybDE0OCwyNTYuMzRBMzIsMzIsMCwwLDAsNDIwLDQ0My44OGgwYTMyLDMyLDAsMCwwLDExLjcxLTQzLjcxWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCAwKSIvPjxyZWN0IHg9IjQ0Ni4wMSIgeT0iMTA4LjE4IiB3aWR0aD0iNjQiIGhlaWdodD0iMzYwIiByeD0iMzIiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04MC4wNSAyNzcuNjEpIHJvdGF0ZSgtMzApIi8+PHJlY3QgeD0iNDQ1Ljk5IiB5PSIyNTYuMTgiIHdpZHRoPSIzNjAiIGhlaWdodD0iNjQiIHJ4PSIzMiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNjMuNDMgNjg2LjIyKSByb3RhdGUoLTYwKSIvPjxwYXRoIGQ9Ik0zNzYuMjgsNDAwLjE3QTMyLDMyLDAsMCwwLDM4OCw0NDMuODhoMGEzMiwzMiwwLDAsMCw0My43Mi0xMS43MUw0NzgsMzUyLjA4bC0zNi45NS02NFoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMCkiLz48cGF0aCBkPSJNNTc5LjcyLDQwMC4zNSw1MTQuOSwyODguMDhsLTM2Ljk1LDY0LDQ2LjM0LDgwLjI3QTMyLDMyLDAsMCwwLDU2OCw0NDQuMDZoMEEzMiwzMiwwLDAsMCw1NzkuNzIsNDAwLjM1WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCAwKSIvPjxwb2x5Z29uIHBvaW50cz0iNDc3Ljk1IDIyNC4wOCA0NDEgMjg4LjA4IDQ3Ny45NSAzNTIuMDggNTE0LjkgMjg4LjA4IDQ3Ny45NSAyMjQuMDgiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik04OTIsNDQ4QTE2MCwxNjAsMCwxLDAsNzMyLDI4OCwxNjAsMTYwLDAsMCwwLDg5Miw0NDhabTAtMjU2YTk2LDk2LDAsMSwxLTk2LDk2QTk2LDk2LDAsMCwxLDg5MiwxOTJaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDApIi8+PHJlY3QgY2xhc3M9ImNscy0xIiB4PSI3MzIiIHk9IjI1NiIgd2lkdGg9IjY0IiBoZWlnaHQ9IjMyMCIgcng9IjMyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxNTI4IDgzMikgcm90YXRlKDE4MCkiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik05NiwxNjBoNjRhMCwwLDAsMCwxLDAsMFY0MTZhMzIsMzIsMCwwLDEtMzIsMzJoMGEzMiwzMiwwLDAsMS0zMi0zMlYxNjBBMCwwLDAsMCwxLDk2LDE2MFoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xNzIuODgsMTEyQTk2LDk2LDAsMCwxLDMwNCw3Ni44OWwzMi01NS40M0ExNjAsMTYwLDAsMCwwLDk2LDE2MGg2NEE5NS41MSw5NS41MSwwLDAsMSwxNzIuODgsMTEyWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCAwKSIvPjxjaXJjbGUgY2xhc3M9ImNscy0xIiBjeD0iMzIwLjAyIiBjeT0iNDkuMTgiIHI9IjMyIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTI4LDEyNy44MkgyNTZhMzIsMzIsMCwwLDEsMzIsMzJ2MGEzMiwzMiwwLDAsMS0zMiwzMkg5NmEwLDAsMCwwLDEsMCwwdi0zMkEzMiwzMiwwLDAsMSwxMjgsMTI3LjgyWiIvPjxyZWN0IHk9IjEyOCIgd2lkdGg9IjY0IiBoZWlnaHQ9IjMyMCIgcng9IjMyIi8+PGNpcmNsZSBjeD0iMzIiIGN5PSIzMiIgcj0iMzIiLz48Y2lyY2xlIGN4PSIxMDg0IiBjeT0iNDE2IiByPSIzMiIvPjxyZWN0IHg9IjEwNTIiIHk9IjEyOCIgd2lkdGg9IjMyMCIgaGVpZ2h0PSI2NCIgcng9IjMyIi8+PHJlY3QgeD0iMTE4MCIgd2lkdGg9IjY0IiBoZWlnaHQ9IjMyMCIgcng9IjMyIi8+PC9zdmc+" alt="' . IFWP_PLUS_NAME . '"></p><p>' . IFWP_PLUS_NAME . ' is proudly powered by <a href="https://vidsoe.com" target="_blank">Vidsoe</a></p>',
]);
$tab->on('wp_enqueue_scripts', function(){
    wp_enqueue_script('ifwp-functions', IFWP_PLUS_URL . 'core/functions.js', array('jquery'), IFWP_PLUS_VERSION);
});