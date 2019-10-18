<?php
namespace App\Http\Controllers;

use App\CronJobStatus;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\ProductImage;
use App\Profit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EontyreApi extends CronJobController {

    private $webshop_id;
    private $site_id;
    private $api_key;

    //Live
    // private $webshop_id = '22';
    // private $site_id = '293';
    // private $api_key = '7cc3dc41098a44989cddefab26312d79';

    //Test
    // private $webshop_id = '22';
    // private $site_id = '472';
    // private $api_key = '188159b929c1486c8abe1121f205b5e6';
    


    // function __construct($site_id, $api_key) {
    function __construct() {

        if(env('APP_ENV') == 'production') {
            $this->webshop_id = '22';
            $this->site_id = '293';
            $this->api_key = '7cc3dc41098a44989cddefab26312d79';
        } else {
            $this->webshop_id = '22';
            $this->site_id = '472';
            $this->api_key = '188159b929c1486c8abe1121f205b5e6';
        }

        $this->url = "https://p{$this->site_id}.eontyre.com/api/v2";
        // $this->api_key = $api_key;

    }

    function list_tyre_products() {
        $products =  $this->call('GET', '/products/export/tyres');

        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);
        // 2024M
        // 3000
        // 60

        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);

        $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated) {
            foreach (array_chunk($products, 200) as $productChunk) {
                // dd($products);
                foreach($productChunk as $product) {
                    // For testing
                    // if($countCreated >= 100 || $countUpdated >= 100) 
                    //     continue;

                    //    Car                  = 0;
                    //    MC                   = 1;
                    //    FourByFour           = 2;
                    //    LightTruckCommercial = 4;
                    if($product['vehicle_type_id'] !== 0 && $product['vehicle_type_id'] !== 1 && $product['vehicle_type_id'] !== 2 && $product['vehicle_type_id'] !== 4)
                        continue;




                    //  AllSeason = 1;
                    //  Summer    = 2;
                    //  Winter    = 3;
                    //  Nordic    = 4;
                    //  Studded   = 5;
                    //  Studdable = 6;
                    //  Spare     = 7;
                    //  Racing    = 8;
                    if($product['tyre_type_id'] >= 6)
                        continue;

                    if(empty($product['brand_name']) || empty($product['width']) || empty($product['diameter']))
                        continue;

                    if(!empty($product['location_id'])) {
                        $prioritySupplier = 0;
                        $deliveryTime = '3-5 dagars leveranstid';
                    } else {
                        $prioritySupplier = 1;
                        $deliveryTime = '5-7 dagars leveranstid';
                    }


                    $updateProduct = Product::where('main_supplier_product_id', $product['product_id'])
                                    ->where('main_supplier_id', 2)
                                    ->where('product_brand', $product['brand_name'])
                                    ->where('product_width', $product['width'])
                                    ->where('product_inch', $product['diameter'])
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        // $updateProduct->product_brand = $product['brand_name'];
                        // $updateProduct->product_model = $product['model_name'];
                        // $updateProduct->product_name = $product['description'];
                        $updateProduct->quantity = $product['stock'];
                        // $updateProduct->product_dimension = $productDimension;
                        //Alla priser är exklusive moms
                        $updateProduct->price = $product['price'] / 100;
                        // $updateProduct->original_price = $product['product-oldprice'];
                        // $updateProduct->tire_manufactor_date = $product['product-tillv'];
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->priority_supplier = $prioritySupplier;
                        $updateProduct->delivery_time = $deliveryTime;
                        // $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;

                        // if(($updateProduct->product_brand == "LASSA" || $updateProduct->product_brand == "LASSA C DÄCK") AND !empty($product['images'])) {
                            
                        //     $updateProductImage = ProductImage::where('product_id', $updateProduct->id)
                        //         ->where('priority', 1)
                        //         ->first();

                        //     $url = "https://api.eontyre.com/images/{$product['images'][0]['image_id']}/big.".$product['images'][0]['filetype'];
                        //     $url = str_replace(' ', '%20', $url);
                        //     $file = $this->file_get_contents_curl($url);
                        //     $fileName = "{$product['images'][0]['image_id']}.".$product['images'][0]['filetype'];
                        //     $path = 'images/product/eontyre_tires/'.$fileName;
                        //     // $thumbnail_path = 'images/product/eontyre_tires/thumb/tn-'.$fileName;
                        //     $thumbnail_path = 'images/product/eontyre_tires/'.$fileName;
                        //     $absolute_path = public_path($path);
                        //     $absolute_thumbnail_path = public_path($thumbnail_path);
                   
                            
                        //     if(!file_exists($absolute_path)) {

                        //         // if(sizeof($existImg) === 0) {
                        //         // if(!$existImg) {
                        //         //     $existImg = file_put_contents($path, $file);
                        //         // }


                        //         $existImg = file_put_contents($path, $file);

                        //         if($existImg && exif_imagetype($path)) {
                        //             // Image::make($path)->resize(600, 600)->save();
                        //             // Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);
                        //             // Image::make($path)->save($absolute_thumbnail_path);

                        //             $updateProductImage->name = $fileName;
                        //             $updateProductImage->path = $path;
                        //             $updateProductImage->thumbnail_path = $thumbnail_path;
                        //             $updateProductImage->priority = 1;
                        //             $updateProductImage->save();
                        //             continue;
                        //         } else {
                        //             File::delete($path);

                        //             $updateProductImage->name = "noTireImg.jpg";
                        //             $updateProductImage->path = "images/product/noTireImg.jpg";
                        //             $updateProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                        //             $updateProductImage->priority = 1;
                        //             $updateProductImage->save(); 
                        //             continue;

                        //         }

                        //     } else {
                        //         $updateProductImage->name = $fileName;
                        //         $updateProductImage->path = $path;
                        //         $updateProductImage->thumbnail_path = $thumbnail_path;
                        //         $updateProductImage->priority = 1;
                        //         $updateProductImage->save();
                        //     }

                        // }

                        continue;
                    }

                    $mystring = $product['description'];
                    $findme   = $product['width'].'/';
                    $pos = strpos($mystring, $findme);
                    $productDimension = null;
                    $productProfile = null;

                    if ($pos == false) {
                        
                        continue;
                    } else {
                        $productDimension = explode(' ', substr($mystring, $pos))[0];
                        $productDimension = str_replace('-', 'R', $productDimension);
                        $subDimension = explode('/', $productDimension)[1];
                        preg_match('/\d+/', $subDimension, $profile);
                        $productProfile = $profile[0];

                        // dd($product, $pos, substr($mystring, $pos), $productDimension, $productProfile);
                    }

                    //  AllSeason = 1;
                    //  Summer    = 2;
                    //  Winter    = 3;
                    //  Nordic    = 4;
                    //  Studded   = 5;
                    if($product['tyre_type_id'] == 2) {
                        $productType = 1;
                        $profitProductType = 1;
                    }

                    if($product['tyre_type_id'] == 1 || $product['tyre_type_id'] == 3 || $product['tyre_type_id'] == 4) {
                        $productType = 2;
                        $profitProductType = 2;
                    }

                    if($product['tyre_type_id'] == 5) {
                        $productType = 3;
                        $profitProductType = 2;
                    }

                    if($product['diameter'] < 12) {
                        $profit = Profit::where('size', -1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } elseif ($product['diameter'] > 22 || $this->isDecimal($product['diameter'])) {
                        $profit = Profit::where('size', 1)
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    } else {
                        $profit = Profit::where('size', $product['diameter'])
                                    ->where('product_type', $profitProductType)
                                    ->first(['id']);
                    }
                    $productLabel = $product['tyre_rolling_resistance'].'-'.$product['tyre_wet_grip'].'-'.$product['tyre_noise_emission_rating'].'-'.$product['tyre_noise_emission_decibel'];
                    // $deliveryDate = $this->ExcelToPHP($product['next_delivery']);
                    
                    

                    $insertProduct = new Product();
                    $insertProduct->product_category_id = 1;
                    $insertProduct->product_type_id = $productType;
                    $insertProduct->profit_id = $profit->id;
                    $insertProduct->main_supplier_id = 2;
                    $insertProduct->main_supplier_product_id = $product['product_id'];
                    $insertProduct->product_description = $product['description'];
                    $insertProduct->product_brand = $product['brand_name'];
                    $insertProduct->product_model = $product['model_name'];
                    $insertProduct->product_name = $product['description'];
                    $insertProduct->product_code = $product['description'];
                    $insertProduct->quantity = $product['stock'];
                    $insertProduct->product_dimension = $productDimension;
                    $insertProduct->product_width = $product['width'];
                    $insertProduct->product_profile = $productProfile;
                    $insertProduct->product_inch = $product['diameter'];
                    // $insertProduct->tire_manufactor_date =  !empty($product['product-tillv']) ? $product['product-tillv'] : null;
                    $insertProduct->load_index = $product['tyre_load_index'];
                    $insertProduct->speed_index = $product['tyre_speed_index'];
                    $insertProduct->is_runflat = $product['tyre_is_runflat'];
                    // $insertProduct->is_ctyre = $product['product-iscdack'];
                    $insertProduct->product_label = $productLabel;
                    $insertProduct->rolling_resistance = $product['tyre_rolling_resistance'];
                    $insertProduct->wet_grip = $product['tyre_wet_grip'];
                    $insertProduct->noise_emission_rating = $product['tyre_noise_emission_rating'];
                    $insertProduct->noise_emission_decibel = $product['tyre_noise_emission_decibel'];
                    // $insertProduct->sub_supplier_id = $product->supplierId;
                    //Alla priser är exklusive moms
                    $insertProduct->price = $product['price'] / 100;
                    // $insertProduct->original_price = $product['product-oldprice'];
                    // $insertProduct->tire_manufactor_date = $product['product-tillv'];
                    // $insertProduct->storage_price = $product->cost;
                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                    $insertProduct->priority_supplier = $prioritySupplier;
                    $insertProduct->delivery_time = $deliveryTime;
                    $insertProduct->is_shown = 1;
                    $insertProduct->save();
                    $countCreated++;
                    // $productID = DB::getPdo()->lastInsertId();
    // } catch(\Exception $ex){ 
    //   dd($ex->getMessage(), $profit, $product->rimDiameter, $profitProductType); 
    //   // Note any method of class PDOException can be called on $ex.
    // }


                    if(empty($product['images'])) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $insertProduct->id;
                        $insertProductImage->name = "noTireImg.jpg";
                        $insertProductImage->path = "images/product/noTireImg.jpg";
                        $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                        continue;
                    }


                    $url = "https://api.eontyre.com/images/{$product['images'][0]['image_id']}/big.".$product['images'][0]['filetype'];
                    $url = str_replace(' ', '%20', $url);
                    $file = $this->file_get_contents_curl($url);
                    $fileName = "{$product['images'][0]['image_id']}.".$product['images'][0]['filetype'];
                    $path = 'images/product/eontyre_tires/'.$fileName;
                    // $thumbnail_path = 'images/product/eontyre_tires/thumb/tn-'.$fileName;
                    $thumbnail_path = 'images/product/eontyre_tires/'.$fileName;
                    $absolute_path = public_path($path);
                    $absolute_thumbnail_path = public_path($thumbnail_path);
                    // $existImg = ProductImage::where('name', $fileName)->get();
                    // $existImg = Storage::disk('public')->exists($path);
                    // $existImg = File::exists($path);

                    
                    
                    // dd($profit, $productType);

                    // if(!file_exists($absolute_path)) {
                    //     if (!is_dir('images/product/eontyre_tires/')) {
                    //         // dir doesn't exist, make it
                    //         mkdir('images/product/eontyre_tires/');
                    //         mkdir('images/product/eontyre_tires/thumb/');
                    //     }

                    //     // if(sizeof($existImg) === 0) {
                    //     if(!$existImg) {
                    //         $existImg = file_put_contents($path, $file);
                    //     }

                    //     // Image::make($path)->resize(600, 600)->save();
                    //     Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);
                    // }
                    

                    // if($existImg) {
                    //     $insertProductImage = new ProductImage();
                    //     $insertProductImage->product_id = $insertProduct->id;
                    //     $insertProductImage->name = $fileName;
                    //     $insertProductImage->path = $path;
                    //     $insertProductImage->thumbnail_path = $thumbnail_path;
                    //     $insertProductImage->priority = 1;
                    //     $insertProductImage->save(); 
                    // }
                    
                    if(!file_exists($absolute_path)) {
                        if (!is_dir('images/product/eontyre_tires/')) {
                            // dir doesn't exist, make it
                            mkdir('images/product/eontyre_tires/');
                            mkdir('images/product/eontyre_tires/thumb/');
                        }

                        // if(sizeof($existImg) === 0) {
                        // if(!$existImg) {
                        //     $existImg = file_put_contents($path, $file);
                        // }


                        $existImg = file_put_contents($path, $file);

                        if($existImg && exif_imagetype($path)) {
                            // Image::make($path)->resize(600, 600)->save();
                            // Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);
                            // Image::make($path)->save($absolute_thumbnail_path);

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
        $storeCronJob->name = "EonTyre tires (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
        // insert into CronJobStatus params: name, response, deleted_products, begin_date
        
        return redirect()->back()->with('message', 'Produkterna har uppdaterats!');
    }

    function list_rim_products() {
        $products = $this->call('GET', '/products/export/rims');

        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);

        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countCreated = 0;
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);
        $counter = DB::transaction(function() use ($products, $countCreated, $countUpdated) {
            foreach (array_chunk($products, 200) as $productChunk) {

                foreach($productChunk as $product) {
                    $productType = 4;

                    // For testing
                    // if($countCreated >= 100 || $countUpdated >= 100) 
                    //     continue;

                    if(empty($product['brand_name']) || empty($product['model_name']) || empty($product['width']) || empty($product['rim_et']) || empty($product['diameter']) || empty($product['rim_centre_bore']) || $product['price'] < 5000 )
                        continue;



                    // if($product['category-name'] == "Aluminiumfälg")
                    //     $productType = 4;
                    // if($product['category-name'] == "Stålfälg")
                    //     $productType = 5;
                    // if($product['category-name'] == "Plåtfälg")
                    //     $productType = 6;
                    // $productName = $product->name ? $product->name : $product->modelName;
                    
                    // $productName = $product['marke']." ".$product['modell']." ".$product['tum']."x".$product['width']." ET:".$product['et']." ".$product['boremax']." ".$product['color'];

                    // if(isset($product['pcd'][7])) {
                    //     if($product['pcd'][7] == '5x120.6' )
                    //         $product['pcd'][7] = '5x120.65';
                    // }
                    if(!empty($product['location_id'])) {
                        $prioritySupplier = 0;
                        $deliveryTime = '3-5 dagars leveranstid';
                    } else {
                        $prioritySupplier = 1;
                        $deliveryTime = '5-7 dagars leveranstid';
                    }

                    // if(strpos($product['ben'], 'Blank')) {
                    //     $deliveryTime = '10-14 dagars leveranstid';                            
                    // } 
                    // elseif( strpos($product['ben'], 'Japan Racing') !== false ) {
                    //     $deliveryTime = '6-8 dagars leveranstid';                            
                    // }

                    $productDimension = $product['width'].'x'.$product['diameter'];

                    $product['model_name'] = str_replace(["/", "\\"], ['-', '-'], $product['model_name']);

                    $updateProduct = Product::where('main_supplier_product_id', $product['product_id'])
                                    ->where('main_supplier_id', 3)
                                    ->first();

                    // if($updateProduct->id !== 66947) 
                    //     continue;

                    if(sizeOf($updateProduct) > 0) {
                        // $updateProduct->product_brand = $product['brand_name'];
                        $updateProduct->product_model = $product['model_name'];
                        // $updateProduct->product_name = $product['description'];
                        $updateProduct->quantity = $product['stock'];
                        // $updateProduct->product_dimension = $productDimension;
                        $updateProduct->price = $product['price']/100;
                        // $updateProduct->original_price = $product['originalpris'];
                        // $updateProduct->available_at = 0; //\Carbon\Carbon::today()->format('Y-m-d');
                        $updateProduct->priority_supplier = $prioritySupplier;
                        $updateProduct->delivery_time = $deliveryTime;
                        // $updateProduct->is_shown = 1;
                        $updateProduct->is_deleted = 0;
                        $updateProduct->save();
                        $updateProduct->touch();
                        $countUpdated++;

                        if(empty($product['images'])) {
                             continue;
                        }

                        foreach($product['images'] as $key => $image) {
                            $url = "https://api.eontyre.com/images/{$image['image_id']}/big.".$image['filetype'];
                            $url = str_replace(' ', '%20', $url);
                            $file = $this->file_get_contents_curl($url);
                            $fileName = "{$image['image_id']}.".$image['filetype'];
                            $path = 'images/product/eontyre_rims/'.$fileName;
                            $thumbnail_path = 'images/product/eontyre_rims/thumb/tn-'.$fileName;
                            $absolute_path = public_path($path);
                            $absolute_thumbnail_path = public_path($thumbnail_path);
                            
                            $existImg = ProductImage::where('product_id', $updateProduct->id)->where('name', $fileName)->get();

                            Storage::append('eonTyre.log', 
                                '['.Carbon::now().'] ID: '. $updateProduct->id .
                                ". \nProductName: ". $updateProduct->product_name.
                                ". \nImageID: ". $image['image_id'].
                                ". \nFilename: ". $fileName. 
                                ". \nImageNr: ". $key
                            );

                            if(sizeOf($existImg) !== 0)
                                continue;

                            // $existImg = Storage::disk('public')->exists($path);
                            // $existImg = File::exists($path);

                            
                            
                            if(!file_exists($absolute_path)) {

                                Storage::append('eonTyre.log', 
                                    "\nabsolute_path doesnt exist"
                                );
                                if (!is_dir('images/product/eontyre_rims/')) {
                                    // dir doesn't exist, make it
                                    mkdir('images/product/eontyre_rims/');
                                    mkdir('images/product/eontyre_rims/thumb/');
                                }

                                // if(sizeof($existImg) === 0) {
                                // if(!$existImg) {
                                //     $existImg = file_put_contents($path, $file);
                                // }


                                $existImg = file_put_contents($path, $file);

                                if($existImg && exif_imagetype($path)) {
                                    Image::make($path)->resize(600, 600)->save();
                                    Image::make($path)->resize(170, 170)->save($absolute_thumbnail_path);

                                    ProductImage::where('product_id', $updateProduct->id)->where('name', 'noRimImg.jpg')->delete();


                                    $insertProductImage = new ProductImage();
                                    $insertProductImage->product_id = $updateProduct->id;
                                    $insertProductImage->name = $fileName;
                                    $insertProductImage->path = $path;
                                    $insertProductImage->thumbnail_path = $thumbnail_path;
                                    $insertProductImage->priority = $key + 1;
                                    $insertProductImage->save();
                                    continue;
                                } else {
                                    File::delete($path);

                                    Storage::append('eonTyre.log', 
                                        "\nPath and image doesnt exist"
                                    );

                                    $insertProductImage = new ProductImage();
                                    $insertProductImage->product_id = $updateProduct->id;
                                    $insertProductImage->name = "noRimImg.jpg";
                                    $insertProductImage->path = "images/product/noRimImg.jpg";
                                    $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                                    $insertProductImage->priority = $key + 1;
                                    $insertProductImage->save(); 
                                    continue;

                                }

                            } else {
                                Storage::append('eonTyre.log', 
                                    "\nabsolute_path exists"
                                );
                                $insertProductImage = new ProductImage();
                                $insertProductImage->product_id = $updateProduct->id;
                                $insertProductImage->name = $fileName;
                                $insertProductImage->path = $path;
                                $insertProductImage->thumbnail_path = $thumbnail_path;
                                $insertProductImage->priority = $key + 1;
                                $insertProductImage->save();
                            }

                        }
                        continue;
                    }
                    
                    
                    if($product['diameter'] < 12) {
                        $profit = Profit::where('size', -1)
                                    ->where('product_type', 3)
                                    ->first(['id']);
                    } elseif ($product['diameter'] > 22 || $this->isDecimal($product['diameter'] )) {
                        $profit = Profit::where('size', 1)
                                    ->where('product_type', 3)
                                    ->first(['id']);
                    } else {
                        $profit = Profit::where('size', $product['diameter'])
                                    ->where('product_type', 3)
                                    ->first(['id']);
                    }

                    $insertProduct = new Product();

                    $insertProduct->product_category_id = 2;
                    $insertProduct->product_type_id = $productType;
                    $insertProduct->profit_id = $profit->id;
                    $insertProduct->main_supplier_id = 3;
                    $insertProduct->main_supplier_product_id = $product['product_id'];
                    // $insertProduct->EANCode = $product->eanCode;
                    $insertProduct->product_description = $product['description'];
                    $insertProduct->product_brand = $product['brand_name'];
                    $insertProduct->product_name = $product['description'];
                    $insertProduct->product_model = $product['model_name'];
                    $insertProduct->product_code = $product['description'];
                    $insertProduct->product_dimension = $productDimension;
                    $insertProduct->price = $product['price']/100;
                    // $insertProduct->original_price = $product['originalpris'];
                    $insertProduct->quantity = $product['stock'];
                    // $insertProduct->ProductDimension = (int) $product->dimension;
                    $insertProduct->product_width = $product['width'];
                    // $insertProduct->product_profile = (int) $product->aspectRatio;
                    $insertProduct->product_inch = $product['diameter'];
                    $insertProduct->et = $product['rim_et'];
                    $insertProduct->bore_max = $product['rim_centre_bore'];
                    foreach ($product['rim_pcds'] as $key => $value) {
                        $insertProduct->{'pcd'.($key+1)} = $value;
                    }
                    $insertProduct->priority_supplier = $prioritySupplier;
                    $insertProduct->delivery_time = $deliveryTime;
                    // $insertProduct->available_at = \Carbon\Carbon::today()->format('Y-m-d');
                    $insertProduct->is_shown = 1;
                    // $insertProduct->storage_location = $product->locationId;
                    // $insertProduct->sub_supplier_id = $product->supplierId;
                    $insertProduct->save();
                    $countCreated++;

                    if(empty($product['images'])) {
                         $insertProductImage = new ProductImage();
                         $insertProductImage->product_id = $insertProduct->id;
                         $insertProductImage->name = "noRimImg.jpg";
                         $insertProductImage->path = "images/product/noRimImg.jpg";
                         $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                         $insertProductImage->priority = 1;
                         $insertProductImage->save(); 
                         continue;
                    }
                    foreach($product['images'] as $key => $image) {
                        $url = "https://api.eontyre.com/images/{$image['image_id']}/big.".$image['filetype'];
                        $url = str_replace(' ', '%20', $url);
                        $file = $this->file_get_contents_curl($url);
                        $fileName = "{$image['image_id']}.".$image['filetype'];
                        $path = 'images/product/eontyre_rims/'.$fileName;
                        $thumbnail_path = 'images/product/eontyre_rims/thumb/tn-'.$fileName;
                        $absolute_path = public_path($path);
                        $absolute_thumbnail_path = public_path($thumbnail_path);
                        // $existImg = ProductImage::where('name', $fileName)->get();
                        // $existImg = Storage::disk('public')->exists($path);
                        // $existImg = File::exists($path);

                        
                        
                        if(!file_exists($absolute_path)) {
                            if (!is_dir('images/product/eontyre_rims/')) {
                                // dir doesn't exist, make it
                                mkdir('images/product/eontyre_rims/');
                                mkdir('images/product/eontyre_rims/thumb/');
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
                                $insertProductImage->priority = $key + 1;
                                $insertProductImage->save();
                                continue;
                            } else {
                                File::delete($path);

                                $insertProductImage = new ProductImage();
                                $insertProductImage->product_id = $insertProduct->id;
                                $insertProductImage->name = "noRimImg.jpg";
                                $insertProductImage->path = "images/product/noRimImg.jpg";
                                $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
                                $insertProductImage->priority = $key + 1;
                                $insertProductImage->save(); 
                                continue;

                            }

                        } else {
                            $insertProductImage = new ProductImage();
                            $insertProductImage->product_id = $insertProduct->id;
                            $insertProductImage->name = $fileName;
                            $insertProductImage->path = $path;
                            $insertProductImage->thumbnail_path = $thumbnail_path;
                            $insertProductImage->priority = $key + 1;
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
        $storeCronJob->name = "EonTyre rims (store)";
        $storeCronJob->response = $response;
        $storeCronJob->created_products = $counter['created'];
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = $counter['deleted'];
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();

        return redirect()->back()->with('message', 'Produkterna har uppdaterats!');
    }

    function create_order(Request $request) {

        // dd($request->all());
        $order = Order::findOrFail($request->order_id);


        $data = [
            'webshop_id' => $this->webshop_id,
            'licenseplate' => $order->carData->reg_number,
            'delivery_option' => $request->deliveryOption,
            'customer' => [
                'name' => $request->fullName,
                'type' => $request->isCompany, //1 = company, 2=private
                'address1' => $request->streetAddress,
                'postal_code' => $request->postalCode,
                'city' => $request->city,
                'country' => $request->country,
                'email' => $request->email,
                'phone' => $request->phone,
                'reference' => $request->order_id
            ]
        ];

        foreach ($request->items as $key => $value) {
            $data['products'][] = [ 'id' => $value['article_number'], 'quantity' => $value['quantity'] ];

            $updateOrderDetails = OrderDetail::findOrFail($key);
            $updateOrderDetails->is_ordered = 1;
            $updateOrderDetails->save();
        }

        // $updateOrder = Order::findOrFail($request->order_id);
        // $updateOrder->is_ordered = 1;
        // $updateOrder->save();

        // dd($data);

        $this->call('POST', '/orders', $data);
        return redirect()->back()->with('message', 'Beställning genomförd.');
    }

    function get_price_changes($since = null) {
        if(is_null($since)) {
            $since = Carbon::now();
            $since->subHours(2);
            // $since->subMonths(4);
        } 
        // dd($since);
        $products =  $this->call('GET', '/prices/export', ['since' => $since]);

        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);

        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);
        $counter = DB::transaction(function() use ($products, $countUpdated) {
            foreach (array_chunk($products, 200) as $productChunk) {

                foreach($productChunk as $product) {
                    $updateProduct = Product::where('main_supplier_product_id', $product['product_id'])
                                    ->where('main_supplier_id', 3)
                                    ->orWhere('main_supplier_id', 2)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->price = $product['price']/100;
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
        $storeCronJob->name = "EonTyre prices (update)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

    function get_stock_changes($since = null) {
        
        if(is_null($since)) {
            $since = Carbon::now();
            $since->subHours(2);
            // $since->subMonths(4);
        } 
        // dd($since);

        $products =  $this->call('GET', '/stock/export', ['since' => $since]);

        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 12000); //300 seconds = 5 minutes
        ini_set("default_socket_timeout", 2000);

        $updatedDate = date("Y-m-d H:i:s");
        $response = "";
        $countUpdated = 0;
        $counter = [];
        \DB::disableQueryLog();

        // dd($products);
        $counter = DB::transaction(function() use ($products, $countUpdated) {
            foreach (array_chunk($products, 200) as $productChunk) {

                foreach($productChunk as $product) {
                    $updateProduct = Product::where('main_supplier_product_id', $product['product_id'])
                                    ->where('main_supplier_id', 3)
                                    ->orWhere('main_supplier_id', 2)
                                    ->first();

                    if(sizeOf($updateProduct) > 0) {
                        $updateProduct->price = $product['stock'];
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
        $storeCronJob->name = "EonTyre stock (update)";
        // $storeCronJob->response = $response;
        $storeCronJob->created_products = 0;
        $storeCronJob->updated_products = $counter['updated'];
        $storeCronJob->deleted_products = 0;
        $storeCronJob->begin_at = $updatedDate;
        $storeCronJob->save();
    }

    private function call($method, $url, $data = null) {
        $method = strtoupper($method);
        if ($method == 'GET' && $data) {
            $url .= '?';
            foreach ($data as $key => $value) {
                $url .= urlencode($key) . '=' . urlencode($value) . '&';
            }
        }
        // $obj = $data;
        $ch = curl_init($this->url . $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Api-Key: {$this->api_key}"
        ]);
        if ($method == 'POST' && $data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $output = curl_exec($ch);
        $data = json_decode($output, true);
        // $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // dd($status, $output, $data, $obj);
        if (!$output || !$data)
            throw new \Exception("Empty output or invalid json from EONTYRE");
        if ($data['err'])
            throw new \Exception("Error from EONTYRE: {$data['err']}");
        return $data['data'];
    }

}
