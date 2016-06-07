##Script to insert 301 redirects to Magento Enterprise edition 1.14.*

##How to use this script

- Insert your redirects to the file url_redirect.csv. The first column must contain the request_url and the second the target url.
- Place both files (url_redirect.php and url_redirect.csv) on root of your magento instalation.
- Change the store_id (line 11) and description of each insertion (line 40) on the php file if necessary.
- Open your terminal and go to your magento instalation folder and type php url_redirect.php

Done!

Hope it helps some people. 
