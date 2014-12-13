<?php
	define('SAVE_FEED_LOCATION','var/export/google_base_feed.txt');//you can set a new folder and file if you want, don't forget to chmod the folder to 777

	// make sure we don't time out
	set_time_limit(0);	

	require_once 'app/Mage.php';
        Mage::app('default');
        
	try{
		$handle = fopen(SAVE_FEED_LOCATION, 'w');

		
		$heading = array('id','title','description','link','image_link','price','brand','product_type');
		$feed_line=implode("\t", $heading)."\r\n";
		fwrite($handle, $feed_line);
		
		//---------------------- GET THE PRODUCTS	
		$products = Mage::getModel('catalog/product')->getCollection();
		$products->addAttributeToFilter('status', 1);//enabled
		$products->addAttributeToFilter('visibility', 4);//catalog, search
		$products->addAttributeToSelect('*');
		$prodIds=$products->getAllIds();
		
		//echo 'Product filter: '.memory_get_usage(false).'<br>';
		//flush();
		
		$product = Mage::getModel('catalog/product');
		
		foreach($prodIds as $productId) {
		    //echo '. ';
		    //flush();
		    //echo 'Loop start: '.memory_get_usage(false).'<br>';
		    //flush();
	
		    //$product = Mage::getModel('catalog/product');
		    $product->load($productId);
		    
		    $product_data = array();	
		    $product_data['sku']=$product->getSku();	
		    $product_data['title']=$product->getName();
		    $product_data['description']=$product->getDescription();
		    $product_data['link']=$product->getProductUrl();
		    $product_data['image_link']=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$product->getImage();
		    $product_data['price']=$product->getPrice();
		    $product_data['brand']=$product->getResource()->getAttribute('manufacturer')->getFrontend()->getValue($product);	
		    $product_data['product_type']='';		
		  
		    //echo 'Product load: '.memory_get_usage(false).'<br>';
		    //flush();		
		  
		    //get the product categories            		
            	    foreach($product->getCategoryIds() as $_categoryId){
			$category = Mage::getModel('catalog/category')->load($_categoryId);
			$product_data['product_type'].=$category->getName().', ';
		    }
		    $product_data['product_type']=rtrim($product_data['product_type'],', ');		

		    //echo 'Category load: '.(memory_get_usage(false)).'<br>';			
			
		    //sanitize data	
		    foreach($product_data as $k=>$val){
			$bad=array('"',"\r\n","\n","\r","\t");
			$good=array(""," "," "," ","");
			$product_data[$k] = '"'.str_replace($bad,$good,$val).'"';
		    }
			

		    $feed_line = implode("\t", $product_data)."\r\n";
		    fwrite($handle, $feed_line);
		    fflush($handle);
		    
		    //echo 'Loop end: '.memory_get_usage(false).'<br>';
		    //flush();
		}

		//---------------------- WRITE THE FEED	
		fclose($handle);
		
	}
	catch(Exception $e){
		die($e->getMessage());
	}
?>
