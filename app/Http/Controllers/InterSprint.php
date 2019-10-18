<?php
namespace App\Http\Controllers;

use App\CronJobStatus;
use App\Product;
use App\ProductImage;
use App\Profit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class InterSprint extends CronJobController
{
	private $username = "whehu9";
	private $password = "2fquz8";
	private $customer_no = "92266";
	private $inter_sprint_host = "test-001.inter-sprint.nl/scripts/cgirpc32.dll/ww0800";

	private $summerTireGroups = array(11, 12, 14, 15, 16, 17, 18, 19, 20, 21, 22, 25, 26, 27, 41, 42, 51, 52, 53);
	private $winterFrictionGroups = array(29);
	private $winterDubbGroups = array(13, 23, 24, 28, 29, 30, 32, 35, 43);

	private $username_intertyre = "whehus9";
	private $password_intertyre = "7s4krn";
	private $customer_no_intertyre = "90485";
	private $inter_tyre_host = "test-002.inter-tyre.nl/scripts/cgirpc32.dll/ww0800";
	
	private $alloyRimsGroups = array(61, 62, 67);
	private $steelRimsGroups = array(65);

	private $brandList = array();

	private function readnext ($file)
    {
        
        $s = fgets ($file, 10000);
    
        if ($s == "" || substr ($s, 0, 5) == "*END*")
            return ("");
        else 
            if (substr ($s, 0, 7) == "*ERROR*")
                die ("invalid argument(s)");
            else
                return ($s);
    }

    // public function file_get_contents_curl($url) {
    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    //     curl_setopt($ch, CURLOPT_HEADER, 0);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    //     $data = curl_exec($ch);
    //     curl_close($ch);

    //     return $data;
    // }

    function url_exists($url) {
        // Version 4.x supported
        $handle   = curl_init($url);
        if (false === $handle)
        {
            return false;
        }
        curl_setopt($handle, CURLOPT_HEADER, false);
        curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
        curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox    
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
        $connectable = curl_exec($handle);
        curl_close($handle);   
        return $connectable;
    }

    public function inter_sprint_products(){
    	ini_set("memory_limit", -1);
		ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        $host = "http://" . $this->username .':' . $this->password ."@". $this->inter_sprint_host ."?103,kl=".$this->customer_no."&artc=r=10";
        // $host = storage_path('ww0800.txt');
        $responseText = @fopen ($host, "r");
        $products = array();
        if ($responseText) {
        	// set the brand list to class brand array variable
        	$this->inter_brands_list("tyres");

            while (($s = $this->readnext ($responseText)) != "") {
                $tempObj = new \StdClass();
                $tempArr = array_map('trim', explode("\t", $s));
                if(count($tempArr)<3)
                	dd($tempArr);
                else{
	                $tempObj->{"artikelnr"} = isset($tempArr[0]) ? trim($tempArr[0]) : '';
	                $tempObj->{"product_name"} = isset($tempArr[1]) ? trim($tempArr[1]) : '';
	                $tempObj->{"brand"} = isset($tempArr[2]) ? trim($tempArr[2]) : '';
	                //set the brand name with brand code
	                $tempObj->{"brand"} = isset($this->brandList[$tempObj->{"brand"}]) ? $this->brandList[$tempObj->{"brand"}] : $tempObj->{"brand"};
	                $groupnr = isset($tempArr[3]) ? (int)trim($tempArr[3]) : '';
	                $tempObj->{"product_code"} = isset($tempArr[4]) ? str_replace('  ', ' ', trim($tempArr[4])) : '';
	                // tire group is from 11 to 35 exclude everything else
	                if(!in_array($groupnr, array_merge($this->summerTireGroups, $this->winterFrictionGroups, $this->winterDubbGroups)) or !$tempObj->{"artikelnr"} or !$tempObj->{"product_code"})
	                	continue;
	                $tempObj->{"is_runflat"} = ($groupnr==25 or $groupnr==28) ? 1 : 0;
	                $tempObj->{"is_ctire"} = ($groupnr==16 or $groupnr==24) ? 1 : 0;
	                $tempObj->{"product_type"} = in_array($groupnr, $this->winterFrictionGroups) ? 2 :  (in_array($groupnr, $this->winterDubbGroups) ? 3 :  1);
	                $tempObj->{"currency"} = isset($tempArr[5]) ? trim($tempArr[5]) : '';
	                $tempObj->{"netpris"} = isset($tempArr[6]) ? (float)trim($tempArr[6]) : '';
	                $tempObj->{"grosspris"} = isset($tempArr[7]) ? (float)trim($tempArr[7]) : '';
	                $tempObj->{"quantity"} = isset($tempArr[8]) ? (int)trim(str_replace(array('>','<'), '', $tempArr[8])) : 0;
	                $bild_name = isset($tempArr[9]) ? trim($tempArr[9]) : '';
	                $tempObj->{"bild_link"} = $bild_name ? 'http://www.etyre.net/preview/t3/'.$bild_name : '';
	                $tempObj->{"bild_link_small"} = $bild_name ? 'http://www.etyre.net/preview/t1/'.$bild_name : '';
	                $tempObj->{"loadspeedindex"} = isset($tempArr[10]) ? trim($tempArr[10]) : '';
	                $tempObj->{"supplierid"} = isset($tempArr[11]) ? trim($tempArr[11]) : '';
	                // $tempObj->{"garagepris"} = isset($tempArr[12]) ? trim($tempArr[12]) : '';
	                // $tempObj->{"alternatekundpris"} = isset($tempArr[13]) ? trim($tempArr[13]) : '';
	                $tempObj->{"eancode"} = isset($tempArr[14]) ? trim($tempArr[14]) : '';
	                // $tempObj->{"weightcat_dpd"} = isset($tempArr[15]) ? trim($tempArr[15]) : '';
	                // $tempObj->{"emarked"} = isset($tempArr[16]) ? trim($tempArr[16]) : '';
	                $tempObj->{"european"} = isset($tempArr[17]) ? trim($tempArr[17]) : '';
	                // $tempObj->{"plyrating"} = isset($tempArr[18]) ? trim($tempArr[18]) : '';
	                // $tempObj->{"loadspeedindex2"} = isset($tempArr[19]) ? trim($tempArr[19]) : '';
	                $tempObj->{"wide"} = isset($tempArr[20]) ? trim($tempArr[20]) : '';
	                $tempObj->{"profil"} = isset($tempArr[21]) ? trim($tempArr[21]) : '';
	                $tempObj->{"speedindex"} = isset($tempArr[22]) ? trim($tempArr[22]) : '';
	                $tempObj->{"inch"} = isset($tempArr[23]) ? trim($tempArr[23]) : '';
	                $tempObj->{"product_model"} = isset($tempArr[24]) ? trim($tempArr[24]) : '';
	                // $tempObj->{"inchrim"} = isset($tempArr[25]) ? trim($tempArr[25]) : '';
	                // $tempObj->{"widerim"} = isset($tempArr[26]) ? trim($tempArr[26]) : '';
	                // $tempObj->{"offset"} = isset($tempArr[27]) ? trim($tempArr[27]) : '';
	                // $tempObj->{"centerhole"} = isset($tempArr[28]) ? trim($tempArr[28]) : '';
	                // $tempObj->{"pcd"} = isset($tempArr[29]) ? trim($tempArr[29]) : '';
	                // $tempObj->{"pcd2"} = isset($tempArr[30]) ? trim($tempArr[30]) : '';
	                $tempObj->{"calculatorprice"} = isset($tempArr[31]) ? (float)trim($tempArr[31]) : '';
	                $tempObj->{"resistence"} = isset($tempArr[32]) ? trim($tempArr[32]) : '';
	                $tempObj->{"wetgrip"} = isset($tempArr[33]) ? trim($tempArr[33]) : '';
	                $tempObj->{"noicedb"} = isset($tempArr[34]) ? trim($tempArr[34]) : '';
	                $tempObj->{"noicesound"} = isset($tempArr[35]) ? trim($tempArr[35]) : '';
	                // $tempObj->{"tpms"} = isset($tempArr[36]) ? trim($tempArr[36]) : '';
	                // $tempObj->{"tuv"} = isset($tempArr[37]) ? trim($tempArr[37]) : '';
	                // $tempObj->{"winter"} = isset($tempArr[38]) ? trim($tempArr[38]) : '';
	                // $tempObj->{"runflat_rim"} = isset($tempArr[39]) ? trim($tempArr[39]) : '';
	                $products[] = $tempObj;
	                // $products[$a] = explode("\t", $s);
	            }
            }
            // dd($products);
        }
        return $products;
    }
    public function inter_tyre_products(){
    	ini_set("memory_limit", -1);
		ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        $host = "http://" . $this->username_intertyre .':' . $this->password_intertyre ."@" . $this->inter_tyre_host . "?103,kl=".$this->customer_no_intertyre."&artc=r=61";
        // $host = storage_path('ww0800_rims.txt');
        $responseText = @fopen ($host, "r");
        $products = array();
        if ($responseText) {
        	// set the brand list to class brand array variable
        	$this->inter_brands_list("rims");
        	// dd($this->brandList);
            while (($s = $this->readnext ($responseText)) != "") {
                $tempObj = new \StdClass();
                $tempArr = array_map('trim', explode("\t", $s));
                if(count($tempArr)<3)
                	dd($tempArr);
                else{
                	$tempObj->{"artikelnr"} = isset($tempArr[0]) ? trim($tempArr[0]) : '';
	                $tempObj->{"product_name"} = isset($tempArr[1]) ? trim($tempArr[1]) : '';
	                $tempObj->{"brand"} = isset($tempArr[2]) ? trim($tempArr[2]) : '';
	                //set the brand name with brand code
	                $tempObj->{"brand"} = isset($this->brandList[$tempObj->{"brand"}]) ? $this->brandList[$tempObj->{"brand"}] : $tempObj->{"brand"};
	                $groupnr = isset($tempArr[3]) ? (int)trim($tempArr[3]) : '';
	                $tempObj->{"product_code"} = isset($tempArr[4]) ? str_replace(array('  ',','), array(' ','.'), trim($tempArr[4])) : '';
	                // tire group is from 11 to 35 exclude everything else
	                if(!in_array($groupnr, array_merge($this->alloyRimsGroups, $this->steelRimsGroups)) or !$tempObj->{"artikelnr"} or !$tempObj->{"product_code"})
	                	continue;
	                $tempObj->{"is_steel"} = $groupnr=='65' ? 1 : 0;
	                $tempObj->{"product_type"} = $groupnr=='65' ? 5 : 4;
	                $tempObj->{"currency"} = isset($tempArr[5]) ? trim($tempArr[5]) : '';
	                $tempObj->{"netpris"} = isset($tempArr[6]) ? (float)trim($tempArr[6]) : '';
	                $tempObj->{"grosspris"} = isset($tempArr[7]) ? (float)trim($tempArr[7]) : '';
	                $tempObj->{"quantity"} = isset($tempArr[8]) ? (int)trim(str_replace(array('>','<'), '', $tempArr[8])) : 0;
	                $bild_name = isset($tempArr[9]) ? trim($tempArr[9]) : '';
	                $tempObj->{"bild_link"} = $bild_name ? 'http://www.etyre.net/preview/w3/'.$bild_name . '.png' : '';
	                $tempObj->{"bild_link_1"} = $bild_name ? 'http://www.etyre.net/preview/w3/'.$bild_name . '-0.png' : '';
	                $tempObj->{"bild_link_2"} = $bild_name ? 'http://www.etyre.net/preview/w3/'.$bild_name . '-2.png' : '';
	                $tempObj->{"bild_link_3"} = $bild_name ? 'http://www.etyre.net/preview/w3/'.$bild_name . '-3.png' : '';
	                $tempObj->{"bild_link_small"} = $bild_name ? 'http://www.etyre.net/preview/w2/'.$bild_name . '.png' : '';
	                $tempObj->{"loadspeedindex"} = isset($tempArr[10]) ? trim($tempArr[10]) : '';
	                $tempObj->{"supplierid"} = isset($tempArr[11]) ? trim($tempArr[11]) : '';
	                // $tempObj->{"garagepris"} = isset($tempArr[12]) ? trim($tempArr[12]) : '';
	                // $tempObj->{"alternatekundpris"} = isset($tempArr[13]) ? trim($tempArr[13]) : '';
	                $tempObj->{"eancode"} = isset($tempArr[14]) ? trim($tempArr[14]) : '';
	                // $tempObj->{"weightcat_dpd"} = isset($tempArr[15]) ? trim($tempArr[15]) : '';
	                // $tempObj->{"emarked"} = isset($tempArr[16]) ? trim($tempArr[16]) : '';
	                $tempObj->{"european"} = isset($tempArr[17]) ? trim($tempArr[17]) : '';
	                $tempObj->{"inch"} = isset($tempArr[25]) ? str_replace(',', '.', trim($tempArr[25])) : '';
	                $tempObj->{"wide"} = isset($tempArr[26]) ? str_replace(',', '.', trim($tempArr[26])) : '';
	                $tempObj->{"offset"} = isset($tempArr[27]) ? trim($tempArr[27]) : '';
	                $tempObj->{"centerhole"} = isset($tempArr[28]) ? str_replace(',', '.', trim($tempArr[28])) : '';
	                $tempObj->{"pcd"} = isset($tempArr[29]) ? strtolower(trim($tempArr[29])) : '';
	                $tempObj->{"pcd2"} = isset($tempArr[30]) ? strtolower(trim($tempArr[30])) : '';
	                $tempObj->{"calculatorprice"} = isset($tempArr[31]) ? (float)trim($tempArr[31]) : '';
	                $tempObj->{"tpms"} = isset($tempArr[36]) ? trim($tempArr[36]) : '';
	                $tempObj->{"tuv"} = isset($tempArr[37]) ? trim($tempArr[37]) : '';
	                $tempObj->{"winter"} = isset($tempArr[38]) ? trim($tempArr[38]) : '';
	                $tempObj->{"runflat_rim"} = isset($tempArr[39]) ? trim($tempArr[39]) : '';
	                $products[] = $tempObj;
	                // $products[$a] = explode("\t", $s);
                }
            }
            // dd($products);
        }
        return $products;
    }
    private function inter_brands_list($type = "tyres"){
    	if($type == "rims")
    		$host = "http://" . $this->username_intertyre .':' . $this->password_intertyre ."@" . $this->inter_tyre_host . "?6";
    	else
    		$host = "http://" . $this->username .':' . $this->password ."@" . $this->inter_sprint_host . "?6";
        $responseText = @fopen ($host, "r");
        $brands = array();
        if ($responseText) {
            while (($s = $this->readnext ($responseText)) != "") {
                $tempArr = explode("\t", $s);
                if(!isset($tempArr[1]) or (isset($tempArr[1]) and !$tempArr[1]) )
                	continue;

                $brandCodeVal = isset($tempArr[0]) ? trim($tempArr[0]) : '';
                $brandName = isset($tempArr[1]) ? trim($tempArr[1]) : '';
                $brands[$brandCodeVal] = $brandName;
            }
        }
        $this->brandList = $brands;
    }

    public function storeInterSprintTires()
    {
        // if($request->user != "abs" || $request->pass != "5566") {
        //     return;
        // }
        // DB::table('products')->truncate();
        // DB::table('productImages')->truncate();

        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);

    	$products = $this->inter_sprint_products();
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);
        $rate = \Swap::latest('EUR/SEK');
        $rate = $rate->getValue();

        $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated, $rate) {
            foreach (array_chunk($products, 200) as $productChunk) {
                // dd($products);
                foreach($productChunk as $product) {
                    if(empty($product->wide) && empty($product->profil) && empty($product->inch))
                        continue;


                    $updateProduct = Product::where('main_supplier_product_id', $product->artikelnr)
                                    ->where('main_supplier_id', 2)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->product_dimension = $product->wide.'/'.$product->profil."R".$product->inch;
                        $updateProduct->product_brand = $product->brand;
                        $updateProduct->product_model = $product->product_model;
                        $updateProduct->product_name = $product->product_code;
                        $updateProduct->quantity = $product->quantity;
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product->netpris * $rate;
                        $updateProduct->original_price = $product->grosspris * $rate;
                        // $updateProduct->tire_manufactor_date = $product['product-tillv'];
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->delivery_time = "5-7 dagars leveranstid";
                        $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;
                        continue;
                    }

                    if($product->product_type == 1) {
                        $profitProductType = 1;
                    }
					if($product->product_type == 3 || $product->product_type == 3) {
                        $profitProductType = 2;
                    }

                    if($product->inch < 12) {
                        $profit = Profit::where('size', -1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } elseif ($product->inch > 22 || $this->isDecimal($product->inch)) {
                        $profit = Profit::where('size', 1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } else {
                        $profit = Profit::where('size', $product->inch)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    }
                    // $labelArr = explode('-', $product['product-label']);
                    // $deliveryDate = $this->ExcelToPHP($product['next_delivery']);
                    
    // try {           

                    $loadIndex = substr($product->loadspeedindex, 0, -1);
                    $speedIndex = substr($product->loadspeedindex, -1);
                    $label = $product->resistence."-".$product->wetgrip."-".$product->noicesound."-".$product->noicedb;


                    $insertProduct = new Product();
                    $insertProduct->product_category_id = 1;
                    $insertProduct->product_type_id = $product->product_type;
                    $insertProduct->profit_id = $profit->id;
                    $insertProduct->main_supplier_id = 2;
                    $insertProduct->main_supplier_product_id = $product->artikelnr;
                    $insertProduct->sub_supplier_id = $product->supplierid;
                    // $insertProduct->product_description = $product['produc->description];
                    $insertProduct->product_brand = $product->brand;
                    $insertProduct->product_model = $product->product_model;
                    $insertProduct->product_name = $product->product_code;
                    $insertProduct->product_code = $product->product_code;
                    $insertProduct->ean = $product->eancode;
                    $insertProduct->product_e_code = $product->european;
                    $insertProduct->quantity = $product->quantity;
                    $insertProduct->product_dimension = $product->wide.'/'.$product->profil."R".$product->inch;
                    $insertProduct->product_width = $product->wide;
                    $insertProduct->product_profile = $product->profil;
                    $insertProduct->product_inch = $product->inch;
                    // $insertProduct->tire_manufactor_date =  !empty($product['product-tillv']) ? $product['product-tillv'] : null;
                    $insertProduct->load_index = $loadIndex;
                    $insertProduct->speed_index = $speedIndex;
                    $insertProduct->is_runflat = $product->is_runflat;
                    $insertProduct->is_ctyre = $product->is_ctire;
                    $insertProduct->product_label = $label;
                    $insertProduct->rolling_resistance = $product->resistence;
                    $insertProduct->wet_grip = $product->wetgrip;
                    $insertProduct->noise_emission_rating = $product->noicesound;
                    $insertProduct->noise_emission_decibel = $product->noicedb;
                    // $insertProduct->sub_supplier_id = $product->supplierId;
                    //Alla priser är exklusive moms
                    $insertProduct->price = $product->netpris * $rate;
                    $insertProduct->original_price = $product->grosspris * $rate;
                    // $insertProduct->tire_manufactor_date = $product['product-tillv'];
                    // $insertProduct->storage_price = $product->cost;
                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                    $insertProduct->delivery_time = "5-7 dagars leveranstid";
                    $insertProduct->is_shown = 1;
                    $insertProduct->save();
                    $countCreated++;
                    // $productID = DB::getPdo()->lastInsertId();
    // } catch(\Exception $ex){ 
    //   dd($ex->getMessage(), $profit, $product->rimDiameter, $profitProductType); 
    //   // Note any method of class PDOException can be called on $ex.
    // }


                    if(empty($product->bild_link)) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = "noTireImg.jpg";
                        $insertProductImage->path = "images/product/noTireImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                        continue;
                    }

                    $url1 = $product->bild_link;
                    $url1 = str_replace(' ', '%20', $url1);
                    // $file1 = file_get_contents($url1);
                    $file1 = $this->file_get_contents_curl($url1);
                    $fileName = basename($url1);
                    $path = 'images/product/inter-sprint_tires/'.$fileName;
                    $thumbnail_path = 'images/product/inter-sprint_tires/thumb/tn-'.$fileName;
                    $absolute_path = public_path($path);
                    $absolute_thumbnail_path = public_path($thumbnail_path);
                    // $existImg = ProductImage::where('name', $fileName)->get();
                    // $existImg = Storage::disk('public')->exists($path);
                    // dd($fileName, $url);
                    
                    if(!file_exists($absolute_path)) {
                        if (!is_dir('images/product/inter-sprint_tires/')) {
                            // dir doesn't exist, make it
                            mkdir('images/product/inter-sprint_tires/');
                            mkdir('images/product/inter-sprint_tires/thumb/');
                        }

	                    $existImg = file_put_contents($path, $file1);
                        $existImg2 = file_put_contents($thumbnail_path, $file1);

                        if($existImg && exif_imagetype($path)) {
                            // Image::make($path)->resize(600, 600)->save();
                            // Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);
                         //    if(!$existImg2 ) {
		                       //  Image::make($path)->save($absolute_thumbnail_path);
	                        // }

                            $insertProductImage = new ProductImage();
                            $insertProductImage->product_id = $insertProduct->id;
                            $insertProductImage->name = $fileName;
                            $insertProductImage->path = $path;
                            $insertProductImage->thumbnail_path = $thumbnail_path;
                            $insertProductImage->priority = 1;
                            $insertProductImage->save();
                            continue;
                        } else {
                            File::delete($path);
                            File::delete($thumbnail_path);

                            $insertProductImage = new ProductImage();
                            $insertProductImage->product_id = $insertProduct->id;
                            $insertProductImage->name = "noTireImg.jpg";
                            $insertProductImage->path = "images/product/noTireImg.jpg";
                            $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                            $insertProductImage->priority = 1;
                            $insertProductImage->save(); 
                            continue;
                        }
                    } else {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = $fileName;
                        $insertProductImage->path = $path;
                        $insertProductImage->thumbnail_path = $thumbnail_path;
                        $insertProductImage->priority = 1;
                        $insertProductImage->save();
                    }
                }
            }
            return ['created' => $countCreated, 'updated' => $countUpdated];
        }); //End DB transaction
        
         $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 2)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);       

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Inter-sprint tires (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
        // insert into CronJobStatus params: name, response, deleted_products, begin_date
    }

    public function updateInterSprintTires()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);

        // if (\App::environment('production')) {
        //     $username = 'wheelzone';
        //     $password = 'wheel@zone123';
        // } else {
        //     $username = 'ptest';
        //     $password = 'ptest';
        // }

        $products = $this->inter_sprint_products();
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);
        $rate = \Swap::latest('EUR/SEK');
        $rate = $rate->getValue();

        $counter = DB::transaction(function() use ($products, $countUpdated, $rate) {
            foreach (array_chunk($products, 200) as $productChunk) {

                foreach($productChunk as $product) {
                	$updateProduct = Product::where('main_supplier_product_id', $product->artikelnr)
                                    ->where('main_supplier_id', 2)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        // $updateProduct->product_brand = $product->brand;
                        // $updateProduct->product_model = $product->product_model;
                        // $updateProduct->product_name = $product->product_code;
                        $updateProduct->quantity = $product->quantity;
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product->netpris * $rate;
                        $updateProduct->original_price = $product->grosspris * $rate;
                        // $updateProduct->tire_manufactor_date = $product['product-tillv'];
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        // $updateProduct->delivery_time = "5-7 dagars leveranstid";
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;
                        continue;
                    }
                }
            }
            return ['updated' => $countUpdated];
        });
 
        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Inter-sprint tyre (update)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }


   	public function storeInterTyreRims()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // if (\App::environment('production')) {
        //     $username = 'wheelzone';
        //     $password = 'wheel@zone123';
        // } else {
        //     $username = 'ptest';
        //     $password = 'ptest';
        // }

        $products = $this->inter_tyre_products();
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";

        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);
        $rate = \Swap::latest('EUR/SEK');
        $rate = $rate->getValue();
        
        $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated, $rate) {
            foreach (array_chunk($products, 200) as $productChunk) {

                foreach($productChunk as $product) {
                    $productType = 4;

                    if(empty($product->brand) || empty($product->wide) || empty($product->offset) || empty($product->inch) || empty($product->centerhole) || $product->netpris < 3 )
                        continue;



                    // if($product['category-name'] == "Aluminiumfälg")
                    //     $productType = 4;
                    // if($product['category-name'] == "Stålfälg")
                    //     $productType = 5;
                    // if($product['category-name'] == "Plåtfälg")
                    //     $productType = 6;
                    // $productName = $product->name ? $product->name : $product->modelName;
                    
                    // $productName = $product['marke']." ".$product['modell']." ".$product['tum']."x".$product['bredd']." ET:".$product['et']." ".$product['boremax']." ".$product['color'];
                    
                    $productDimension = $product->wide .'x'. $product->inch;

                    $updateProduct = Product::where('main_supplier_product_id', $product->artikelnr)
                                    ->where('main_supplier_id', 3)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        // $updateProduct->profit_id = 1;
                        $updateProduct->product_brand = $product->brand;
                        // $updateProduct->product_model = $product['modell'];
                        $updateProduct->product_name = $product->product_code;
                        $updateProduct->product_dimension = $productDimension;
                        $updateProduct->quantity = $product->quantity;
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product->netpris * $rate;
                        $updateProduct->original_price = $product->grosspris * $rate;
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->delivery_time = "5-7 dagars leveranstid";
                        $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;

                        // if(empty($product['image-url']))
                        //     continue;

                        // $url = $product['image-url'];
                        // $file = $this->file_get_contents_curl($url);
                        // $fileName = basename($url);
                        // $path = 'images/product/abs/'.$fileName;
                        // $thumbnail_path = 'images/product/abs/thumb/tn-'.$fileName;

                        // if (!is_dir('images/product/abs/')) {
                        //     // dir doesn't exist, make it
                        //     mkdir('images/product/abs/');
                        //     mkdir('images/product/abs/thumb/');
                        // }

                        // $existImg = ProductImage::where('name', $fileName)->get(); 
                        // if(sizeof($existImg) === 0) {
                        //     $existImg = file_put_contents($path, $file);
                        // }

                        // $absolute_thumbnail_path = public_path($thumbnail_path);

                        // // Image::make($path)->resize(600, 600)->save();
                        // Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);

                        // if($existImg) {
                        //     $insertProductImage = new ProductImage();
                        //     $insertProductImage->product_id = $updateProduct->id;
                        //     $insertProductImage->name = $fileName;
                        //     $insertProductImage->path = $path;
                        //     $insertProductImage->thumbnail_path = $thumbnail_path;
                        //     $insertProductImage->priority = 1;
                        //     $insertProductImage->save(); 
                        // }
                        continue;
                    }
                    
                    
                    if($product->inch < 12) {
                        $profit = Profit::where('size', -1)
                                    ->where('product_type', 3)
                                    ->first(['id']);
                    } elseif ($product->inch > 22 || $this->isDecimal($product->inch )) {
                        $profit = Profit::where('size', 1)
                                    ->where('product_type', 3)
                                    ->first(['id']);
                    } else {
                        $profit = Profit::where('size', $product->inch)
                                    ->where('product_type', 3)
                                    ->first(['id']);
                    }

                    if(isset($product->pcd)) {
                        if($product->pcd == '4x098')
                            $product->pcd = '4x98';

                        if($product->pcd == '4x114')
                            $product->pcd = '4x114.3';

                        if($product->pcd == '5x098')
                            $product->pcd = '5x98';

                        if($product->pcd == '5x114')
                            $product->pcd = '5x114.3';

                        if($product->pcd == '6x114')
                            $product->pcd = '7x114.3';

                        if($product->pcd == '6x139')
                            $product->pcd = '6x139.7';

                      if( $product->pcd == '5x120.6')
                            $product->pcd = '5x120.65';
                    }

                    if(isset($product->pcd2)) {
                        if($product->pcd2 == '4x098')
                            $product->pcd2 = '4x98';

                        if($product->pcd2 == '4x114')
                            $product->pcd2 = '4x114.3';

                        if($product->pcd2 == '5x098')
                            $product->pcd2 = '5x98';

                        if($product->pcd2 == '5x114')
                            $product->pcd2 = '5x114.3';

                        if($product->pcd2 == '6x114')
                            $product->pcd2 = '7x114.3';

                        if($product->pcd2 == '6x139')
                            $product->pcd2 = '6x139.7';

                        if( $product->pcd2 == '5x120.6')
                            $product->pcd2 = '5x120.65';
                    }

                    $insertProduct = new Product();

                    $insertProduct->product_category_id = 2;
                    $insertProduct->product_type_id = $productType;
                    $insertProduct->profit_id = $profit->id;
                    $insertProduct->main_supplier_id = 3;
                    $insertProduct->main_supplier_product_id = $product->artikelnr;
                    $insertProduct->sub_supplier_id = $product->supplierid;
                    $insertProduct->product_brand = $product->brand;
                    $insertProduct->product_name = $product->product_code;
                    // $insertProduct->product_model = $product['modell'];
                    // $insertProduct->product_description = $product['ben'];
                    $insertProduct->product_code = $product->product_code;
                    $insertProduct->product_e_code = $product->european;
                    $insertProduct->ean = $product->eancode;
                    $insertProduct->price = $product->netpris * $rate;
                    $insertProduct->original_price = $product->grosspris * $rate;
                    $insertProduct->quantity = $product->quantity;
                    $insertProduct->product_dimension = $productDimension;
                    $insertProduct->product_width = $product->wide;
                    // $insertProduct->product_profile = (int) $product->aspectRatio;
                    $insertProduct->product_inch = $product->inch;
                    $insertProduct->et = $product->offset;
                    $insertProduct->bore_max = $product->centerhole;
                    $insertProduct->pcd1 = isset($product->pcd) ? $product->pcd : null;
                    $insertProduct->pcd2 = isset($product->pcd2) ? $product->pcd2 : null;
                    $insertProduct->is_runflat = $product->runflat_rim == "N" ? 0 : 1;
                    $insertProduct->is_winter_rim = $product->winter == "N" ? 0 : 1;
                    $insertProduct->priority_supplier = 2;
                    $insertProduct->delivery_time = "5-7 dagars leveranstid";
                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                    $insertProduct->is_shown = 1;
                    // $insertProduct->storage_location = $product->locationId;
                    // $insertProduct->sub_supplier_id = $product->supplierId;
                    $insertProduct->save();
                    $countCreated++;

                    if(empty($product->bild_link) || empty($product->bild_link_1) || empty($product->bild_link_2) || empty($product->bild_link_3)) {
                         $insertProductImage = new ProductImage();
                         $insertProductImage->product_id = $insertProduct->id;
                         $insertProductImage->name = "noRimImg.jpg";
                         $insertProductImage->path = "images/product/noRimImg.jpg";
                         $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                         $insertProductImage->priority = 1;
                         $insertProductImage->save(); 
                         continue;
                    }
 
                    $urlArr = [
                		'1' => $product->bild_link,
                		'2' => $product->bild_link_1,
                		'3' => $product->bild_link_3,
                    ];
                    
                    
                    foreach($urlArr as $key => $url) {
                        if(!$this->url_exists($url) && $key != 1)
                            continue;

                        if(!$this->url_exists($url) && $key == 1) {
                            $insertProductImage = new ProductImage();
                            $insertProductImage->product_id = $insertProduct->id;
                            $insertProductImage->name = "noRimImg.jpg";
                            $insertProductImage->path = "images/product/noRimImg.jpg";
                            $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                            $insertProductImage->priority = 1;
                            $insertProductImage->save(); 
                            continue;
                        }

                    	// $url = $product->bild_link;
                    	$url = str_replace(' ', '%20', $url);
	                    $file = $this->file_get_contents_curl($url);
	                    $fileName = basename($url);
	                    $path = 'images/product/inter-tyre_rims/'.$fileName;
	                    $thumbnail_path = 'images/product/inter-tyre_rims/thumb/tn-'.$fileName;
	                    $absolute_path = public_path($path);
	                    $absolute_thumbnail_path = public_path($thumbnail_path);
	                    // $existImg = ProductImage::where('name', $fileName)->get();
	                    // $existImg = Storage::disk('public')->exists($path);
	                    // $existImg = File::exists($path);

	                    
	                    
	                    if(!file_exists($absolute_path)) {
	                        if (!is_dir('images/product/inter-tyre_rims')) {
	                            // dir doesn't exist, make it
	                            // mkdir('images/product/');
	                            mkdir('images/product/inter-tyre_rims/');
	                            mkdir('images/product/inter-tyre_rims/thumb/');
	                        }

	                        // if(sizeof($existImg) === 0) {
	                        // if(!$existImg) {
	                        //     $existImg = file_put_contents($path, $file);
	                        // }


	                        $existImg = file_put_contents($path, $file);
	                        // $existImg2 = file_put_contents($thumbnail_path, $file);

	                        if($existImg && exif_imagetype($path)) {
	                            // Image::make($path)->resize(600, 600)->save();
	                            
                                Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);

	                            $insertProductImage = new ProductImage();
	                            $insertProductImage->product_id = $insertProduct->id;
	                            $insertProductImage->name = $fileName;
	                            $insertProductImage->path = $path;
	                            $insertProductImage->thumbnail_path = $thumbnail_path;
	                            $insertProductImage->priority = $key;
	                            $insertProductImage->save();
	                            continue;
	                        } elseif ($key == 1) {
	                            File::delete($path);
	                            File::delete($thumbnail_path);

	                            $insertProductImage = new ProductImage();
	                            $insertProductImage->product_id = $insertProduct->id;
	                            $insertProductImage->name = "noRimImg.jpg";
	                            $insertProductImage->path = "images/product/noRimImg.jpg";
	                            $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
	                            $insertProductImage->priority = $key;
	                            $insertProductImage->save(); 
	                            continue;
	                        } else {
		                        File::delete($path);
		                        File::delete($thumbnail_path);
	                        }

	                    } else {
	                        $insertProductImage = new ProductImage();
	                        $insertProductImage->product_id = $insertProduct->id;
	                        $insertProductImage->name = $fileName;
	                        $insertProductImage->path = $path;
	                        $insertProductImage->thumbnail_path = $thumbnail_path;
	                        $insertProductImage->priority = $key;
	                        $insertProductImage->save();
	                    }
	                }
                }
            }

            return ['created' => $countCreated, 'updated' => $countUpdated];
        });//End DB transaction

        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 3)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Inter-tyre rims (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

    
    public function updateInterTyreRims()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);

        // if (\App::environment('production')) {
        //     $username = 'wheelzone';
        //     $password = 'wheel@zone123';
        // } else {
        //     $username = 'ptest';
        //     $password = 'ptest';
        // }



        $products = $this->inter_tyre_products();
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);
        $rate = \Swap::latest('EUR/SEK');
        $rate = $rate->getValue();

        $counter = DB::transaction(function() use ($products, $countUpdated, $rate) {
            foreach (array_chunk($products, 200) as $productChunk) {

                foreach($productChunk as $product) {

                    if(isset($product->pcd)) {
                        if($product->pcd == '4x098')
                            $product->pcd = '4x98';

                        if($product->pcd == '4x114')
                            $product->pcd = '4x114.3';

                        if($product->pcd == '5x098')
                            $product->pcd = '5x98';

                        if($product->pcd == '5x114')
                            $product->pcd = '5x114.3';

                        if($product->pcd == '6x114')
                            $product->pcd = '7x114.3';

                        if($product->pcd == '6x139')
                            $product->pcd = '6x139.7';

                      if( $product->pcd == '5x120.6')
                            $product->pcd = '5x120.65';
                    }

                    if(isset($product->pcd2)) {
                        if($product->pcd2 == '4x098')
                            $product->pcd2 = '4x98';

                        if($product->pcd2 == '4x114')
                            $product->pcd2 = '4x114.3';

                        if($product->pcd2 == '5x098')
                            $product->pcd2 = '5x98';

                        if($product->pcd2 == '5x114')
                            $product->pcd2 = '5x114.3';

                        if($product->pcd2 == '6x114')
                            $product->pcd2 = '7x114.3';

                        if($product->pcd2 == '6x139')
                            $product->pcd2 = '6x139.7';

                        if( $product->pcd2 == '5x120.6')
                            $product->pcd2 = '5x120.65';
                    }

                	$updateProduct = Product::where('main_supplier_product_id', $product->artikelnr)
                                    ->where('main_supplier_id', 3)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        // $updateProduct->profit_id = 1;
                        // $updateProduct->pcd1 = isset($product->pcd) ? $product->pcd : null;
                        // $updateProduct->pcd2 = isset($product->pcd2) ? $product->pcd2 : null;
                        // $updateProduct->product_brand = $product->brand;
                        // $updateProduct->product_model = $product['modell'];
                        // $updateProduct->product_name = $product->product_code;
                        $updateProduct->quantity = $product->quantity;
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product->netpris * $rate;
                        $updateProduct->original_price = $product->grosspris * $rate;
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        // $updateProduct->delivery_time = "5-7 dagars leveranstid";
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;
                    }
                }
            }
            return ['updated' => $countUpdated];
        });
 
        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Inter-tyre rims (update)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

}

?>