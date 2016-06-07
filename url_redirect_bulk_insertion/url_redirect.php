<?php
/**
 * 301 Url Redirect Import via CSV file for Magento Enterprise Edition
 * @author Ligia Cavallini
 */

//change it to your right path
require_once 'app/Mage.php';

//set the store id that is going to receive the data
$store_id = 1;
Mage::app()->setCurrentStore($store_id);

//path/name of your file
$file = 'url_redirect.csv';

echo "Running url redirect import...\n";

if (($handle = fopen($file, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 4086, ","))) {

        //check if redirect already exists
        $load_item = Mage::getSingleton('enterprise_urlrewrite/redirect')
            ->getCollection()
            ->addFieldToFilter('identifier', $data[0])
            ->addFieldToFilter('store_id', $store_id);

        if(count($load_item)>0){
            echo "Redirect from " . $data[0] . " to " . $data[1] . " already exists.\n";
        }else{
        	//if redirect doesn't exist yet, try to insert
            try {
                $redirect = Mage::getSingleton('enterprise_urlrewrite/redirect')
                    ->setRedirectId(null)
                    ->setTargetPath($data[1])
                    ->setIdentifier($data[0])
                    ->setStoreId($store_id)
                    ->setOptions('RP')
                    ->setDescription('This description is optional');

                $redirect->save();

				//reindex
                $client = Mage::getModel('enterprise_mview/client');
                $client->init('enterprise_url_rewrite_redirect');
                $client->execute('enterprise_urlrewrite/index_action_url_rewrite_redirect_refresh_row',
                    array('redirect_id' => $redirect->getId())
                );
                echo "Imported from " . $data[0] . " to " . $data[1] . " done.\n";
            } catch (Exception  $e) {
                echo "Exception Occured: " . $e;
                echo "Error importing redirect from " . $data[0] . " to " . $data[1] . "\n";
            }
        }
    }
}else{
    echo "Error trying to read the file";
}
