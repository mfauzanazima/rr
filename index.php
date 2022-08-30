<?php
	header('Content-Type: application/json; charset=UTF-8');
?>
<?php
	

	//http://example.blogspot.com/feeds/posts/default/-/fedora/?alt=rss


	// SETTINGS ===============================================
	//$alamat_blog = "https://mamaseh.blogspot.com";
	//$alamat_blog   = "https://www.merdeka.com";

	// config
	//include("../config.php");
  
  $url_blog     = "https://wsazima.blogspot.com";

	$ukuran_thumb = "s1080";
	$max_data     = 500;
	
	$alamat_blog = $url_blog.'/feeds/posts/default';

	// SETTINGS ===============================================
	

	$response["data"]  = array();
	$resp["image"]  = array();

	// Mengambil Data Content dari Blogger
	$json = file_get_contents($alamat_blog."?alt=json&max-results=".$max_data);
	// Replace Variable $ menjadi tanpa $
	$data  = str_replace('"$t"', '"t"', $json);
	$data1 = str_replace('"media$thumbnail"', '"thumbnail"', $data);
	
	// Mengubah Json menjadi Array Model
    $datanya  = json_decode($data1);


    //print_r($data1);

    // mengetahui berapa jumlah postingan data
    $jumlah_entry = count($datanya->feed->entry);

    // PERULANGAN DATA UNTUK CONTENT
    for($i=0; $i<$jumlah_entry; $i++) {
	    // Mengambil Data Hanya Pada bagian Isi Content

		$id_content = $datanya->feed->entry[$i]->id->t;

    	if ($id_content == "tag:blogger.com,1999:blog-5469459903824517910.post-8413447319998099036") {
    		//echo "true";

    		$content = $datanya->feed->entry[$i]->content->t;

    		// Mengizinkan Konten yang hanya memiliki tag P
    		$clean   = strip_tags($content, '<img>');

    		// Menghapus tag <p>, </p>1 dari konten
    		$clean   = str_replace('<p>', '', $clean);
    		$clean   = str_replace('</p>', ' ', $clean);
    		$content = trim($clean);

    		$title       = $datanya->feed->entry[$i]->title->t;
    		$thumbnail1  = $datanya->feed->entry[$i]->thumbnail->url;
    		$thumbnail   = str_replace('s72-c', $ukuran_thumb, $thumbnail1);


    		// MEMBENTUK DATA CONTENT
    		$D["title"]       = $title;
    		$D["content"]     = $content;
    		$D["thumbnail"]   = $thumbnail;

    		// Membentuk Array Data Content
    		array_push($response["data"], $D);
    	}else{

    	}

		
    }

    /**/
    $content_string =  json_encode($response);

    $content_string_array = json_decode($content_string);

    //print_r($content_string);

    $data_content_id_post = $content_string_array->data[0]->content;

    $jumlah_image = substr_count($data_content_id_post,'src="');

    //echo $data_content_id_post;
    //echo "<br><br>";


    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($data_content_id_post); // loads your html
    $xpath = new DOMXPath($doc);
    $nodelist = $xpath->query("//img"); // find your image

    for ($i=0; $i <$jumlah_image ; $i++) { 
    	$node = $nodelist->item($i); // gets the 1st image
    	$value = $node->attributes->getNamedItem('src')->nodeValue;
    	//echo "$value\n"; // prints src of image

    	$value   = str_replace('s320', $ukuran_thumb, $value);

    	$F["nama"]        = "gambar_".($i+1);
    	$F["thumbnail"]   = $value;

    	// Membentuk Array Data Content
    	array_push($resp["image"], $F);
    }

    echo $img =  json_encode($resp);



    /**/

	
    /**
    	foreach ($response["data"] as $key) {
    		echo "<b>".$key["title"]."</b><br><br>";
    		echo "<img src='".$key["thumbnail"]."'>" ."<br><br>";
    		echo $key["content"]."<br><br>";
    	}
    /**/


    



    
    

?>
