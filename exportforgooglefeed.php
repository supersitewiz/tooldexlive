<?php
	define('SAVE_FEED_LOCATION','var/export/google_base_feed.txt');//you can set a new folder and file if you want, don't forget to chmod the folder to 777

	// make sure we don't time out
	set_time_limit(0);	

	require_once 'app/Mage.php';
        Mage::app('default');
        
	try{
		$handle = fopen(SAVE_FEED_LOCATION, 'w');

		
		$heading = array('id','title','description','link','image_link','price','brand','product_type','weight','quantity','availability','condition','upc','isbn','mpn','Google product category','shipping','tax');
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
		    $product_data['price']=round($product->getPrice(),2);
		    $product_data['brand']=$product->getResource()->getAttribute('brand')->getFrontend()->getValue($product);	
		    $product_data['product_type']='"Hardware > Tools"';	
			$product_data['weight']=$product->getWeight().' lb';
			$product_data['quantity']='10000';
			$product_data['availability']='in stock';			
			$product_data['condition']='new';	
			$product_data['upc']=$product->getResource()->getAttribute('upc')->getFrontend()->getValue($product);	
			$product_data['isbn']='';	
			$product_data['mpn']='';	
			$product_data['Google product category']='"Hardware > Tools"';	
			$product_data['shipping']='';	
			$product_data['tax']='';	
			 
			
			
				
		  
		    //echo 'Product load: '.memory_get_usage(false).'<br>';
		    //flush();		
		  
		    //get the product categories            		
            	   /* foreach($product->getCategoryIds() as $_categoryId){
			$category = Mage::getModel('catalog/category')->load($_categoryId);
			$product_data['product_type'].=$category->getName().', ';
		    }
		    $product_data['product_type']=rtrim($product_data['product_type'],', ');		
             */
		    //echo 'Category load: '.(memory_get_usage(false)).'<br>';			
			
		    //sanitize data	
		    foreach($product_data as $k=>$val){
			$bad=array('"',"\r\n","\n","\r","\t");
			$good=array(""," "," "," ","");
			$product_data[$k] = str_replace($bad,$good,$val);
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



<table width="1000" align="center" cellpadding="5" cellspacing="5" border="1">
<tr><td align="center"><h1>Google Feed in Magento</h1></td></tr>
<tr><td>Google Feed TXT file Successfully Created file Location : /var/export/google_base_feed.txt</td></tr>
<tr><td>Download " google_base_feed.txt " file and upload google merchant account</td></tr>
<tr><td><strong>Note </strong>: Without refresh click : <a href="http://www.tooldex.com/">Go To Home </a></td></tr>
</table>
