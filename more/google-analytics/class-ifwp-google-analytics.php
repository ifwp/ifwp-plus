<?php

if(!class_exists('_IFWP_Google_Analytics')){
    class _IFWP_Google_Analytics extends _IFWP_Tab {

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function head(){
            $tracking_id = $this->get_option('tracking_id');
            if($tracking_id){ ?>
                <!-- Global site tag (gtag.js) - Google Analytics -->
                <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $tracking_id; ?>"></script>
                <script>
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('config', '<?php echo $tracking_id; ?>');
                </script><?php
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
