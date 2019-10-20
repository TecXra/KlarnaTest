<?php

namespace App\Http\Controllers;

use App\CronJobStatus;
use App\Http\Requests;
use App\Http\Utilities\WsseAuthHeader;
use App\Product;
use App\ProductImage;
use App\Profit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use SoapClient;

class CronJobController extends Controller
{
    public function index()
    {
        return view('admin.cron_jobs');
    }

    public function isDecimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    public function formatPCD($pcd)
    {
        return str_replace(['/', 'X', ','], ['x', 'x','.'], $pcd);
    }
 
    public function ExcelToPHP($dateValue = 0, $ExcelBaseDate = 1900) {
        if($dateValue == null) 
            return;

        if ($ExcelBaseDate == 1900) {
            $myExcelBaseDate = 25569;
            //    Adjust for the spurious 29-Feb-1900 (Day 60)
            if ($dateValue < 60) {
                --$myExcelBaseDate;
            }
        } else {
            $myExcelBaseDate = 24107;
        }

        // Perform conversion
        if ($dateValue >= 1) {
            $utcDays = $dateValue - $myExcelBaseDate;
            $returnValue = round($utcDays * 86400);
            if (($returnValue <= PHP_INT_MAX) && ($returnValue >= -PHP_INT_MAX)) {
                $returnValue = (integer) $returnValue;
            }
        } else {
            $hours = round($dateValue * 24);
            $mins = round($dateValue * 1440) - round($hours * 60);
            $secs = round($dateValue * 86400) - round($hours * 3600) - round($mins * 60);
            $returnValue = (integer) gmmktime($hours, $mins, $secs);
        }

        // Format Y-m-d
        $returnValue = date("Y-m-d",$returnValue);

        // Return
        return $returnValue;
    }    //    function ExcelToPHP()

    public function CallAPIHeader($method, $headers, $url, $data = false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        switch ($method)
        {   
            case "POST":
                if($data){
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;

            case "PUT":
                curl_setopt($curl,CURLOPT_PUT, 1);
                break;

            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    // public function file_get_contents_curl($url) {
    //     $curl = curl_init();
    //     curl_setopt($curl, CURLOPT_URL, $url);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
    //     $data = curl_exec($curl);
    //     curl_close($curl);
    //     return $data;
    // }


    public function file_get_contents_curl($url) {
        $ch = curl_init();
        set_time_limit(900);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }


    public function getJRAccessoriesFile()
    {
        return storage_path('exports') . '/StreetwheelsAccessories.xls';
    }


    public function storeAmringProducts()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        $username = '303713';
        $password = 'P9WQ8eR07F16Ogi';
        $wsdl_path = storage_path('exports/amringWSDL/ProductService.wsdl');
        // Create WSSE header
        $wsse_header = new WsseAuthHeader($username, $password);
         
        // Create SOAP client object + options, based on local WSDL
        $client = new SoapClient($wsdl_path, array(
            'exceptions' => true, 
            'trace' => 1, 
            'wdsl_local_copy' => true,
            'keep_alive' => false,
            'stream_context'=> stream_context_create(array('ssl'=> array(
                    'verify_peer'=>false,
                    'verify_peer_name'=>false, 
                    'allow_self_signed' => true //can fiddle with this one.
                         )
                    )
                )
        ));

         
        // $countRims = 0;
        // $countTires = 0;
        // Set up SOAP headers
        $client->__setSoapHeaders(array($wsse_header));
        $items = $rimsArray = $tiresArray = array();
        try
        {   
            $items = $client->GetAllProducts();
            $products = $client->GetAllProducts()->GetAllProductsResult->Product;
            if($items){
                $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated) {
                    foreach (array_chunk($products, 200) as $productChunk) {
                // dd($products);
                        foreach($productChunk as $product) {
                            
                            // if($product->ProdGrpDesc=="Tire")
                            //     $countTires++;

                            // if($product->ProdGrpDesc=="Wheel")
                            //     $countRims++;

                            // continue;

                            if(isset($product->ProdGrpDesc) and isset($product->AmringPN)){
                                if($product->ProdGrpDesc == "Tire" and round($product->Price)!=0 and $product->StockValue > 1 and ($product->SeasonId == 1 or $product->SeasonId == 2)) {

                                    $checkPrice = isset($product->Price) ? $product->Price : 0;
                                    
                                    if((isset($product->ProdTypeDesc) and strtolower($product->ProdTypeDesc)=="mc tiers") or $checkPrice<30)
                                    continue;
                                    //$tiresArray[] = $product;
                                    
                                    $updateProduct = Product::where('main_supplier_product_id', $product->AmringPN)
                                        ->where('main_supplier_id', 2)
                                        ->first();  


                                    $product->Inches = str_replace(",", ".", $product->Inches);
                                    $product->Description = str_replace(["'", '"'], "", $product->Description);
                                    $product->Pattern  = str_replace(["'", '"'], "", $product->Pattern);


                                    $productType = 1;
                                    $profitProductType = 1;

                                    if($product->SeasonDesc == "Winter") {
                                        $profitProductType = 2;

                                        if($product->StuddedFrictionID == 2 || $product->StuddedFrictionID == 3) 
                                            $productType = 2;
                                        else
                                            $productType = 3;
                                    }

                                    if($product->Inches < 12) {
                                        $profit = Profit::where('size', -1)
                                                    ->where('product_type', $profitProductType)
                                                    ->first(['id']);
                                    } elseif ($product->Inches > 22 || $this->isDecimal($product->Inches )) {
                                        $profit = Profit::where('size', 1)
                                                    ->where('product_type', $profitProductType)
                                                    ->first(['id']);
                                    } else {
                                        $profit = Profit::where('size', $product->Inches)
                                                    ->where('product_type', $profitProductType)
                                                    ->first(['id']);
                                    }

                                    if(sizeOf($updateProduct) > 0) {
                                        $updateProduct->profit_id = $profit->id;
                                        $updateProduct->product_brand = isset($product->BrandDesc) ? $product->BrandDesc : strtok($product->Description, " ");
                                        $updateProduct->product_model = $product->Pattern;
                                        $updateProduct->product_name = $product->Description;
                                        $updateProduct->quantity = (int) $product->StockValue;
                                        //Alla priser är exklusive moms
                                        $updateProduct->price = $product->Price;
                                        $updateProduct->original_price = $product->Price;
                                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                                        $updateProduct->delivery_time = "5-7 dagars leveranstid";
                                        $updateProduct->is_shown = 1;
                                        $updateProduct->is_deleted = 0;
                                        $updateProduct->save();
                                        $updateProduct->touch();
                                        $countUpdated++;            
                                        continue;
                                    }

                                    $fuelGripNoise = ["","",""]; 
                                    $productLabel = "";
                                    if(strlen($product->FuelGripNoise) > 2) {
                                        $fuelGripNoise =  $product->FuelGripNoise; 
                                        $productLabel = $fuelGripNoise[0]."-".$fuelGripNoise[1]."-".$fuelGripNoise[2]."-".$product->Db;  
                                    }

                                    $insertProduct = new Product();
                                    $insertProduct->product_category_id = 1;
                                    $insertProduct->product_type_id = $productType;
                                    $insertProduct->profit_id = $profit->id;
                                    $insertProduct->main_supplier_id = 2;
                                    $insertProduct->main_supplier_product_id = $product->AmringPN;
                                    // $insertProduct->EANCode = $product->eanCode;
                                    $insertProduct->product_description = "";
                                    $insertProduct->ean = $product->EAN;
                                    $insertProduct->product_brand = isset($product->BrandDesc) ? $product->BrandDesc : strtok($product->Description, " ");
                                    $insertProduct->product_name = $product->Description;
                                    $insertProduct->product_model = $product->Pattern;
                                    $insertProduct->product_code = $product->Description;
                                    $insertProduct->price = $product->Price;                                
                                    $insertProduct->original_price = $product->Price;
                                    $insertProduct->storage_price = $product->Price;
                                    $insertProduct->quantity = (int) $product->StockValue;
                                    $insertProduct->product_dimension = (int) $product->Width.'/'.(int) $product->Profile.'R'. (int) $product->Inches;
                                    $insertProduct->product_width = $product->Width;
                                    $insertProduct->product_profile = $product->Profile;
                                    $insertProduct->product_inch = $product->Inches;
                                    $insertProduct->et = $product->ET;
                                    // $insertProduct->et_min = $product['product-et'];
                                    // $insertProduct->tire_manufactor_date =  !empty($product['product-tillv']) ? $product['product-tillv'] : null;
                                    $insertProduct->load_index = $product->LoadIndex;
                                    $insertProduct->speed_index = $product->SpeedIndex;
                                    $insertProduct->is_runflat = $product->Runflat == "Not runflat" ? 0 : 1;
                                    $insertProduct->is_ctyre = strtolower($product->ProdTypeDesc) == "light truck tire" ? 1 : 0;
                                    $insertProduct->product_label = $productLabel;
                                    $insertProduct->rolling_resistance = $fuelGripNoise[0];
                                    $insertProduct->wet_grip = $fuelGripNoise[1];
                                    $insertProduct->noise_emission_rating = $fuelGripNoise[2];
                                    $insertProduct->noise_emission_decibel = $product->Db;
                                    $insertProduct->priority_supplier = 2;
                                    $insertProduct->delivery_time = "5-7 dagars leveranstid";
                                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                                    $insertProduct->is_shown = 1;
                                    // $insertProduct->storage_location = $product->locationId;
                                    // $insertProduct->sub_supplier_id = $product->supplierId;
                                    $insertProduct->save();

                                    $insertProductImage = new ProductImage();
                                    $insertProductImage->product_id = $insertProduct->id;
                                    $insertProductImage->name = "noTireImg.jpg";
                                    $insertProductImage->path = "images/product/noTireImg.jpg";
                                    $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                                    $insertProductImage->priority = 1;
                                    $insertProductImage->save(); 
                                    $countCreated++;
                                    continue;
                                     
                                }
                            }
                        }

                        // reset($productChunk);

                        foreach($productChunk as $product) {
                            if(isset($product->ProdGrpDesc) and isset($product->AmringPN)){
                                if($product->ProdGrpDesc=="Wheel" and $product->MaterialID==50 and $product->StockValue > 3){
                                    
                                    $checkWide = isset($product->Width) ? $product->Width : 0;
                                    $checkInch = isset($product->Inches) ? $product->Inches : 0;
                                    $checkPrice = isset($product->Price) ? $product->Price : 0;
                                    
                                    if($checkInch=='0' or $checkWide=='0' or $checkPrice<100)
                                        continue;

                                    $updateProduct = Product::where('main_supplier_product_id', $product->AmringPN)
                                        ->where('main_supplier_id', 2)
                                        ->first();  


                                    $product->Inches = str_replace(",", ".", $product->Inches);
                                    $product->Description = str_replace(["'", '"'], "", $product->Description);
                                    $product->Pattern  = str_replace(["'", '"'], "", $product->Pattern);
                                   
                                    $productType = 4;

                                    if($product->Inches < 12) {
                                        $profit = Profit::where('size', -1)
                                                    ->where('product_type', 3)
                                                    ->first(['id']);
                                    } elseif ($product->Inches > 22 || $this->isDecimal($product->Inches )) {
                                        $profit = Profit::where('size', 1)
                                                    ->where('product_type', 3)
                                                    ->first(['id']);
                                    } else {
                                        $profit = Profit::where('size', $product->Inches)
                                                    ->where('product_type', 3)
                                                    ->first(['id']);
                                    }
                                   

                                    if(sizeOf($updateProduct) > 0) {
                                        $updateProduct->profit_id = $profit->id;
                                        $updateProduct->product_brand = isset($product->BrandDesc) ? $product->BrandDesc : strtok($product->Description, " ");
                                        $updateProduct->product_model = $product->Pattern;
                                        $updateProduct->product_name = $product->Description;
                                        $updateProduct->quantity = (int) $product->StockValue;
                                        //Alla priser är exklusive moms
                                        $updateProduct->price = $product->Price;
                                        $updateProduct->original_price = $product->Price;
                                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                                        $updateProduct->delivery_time = "5-7 dagars leveranstid";
                                        $updateProduct->is_shown = 1;
                                        $updateProduct->is_deleted = 0;
                                        $updateProduct->save();
                                        $updateProduct->touch();
                                        $countUpdated++;          
                                        continue;
                                    }

                                    $insertProduct = new Product();

                                    $insertProduct->product_category_id = 2;
                                    $insertProduct->product_type_id = $productType;
                                    $insertProduct->profit_id = $profit->id;
                                    $insertProduct->main_supplier_id = 2;
                                    $insertProduct->main_supplier_product_id = $product->AmringPN;
                                    // $insertProduct->EANCode = $product->eanCode;
                                    $insertProduct->product_description = "";
                                    $insertProduct->ean = $product->EAN;
                                    $insertProduct->product_brand = isset($product->BrandDesc) ? $product->BrandDesc : strtok($product->Description, " ");;
                                    $insertProduct->product_name = $product->Description;
                                    $insertProduct->product_model = $product->Pattern;
                                    $insertProduct->product_code = $product->Description;
                                    $insertProduct->price = $product->Price;
                                    $insertProduct->original_price = $product->Price;
                                    $insertProduct->storage_price = $product->Price;
                                    $insertProduct->quantity = (int) $product->StockValue;
                                    $insertProduct->product_width = $product->Width;
                                    // $insertProduct->product_profile = $product->Profile;
                                    $insertProduct->product_inch = $product->Inches;
                                    $insertProduct->et = $product->ET;
                                    // $insertProduct->et_min = $product['product-et'];
                                    // $insertProduct->tire_manufactor_date =  !empty($product['product-tillv']) ? $product['product-tillv'] : null;
                                    $insertProduct->bore_max = $product->CenterBore;
                                    $insertProduct->pcd1 = $this->formatPCD($product->Profile);
                                    $insertProduct->priority_supplier = 3;
                                    $insertProduct->delivery_time = "5-7 dagars leveranstid";
                                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                                    $insertProduct->is_shown = 1;
                                    // $insertProduct->storage_location = $product->locationId;
                                    // $insertProduct->sub_supplier_id = $product->supplierId;
                                    $insertProduct->save();
                                    $countCreated++;


                                    $insertProductImage = new ProductImage();
                                    $insertProductImage->product_id = $insertProduct->id;
                                    $insertProductImage->name = "noRimImg.jpg";
                                    $insertProductImage->path = "images/product/noRimImg.jpg";
                                    $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                                    $insertProductImage->priority = 1;
                                    $insertProductImage->save(); 
                                    continue;
                                }
                            }
                        }
                    }
                    return ['created' => $countCreated, 'updated' => $countUpdated];
                });//End DB transaction
                // dd($countTires, $countRims);
                $counter['deleted'] = DB::table('products')
                                ->where('main_supplier_id', 2)
                                ->where('updated_at', '<', $updatedDate)
                                ->where('is_deleted', 0)
                                ->update([
                                    'is_deleted' => 1, 
                                    'is_shown' => 0 
                                ]);

                $storeCronJob = new CronJobStatus;
                $storeCronJob->name = "Amring products (store)";
                $storeCronJob->response = $response;
                $storeCronJob->created_products = $counter['created'];
                $storeCronJob->updated_products = $counter['updated'];
                $storeCronJob->deleted_products = $counter['deleted'];
                $storeCronJob->begin_at = $updatedDate;
                $storeCronJob->save();
            }
        } catch(Exception $e) {
            echo "An exception occured: ".$items["Exception"] = $e->getMessage();
        }
    }


    public function updateAmringProducts()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();


        $username = '303713';
        $password = 'P9WQ8eR07F16Ogi';
        $wsdl_path = storage_path('exports/amringWSDL/ProductService.wsdl');
        // Create WSSE header
        $wsse_header = new WsseAuthHeader($username, $password);
         
        // Create SOAP client object + options, based on local WSDL
        $client = new SoapClient($wsdl_path, array('exceptions' => true, 'trace' => 1, 'wdsl_local_copy' => true));
         
        // Set up SOAP headers
        $client->__setSoapHeaders(array($wsse_header));
        $items = $rimsArray = $tiresArray = array();
        try
        {
            $items = $client->GetAllProducts();
            $products = $client->GetAllProducts()->GetAllProductsResult->Product;
            if($items){
                $counter = DB::transaction(function() use ($products, $countUpdated) {
                    foreach($products as $product){
                        if(isset($product->ProdGrpDesc) and isset($product->AmringPN)){
                            if($product->ProdGrpDesc == "Tire" and round($product->Price)!=0 and $product->StockValue > 1 and ($product->SeasonId == 1 or $product->SeasonId == 2)) {

                                $checkPrice = isset($product->Price) ? $product->Price : 0;
                                
                                if((isset($product->ProdTypeDesc) and strtolower($product->ProdTypeDesc)=="mc tiers") or $checkPrice<30)
                                continue;
                                //$tiresArray[] = $product;
                                
                                $updateProduct = Product::where('main_supplier_product_id', $product->AmringPN)
                                    ->where('main_supplier_id', 2)
                                    ->first();

                                if(sizeOf($updateProduct) > 0) {
                                    $updateProduct->quantity = (int) $product->StockValue;
                                    //Alla priser är exklusive moms
                                    $updateProduct->price = $product->Price;
                                    $updateProduct->original_price = $product->Price;
                                    $updateProduct->save();
                                    $updateProduct->touch();  
                                    $countUpdated++;          
                                    continue;
                                }
                            }

                            
                            if($product->ProdGrpDesc=="Wheel" and $product->MaterialID=="50" and $product->StockValue > 3 and isset($product->ProdGrpDesc)){
                                
                                $checkWide = isset($product->Width) ? $product->Width : 0;
                                $checkInch = isset($product->Inches) ? $product->Inches : 0;
                                $checkPrice = isset($product->Price) ? $product->Price : 0;
                                
                                if($checkInch=='0' or $checkWide=='0' or $checkPrice<100)
                                    continue;

                                $updateProduct = Product::where('main_supplier_product_id', $product->AmringPN)
                                    ->where('main_supplier_id', 2)
                                    ->first(); 
                            
                                if(sizeOf($updateProduct) > 0) {
                                    $updateProduct->quantity = (int) $product->StockValue;
                                    //Alla priser är exklusive moms
                                    $updateProduct->price = $product->Price;
                                    $updateProduct->original_price = $product->Price;
                                    $updateProduct->save();
                                    $updateProduct->touch();
                                    $countUpdated++;         
                                    continue;
                                }
                            }
                        }
                    }
                    return ['updated' => $countUpdated];
                });//End DB transaction

                $storeCronJob = new CronJobStatus;
                $storeCronJob->name = "Amring products (update)";
                // $storeCronJob->response = $response;
                $storeCronJob->created_products = 0;
                $storeCronJob->updated_products = $counter['updated'];
                $storeCronJob->deleted_products = 0;
                $storeCronJob->begin_at = $updatedDate;
                $storeCronJob->save();
            }
        } catch(Exception $e) {
            echo "An exception occured: ".$items["Exception"] = $e->getMessage();
        }
    }


    public function getVandenbanFile()
    {
        return storage_path('exports') . '/602700.xls';
        // return storage_path('exports') . '/602700.csv';
        // return storage_path('exports') . '/RADI8_abs-stock.xls';
    }

    public function storeVandenbanTires()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date('Y-m-d H:i:s');
        $response = "";

        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        if (($handle = fopen($this->getVandenbanFile(), "r")) !== FALSE) {
            $loop = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($loop == 0) {
                    $loop++;
                    continue;
                }

                $rowString = implode(".", $data);
                $rowString = str_replace('"','', $rowString);
                $rec = explode("\t", $rowString);
                if(count($rec) == 28) {
                    $products[] = $rec;
                }
            }
        }

        
        $rate = \Swap::latest('EUR/SEK');
        $rate = $rate->getValue();
        // dd($products);
        $counter = DB::transaction(function() use ($products, $rate, $countCreated, $countUpdated) {
            // $Application = [];
            // $Attribute = [];
            foreach (array_chunk($products, 200) as $productChunk) {
                foreach($productChunk as $product) {
                
                    // $Application[] =  $product[26];
                    // $Attribute[] =  $product[27];

                    // continue;
                    
                    // If with is less than 16, continue
                    if($product[4] < 16)
                        continue;

                    // If pirce less then 2 euro, continue
                    if($product[15] < 2)
                        continue;

                    if($product[10] == "Vrachtwagen") 
                        continue;

                    if($product[27] == "SPIKED")
                        continue;


                    $dimensionLetters = $product[7] == "R" ? "R" : $product[7]."R";

                    $productType = 1;
                    $profitProductType = 1;

                    if($product[11] !== "Zomer") {
                        $profitProductType = 2;
                        $productType = 2;

                        if($product[27] == "SPIKED") {
                            $productType = 3;
                        }
                    }

                    $updateProduct = Product::where('main_supplier_product_id', $product[0])
                                        ->where('main_supplier_id', 3)
                                        ->first();  

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->product_type_id = $productType;
                        $updateProduct->product_brand = $product[3];
                        $updateProduct->product_model = $product[8];
                        $updateProduct->product_name = $product[3]." ".$product[4]."/".$product[5].$dimensionLetters.$product[6]." ".$product[8];
                        $updateProduct->quantity = (int) $product[16];
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product[15] * $rate + 150;
                        $updateProduct->original_price = $product[15] * $rate;
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->delivery_time = "5-7 dagars leveranstid";
                        $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;          
                        continue;
                    }

                    
                    if($product[4] < 12) {
                        $profit = Profit::where('size', -1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } elseif ($product[4] > 22 || $this->isDecimal($product[4] )) {
                        $profit = Profit::where('size', 1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } else {
                        $profit = Profit::where('size', $product[4])
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    }

                    $insertProduct = new Product();
                    $insertProduct->product_category_id = 1;
                    $insertProduct->product_type_id = $productType;
                    $insertProduct->profit_id = $profit->id;
                    $insertProduct->main_supplier_id = 3;
                    $insertProduct->main_supplier_product_id = $product[0];
                    // $insertProduct->EANCode = $product->eanCode;
                    $insertProduct->product_description = $product[24];
                    $insertProduct->ean = $product[1];
                    $insertProduct->product_brand = $product[3];
                    $insertProduct->product_name = $product[3]." ".$product[4]."/".$product[5].$dimensionLetters.$product[6]." ".$product[8];
                    $insertProduct->product_model = $product[8];
                    $insertProduct->product_code = $product[3]." ".$product[4]."/".$product[5].$dimensionLetters.$product[6]." ".$product[8];
                    $insertProduct->price = $product[15] * $rate + 150;                     
                    $insertProduct->original_price = $product[15] * $rate;
                    $insertProduct->storage_price = $product[15] * $rate;
                    $insertProduct->quantity = (int) $product[16];
                    $insertProduct->product_dimension = $product[4]."/".$product[5].$dimensionLetters.$product[6];
                    $insertProduct->product_width = $product[4];
                    $insertProduct->product_profile = $product[5];
                    $insertProduct->product_inch = $product[6];
                    // $insertProduct->et = $product->ET;
                    // $insertProduct->et_min = $product['product-et'];
                    // $insertProduct->tire_manufactor_date =  !empty($product['product-tillv']) ? $product['product-tillv'] : null;
                    $insertProduct->load_index = $product[9];
                    $insertProduct->speed_index = $product[7];
                    $insertProduct->is_runflat = $product[13] == "No" ? 0 : 1;
                    $insertProduct->is_ctyre = 0;//$product[10] == "Personenwagen" ? 0 : 1;
                    $insertProduct->product_label = $product[18]."-".$product[19]."-".$product[21]."-".$product[20];
                    $insertProduct->rolling_resistance = $product[18];
                    $insertProduct->wet_grip = $product[19];
                    $insertProduct->noise_emission_rating = $product[21];
                    $insertProduct->noise_emission_decibel = $product[20];
                    $insertProduct->priority_supplier = 2;
                    $insertProduct->delivery_time = "5-7 dagars leveranstid";
                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                    $insertProduct->is_shown = 1;
                    // $insertProduct->storage_location = $product->locationId;
                    // $insertProduct->sub_supplier_id = $product->supplierId;
                    $insertProduct->save();
                    $countCreated++;

                    if(empty($product[17])) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = "noTireImg.jpg";
                        $insertProductImage->path = "images/product/noTireImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                        continue;
                    }

                    $url = $product[17];
                    // $url = str_replace(' ', '%20', $url);
                    $file = $this->file_get_contents_curl($url);
                    $fileName = basename($url);
                    $path = 'images/product/vandenban/'.$fileName;
                    $thumbnail_path = 'images/product/vandenban/thumb/tn-'.$fileName;
                    $absolute_path = public_path($path);
                    $absolute_thumbnail_path = public_path($thumbnail_path);
                    // $existImg = ProductImage::where('name', $fileName)->get();
                    // $existImg = Storage::disk('public')->exists($path);
                    $existImg = File::exists($path);
                    // dd($fileName, $url);
                    
                    
                    // dd($profit, $productType);

                    if(!file_exists($absolute_path)) {
                        if (!is_dir('images/product/vandenban/')) {
                            // dir doesn't exist, make it
                            mkdir('images/product/vandenban/');
                            mkdir('images/product/vandenban/thumb/');
                        }

                        // if(sizeof($existImg) === 0) {
                        if(!$existImg) {
                            $existImg = file_put_contents($path, $file);
                        }

                        // Image::make($path)->resize(600, 600)->save();
                        // Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);
                        Image::make($path)->save($absolute_thumbnail_path);
                    }
                    

                    if($existImg) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = $fileName;
                        $insertProductImage->path = $path;
                        $insertProductImage->thumbnail_path = $thumbnail_path;
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                    } else {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = "noTireImg.jpg";
                        $insertProductImage->path = "images/product/noTireImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                    }
                }
            }
            return ['created' => $countCreated, 'updated' => $countUpdated];
            // dd($Application, $Attribute);
        }); //End DB transaction
        
        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 3)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Vandenban tires (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();

        // $excelFilePath = 'filepath/'.$excelFileName;
        // if (!file_exists($excelFilePath)) {
        // if (!file_exists($this->getVandenbanFile())) {
        //     echo "$excelFileName does not exist";
        // } else {
        //     $dataArr = array();
        //     if (($handle = fopen($this->getVandenbanFile(), "r")) !== FALSE) {
        //         while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //             $rowString = implode(".", $data);
        //             $rowString = str_replace('"','', $rowString);
        //             $rec = explode("\t", $rowString);
        //             if(count($rec) == 26)
        //                 $dataArr[] = $rec;
        //         }
        //     } else {
        //         echo $excelFileName.': Some error with opening file or empty file';
        //     }
        // }
        // dd($dataArr);
    }

    public function getVandenbanUpdateFile()
    {
        return storage_path('exports') . '/602700-2.xls';
        // return storage_path('exports') . '/602700.csv';
        // return storage_path('exports') . '/RADI8_abs-stock.xls';
    }

    public function updateVandenbanTires()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";

        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        if (($handle = fopen($this->getVandenbanUpdateFile(), "r")) !== FALSE) {
            $loop = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // if($loop == 0) {
                //     $loop++;
                //     continue;
                // }

                $rowString = implode(".", $data);
                $rowString = str_replace('"','', $rowString);
                $rec = explode("\t", $rowString);
                if(count($rec) == 6) {
                    $products[] = $rec;
                }
            }
        }
        $rate = \Swap::latest('EUR/SEK');
        $rate = $rate->getValue();
        // dd($rate->getValue());
        // dd($products);
        $counter = DB::transaction(function() use ($products, $rate, $countUpdated) {
            foreach (array_chunk($products, 200) as $productChunk) {
                foreach($productChunk as $product) {

                    $updateProduct = Product::where('main_supplier_product_id', $product[0])
                                        ->where('main_supplier_id', 3)
                                        ->first();  

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->quantity = (int) $product[3];
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product[4] * $rate + 150;
                        $updateProduct->original_price = $product[4] * $rate;
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
        $storeCronJob->name = "Vandenban tires (update)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

    public function getDelticomFile()
    {
        // return storage_path('exports') . '/dinadack_dackline.csv';
        return storage_path('exports') . '/dinadack_dackline_XML.xml';
    }

    public function xml2Array($filename)
    {
        $xml = simplexml_load_file($filename, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        return json_decode($json,TRUE);
    }

    public function storeDelticomTires()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";

        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        $products = $this->xml2Array($this->getDelticomFile())['shopitem'];
        // dd($products);
        $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated) {
            foreach (array_chunk($products, 200) as $productChunk) {
                foreach($productChunk as $product) {
                    
                    //size 16 och uppåt behålles, resten filtreras eventuellt
                    if($product['price'] < 20)
                        continue;

                    if(empty($product['model']) || empty($product['width']) || empty($product['profile']) || empty($product['construction_type']) || empty($product['size']) || empty($product['brand']) )
                        continue;

                    $productName = $product['model']." ".$product['width']."/".$product['profile'].$product['construction_type'].$product['size']." ".$product['brand'];

                    $updateProduct = Product::where('main_supplier_product_id', $product['id'])
                        ->where('main_supplier_id', 4)
                        ->first();   

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->product_model = $product['model'];
                        $updateProduct->product_name = $productName;
                        $updateProduct->quantity = (int) $product['pcs'];
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product['price'];
                        $updateProduct->original_price = $product['price'];
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->delivery_time = "5-7 dagars leveranstid";
                        $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch(); 
                        $countUpdated++;           
                        continue;
                    }

                    $productType = 1;
                    $profitProductType = 1;

                    if($product['sowigan'] !== "So") {
                        $profitProductType = 2;
                        $productType = 2;
                    }

                    if($product['size'] < 12) {
                        $profit = Profit::where('size', -1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } elseif ($product['size'] > 22 || $this->isDecimal($product['size'] )) {
                        $profit = Profit::where('size', 1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } else {
                        $profit = Profit::where('size', $product['size'])
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    }


                   

                    $productLabel = "";
                    if(!empty($product['label_roll']) && !empty($product['label_wet']) && !empty($product['label_noise2']) && !empty($product['label_noise1'])) {
                        $productLabel = $product['label_roll']."-".$product['label_wet']."-".$product['label_noise2']."-".$product['label_noise1'];
                    }

                    $insertProduct = new Product();
                    $insertProduct->product_category_id = 1;
                    $insertProduct->product_type_id = $productType;
                    $insertProduct->profit_id = $profit->id;
                    $insertProduct->main_supplier_id = 4;
                    $insertProduct->main_supplier_product_id = $product['id'];
                    // $insertProduct->EANCode = $product['eanCode'];
                    $insertProduct->product_description = "";
                    $insertProduct->ean = empty($product['ean']) ? "": $product['ean'];
                    $insertProduct->product_brand = $product['brand'];
                    $insertProduct->product_model = $product['model'];
                    $insertProduct->product_name = $productName;
                    $insertProduct->product_code = $productName;
                    $insertProduct->price = $product['price']; 
                    $insertProduct->original_price = $product['price'];
                    $insertProduct->storage_price = $product['price'];
                    $insertProduct->quantity = (int) $product['pcs'];
                    $insertProduct->product_dimension = (int) $product['width'].'/'.(int) $product['profile'].$product['construction_type']. (int) $product['size'];
                    $insertProduct->product_width = $product['width'];
                    $insertProduct->product_profile = $product['profile'];
                    $insertProduct->product_inch = $product['size'];
                    // $insertProduct->et = $product['ET'];
                    // $insertProduct->et_min = $product['product-et'];
                    // $insertProduct->tire_manufactor_date =  !empty($product['product-tillv']) ? $product['product-tillv'] : null;
                    $insertProduct->load_index = empty($product['loadindex'])? "":$product['loadindex'];
                    $insertProduct->speed_index = empty($product['speedrating']) ? "":$product['speedrating'];
                    $insertProduct->is_runflat = empty($product['runflat']) ? 0:1;
                    $insertProduct->is_ctyre = empty($product['c_flag']) ? 0:1;
                    $insertProduct->product_label = $productLabel;
                    $insertProduct->rolling_resistance = empty($product['label_roll']) ? "": $product["label_roll"];
                    $insertProduct->wet_grip = empty($product['label_wet']) ? "": $product["label_wet"];
                    $insertProduct->noise_emission_rating = empty($product['label_noise2']) ? "": $product["label_noise2"];
                    $insertProduct->noise_emission_decibel = empty($product['label_noise1']) ? "": $product["label_noise1"];
                    $insertProduct->priority_supplier = 2;
                    $insertProduct->delivery_time = "5-7 dagars leveranstid";
                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                    $insertProduct->is_shown = 1;
                    // $insertProduct->storage_location = $product['locationId'];
                    // $insertProduct->sub_supplier_id = $product['supplierId'];
                    $insertProduct->save();
                    $countCreated++;

                    $insertProductImage = new ProductImage();
                    $insertProductImage->product_id = $insertProduct->id;
                    $insertProductImage->name = "noTireImg.jpg";
                    $insertProductImage->path = "images/product/noTireImg.jpg";
                    $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                    $insertProductImage->priority = 1;
                    $insertProductImage->save(); 
                    continue;

                }
            }
            return ['created' => $countCreated, 'updated' => $countUpdated];
        }); //end transaction

        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 4)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Delticom tires (store)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

    public function updateDelticomTires()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";

        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        $products = $this->xml2Array($this->getDelticomFile())['shopitem'];
        // dd($products);
        $counter = DB::transaction(function() use ($products, $countUpdated) {
            foreach (array_chunk($products, 200) as $productChunk) {
                foreach($productChunk as $product) {

                    if($product['price'] < 20)
                        continue;

                    $updateProduct = Product::where('main_supplier_product_id', $product['id'])
                                        ->where('main_supplier_id', 4)
                                        ->first();  

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->quantity = (int) $product['pcs'];
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product['price'];
                        $updateProduct->original_price = $product['price'];
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
        $storeCronJob->name = "Delticom tires (update)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }
    

    public function storeABSRims()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // $service_url = "https://www.abswheels.se/abs_api/falgarfeed/?user=absnetto&pass=13@55";
        // $result = $this->file_get_contents_curl($service_url);
        // $JSONResponse = $this->file_get_contents_curl($service_url);
        // $searchData = json_decode($JSONResponse);

        if (\App::environment('production')) {
            $username = env('API_SEARCH_USER');
            $password = env('API_SEARCH_PASS');
        } else {
            $username = 'ptest';
            $password = 'ptest';
        }

        $headers[] = 'Authorization: Basic ' .
        base64_encode($username.':'.$password);
        $headers[] = 'Content-Type: application/json';
        $host = "https://slimapi.abswheels.se/getProducts/Rims/";

        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $apiResponse = json_decode($apiResponse, true);
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";

        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        $counter['created'] = 0;
        $counter['updated'] = 0;
        $counter['deleted'] = 0;
        \DB::disableQueryLog();

        // dd($apiResponse['data']);
        
        if($apiResponse['status'] == "Ok") {
            $products = $apiResponse['data'];
            // dd($products);
        
            $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated) {
                foreach (array_chunk($products, 200) as $productChunk) {

                    foreach($productChunk as $product) {
                        $productType = 4;

                        if(empty($product['marke']) || empty($product['modell']) || empty($product['bredd']) || empty($product['tum']) || empty($product['boremax']) || $product['pris'] < 50 )
                            continue;
                        // if($product['category-name'] == "Aluminiumfälg")
                        //     $productType = 4;
                        // if($product['category-name'] == "Stålfälg")
                        //     $productType = 5;
                        // if($product['category-name'] == "Plåtfälg")
                        //     $productType = 6;
                        // $productName = $product->name ? $product->name : $product->modelName;
                        
                        // $productName = $product['marke']." ".$product['modell']." ".$product['tum']."x".$product['bredd']." ET:".$product['et']." ".$product['boremax']." ".$product['color'];

                        if(isset($product['pcd'][7])) {
                            if($product['pcd'][7] == '5x120.6' )
                                $product['pcd'][7] = '5x120.65';
                        }


                        $deliveryTime = !empty($product['delivery_days']) ? $product['delivery_days'] : '5-7 dagars leveranstid';

                        if(strpos($product['ben'], 'Blank')) {
                            $deliveryTime = '10-14 dagars leveranstid';                            
                        } 
                        elseif( strpos($product['ben'], 'Japan Racing') !== false ) {
                            $deliveryTime = '6-8 dagars leveranstid';                            
                        }

                        // if($product['isnetto']) {
                        //     $price = $product['lager_pris'];
                        // } else {
                            $price = $product['pris'] * 0.8;
                        // }

                        $productDimension = $product['bredd'].'x'.$product['tum'];

                        // $updateProduct = Product::where('main_supplier_product_id', $product['id'])
                        //                 ->where('main_supplier_id', 2)
                        //                 ->first();
                        
                         if( $product['marke'] == 'ABS') {
                            $productBrand = trim($product['marke']);
                            $productModel = $product['modell'];
                        } else {
                            $productBrand = trim($product['modell']);
                            $productModel = $product['marke'];
                        }
                        $updateProduct = Product::where('main_supplier_product_id', $product['id'])
                            ->where('main_supplier_id', 2)
                            ->where('product_type_id', $productType)
                            ->first();

                        $calculatedPrice = $price * 1.25;

                        if(sizeOf($updateProduct) > 0) {
                            $updateProduct->product_brand = $product['marke'];
                            $updateProduct->product_model = $product['modell'];
                            $updateProduct->product_name = $product['ben'];
                            $updateProduct->quantity = $product['quantity'];
                            $updateProduct->et = $product['et'];
                            $updateProduct->et_min = $product['min_et'];
                            $updateProduct->bore_max = $product['boremax'];
                            $updateProduct->product_dimension = $productDimension;
                            $updateProduct->pcd1 = isset($product['pcd'][0]) ? strtolower($product['pcd'][0]) : null;
                            $updateProduct->pcd2 = isset($product['pcd'][1]) ? strtolower($product['pcd'][1]) : null;
                            $updateProduct->pcd3 = isset($product['pcd'][2]) ? strtolower($product['pcd'][2]) : null;
                            $updateProduct->pcd4 = isset($product['pcd'][3]) ? strtolower($product['pcd'][3]) : null;
                            $updateProduct->pcd5 = isset($product['pcd'][4]) ? strtolower($product['pcd'][4]) : null;
                            $updateProduct->pcd6 = isset($product['pcd'][5]) ? strtolower($product['pcd'][5]) : null;
                            $updateProduct->pcd7 = isset($product['pcd'][6]) ? strtolower($product['pcd'][6]) : null;
                            $updateProduct->pcd8 = isset($product['pcd'][7]) ? strtolower($product['pcd'][7]) : null;
                            $updateProduct->pcd9 = isset($product['pcd'][8]) ? strtolower($product['pcd'][8]) : null;
                            $updateProduct->pcd10 = isset($product['pcd'][9]) ? strtolower($product['pcd'][9]) : null;
                            $updateProduct->pcd11 = isset($product['pcd'][10]) ? strtolower($product['pcd'][10]) : null;
                            $updateProduct->pcd12 = isset($product['pcd'][11]) ? strtolower($product['pcd'][11]) : null;
                            $updateProduct->pcd13 = isset($product['pcd'][12]) ? strtolower($product['pcd'][12]) : null;
                            $updateProduct->pcd14 = isset($product['pcd'][13]) ? strtolower($product['pcd'][13]) : null;
                            $updateProduct->pcd15 = isset($product['pcd'][14]) ? strtolower($product['pcd'][14]) : null;

                            //Alla priser är exklusive moms
                            $updateProduct->calculated_price = $calculatedPrice;
                            $updateProduct->price = $price;
                            $updateProduct->original_price = $product['originalpris'];
                            // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                            $updateProduct->delivery_time = $deliveryTime;
                            $updateProduct->is_deleted = 0;
                            $updateProduct->save();
                            $updateProduct->touch();
                            $countUpdated++;

                            continue;

                            $existImg = ProductImage::where('product_id', $updateProduct->id)->get(); 
                            $url = $product['imagelarge'];
                            $url = str_replace(' ', '%20', $url);
                            $file = $this->file_get_contents_curl($url);
                            $fileName = basename($url);

                            if( empty($product['imagelarge']) || 
                                // $product['imagelarge'] == "https://www.abswheels.se/img/falgar/Noproduct_image.jpg" ||
                                $fileName == "Noproduct_image.jpg" ||
                                sizeOf($existImg) <= 0) {

                                foreach ($existImg as $img) {
                                    $img->delete();
                                }
                                 $insertProductImage = new ProductImage();
                                 $insertProductImage->product_id = $updateProduct->id;
                                 $insertProductImage->name = "Noproduct.jpg";
                                 $insertProductImage->path = "images/product/Noproduct.jpg";
                                 $insertProductImage->thumbnail_path = "images/product/Noproduct.jpg";
                                 $insertProductImage->priority = 1;
                                 $insertProductImage->save(); 
                                 continue;
                            }


                            // $url = $product['imagelarge'];
                            // $url = str_replace(' ', '%20', $url);
                            // $file = $this->file_get_contents_curl($url);
                            // $fileName = basename($url);
                            // $path = 'images/product/abs_rims/'.$fileName;
                            // $thumbnail_path = 'images/product/abs_rims/thumb/tn-'.$fileName;
                            // $absolute_path = public_path($path);
                            // $absolute_thumbnail_path = public_path($thumbnail_path);

                            // if (!is_dir('images/product/abs_rims/')) {
                            //     // dir doesn't exist, make it
                            //     mkdir('images/product/abs_rims/');
                            //     mkdir('images/product/abs_rims/thumb/');
                            // }

                            // $existImg = ProductImage::where('name', $fileName)->get(); 
                            // if(sizeof($existImg) === 0) {
                            //     $existImg = file_put_contents($path, $file);
                            // }

                            // if($existImg && exif_imagetype($path)) {
                            //     Image::make($path)->resize(600, 600)->save();
                            //     Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);

                            //     $insertProductImage = new ProductImage();
                            //     $insertProductImage->product_id = $updateProduct->id;
                            //     $insertProductImage->name = $fileName;
                            //     $insertProductImage->path = $path;
                            //     $insertProductImage->thumbnail_path = $thumbnail_path;
                            //     $insertProductImage->priority = 1;
                            //     $insertProductImage->save();
                            // } else {
                            //     File::delete($path);

                            //     $insertProductImage = new ProductImage();
                            //     $insertProductImage->product_id = $updateProduct->id;
                            //     $insertProductImage->name = "noRimImg.jpg";
                            //     $insertProductImage->path = "images/product/noRimImg.jpg";
                            //     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                            //     $insertProductImage->priority = 1;
                            //     $insertProductImage->save(); 
                            // }
                            // continue;

                            // Image::make($path)->resize(600, 600)->save();
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
                        $profitId = 1;
                        $insertProduct = new Product();

                        $insertProduct->product_category_id = 2;
                        $insertProduct->product_type_id = $productType;
                        $insertProduct->profit_id = 1;
                        $insertProduct->main_supplier_id = 2;
                        $insertProduct->main_supplier_product_id = $product['id'];
                        // $insertProduct->EANCode = $product->eanCode;
                        $insertProduct->product_brand = $productBrand;
                        $insertProduct->product_model = $productModel;
                        $insertProduct->product_description = $product['ben'];
                        $insertProduct->product_name = $product['ben'];
                        $insertProduct->product_code = $product['ben'];
                        $insertProduct->product_dimension = $productDimension;
                        $insertProduct->calculated_price = $calculatedPrice;
                        $insertProduct->price = $price;
                        $insertProduct->original_price = $product['originalpris'];
                        $insertProduct->quantity = $product['quantity'];
                        // $insertProduct->ProductDimension = (int) $product->dimension;
                        $insertProduct->product_width = $product['bredd'];
                        // $insertProduct->product_profile = (int) $product->aspectRatio;
                        $insertProduct->product_inch = $product['tum'];
                        $insertProduct->et = $product['et'];
                        $insertProduct->bore_max = $product['boremax'];
                        $insertProduct->pcd1 = isset($product['pcd'][0]) ? strtolower($product['pcd'][0]) : null;
                        $insertProduct->pcd2 = isset($product['pcd'][1]) ? strtolower($product['pcd'][1]) : null;
                        $insertProduct->pcd3 = isset($product['pcd'][2]) ? strtolower($product['pcd'][2]) : null;
                        $insertProduct->pcd4 = isset($product['pcd'][3]) ? strtolower($product['pcd'][3]) : null;
                        $insertProduct->pcd5 = isset($product['pcd'][4]) ? strtolower($product['pcd'][4]) : null;
                        $insertProduct->pcd6 = isset($product['pcd'][5]) ? strtolower($product['pcd'][5]) : null;
                        $insertProduct->pcd7 = isset($product['pcd'][6]) ? strtolower($product['pcd'][6]) : null;
                        $insertProduct->pcd8 = isset($product['pcd'][7]) ? strtolower($product['pcd'][7]) : null;
                        $insertProduct->pcd9 = isset($product['pcd'][8]) ? strtolower($product['pcd'][8]) : null;
                        $insertProduct->pcd10 = isset($product['pcd'][9]) ? strtolower($product['pcd'][9]) : null;
                        $insertProduct->pcd11 = isset($product['pcd'][10]) ? strtolower($product['pcd'][10]) : null;
                        $insertProduct->pcd12 = isset($product['pcd'][11]) ? strtolower($product['pcd'][11]) : null;
                        $insertProduct->pcd13 = isset($product['pcd'][12]) ? strtolower($product['pcd'][12]) : null;
                        $insertProduct->pcd14 = isset($product['pcd'][13]) ? strtolower($product['pcd'][13]) : null;
                        $insertProduct->pcd15 = isset($product['pcd'][14]) ? strtolower($product['pcd'][14]) : null;
                        $insertProduct->priority_supplier = 2;
                        $insertProduct->delivery_time = $deliveryTime;
                        // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                        $insertProduct->is_shown = 1;
                        // $insertProduct->storage_location = $product->locationId;
                        // $insertProduct->sub_supplier_id = $product->supplierId;
                        $insertProduct->save();
                        $countCreated++;

                        if(empty($product['imagelarge']) || $product['imagelarge'] == "https://www.abswheels.se/img/falgar/Noproduct_image.jpg") {
                             $insertProductImage = new ProductImage();
                             $insertProductImage->product_id = $insertProduct->id;
                             $insertProductImage->name = "Noproduct.jpg";
                             $insertProductImage->path = "images/product/Noproduct.jpg";
                             $insertProductImage->thumbnail_path = "images/product/Noproduct.jpg";
                             $insertProductImage->priority = 1;
                             $insertProductImage->save(); 
                             continue;
                        }
                        $url = $product['imagelarge'];
                        $url = str_replace(' ', '%20', $url);

                        // $arrContextOptions=array(
                        //     "ssl"=>array(
                        //         "verify_peer"=>false,
                        //         "verify_peer_name"=>false,
                        //     ),
                        // );  
                        // $file = file_get_contents($url, false, stream_context_create($arrContextOptions));

                        $fileName = basename($url);
                        $path = 'images/product/abs_rims/'.$fileName;
                        $thumbnail_path = 'images/product/abs_rims/thumb/tn-'.$fileName;
                        $absolute_path = public_path($path);
                        $absolute_thumbnail_path = public_path($thumbnail_path);
                        
                        if(!file_exists($absolute_path)) {
                            $file = $this->file_get_contents_curl($url);
                            if (!is_dir('images/product/abs_rims/')) {
                                // dir doesn't exist, make it
                                mkdir('images/product/abs_rims/');
                                mkdir('images/product/abs_rims/thumb/');
                            }

                            $existImg = file_put_contents($path, $file);

                            if($existImg && exif_imagetype($path)) {
                                Image::make($path)->resize(600, 600)->save();
                                Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);

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

                                $insertProductImage = new ProductImage();
                                $insertProductImage->product_id = $insertProduct->id;
                                $insertProductImage->name = "Noproduct.jpg";
                                $insertProductImage->path = "images/product/Noproduct.jpg";
                                $insertProductImage->thumbnail_path = "images/product/Noproduct.jpg";
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
            });//End DB transaction
        }

        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 2)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1
                        ]);

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "ABS rims (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }
    
    public function updateABSRims()
    {
        // DB::table('products')->truncate();
        // DB::table('productImages')->truncate();

        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // $service_url = "https://www.abswheels.se/abs_api/falgarfeed/?user=absnetto&pass=13@55";
        // $result = $this->file_get_contents_curl($service_url);
        // $products = json_decode($result, true);$username = 'ptest';
        
        if (\App::environment('production')) {
            $username = env('API_SEARCH_USER');
            $password = env('API_SEARCH_PASS');
        } else {
            $username = 'ptest';
            $password = 'ptest';
        }
        
        $headers[] = 'Authorization: Basic ' .
        base64_encode($username.':'.$password);
        $headers[] = 'Content-Type: application/json';
        $host = "https://slimapi.abswheels.se/getProducts/Rims/";

        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $apiResponse = json_decode($apiResponse, true);
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";

        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($apiResponse['data']);
        
        if($apiResponse['status'] == "Ok") {
            $products = $apiResponse['data'];
        
            $counter = DB::transaction(function() use ($products, $countUpdated) {
                foreach (array_chunk($products, 200) as $productChunk) {

                    foreach($productChunk as $product) {

                        // $updateProduct = Product::where('main_supplier_product_id', $product['id'])
                        //                 ->where('main_supplier_id', 2)
                        //                 ->first();
                        
                        if( $product['marke'] == 'ABS') {
                            $productBrand = trim($product['marke']);
                            $productModel = $product['modell'];
                        } else {
                            $productBrand = trim($product['modell']);
                            $productModel = $product['marke'];
                        }

                        $updateProduct = Product::from(DB::raw('products USE INDEX (main_index)'))
                                        ->where('main_supplier_product_id', $product['id'])
                                        ->where('main_supplier_id', 2)
                                        ->where('product_type_id', 4)
                                        ->where('product_brand', $productBrand )
                                        ->where('product_width', $product['bredd'])
                                        ->where('product_inch', $product['tum'])
                                        ->first(['quantity', 'price', 'original_price']);

                        // if($product['isnetto']) {
                        //     $price = $product['lager_pris'];
                        // } else {
                            $price = $product['pris'] * 0.8;
                        // }

                        if(sizeOf($updateProduct) > 0) {
                            // $updateProduct->product_name = $product['ben'];
                            $updateProduct->quantity = $product['quantity'];
                            $updateProduct->price = $price;
                            $updateProduct->original_price = $product['originalpris'];
                            $updateProduct->save();
                            $updateProduct->touch();
                            $countUpdated++;
                        }
                    }
                }
                return ['updated' => $countUpdated];
            });
        }
 
        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "ABS rims (update)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

    public function storeABSTires(Request $request)
    {    
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);

        if (\App::environment('production')) {
            $username = env('API_SEARCH_USER');
            $password = env('API_SEARCH_PASS');
        } else {
            $username = 'ptest';
            $password = 'ptest';
        }
        
        $headers[] = 'Authorization: Basic ' .
        base64_encode($username.':'.$password);
        $headers[] = 'Content-Type: application/json';
        $host = "https://slimapi.abswheels.se/getProducts/Tires/";

        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $apiResponse = json_decode($apiResponse, true);

        $updatedDate = date("Y-m-d H:i:s");
        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        $counter['created'] = 0;
        $counter['updated'] = 0;
        $counter['deleted'] = 0;
        \DB::disableQueryLog();

        // dd($apiResponse );

        if($apiResponse['status'] == "Ok") {
            $products = $apiResponse['data'];
            
            // dd($products);

            $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated) {
                foreach (array_chunk($products, 200) as $productChunk) {
                    // dd($products);
                    foreach($productChunk as $product) {
                        if($product['category_id'] == 6 )
                            continue;
                        
                        if(empty($product['bredd']) && empty($product['profil']) && empty($product['tum']))
                            continue;

                        $deliveryTime = !empty($product['delivery_days']) ? $product['delivery_days'] : '5-7 dagars leveranstid';


                        if($product['isnetto']) {
                            $price = $product['lager_pris'];
                        } else {
                            $price = $product['pris'] * 0.8;
                        }

                        if($product['tum'] == 12 && $product['profil'] >= 70) 
                            $price += 64;
                        
                        if($product['tum'] == 13 && $product['profil'] >= 65) 
                            $price += 64;

                        if($product['tum'] == 14 && $product['profil'] >= 65) 
                            $price += 64;

                        if($product['tum'] == 15 && $product['profil'] >= 70) 
                            $price += 64;


                        if($product['tum'] == 16 && $product['profil'] >= 65) 
                            $price += 80;
                        
                        if($product['tum'] == 17 && $product['profil'] >= 55) 
                            $price += 80;

                        if($product['tum'] == 18 && $product['profil'] >= 45) 
                            $price += 80;

                        if($product['tum'] == 19 && $product['profil'] >= 40) 
                            $price += 80;

                        if($product['tum'] == 20 && $product['profil'] >= 40) 
                            $price += 80;

                        // $updateProduct = Product::where('main_supplier_product_id', $product['id'])
                        //                 ->where('main_supplier_id', 3)
                        //                 ->first();


                        if($product['category_id'] == 3) {
                            $productType = 1;
                            $profitProductType = 1;
                        }

                        if($product['category_id'] == 4) {
                            $productType = 3;
                            $profitProductType = 2;
                        }

                        if($product['category_id'] == 5) {
                            $productType = 2;
                            $profitProductType = 2;
                        }


                        $profitId = 1;
                        if($product['isnetto']) {
                            if($product['tum'] < 12) {
                                $profit = Profit::where('size', -1)
                                            ->where('product_type', $profitProductType)
                                            ->first(['id', 'in_cash']);
                            } elseif ($product['tum'] > 22 || $this->isDecimal($product['tum'])) {
                                $profit = Profit::where('size', 1)
                                            ->where('product_type', $profitProductType)
                                            ->first(['id', 'in_cash']);
                            } else {
                                $profit = Profit::where('size', $product['tum'])
                                            ->where('product_type', $profitProductType)
                                            ->first(['id', 'in_cash']);
                            }
                            $profitId = $profit->id;
                        }
                        $calculatedPrice = round( ($price + $profit->in_cash) * 1.25 );
                        // dd($calculatedPrice, $price, $profit->in_cash);





                        $updateProduct = Product::from(DB::raw('products USE INDEX (main_index)'))
                                        ->where('main_supplier_product_id', $product['id'])
                                        ->where('main_supplier_id', 3)
                                        ->where('product_type_id', $productType)
                                        ->where('product_brand', $product['marke'])
                                        ->where('product_width', $product['bredd'])
                                        ->where('product_inch', $product['tum'])
                                        ->first(['id', 'quantity', 'price', 'original_price', 'tire_manufactor_date', 'delivery_time', 'is_deleted']);

                        if(sizeOf($updateProduct) > 0) {
                            $updateProduct->quantity = $product['quantity'];
                            //Alla priser är exklusive moms
                            $updateProduct->calculated_price = $calculatedPrice;
                            $updateProduct->price = $price;
                            $updateProduct->original_price = $product['originalpris'];
                            $updateProduct->tire_manufactor_date = $product['tillv'];
                            // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                            $updateProduct->delivery_time = "5-10 dagars leveranstid";
                            // $updateProduct->is_shown = 1;
                            $updateProduct->is_deleted = 0;
                            $updateProduct->save();
                            $updateProduct->touch();
                            $countUpdated++;

                            continue;

                            $existImg = ProductImage::where('product_id', $updateProduct->id)->get(); 
                            $url = $product['imagelarge'];
                            $url = str_replace(' ', '%20', $url);
                            $file = $this->file_get_contents_curl($url);
                            $fileName = basename($url);

                            if( empty($product['imagelarge']) || 
                                // $product['imagelarge'] == "https://www.abswheels.se/img/falgar/Noproduct_image.jpg" ||
                                $fileName == "Noproduct_image.jpg" ||
                                sizeOf($existImg) <= 0) {

                                foreach ($existImg as $img) {
                                    $img->delete();
                                }
                                 $insertProductImage = new ProductImage();
                                 $insertProductImage->product_id = $updateProduct->id;
                                 $insertProductImage->name = "Noproduct.jpg";
                                 $insertProductImage->path = "images/product/Noproduct.jpg";
                                 $insertProductImage->thumbnail_path = "images/product/Noproduct.jpg";
                                 $insertProductImage->priority = 1;
                                 $insertProductImage->save(); 
                                 continue;
                            }

                            // if(empty($product['imagelarge']))
                            //     continue;

                            // $url = $product['imagelarge'];
                            // $file = file_get_contents($url);
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

                        // if($product['category_id'] == 3) {
                        //     $productType = 1;
                        //     $profitProductType = 1;
                        // }

                        // if($product['category_id'] == 4) {
                        //     $productType = 3;
                        //     $profitProductType = 2;
                        // }

                        // if($product['category_id'] == 5) {
                        //     $productType = 2;
                        //     $profitProductType = 2;
                        // }

                        // $profitId = 1;

                        // if($product['isnetto']) {
                        //     if($product['tum'] < 12) {
                        //         $profit = Profit::where('size', -1)
                        //                     ->where('product_type', $profitProductType)
                        //                     ->first(['id']);
                        //     } elseif ($product['tum'] > 22 || $this->isDecimal($product['tum'])) {
                        //         $profit = Profit::where('size', 1)
                        //                     ->where('product_type', $profitProductType)
                        //                     ->first(['id']);
                        //     } else {
                        //         $profit = Profit::where('size', $product['tum'])
                        //                     ->where('product_type', $profitProductType)
                        //                     ->first(['id']);
                        //     }
                        //     $profitId = $profit->id;
                        // }

                        $labelArr = explode('-', $product['tire_label']);
                        // $deliveryDate = $this->ExcelToPHP($product['next_delivery']);
                        
        // try {           


                        $insertProduct = new Product();
                        $insertProduct->product_category_id = 1;
                        $insertProduct->product_type_id = $productType;
                        $insertProduct->profit_id = $profitId;
                        $insertProduct->main_supplier_id = 3;
                        $insertProduct->main_supplier_product_id = $product['id'];
                        $insertProduct->product_description = $product['ben'];
                        $insertProduct->product_brand = trim($product['marke']);
                        $insertProduct->product_model = $product['modell'];
                        $insertProduct->product_name = $product['ben'];
                        $insertProduct->product_code = $product['ben'];
                        $insertProduct->quantity = $product['quantity'];
                        $insertProduct->product_dimension = (int) $product['bredd'].'/'.(int) $product['profil'].'R'. (int) $product['tum'];
                        $insertProduct->product_width = (int) $product['bredd'];
                        $insertProduct->product_profile = (int) $product['profil'];
                        $insertProduct->product_inch = (int) $product['tum'];
                        $insertProduct->load_index = $product['loadindex'];
                        $insertProduct->speed_index = $product['speedindex'];
                        $insertProduct->is_runflat = $product['runflat'];
                        $insertProduct->is_ctyre = $product['cdack'];
                        $insertProduct->product_label = $product['tire_label'];
                        $insertProduct->rolling_resistance = isset($labelArr[0]) ? $labelArr[0] : "";
                        $insertProduct->wet_grip = isset($labelArr[1]) ? $labelArr[1] : "";
                        $insertProduct->noise_emission_rating = isset($labelArr[2]) ? $labelArr[2] : "";
                        $insertProduct->noise_emission_decibel = isset($labelArr[3]) ? $labelArr[3] : "";
                        // $insertProduct->sub_supplier_id = $product->supplierId;
                        //Alla priser är exklusive moms
                        $insertProduct->calculated_price = $calculatedPrice;
                        $insertProduct->price = $price;
                        $insertProduct->original_price = $product['originalpris'];
                        $insertProduct->tire_manufactor_date = $product['tillv'];
                        // $insertProduct->storage_price = $product->cost;
                        // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                        $insertProduct->delivery_time = $deliveryTime;
                        $insertProduct->is_shown = 1;
                        $insertProduct->save();
                        $countCreated++;
                        // $productID = DB::getPdo()->lastInsertId();
                        

                        if(empty($product['imagelarge']) || $product['imagelarge'] == 'https://www.abswheels.se/img/dack/-.jpg' || $product['imagelarge'] == "https://www.abswheels.se/img/dack/Noproduct_image.jpg") {
                            $insertProductImage = new ProductImage();
                            $insertProductImage->product_id = $insertProduct->id;
                            $insertProductImage->name = "Noproduct.jpg";
                            $insertProductImage->path = "images/product/Noproduct.jpg";
                            $insertProductImage->thumbnail_path = "images/product/Noproduct.jpg";
                            $insertProductImage->priority = 1;
                            $insertProductImage->save(); 
                            continue;
                        }

                        $url = $product['imagelarge'];
                        $url = str_replace(' ', '%20', $url);
                        // $file = file_get_contents($url);
                        $file = $this->file_get_contents_curl($url);
                        $fileName = basename($url);
                        $path = 'images/product/abs_tires/'.$fileName;
                        $thumbnail_path = 'images/product/abs_tires/thumb/tn-'.$fileName;
                        $absolute_path = public_path($path);
                        $absolute_thumbnail_path = public_path($thumbnail_path);
                        // $existImg = ProductImage::where('name', $fileName)->get();
                        $existImg = Storage::disk('public')->exists($path);
                        // dd($fileName, $url);
                        
                        
                        // dd($profit, $productType);

                        if(!file_exists($absolute_path)) {
                            if (!is_dir('images/product/abs_tires/')) {
                                // dir doesn't exist, make it
                                mkdir('images/product/abs_tires/');
                                mkdir('images/product/abs_tires/thumb/');
                            }

                            // if(sizeof($existImg) === 0) {
                            // if(!$existImg) {
                            //     $existImg = file_put_contents($path, $file);
                            // }

                            // Image::make($path)->resize(600, 600)->save();
                            

                            $existImg = file_put_contents($path, $file);

                            if($existImg && exif_imagetype($path)) {
                                Image::make($path)->resize(600, 600)->save();

                                if($product['imagelarge'] == $product['imagesmall'] || empty($product['imagesmall'])) {
                                    Image::make($path)->save($absolute_thumbnail_path);
                                } else {
                                    Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);
                                }

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

                                $insertProductImage = new ProductImage();
                                $insertProductImage->product_id = $insertProduct->id;
                                $insertProductImage->name = "Noproduct.jpg";
                                $insertProductImage->path = "images/product/Noproduct.jpg";
                                $insertProductImage->thumbnail_path = "images/product/Noproduct.jpg";
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
        }
        
         $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 3)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1
                        ]);       

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "ABS tires (store)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
        // insert into CronJobStatus params: name, response, deleted_products, begin_date
    }

    public function updateABSTires()
    {
        // DB::table('products')->truncate();
        // DB::table('productImages')->truncate();

        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // $service_url = "https://www.abswheels.se/abs_api/falgarfeed/?user=absnetto&pass=13@55";
        // $result = $this->file_get_contents_curl($service_url);
        // $products = json_decode($result, true);$username = 'ptest';
        
        if (\App::environment('production')) {
            $username = env('API_SEARCH_USER');
            $password = env('API_SEARCH_PASS');
        } else {
            $username = 'ptest';
            $password = 'ptest';
        }
        
        $headers[] = 'Authorization: Basic ' .
        base64_encode($username.':'.$password);
        $headers[] = 'Content-Type: application/json';
        $host = "https://slimapi.abswheels.se/getProducts/Tires/";

        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $apiResponse = json_decode($apiResponse, true);
        $updatedDate = date("Y-m-d H:i:s");
        $response = "";

        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($apiResponse['data']);
        
        if($apiResponse['status'] == "Ok") {
            $products = $apiResponse['data'];
        
            $counter = DB::transaction(function() use ($products, $countUpdated) {
                foreach (array_chunk($products, 200) as $productChunk) {

                    foreach($productChunk as $product) {

                        // $updateProduct = Product::where('main_supplier_product_id', $product['id'])
                        //                 ->where('main_supplier_id', 3)
                        //                 ->first();

                        if($product['category_id'] == 3) {
                            $productType = 1;
                            $profitProductType = 1;
                        }

                        if($product['category_id'] == 4) {
                            $productType = 3;
                            $profitProductType = 2;
                        }

                        if($product['category_id'] == 5) {
                            $productType = 2;
                            $profitProductType = 2;
                        }

                        $updateProduct = Product::from(DB::raw('products USE INDEX (main_index)'))
                                        ->where('main_supplier_product_id', $product['id'])
                                        ->where('main_supplier_id', 3)
                                        ->where('product_type_id', $productType)
                                        ->where('product_brand', $product['marke'])
                                        ->where('product_width', $product['bredd'])
                                        ->where('product_inch', $product['tum'])
                                        ->first(['quantity', 'price', 'original_price']);

                        if($product['isnetto']) {
                            $price = $product['lager_pris'];
                        } else {
                            $price = $product['pris'] * 0.8;
                        }

                        if(sizeOf($updateProduct) > 0) {
                            // $updateProduct->profit_id = 1;
                            // $updateProduct->product_name = $product['ben'];
                            $updateProduct->quantity = $product['quantity'];
                            $updateProduct->price = $price;
                            $updateProduct->original_price = $product['originalpris'];
                            $updateProduct->save();
                            $updateProduct->touch();
                            $countUpdated++;
                        }
                    }
                }
                return ['updated' => $countUpdated];
            });
        }
 
        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "ABS tires (update)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }


    public function getImadProductsFile()
    {
        // return storage_path('exports') . '/dinadack_dackline.csv';
        // return storage_path('exports') . '/abs_wheels_imad.csv';
        return storage_path('exports') . '/imad_produkter.csv';
    }


    public function storeImadProducts()
    {
        dd('error');
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date('Y-m-d H:i:s');
        $response = "";


        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        Excel::selectSheets()->filter('chunk')->load($this->getImadProductsFile())->chunk(250, function($data) use ($countCreated, $countUpdated) {
            // dd($data->toArray());
            // $counter = 0;

            foreach ($data as $product) {
                // $counter++;
                // if($counter < 100) {
                //     continue;
                // }
                // else {
                //     // $boremax = round($product['bore_max'], 1);
                //     $boremax = number_format($product['bore_max'], 1);
                //     // $boremax = (string) $product['bore_max'];
                //     // $boremax = (int) $product['bore_max'];
                //     dd($product, $boremax, gettype($boremax));
                // }


                if(empty($product['article_number'])) {
                        $product['article_number'] = 'autogen'.rand(10000000, 99999999);
                }

                if($product['product_type_id'] >= 1 && $product['product_type_id'] <= 6) {
                    
                    $productName =  "";
                    $productLabel =  "";
                    $productDimension = "";
                    $productType = 1;
                    $productCategoryId = 1;

                    if(empty($product['price'])) {
                        $product['price'] = $product['storage_price'];
                    }

                    $boreMax = number_format($product['bore_max'], 1);
                    // $product['bore_max'] = (float) $product['bore_max'];


                    if($product['product_type_id'] >= 1 && $product['product_type_id'] <= 3) {
                        if(empty($product['product_brand']) || empty($product['product_model']) || empty($product['product_width']) || empty($product['product_profile']) || empty($product['product_inch']) || $product['price'] < 50 )
                            continue;

                        $productCategoryId = 1;
                        $productType = $product['product_type_id'] == 1 ? 1 : 2;
                        $productDimension = $product['product_width']."/".$product['product_profile']."R".$product['product_inch'];

                        $productLabel = $product['rolling_resistance']."-".$product['wet_grip']."-".$product['noise_emission_rating']."-".$product['noise_emission_decibel'];

                        $productName = $product['product_brand']." ".$product['product_model']." ".$product['product_width']."/".$product['product_profile']."R".$product['product_inch']." ".$product['load_index'].$product['speed_index'];
                    }

                    if($product['product_type_id'] == 4 ) {
                        if(empty($product['product_brand']) || empty($product['product_model']) || empty($product['product_width']) || empty($product['et']) || empty($product['product_inch']) || empty($product['bore_max']) || $product['price'] < 50 )
                            continue;

                        $productCategoryId = 2;
                        $productType = 3;
                        $productDimension = $product['product_width']."x".$product['product_inch'];

                        $productName = $product['product_brand']." ".$product['product_model']." ".$product['product_width']."x".$product['product_inch']." ET:".$product['et']." ".$boreMax." ".$product['product_color'];
                    }

                    $updateProduct = Product::where('main_supplier_product_id', $product['article_number'])
                                    ->where('main_supplier_id', 5)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->profit_id = 1;
                        $updateProduct->quantity = $product['quantity'];
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product['price'];
                        $updateProduct->original_price = $product['original_price'];
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->delivery_time = $product['delivery_time'];
                        $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;

                        continue;
                    }
                    
                    
                    if($product['product_inch'] < 12) {
                        $profit = Profit::where('size', -1)
                                    ->where('product_type', $productType)
                                    ->first(['id']);
                    } elseif ($product['product_inch'] > 22 || $this->isDecimal($product['product_inch'] )) {
                        $profit = Profit::where('size', 1)
                                    ->where('product_type', $productType)
                                    ->first(['id']);
                    } else {
                        $profit = Profit::where('size', $product['product_inch'])
                                    ->where('product_type', $productType)
                                    ->first(['id']);
                    }

                    $insertProduct = new Product();

                    $insertProduct->product_category_id = $productCategoryId;
                    $insertProduct->product_type_id = $product['product_type_id'];
                    $insertProduct->profit_id = $profit->id;
                    $insertProduct->main_supplier_id = 5;
                    $insertProduct->main_supplier_product_id = $product['article_number'];
                    $insertProduct->ean = $product['ean'];
                    $insertProduct->product_description = $product['product_description'];
                    $insertProduct->product_brand = $product['product_brand'];
                    $insertProduct->product_name = $productName;
                    $insertProduct->product_model = $product['product_model'];
                    $insertProduct->product_code = $productName;
                    $insertProduct->product_color = $product['product_color'];
                    $insertProduct->product_dimension = $productDimension;
                    $insertProduct->product_width = $product['product_width'];
                    $insertProduct->product_profile = $product['product_profile'];
                    $insertProduct->product_inch = $product['product_inch'];
                    $insertProduct->et = $product['et'];
                    $insertProduct->storage_et = $product['storage_et'];
                    $insertProduct->product_label = $productLabel;
                    $insertProduct->rolling_resistance = $product['rolling_resistance'];
                    $insertProduct->wet_grip = $product['wet_grip'];
                    $insertProduct->noise_emission_rating = $product['noise_emission_rating'];
                    $insertProduct->noise_emission_decibel = $product['noise_emission_decibel'];
                    $insertProduct->load_index = $product['load_index'];
                    $insertProduct->speed_index = $product['speed_index'];
                    $insertProduct->is_ctyre = $product['is_ctyre'];
                    $insertProduct->is_runflat = $product['is_runflat'];
                    $insertProduct->bore_max = $product['bore_max'];
                    $insertProduct->bore_min = $product['bore_min'];
                    $insertProduct->pcd1 = $this->formatPCD($product['pcd1']);
                    $insertProduct->pcd2 = $this->formatPCD($product['pcd2']);
                    $insertProduct->pcd3 = $this->formatPCD($product['pcd3']);
                    $insertProduct->pcd4 = $this->formatPCD($product['pcd4']);
                    $insertProduct->pcd5 = $this->formatPCD($product['pcd5']);
                    $insertProduct->pcd6 = $this->formatPCD($product['pcd6']);
                    $insertProduct->pcd7 = $this->formatPCD($product['pcd7']);
                    $insertProduct->pcd8 = $this->formatPCD($product['pcd8']);
                    $insertProduct->pcd9 = $this->formatPCD($product['pcd9']);
                    $insertProduct->storage_pcd = $product['storage_pcd'];
                    $insertProduct->storage_location = $product['storage_location'];
                    $insertProduct->price = $product['price'];
                    $insertProduct->original_price = $product['original_price'];
                    $insertProduct->storage_price = $product['storage_price'];
                    $insertProduct->quantity = $product['quantity'];
                    $insertProduct->discount1 = $product['discount1'];
                    $insertProduct->discount2 = $product['discount2'];
                    $insertProduct->discount3 = $product['discount3'];
                    $insertProduct->discount4 = $product['discount4'];
                    $insertProduct->priority_supplier = 1;
                    $insertProduct->tire_manufactor_date = $product['tire_manufactorer_date'];
                    $insertProduct->delivery_time = $product['delivery_time'];
                    $insertProduct->is_shown = 1;
                    $insertProduct->save();
                    $countCreated++;


                    if($product['product_type_id'] == 4) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = "noRimImg.jpg";
                        $insertProductImage->path = "images/product/noRimImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                        continue;
                    } else {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = "noTireImg.jpg";
                        $insertProductImage->path = "images/product/noTireImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                        continue;
                    }
                }

                if($product['product_type_id'] == 10) {
                    
                    // $productName =  "";
                    // $productLabel =  "";
                    // $productType = 1;

                    // if(empty($product['article_number'])) {
                    //     $product['article_number'] = 'autogen'.rand(100000, 9999999);
                    // }

                    // if($product['product_type_id'] >= 1 && $product['product_type_id'] <= 3) {
                    //     if(empty($product['product_brand']) || empty($product['product_model']) || empty($product['product_width']) || empty($product['product_profile']) || empty($product['product_inch']) || $product['price'] < 50 )
                    //         continue;

                    //     $productType = $product['product_type_id'] == 1 ? 1 : 2;

                    //     $productLabel = $product['rolling_resistance']."-".$product['wet_grip']."-".$product['noise_emission_rating']."-".$product['noise_emission_decibel'];

                    //     $productName = $product['product_brand']." ".$product['product_model']." ".$product['product_width']."/".$product['product_profile']."R".$product['product_inch']." ".$product['load_index'].$product['speed_index'];
                    // }

                    // if($product['product_type_id'] == 3) {
                    //     if(empty($product['product_brand']) || empty($product['product_model']) || empty($product['product_width']) || empty($product['et']) || empty($product['product_inch']) || empty($product['boremax']) || $product['price'] < 50 )
                    //         continue;

                    //     $productType = 3;

                    //     $productName = $product['product_brand']." ".$product['product_model']." ".$product['product_inch']."x".$product['product_width']." ET:".$product['et']." ".$product['boremax']." ".$product['color'];
                    // }

                    $updateProduct = Product::where('main_supplier_product_id', $product['article_number'])
                                    ->where('main_supplier_id', 5)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->quantity = $product['quantity'];
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product['price'];
                        $updateProduct->original_price = $product['original_price'];
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->delivery_time = $product['delivery_time'];
                        $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;

                        continue;
                    }
                    
                    $insertProduct = new Product();

                    $insertProduct->product_category_id = 3;
                    $insertProduct->product_type_id = $product['product_type_id'];
                    $insertProduct->profit_id = 1;
                    $insertProduct->main_supplier_id = 5;
                    $insertProduct->main_supplier_product_id = $product['article_number'];
                    $insertProduct->ean = $product['ean'];
                    $insertProduct->product_description = $product['product_description'];
                    $insertProduct->product_brand = $product['product_brand'];
                    $insertProduct->product_name = $product['product_name'];
                    $insertProduct->product_code = 'Ringar';
                    $insertProduct->product_color = $product['product_color'];
                    $insertProduct->product_dimension = $product['product_dimension'];
                    $insertProduct->price = $product['price'];
                    $insertProduct->original_price = $product['original_price'];
                    $insertProduct->storage_price = $product['storage_price'];
                    $insertProduct->quantity = $product['quantity'];
                    $insertProduct->discount1 = $product['discount1'];
                    $insertProduct->discount2 = $product['discount2'];
                    $insertProduct->discount3 = $product['discount3'];
                    $insertProduct->discount4 = $product['discount4'];
                    $insertProduct->priority_supplier = 1;
                    $insertProduct->delivery_time = $product['delivery_time'];
                    $insertProduct->is_shown = 1;
                    $insertProduct->save();
                    $countCreated++;

                    $insertProductImage = new ProductImage();
                    $insertProductImage->product_id = $insertProduct->id;
                    $insertProductImage->name = "rings.jpg";
                    $insertProductImage->path = "images/product/accessories/rings.jpg";
                    $insertProductImage->thumbnail_path = "images/product/accessories/rings.jpg";
                    $insertProductImage->priority = 1;
                    $insertProductImage->save(); 
                    continue;
                }
            }
            //return ['created' => $countCreated, 'updated' => $countUpdated];
        });

        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 5)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);


        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Imads products (store)";
        $storeCronJob->response = $response;
        // $storeCronJob->created_products = $counter['created'];
        // $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

    public function getWZProductsFile()
    {
        // return storage_path('exports') . '/dinadack_dackline.csv';
        // return storage_path('exports') . '/abs_wheels_imad.csv';
        // return storage_path('exports') . '/products.json';
        return storage_path('exports') . '/products.csv';
    }


    public function storeWZProducts()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date('Y-m-d H:i:s');
        $response = "";


        $countCreated = 0;
        $countUpdated = 0;
        Session::put('countProducts.created', 0);
        Session::put('countProducts.updated', 0);
        $counter = [];
        \DB::disableQueryLog();

        Excel::selectSheets()->filter('chunk')->load($this->getWZProductsFile())->chunk(250, function($data) use ($countCreated, $countUpdated) {
            // dd($data->toArray());
            // $counter = 0;

            foreach ($data as $product) {


                $productType = 4;

                if(empty($product['product_brand']) || empty($product['product_model']) || empty($product['product_width']) || empty($product['et']) || empty($product['product_inch']) || empty($product['bore_max']) || $product['price'] < 50 )
                    continue;



                // if($product['category-name'] == "Aluminiumfälg")
                //     $productType = 4;
                // if($product['category-name'] == "Stålfälg")
                //     $productType = 5;
                // if($product['category-name'] == "Plåtfälg")
                //     $productType = 6;
                // $productName = $product->name ? $product->name : $product->modelName;
                
                // $productName = $product['marke']." ".$product['modell']." ".$product['tum']."x".$product['bredd']." ET:".$product['et']." ".$product['boremax']." ".$product['color'];


                $deliveryTime = '5-7 dagars leveranstid';

                // if(strpos($product['name'], 'Blank')) {
                //     $deliveryTime = '10-14 dagars leveranstid';                            
                // } 
                // elseif( strpos($product['name'], 'Japan Racing') !== false ) {
                //     $deliveryTime = '6-8 dagars leveranstid';                            
                // }

                $updateProduct = Product::where('main_supplier_product_id', $product['main_supplier_product_id'])
                                ->where('main_supplier_id', 1)
                                ->first();

                if(sizeOf($updateProduct) > 0) {
                    $updateProduct->product_brand = $product['product_brand'];
                    $updateProduct->product_model = $product['product_model'];
                    $updateProduct->product_name = $product['product_name'];
                    $updateProduct->quantity = $product['quantity'];
                    //Alla priser är exklusive moms
                    $updateProduct->price = $product['price'];
                    $updateProduct->original_price = $product['original_price'];
                    // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                    $updateProduct->delivery_time = $deliveryTime;
                    $updateProduct->is_shown = 1;
                    $updateProduct->is_deleted = 0;
                    $updateProduct->save();
                    $updateProduct->touch();
                    $countUpdated++;
                    Session::put('countProducts.updated', $countUpdated);

                    if(empty($product['name'])) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $updateProduct->id;
                        $insertProductImage->name = "noRimImg.jpg";
                        $insertProductImage->path = "images/product/noRimImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                        continue;
                    } else {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $updateProduct->id;
                        $insertProductImage->name = $product['name'];
                        $insertProductImage->path = $product['path'];
                        $insertProductImage->thumbnail_path = $product['thumbnail_path'];
                        $insertProductImage->priority = $product['priority'];
                        $insertProductImage->save(); 
                        continue;
                    }

                    continue;
                }
                
                
                if($product['product_inch'] < 12) {
                    $profit = Profit::where('size', -1)
                                ->where('product_type', 3)
                                ->first(['id']);
                } elseif ($product['product_inch'] > 22 || $this->isDecimal($product['product_inch'] )) {
                    $profit = Profit::where('size', 1)
                                ->where('product_type', 3)
                                ->first(['id']);
                } else {
                    $profit = Profit::where('size', $product['product_inch'])
                                ->where('product_type', 3)
                                ->first(['id']);
                }
                
               

                $insertProduct = new Product();

                $insertProduct->product_category_id = 2;
                $insertProduct->product_type_id = $productType;
                $insertProduct->profit_id = $profit->id;
                $insertProduct->main_supplier_id = 1;
                $insertProduct->main_supplier_product_id = $product['main_supplier_product_id'];
                // $insertProduct->EANCode = $product->eanCode;
                $insertProduct->product_description = $product['product_description'];
                $insertProduct->product_brand = $product['product_brand'];
                $insertProduct->product_name = $product['product_name'];
                $insertProduct->product_model = $product['product_model'];
                $insertProduct->product_code = $product['product_code'];
                $insertProduct->price = $product['price'];
                $insertProduct->original_price = $product['original_price'];
                $insertProduct->quantity = $product['quantity'];
                // $insertProduct->ProductDimension = (int) $product->dimension;
                $insertProduct->product_width = $product['product_width'];
                // $insertProduct->product_profile = (int) $product->aspectRatio;
                $insertProduct->product_inch = $product['product_inch'];
                $insertProduct->et = $product['et'];
                $insertProduct->bore_max = $product['bore_max'];
                $insertProduct->pcd1 = $product['pcd1'];
                $insertProduct->pcd2 = $product['pcd2'];
                $insertProduct->pcd3 = $product['pcd3'];
                $insertProduct->pcd4 = $product['pcd4'];
                $insertProduct->pcd5 = $product['pcd5'];
                $insertProduct->pcd6 = $product['pcd6'];
                $insertProduct->pcd7 = $product['pcd7'];
                $insertProduct->pcd8 = $product['pcd8'];
                $insertProduct->pcd9 = $product['pcd9'];
                $insertProduct->pcd10 = $product['pcd10'];
                $insertProduct->pcd11 = $product['pcd11'];
                $insertProduct->pcd12 = $product['pcd12'];
                $insertProduct->pcd13 = $product['pcd13'];
                $insertProduct->pcd14 = $product['pcd14'];
                $insertProduct->pcd15 = $product['pcd15'];
                $insertProduct->priority_supplier = 1;
                $insertProduct->delivery_time = $deliveryTime;
                // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                $insertProduct->is_shown = 1;
                // $insertProduct->storage_location = $product->locationId;
                // $insertProduct->sub_supplier_id = $product->supplierId;
                $insertProduct->save();
                $countCreated++;
                Session::put('countProducts.created', $countCreated);

                if(empty($product['name'])) {
                     $insertProductImage = new ProductImage();
                     $insertProductImage->product_id = $insertProduct->id;
                     $insertProductImage->name = "noRimImg.jpg";
                     $insertProductImage->path = "images/product/noRimImg.jpg";
                     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                     $insertProductImage->priority = 1;
                     $insertProductImage->save(); 
                     continue;
                } else {
                    $insertProductImage = new ProductImage();
                     $insertProductImage->product_id = $insertProduct->id;
                     $insertProductImage->name = $product['name'];
                     $insertProductImage->path = $product['path'];
                     $insertProductImage->thumbnail_path = $product['thumbnail_path'];
                     $insertProductImage->priority = $product['priority'];
                     $insertProductImage->save(); 
                     continue;
                }
            }
            // return ['created' => $countCreated, 'updated' => $countUpdated];
        });//End DB transaction

        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 1)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Wheelzone rims (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = Session::get('countProducts.created');
        $storeCronJob->updated_products = Session::get('countProducts.updated');
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();

        Session::forget('countProducts.created');
        Session::forget('countProducts.updated');
        Session::flush();
    }

    public function getImazWheelsFile()
    {
        // return storage_path('exports') . '/dinadack_dackline.csv';
        // return storage_path('exports') . '/abs_wheels_imad.csv';
        // return storage_path('exports') . '/products.json';
        return storage_path('exports') . '/imaz_wheels.csv';
    }


    public function storeImazWheelsRims()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date('Y-m-d H:i:s');
        $response = "";

        $countCreated = 0;
        $countUpdated = 0;
        Session::put('countProducts.created', 0);
        Session::put('countProducts.updated', 0);
        $counter = [];
        \DB::disableQueryLog();

        Excel::selectSheets()->filter('chunk')->load($this->getImazWheelsFile())->chunk(250, function($data) use ($countCreated, $countUpdated) {
            dd($data->toArray());
            // $counter = 0;

            foreach ($data as $product) {


                $productType = 4;

                if(empty($product['product_brand']) || empty($product['product_model']) || empty($product['product_width']) || empty($product['et']) || empty($product['product_inch']) || empty($product['bore_max']) || $product['price'] < 50 )
                    continue;



                // if($product['category-name'] == "Aluminiumfälg")
                //     $productType = 4;
                // if($product['category-name'] == "Stålfälg")
                //     $productType = 5;
                // if($product['category-name'] == "Plåtfälg")
                //     $productType = 6;
                // $productName = $product->name ? $product->name : $product->modelName;
                
                // $productName = $product['marke']." ".$product['modell']." ".$product['tum']."x".$product['bredd']." ET:".$product['et']." ".$product['boremax']." ".$product['color'];


                $deliveryTime = '5-7 dagars leveranstid';

                // if(strpos($product['name'], 'Blank')) {
                //     $deliveryTime = '10-14 dagars leveranstid';                            
                // } 
                // elseif( strpos($product['name'], 'Japan Racing') !== false ) {
                //     $deliveryTime = '6-8 dagars leveranstid';                            
                // }

                $updateProduct = Product::where('main_supplier_product_id', $product['main_supplier_product_id'])
                                ->where('main_supplier_id', 1)
                                ->first();

                if(sizeOf($updateProduct) > 0) {
                    $updateProduct->product_brand = $product['product_brand'];
                    $updateProduct->product_model = $product['product_model'];
                    $updateProduct->product_name = $product['product_name'];
                    $updateProduct->quantity = $product['quantity'];
                    //Alla priser är exklusive moms
                    $updateProduct->price = $product['price'];
                    $updateProduct->original_price = $product['original_price'];
                    // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                    $updateProduct->delivery_time = $deliveryTime;
                    $updateProduct->is_shown = 1;
                    $updateProduct->is_deleted = 0;
                    $updateProduct->save();
                    $updateProduct->touch();
                    $countUpdated++;
                    Session::put('countProducts.updated', $countUpdated);

                    if(empty($product['name'])) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $updateProduct->id;
                        $insertProductImage->name = "noRimImg.jpg";
                        $insertProductImage->path = "images/product/noRimImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                        continue;
                    } else {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $updateProduct->id;
                        $insertProductImage->name = $product['name'];
                        $insertProductImage->path = $product['path'];
                        $insertProductImage->thumbnail_path = $product['thumbnail_path'];
                        $insertProductImage->priority = $product['priority'];
                        $insertProductImage->save(); 
                        continue;
                    }

                    continue;
                }
                
                
                if($product['product_inch'] < 12) {
                    $profit = Profit::where('size', -1)
                                ->where('product_type', 3)
                                ->first(['id']);
                } elseif ($product['product_inch'] > 22 || $this->isDecimal($product['product_inch'] )) {
                    $profit = Profit::where('size', 1)
                                ->where('product_type', 3)
                                ->first(['id']);
                } else {
                    $profit = Profit::where('size', $product['product_inch'])
                                ->where('product_type', 3)
                                ->first(['id']);
                }
                
               

                $insertProduct = new Product();

                $insertProduct->product_category_id = 2;
                $insertProduct->product_type_id = $productType;
                $insertProduct->profit_id = $profit->id;
                $insertProduct->main_supplier_id = 1;
                $insertProduct->main_supplier_product_id = $product['main_supplier_product_id'];
                // $insertProduct->EANCode = $product->eanCode;
                $insertProduct->product_description = $product['product_description'];
                $insertProduct->product_brand = $product['product_brand'];
                $insertProduct->product_name = $product['product_name'];
                $insertProduct->product_model = $product['product_model'];
                $insertProduct->product_code = $product['product_code'];
                $insertProduct->price = $product['price'];
                $insertProduct->original_price = $product['original_price'];
                $insertProduct->quantity = $product['quantity'];
                // $insertProduct->ProductDimension = (int) $product->dimension;
                $insertProduct->product_width = $product['product_width'];
                // $insertProduct->product_profile = (int) $product->aspectRatio;
                $insertProduct->product_inch = $product['product_inch'];
                $insertProduct->et = $product['et'];
                $insertProduct->bore_max = $product['bore_max'];
                $insertProduct->pcd1 = $product['pcd1'];
                $insertProduct->pcd2 = $product['pcd2'];
                $insertProduct->pcd3 = $product['pcd3'];
                $insertProduct->pcd4 = $product['pcd4'];
                $insertProduct->pcd5 = $product['pcd5'];
                $insertProduct->pcd6 = $product['pcd6'];
                $insertProduct->pcd7 = $product['pcd7'];
                $insertProduct->pcd8 = $product['pcd8'];
                $insertProduct->pcd9 = $product['pcd9'];
                $insertProduct->pcd10 = $product['pcd10'];
                $insertProduct->pcd11 = $product['pcd11'];
                $insertProduct->pcd12 = $product['pcd12'];
                $insertProduct->pcd13 = $product['pcd13'];
                $insertProduct->pcd14 = $product['pcd14'];
                $insertProduct->pcd15 = $product['pcd15'];
                $insertProduct->priority_supplier = 1;
                $insertProduct->delivery_time = $deliveryTime;
                // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                $insertProduct->is_shown = 1;
                // $insertProduct->storage_location = $product->locationId;
                // $insertProduct->sub_supplier_id = $product->supplierId;
                $insertProduct->save();
                $countCreated++;
                Session::put('countProducts.created', $countCreated);

                if(empty($product['name'])) {
                     $insertProductImage = new ProductImage();
                     $insertProductImage->product_id = $insertProduct->id;
                     $insertProductImage->name = "noRimImg.jpg";
                     $insertProductImage->path = "images/product/noRimImg.jpg";
                     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                     $insertProductImage->priority = 1;
                     $insertProductImage->save(); 
                     continue;
                } else {
                    $insertProductImage = new ProductImage();
                     $insertProductImage->product_id = $insertProduct->id;
                     $insertProductImage->name = $product['name'];
                     $insertProductImage->path = $product['path'];
                     $insertProductImage->thumbnail_path = $product['thumbnail_path'];
                     $insertProductImage->priority = $product['priority'];
                     $insertProductImage->save(); 
                     continue;
                }
            }
            // return ['created' => $countCreated, 'updated' => $countUpdated];
        });//End DB transaction

        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 1)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "Wheelzone rims (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = Session::get('countProducts.created');
        $storeCronJob->updated_products = Session::get('countProducts.updated');
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();

        Session::forget('countProducts.created');
        Session::forget('countProducts.updated');
        Session::flush();
    }

    public function get2ForgeFile()
    {
        return storage_path('exports') . '/2Forge_STOCKLIST_2017.xls';
    }

    public function store2ForgeRims()
    {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // dd(DB::logging());
        $updatedDate = date('Y-m-d H:i:s');
        $response = "";
        $countCreated = 0;
        $countUpdated = 0;
        Session::put('countProducts.created', 0);
        Session::put('countProducts.updated', 0);
        $counter = [];
        \DB::disableQueryLog();

        $rate = \Swap::latest('GBP/SEK');
        $rate = $rate->getValue();

        Excel::selectSheets('Sheet1')->filter('chunk')->load($this->get2ForgeFile())->chunk(250, function($data) use ($rate, $countCreated, $countUpdated) {
            dd($data->toArray());
            // $counter = 0;

            foreach ($data as $product) {

                $productType = 4;

                if(empty($product['stock_code']) || empty($product['model']) || empty($product['size']) || empty($product['offset']) || empty($product['cb']) || $product['rrp_per_wheel_inc_vat'] < 5 )
                    continue;



                // if($product['category-name'] == "Aluminiumfälg")
                //     $productType = 4;
                // if($product['category-name'] == "Stålfälg")
                //     $productType = 5;
                // if($product['category-name'] == "Plåtfälg")
                //     $productType = 6;
                // $productName = $product->name ? $product->name : $product->modelName;
                
                // $product['cb'] = round( $product['cb'], 1);
                $product['cb'] = sprintf( "%.1f", $product['cb']);
                $sizeArr = explode('X', $product['size']);
                $width = $sizeArr[0];
                $inch = $sizeArr[1];

                $etArr = explode(' TO ', $product['offset']);
                $etMin= $etArr[0];
                $etMax = $etArr[1];

                $product['offset'] = str_replace(' TO ', '-', $product['offset']);
                $price = ($product['rrp_per_wheel_inc_vat'] + 15) * $rate;
                $storagePrice = $product['1_set'] * $rate;

                $productName = "2Forge ".$product['model']." ".$product['size']." ET:".$product['offset']." ".$product['cb']." ".$product['colour'];

                // dd($product['offset'], $etArr, $sizeArr, $productName, $product['cb'], $price, $product['rrp_per_wheel_inc_vat']);

                $deliveryTime = '5-7 dagars leveranstid';

                // if(strpos($product['name'], 'Blank')) {
                //     $deliveryTime = '10-14 dagars leveranstid';                            
                // } 
                // elseif( strpos($product['name'], 'Japan Racing') !== false ) {
                //     $deliveryTime = '6-8 dagars leveranstid';                            
                // }

                $updateProduct = Product::where('main_supplier_product_id', $product['stock_code'])
                                ->where('main_supplier_id', 6)
                                ->first();

                if(sizeOf($updateProduct) > 0) {
                    $updateProduct->product_brand = "2Forge";
                    $updateProduct->product_model = $product['model'];
                    $updateProduct->product_name = $productName;
                    $updateProduct->quantity = $product['stock'];
                    //Alla priser är exklusive moms
                    $updateProduct->price = $price;
                    $updateProduct->storage_price = $storagePrice;
                    // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                    $updateProduct->delivery_time = $deliveryTime;
                    $updateProduct->is_shown = 1;
                    $updateProduct->is_deleted = 0;
                    $updateProduct->save();
                    $updateProduct->touch();
                    $countUpdated++;
                    Session::put('countProducts.updated', $countUpdated);

                    // if(empty($product['name'])) {
                    //     $insertProductImage = new ProductImage();
                    //     $insertProductImage->product_id = $updateProduct->id;
                    //     $insertProductImage->name = "noRimImg.jpg";
                    //     $insertProductImage->path = "images/product/noRimImg.jpg";
                    //     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                    //     $insertProductImage->priority = 1;
                    //     $insertProductImage->save(); 
                    //     continue;
                    // } else {
                    //     $insertProductImage = new ProductImage();
                    //     $insertProductImage->product_id = $updateProduct->id;
                    //     $insertProductImage->name = $product['name'];
                    //     $insertProductImage->path = $product['path'];
                    //     $insertProductImage->thumbnail_path = $product['thumbnail_path'];
                    //     $insertProductImage->priority = $product['priority'];
                    //     $insertProductImage->save(); 
                    //     continue;
                    // }

                    continue;
                }
                
                
                // if($product['product_inch'] < 12) {
                //     $profit = Profit::where('size', -1)
                //                 ->where('product_type', 3)
                //                 ->first(['id']);
                // } elseif ($product['product_inch'] > 22 || $this->isDecimal($product['product_inch'] )) {
                //     $profit = Profit::where('size', 1)
                //                 ->where('product_type', 3)
                //                 ->first(['id']);
                // } else {
                //     $profit = Profit::where('size', $product['product_inch'])
                //                 ->where('product_type', 3)
                //                 ->first(['id']);
                // }
                
               

                $insertProduct = new Product();

                $insertProduct->product_category_id = 2;
                $insertProduct->product_type_id = $productType;
                $insertProduct->profit_id = 1;
                $insertProduct->main_supplier_id = 6;
                $insertProduct->main_supplier_product_id = $product['stock_code'];
                // $insertProduct->EANCode = $product->eanCode;
                $insertProduct->product_description = $productName;
                $insertProduct->product_brand = "2Forge";
                $insertProduct->product_name = $productName;
                $insertProduct->product_model = $product['model'];
                $insertProduct->product_code = $productName;
                $insertProduct->price = $price;
                $insertProduct->storage_price = $storagePrice;
                $insertProduct->quantity = $product['stock'];
                // $insertProduct->ProductDimension = (int) $product->dimension;
                $insertProduct->product_width = $width;
                // $insertProduct->product_profile = (int) $product->aspectRatio;
                $insertProduct->product_inch = $inch;
                $insertProduct->et = $etMax;
                $insertProduct->et_min = $etMin;
                $insertProduct->bore_max = $product['cb'];
                $insertProduct->pcd1 = $product['pcd'];
                $insertProduct->delivery_time = $deliveryTime;
                // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                $insertProduct->is_shown = 1;
                // $insertProduct->storage_location = $product->locationId;
                // $insertProduct->sub_supplier_id = $product->supplierId;
                $insertProduct->save();
                $countCreated++;
                Session::put('countProducts.created', $countCreated);

                if(empty($product['image']) || $product['image'] == "COMING SOON") {
                     $insertProductImage = new ProductImage();
                     $insertProductImage->product_id = $insertProduct->id;
                     $insertProductImage->name = "noRimImg.jpg";
                     $insertProductImage->path = "images/product/noRimImg.jpg";
                     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                     $insertProductImage->priority = 1;
                     $insertProductImage->save(); 
                     continue;
                }
            
                // $url = $product['photo'];
                // $ch = curl_init ( $url );
                // curl_setopt( $ch , CURLOPT_HEADER, 0);
                // curl_setopt( $ch , CURLOPT_RETURNTRANSFER, 1);
                // $file=curl_exec( $ch );
                // curl_close ( $ch );
    // dd($fileName, $url);
                
                
                // dd($profit, $productType);

                
                // $extension = pathinfo($url, PATHINFO_EXTENSION);
                $url = $product['image'];
                $url = str_replace(' ', '%20', $url);

                // $arrContextOptions=array(
                //     "ssl"=>array(
                //         "verify_peer"=>false,
                //         "verify_peer_name"=>false,
                //     ),
                // );  
                // $file = file_get_contents($url, false, stream_context_create($arrContextOptions));
                
                $file = $this->file_get_contents_curl($url);
                $fileName = basename($url);
                $path = 'images/product/2forge_rims/'.$fileName;
                $thumbnail_path = 'images/product/2forge_rims/thumb/tn-'.$fileName;
                $absolute_path = public_path($path);
                $absolute_thumbnail_path = public_path($thumbnail_path);
                // $existImg = ProductImage::where('name', $fileName)->get();
                // $existImg = Storage::disk('public')->exists($path);
                // $existImg = File::exists($path);

                
                
                if(!file_exists($absolute_path)) {
                    if (!is_dir('images/product/2forge_rims/')) {
                        // dir doesn't exist, make it
                        mkdir('images/product/2forge_rims/');
                        mkdir('images/product/2forge_rims/thumb/');
                    }

                    // if(sizeof($existImg) === 0) {
                    // if(!$existImg) {
                    //     $existImg = file_put_contents($path, $file);
                    // }


                    $existImg = file_put_contents($path, $file);

                    if($existImg && exif_imagetype($path)) {
                        Image::make($path)->resize(600, 600)->save();
                        Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);

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

                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = "noRimImg.jpg";
                        $insertProductImage->path = "images/product/noRimImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
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
            // return ['created' => $countCreated, 'updated' => $countUpdated];
        });//End DB transaction

        $counter['deleted'] = DB::table('products')
                        ->where('main_supplier_id', 6)
                        ->where('updated_at', '<', $updatedDate)
                        ->where('is_deleted', 0)
                        ->update([
                            'is_deleted' => 1, 
                            'is_shown' => 0 
                        ]);

        $storeCronJob = new CronJobStatus;
        $storeCronJob->name = "2Forge rims (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = Session::get('countProducts.created');
        $storeCronJob->updated_products = Session::get('countProducts.updated');
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();

        Session::forget('countProducts.created');
        Session::forget('countProducts.updated');
        Session::flush();
    }

    // public function storeImadProducts()
    // {
    //     ini_set("memory_limit",-1);
    //     ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
    //     ini_set("default_socket_timeout", 2000);
    //     // dd(DB::logging());
    //     $updatedDate = date('Y-m-d H:i:s');
    //     $response = "";


    //     $countCreated = 0;
    //     $countUpdated = 0;
    //     $counter = [];

    //     if (($handle = fopen($this->getImadProductsFile(), "r")) !== FALSE) {
    //         $loop = 0;
    //         while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    //             if(count($data) == 42) {
    //                 $products[] = $data;
    //             }
    //         }
    //     }
    //     // $rate = \Swap::latest('EUR/SEK');
    //     // $rate = $rate->getValue();
    //     // dd($products);
    //     $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated) {
    //         // $Application = [];
    //         // $Attribute = [];
    //         foreach (array_chunk($products, 200) as $productChunk) {
    //             foreach($productChunk as $product) {
                        
    //                 $updateProduct = Product::where('main_supplier_product_id', $product[0])
    //                     ->where('main_supplier_id', 5)
    //                     ->first();  


    //                 if(sizeOf($updateProduct) > 0) {
    //                     $updateProduct->product_brand = $product[3];
    //                     $updateProduct->product_model = $product[6];
    //                     $updateProduct->product_name = $product[7];
    //                     $updateProduct->quantity = (int) $product[31];
    //                     //Alla priser är exklusive moms
    //                     $updateProduct->price = $product[28] * 0.8;
    //                     $updateProduct->original_price = $product[27] * 0.8;
    //                     // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
    //                     $updateProduct->delivery_time = "5-7 dagars leveranstid";
    //                     $updateProduct->is_shown = 1;
    //                     $updateProduct->is_deleted = 0;
    //                     $updateProduct->save();
    //                     $updateProduct->touch();            
    //                     $countUpdated++;
    //                     continue;
    //                 }                

    //                 if($product[8] < 12) {
    //                     $profit = Profit::where('size', -1)
    //                                 ->where('product_type', 3)
    //                                 ->first(['id']);
    //                 } elseif ($product[8] > 22 || $this->isDecimal($product[8] )) {
    //                     $profit = Profit::where('size', 1)
    //                                 ->where('product_type', 3)
    //                                 ->first(['id']);
    //                 } else {
    //                     $profit = Profit::where('size', $product[8])
    //                                 ->where('product_type', 3)
    //                                 ->first(['id']);
    //                 }

    //                 $insertProduct = new Product();

    //                 $productName = $product[3]." ".$product[9]."x".$product[8]." ET:".$product[10]." ".$product[6]." ".$product[5];

    //                 $insertProduct->product_category_id = 2;
    //                 $insertProduct->product_type_id = 4;
    //                 $insertProduct->profit_id = $profit->id;
    //                 $insertProduct->main_supplier_id = 5;
    //                 $insertProduct->main_supplier_product_id = $product[0];
    //                 // $insertProduct->EANCode = $product->eanCode;
    //                 $insertProduct->product_description = $product[30];
    //                 // $insertProduct->ean = $product->EAN;
    //                 $insertProduct->product_brand = $product[3];
    //                 $insertProduct->product_name = $productName;
    //                 $insertProduct->product_model = $product[6];
    //                 $insertProduct->product_code = $productName;
    //                 $insertProduct->price = $product[28] * 0.8;                                
    //                 $insertProduct->original_price = $product[27] * 0.8;
    //                 $insertProduct->storage_price = $product[29] * 0.8;
    //                 $insertProduct->quantity = (int) $product[31];
    //                 $insertProduct->product_width = $product[8];
    //                 // $insertProduct->product_profile = $product->Profile;
    //                 $insertProduct->product_inch = $product[9];
    //                 $insertProduct->et = $product[10];
    //                 // $insertProduct->et_min = $product['product-et'];
    //                 // $insertProduct->tire_manufactor_date =  !empty($product['product-tillv']) ? $product['product-tillv'] : null;
    //                 $insertProduct->storage_pcd = $product[21];
    //                 $insertProduct->storage_location = $product[22];
    //                 $insertProduct->bore_min = $product[23];
    //                 $insertProduct->bore_max = $product[24];
    //                 $insertProduct->pcd1 = $this->formatPCD($product[12]);
    //                 $insertProduct->pcd2 = $this->formatPCD($product[13]);
    //                 $insertProduct->pcd3 = $this->formatPCD($product[14]);
    //                 $insertProduct->pcd4 = $this->formatPCD($product[15]);
    //                 $insertProduct->pcd5 = $this->formatPCD($product[16]);
    //                 $insertProduct->pcd6 = $this->formatPCD($product[17]);
    //                 $insertProduct->pcd7 = $this->formatPCD($product[18]);
    //                 $insertProduct->pcd8 = $this->formatPCD($product[19]);
    //                 $insertProduct->pcd9 = $this->formatPCD($product[20]);
    //                 $insertProduct->discount1 = $product[32];
    //                 $insertProduct->discount2 = $product[33];
    //                 $insertProduct->discount3 = $product[34];
    //                 $insertProduct->discount4 = $product[35];
    //                 $insertProduct->video_link = $product[36];
    //                 // $insertProduct->priority = $product[35];
    //                 $insertProduct->priority_supplier = 1;
    //                 $insertProduct->delivery_time = "5-7 dagars leveranstid";
    //                 // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
    //                 $insertProduct->is_shown = 1;
    //                 // $insertProduct->storage_location = $product->locationId;
    //                 // $insertProduct->sub_supplier_id = $product->supplierId;
    //                 $insertProduct->save();
    //                 $countCreated++;


    //                 if(empty($product[26]) || $product[26] == "ML501.GM.MF.jpg" || $product[26] == "5015-MB CL-63.jpg" || $product[26] == "Audi.beg.jpg" || $product[26] == "IM302_400x400.jpg" || $product[26] == "bb5ac6c0-b604-4df3-4417-4a12bd336732-2015-06-04-15" || $product[26] == "Genova.jpg") {
    //                     $insertProductImage = new ProductImage();
    //                     $insertProductImage->product_id = $insertProduct->id;
    //                     $insertProductImage->name = "noRimImg.jpg";
    //                     $insertProductImage->path = "images/product/noRimImg.jpg";
    //                     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
    //                     $insertProductImage->priority = 1;
    //                     $insertProductImage->save(); 
    //                     continue;
    //                 }

    //                 $url = "http://www.dackline.se/img/falgar/" . $product[26];
    //                 $url = str_replace(' ', '%20', $url);
    //                 $file = $this->file_get_contents_curl($url);
    //                 $fileName = $product[26];
    //                 $path = 'images/product/imad_rims/'.$fileName;
    //                 $thumbnail_path = 'images/product/imad_rims/thumb/tn-'.$fileName;
    //                 $absolute_path = public_path($path);
    //                 $absolute_thumbnail_path = public_path($thumbnail_path);
    //                 // $existImg = ProductImage::where('name', $fileName)->get();
    //                 // $existImg = Storage::disk('public')->exists($path);
    //                 $existImg = File::exists($path);
    //                 // dd($fileName, $url);
                    
                    
    //                 // dd($profit, $productType);

    //                 if(!file_exists($absolute_path)) {
    //                     if (!is_dir('images/product/imad_rims/')) {
    //                         // dir doesn't exist, make it
    //                         mkdir('images/product/imad_rims/');
    //                         mkdir('images/product/imad_rims/thumb/');
    //                     }

    //                     // if(sizeof($existImg) === 0) {
    //                     if(!$existImg) {
    //                         $existImg = file_put_contents($path, $file);
    //                     }

    //                     Image::make($path)->resize(600, 600)->save();
    //                     Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);
    //                     // Image::make($path)->save($absolute_thumbnail_path);
    //                 }
                    

    //                 if($existImg) {
    //                     $insertProductImage = new ProductImage();
    //                     $insertProductImage->product_id = $insertProduct->id;
    //                     $insertProductImage->name = $fileName;
    //                     $insertProductImage->path = $path;
    //                     $insertProductImage->thumbnail_path = $thumbnail_path;
    //                     $insertProductImage->priority = 1;
    //                     $insertProductImage->save(); 
    //                 } else {
    //                     $insertProductImage = new ProductImage();
    //                     $insertProductImage->product_id = $insertProduct->id;
    //                     $insertProductImage->name = "noRimImg.jpg";
    //                     $insertProductImage->path = "images/product/noRimImg.jpg";
    //                     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
    //                     $insertProductImage->priority = 1;
    //                     $insertProductImage->save(); 
    //                 }
    //             }
    //         }

    //         return ['created' => $countCreated, 'updated' => $countUpdated];
    //     });

    //     $counter['deleted'] = DB::table('products')
    //                     ->where('main_supplier_id', 5)
    //                     ->where('updated_at', '<', $updatedDate)
    //                     ->where('is_deleted', 0)
    //                     ->update([
    //                         'is_deleted' => 1, 
    //                         'is_shown' => 0 
    //                     ]);


    //     $storeCronJob = new CronJobStatus;
    //     $storeCronJob->name = "Imads rims (store)";
    //     $storeCronJob->response = $response;
    //     $storeCronJob->created_products = $counter['created'];
    //     $storeCronJob->updated_products = $counter['updated'];
    //     $storeCronJob->deleted_products = $counter['deleted'];
    //     $storeCronJob->begin_at = $updatedDate;
    //     $storeCronJob->save();
    // }
    

    public function fullBackup()
    {
        /*
        PHP script to allow periodic cPanel backups automatically, optionally to a remote FTP server.
        This script contains passwords. It is important to keep access to this file secure (we would ask you to place it in your 
        home directory, not public_html)
        To schedule the script to run regularly, add this script to run as a cron job in your cpanel.
        You need create 'backups' folder in your home directory ( or any other folder that you would like to store your backups in ).
        Reference: https://www.namecheap.com/support/knowledgebase/article.aspx/915
        */
        // ********* THE FOLLOWING ITEMS NEED TO BE CONFIGURED *********
        // Information required for cPanel access
        $cpuser = "hjulonline"; // Username used to login to cPanel
        $cppass = 'zQv3iT!2MWEG'; // Password used to login to cPanel. NB! you could face some issues with the "$#&/" chars in the password, so if script does not work, please try to change the password.
        $domain = "hjulonline.se"; // Domain name where CPanel is run
        $skin = "paper_lantern"; // Set to cPanel skin you use (script will not work if it does not match). Most people run the default "x" theme or "x3" theme
        // Information required for FTP host
        $ftpuser = "hjulonline"; // Username for FTP account
        $ftppass = 'zQv3iT!2MWEG'; // Password for FTP account NB! you could face some issues with the "$#&/" chars in the password, so if script does not work, please try to change the password.
        $ftphost = "188.114.255.115"; // IP address of your hosting account
        $ftpmode = "passiveftp"; // FTP mode: homedir - Home Directory, ftp - Remote FTP Server, passiveftp - Remote FTP Server (passive mode transfer), scp - Secure Copy (SCP)

        // Notification information 
        $notifyemail = "sibar@abswheels.se"; // Email address to send results

        // Secure or non-secure mode 
        $secure = 1; // Set to 1 for SSL (requires SSL support), otherwise will use standard HTTP

        // Set to 1 to have web page result appear in your cron log 
        $debug = 0;

        $ftpport = "21";
        $ftpdir = "/backup/"; // Directory where backups stored (make it in your /home/ directory). Or you can change 'backups' to the name of any other folder created for the backups;

        // *********** NO CONFIGURATION ITEMS BELOW THIS LINE *********
        if ($secure) {
          $url = "ssl://".$domain;
          $port = 2083;
        } else {
          $url = $domain;
          $port = 2082;
        }
        $socket = fsockopen($url,$port);
        if (!$socket) { echo "Failed to open socket connection... Bailing out!n"; exit; }
        // Encode authentication string
        $authstr = $cpuser.":".$cppass;
        $pass = base64_encode($authstr);
        $params = "dest=$ftpmode&email=$notifyemail&server=$ftphost&user=$ftpuser&pass=$ftppass&port=$ftpport&rdir=$ftpdir&submit=Generate Backup";
        // $params = "dest=$ftpmode&email=$notifyemail&server=$ftphost&user=$ftpuser&pass=$ftppass&port=$ftpport&submit=Generate Backup";
        // Make POST to cPanel
        fputs($socket,"POST /frontend/".$skin."/backup/dofullbackup.html?".$params." HTTP/1.0\r\n");
        fputs($socket,"Host: $domain\r\n");
        fputs($socket,"Authorization: Basic $pass\r\n");
        fputs($socket,"Connection: Close\r\n");
        fputs($socket,"\r\n");
        // Grab response even if we do not do anything with it.
        while (!feof($socket)) {
          $response = fgets($socket,4096); if ($debug) echo $response;
        }
        fclose($socket);

    }


}