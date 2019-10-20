<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Product;
use App\ProductImage;
use App\ProductType;
use App\Profit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class AdminProductsController extends Controller
{
    protected $filterProducts;
    protected $tireType;
    protected $tireWidth;
    protected $tireProfile;
    protected $tireInch;
    protected $tireBrand;
    protected $tireSearch;
    protected $tirePriority;
    protected $rimET;
    protected $rimWidth;
    protected $rimInch;
    protected $rimBrand;
    protected $rimModel;
    protected $rimSearch;
    protected $rimPriority;
    protected $accessoryType;
    protected $accessorySearch;


    public function tires(Request $request)
    {
	    if($request->ajax()) { 
	    	
	    	if($request->action) {
	    		$product = Product::find($request->id);
				if($request->action == "show")
					$product->is_shown = 0;

				if($request->action == "dontShow")
					$product->is_shown = 1;

				if($request->action == "delete")
					$product->is_deleted = 1;

				$product->save();
			}

            $products = $this->getSavedTireList();

			return [
				'table' => view('admin.partials.table.tire_table', compact('products'))->render()
			];
		}

    	$products = Product::where('product_category_id', 1)
	    				->where('is_deleted', 0)
                        ->orderBy('created_at', 'DESC')
	    				->paginate(50);

    	$brands = Product::distinct()->select('product_brand')
                        ->where('product_category_id', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('product_brand')
                        ->get(); 

        $widths = Product::distinct()->select('product_width')
    					->where('product_category_id', 1)
	    				->where('is_deleted', 0)
	    				->orderBy('product_width')
    				 	->get();

        $profiles = Product::distinct()->select('product_profile')
                        ->where('product_category_id', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('product_profile')
                        ->get();

        $inches = Product::distinct()->select('product_inch')
                        ->where('product_category_id', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('product_inch')
                        ->get();                          
                       
        $images = [];
    	return view('admin.tires', compact('products', 'brands', 'widths', 'profiles', 'inches', 'images'));
    }

    public function getSavedTireList()
    {
        $this->getSearchCookie();

        $products = Product::where(function($query) {
                        isset($this->tireType) ? $query->where('product_type_id', $this->tireType):'';
                        isset($this->tireWidth) ? $query->where('product_width', $this->tireWidth):'';
                        isset($this->tireProfile) ? $query->where('product_profile', $this->tireProfile):'';
                        isset($this->tireInch) ? $query->where('product_inch', $this->tireInch):'';
                        isset($this->tireBrand) ? $query->where('product_brand', $this->tireBrand):'';
                        isset($this->tireSearch) ? $query->where('product_name', 'like', "%{$this->tireSearch}%"):'';

                        if($this->tirePriority) {
                            $query->where('priority', '>', 0);
                            // ->where('priority', '<=', 5)
                        }
                    })
                    ->where('product_category_id', 1)
                    ->where('is_deleted', 0)
                    ->orderBy('priority')
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);

        return $products;
    }

    public function rims(Request $request)
    {
        if($request->ajax()) { 
            
            if($request->action) {
                $product = Product::find($request->id);
                if($request->action == "show")
                    $product->is_shown = 0;

                if($request->action == "dontShow")
                    $product->is_shown = 1;

                if($request->action == "delete")
                    $product->is_deleted = 1;

                $product->save();
            }

            $products = $this->getSavedRimList();

            // dd($products);

            return [
                'table' => view('admin.partials.table.rim_table', compact('products'))->render()
            ];
        }

        $products = Product::where('product_category_id', 2)
                        ->where('is_deleted', 0)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(50);

        $brands = Product::distinct()->select('product_brand')
                        ->where('product_category_id', 2)
                        ->where('is_deleted', 0)
                        ->orderBy('product_brand')
                        ->get();

        $models = Product::distinct()->select('product_model')
                        ->where('product_category_id', 2)
                        ->where('is_deleted', 0)
                        ->orderBy('product_model')
                        ->get();

        $ets = Product::distinct()->select('et')
                        ->where('product_category_id', 2)
                        ->where('is_deleted', 0)
                        ->orderBy('et')
                        ->get();

        $widths = Product::distinct()->select('product_width')
                        ->where('product_category_id', 2)
                        ->where('is_deleted', 0)
                        ->orderBy('product_width')
                        ->get();

        $inches = Product::distinct()->select('product_inch')
                        ->where('product_category_id', 2)
                        ->where('is_deleted', 0)
                        ->orderBy('product_inch')
                        ->get();

        return view('admin.rims', compact('products', 'brands', 'models','ets', 'widths', 'inches'));
    }

    public function getSavedRimList()
    {
        $this->getSearchCookie();

        // \DB::enableQueryLog();

        $products = Product::where(function($query) {
                        isset($this->rimET) ? $query->where('et', $this->rimET):'';
                        isset($this->rimWidth) ? $query->where('product_width', $this->rimWidth):'';
                        isset($this->rimInch) ? $query->where('product_inch', $this->rimInch):'';
                        isset($this->rimBrand) ? $query->where('product_brand', $this->rimBrand):'';
                        isset($this->rimModel) ? $query->where('product_model', $this->rimModel):'';
                        isset($this->rimSearch) ? $query->where('product_name', 'like', "%{$this->rimSearch}%"):'';

                        if($this->rimPriority == "true") {
                            $query->where('priority', '>', 0);
                            // ->where('priority', '<=', 5)
                        }
                    })
                    ->where('product_category_id', 2)
                    ->where('is_deleted', 0)
                    ->orderBy('priority')
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);

        // $query = \DB::getQueryLog();
        // $query = end($query);
        // dd($query);

        // dd($products);
        return $products;
    }

    public function accessories(Request $request)
    {
        if($request->ajax()) { 
            
            if($request->action) {
                $product = Product::find($request->id);
                if($request->action == "show")
                    $product->is_shown = 0;

                if($request->action == "dontShow")
                    $product->is_shown = 1;

                if($request->action == "delete")
                    $product->is_deleted = 1;

                $product->save();
            }

            $productTypes = ProductType::where('id','>=', 8)
                        ->where('id','!=', 15)
                        ->where('id','!=', 16)
                        ->where('id','!=', 17)
                        ->get();

            $products = $this->getSavedAccessoryList();

            return [
                'table' => view('admin.partials.table.accessory_table', compact('products', 'productTypes'))->render()
            ];
        }

        // $productTypes = ProductType::select(DB::raw('DISTINCT(label), id'))
        //     ->whereHas('products', function($query) {
        //         $query
        //             ->where('product_category_id', '>=', 3)
        //             ->where('is_deleted', 0);
        //     })
        //     ->get();

        $productTypes = ProductType::where('id','>=', 8)
                        ->where('id','!=', 15)
                        ->where('id','!=', 16)
                        ->where('id','!=', 17)
                        ->get();

        $products = Product::where('product_category_id', '>=', 3)
                    ->where('product_category_id', '<=', 4)
                    ->where('is_deleted', 0)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);

        return view('admin.accessories', compact('products', 'productTypes'));
    }

    public function getSavedAccessoryList()
    {
        $this->getSearchCookie();

        // \DB::enableQueryLog();

        $products = Product::where(function($query) {
                        isset($this->accessoryType) ? $query->where('product_type_id', $this->accessoryType):'';
                        isset($this->accessorySearch) ? $query->where('product_name', 'like', "%{$this->accessorySearch}%"):'';
                    })
                    ->where('product_category_id', '>=', 3)
                    ->where('product_category_id', '<=', 4)
                    ->where('is_deleted', 0)
                    ->orderBy('priority')
                    ->orderBy('created_at', 'DESC')
                    ->paginate(50);

        // $query = \DB::getQueryLog();
        // $query = end($query);
        // dd($query);

        // dd($products);
        return $products;
    }

    public function exportRimsAndAccessoiresToFile()
    {
        $products = DB::table('products')->select(
            'products.id', 'main_supplier_product_id', 'product_brand', 'product_name', 'product_description', 
            'product_model', 'product_color', 'pcd1', 'pcd2', 'pcd3', 'pcd4', 'pcd5', 'pcd6', 'pcd7',
            'pcd8', 'pcd9', 'pcd10', 'pcd11', 'pcd12', 'pcd13', 'pcd14', 'pcd15', 'product_width', 
            'product_inch', 'et', 'et_min', 'bore_max', 'price', 'quantity', 'three_d_link', 
            'product_images.name AS img_name') 
            ->leftJoin('product_images', function($join) {
                $join->on('products.id', '=', 'product_images.product_id');
            })  
            ->where(function($query){
                return $query
                    ->where('product_category_id',   2)
                    ->orWhere('product_category_id', '>=', 3);
            })
            ->get();

        foreach($products as $object)
        {
            $productsArr[] =  (array) $object;
        }

        $timeStamp = date('Y-m-d');
        Excel::create('Fälgar&Tillbehör-'.$timeStamp, function($excel) use($productsArr) {
            $excel->sheet('Sheet 1', function($sheet) use($productsArr) {
                $sheet->fromArray($productsArr, null, 'A1', true);
            });
        })->download('xls');
    }


    public function exportJRRimsToFile()
    {
        $rims = DB::table('products')->select(
            'products.id', 'main_supplier_product_id', 'product_brand', 'product_name', 'product_description', 
            'product_model', 'product_color', 'pcd1', 'pcd2', 'pcd3', 'pcd4', 'pcd5', 'pcd6', 'pcd7',
            'pcd8', 'pcd9', 'pcd10', 'pcd11', 'pcd12', 'pcd13', 'pcd14', 'pcd15', 'product_width', 
            'product_inch', 'et', 'et_min', 'bore_max', 'price', 'quantity', 'three_d_link', 
            'product_images.name AS img_name') 
            ->leftJoin('product_images', function($join) {
                $join->on('products.id', '=', 'product_images.product_id');
            })  
            ->where('product_brand', 'JAPAN RACING')
            ->get();

        foreach($rims as $object)
        {
            $object->price = $object->price * 1.25;
            $rimsArr[] =  (array) $object;
        }


        // $rims = Product::select(
        //     'id', 'main_supplier_product_id', 'product_brand', 'product_name', 'product_description', 
        //     'product_model', 'product_color', 'pcd1', 'pcd2', 'pcd3', 'pcd4', 'pcd5', 'pcd6', 'pcd7',
        //     'pcd8', 'pcd9', 'pcd10', 'pcd11', 'pcd12', 'pcd13', 'pcd14', 'pcd15', 'product_width', 
        //     'product_inch', 'et', 'et_min', 'bore_max', DB::raw('price' * 1.25), 'quantity', 'three_d_link'
        //     )->where('main_supplier_id', 4)
        //     ->with(['productImages'])
        //     ->get();

        $timeStamp = date('Y-m-d');
        Excel::create('JRFälgar-'.$timeStamp, function($excel) use($rimsArr) {
            $excel->sheet('Sheet 1', function($sheet) use($rimsArr) {
                $sheet->fromArray($rimsArr, null, 'A1', true);
            });
        })->download('xls');
    }

    public function filterTires(Request $request)
    {
        // dd($request->all());
        $this->filterProducts = Product::where('product_category_id', 1)->where('is_deleted', 0);

        if(!empty($request->tireType)) {
            $this->tireType = $request->tireType;
            $this->filterByProductType($request->tireType);
        }

        if(!empty($request->width)) {
            $this->tireWidth = $request->width;
            $this->filterByWidth($request->width);
        }

        if(!empty($request->profile)) {
            $this->tireProfile = $request->profile;
            $this->filterByProfile($request->profile);
        }

        if(!empty($request->inch)) {
            $this->tireInch = $request->inch;
            $this->filterByInch($request->inch);
        }

        if(!empty($request->brand)) {
            $this->tireBrand = $request->brand;
            $this->filterByBrand($request->brand);
        }

        if(!empty($request->search)) {
            $this->tireSearch = $request->search;
            $this->filterBySearch($request->search);
        }

        if( ($request->priority == "true") ) {
            $this->tirePriority = $request->priority;
            $this->filterByPriority();
        } else {
            $this->filterProducts->orderBy('id', 'DESC');
        }

        $products =  $this->filterProducts->paginate(50);
        $this->storeSearchInCookie();

        return [
            'table' => view('admin.partials.table.tire_table', compact('products'))->render()
        ];
    }

    public function filterRims(Request $request)
    {
        $this->filterProducts = Product::where('product_category_id', 2)->where('is_deleted', 0);
        
        if(!empty($request->et)) {
            $this->rimET = $request->et;
            $this->filterByET($request->et);
        }

        if(!empty($request->width)) {
            $this->rimWidth = $request->width;
            $this->filterByWidth($request->width);
        }

        if(!empty($request->inch)) {
            $this->rimInch = $request->inch;
            $this->filterByInch($request->inch);
        }

        if(!empty($request->brand)) {
            $this->rimBrand = $request->brand;
            $this->filterByBrand($request->brand);
        }

        if(!empty($request->model)) {
            $this->rimModel = $request->model;
            $this->filterByModel($request->model);
        }

        if(!empty($request->search)) {
            $this->rimSearch = $request->search;
            $this->filterBySearch($request->search);
        }

        if( ($request->priority == "true") ) {
            $this->rimPriority = $request->priority;
            $this->filterByPriority();
        } else {
            $this->filterProducts->orderBy('id', 'DESC');
        }

        $products = $this->filterProducts->paginate(50);
        $this->storeSearchInCookie();

        return [
            'table' => view('admin.partials.table.rim_table', compact('products'))->render()
        ];
    }

    public function filterAccessories(Request $request)
    {
        $this->filterProducts = Product::where('product_category_id', '>', 2)->where('is_deleted', 0);
        
        if(!empty($request->productType)) {
            $this->accessoryType = $request->productType;
            $this->filterByProductType($request->productType);
        }

        if(!empty($request->search)) {
            $this->accessorySearch = $request->search;
            $this->filterBySearch($request->search);
        }

        $products = $this->filterProducts->paginate(50);
        $this->storeSearchInCookie();

        return [
            'table' => view('admin.partials.table.accessory_table', compact('products'))->render()
        ];
    }

    public function filterByProductType($productType)
    {
        $this->filterProducts->where('product_type_id', $productType);
    }

    public function filterByET($et)
    {
        $this->filterProducts->where('et', $et);
    }

    public function filterByWidth($width)
    {
        $this->filterProducts->where('product_width', $width);
    }

    public function filterByProfile($profile)
    {
        $this->filterProducts->where('product_profile', $profile);
    }

    public function filterByInch($inch)
    {
        $this->filterProducts->where('product_inch', $inch);
    }

    public function filterByBrand($brand)
    {
        $this->filterProducts->where('product_brand', $brand);
    }

    public function filterByModel($model)
    {
        $this->filterProducts->where('product_model', $model);
    }

    public function filterBySearch($search)
    {
        $this->filterProducts->where(function($query) use ($search) {
            return $query
                ->where('product_name', 'like', "%{$search}%");
        });
    }

    public function filterByPriority()
    {
        $this->filterProducts->where('priority', '>', 0)
                            // ->where('priority', '<=', 5)
                            ->orderBy('priority')
                            ->orderBy('created_at', 'DESC');
    }

    public function getSearchCookie()
    {
        $productFilter = json_decode(Cookie::get('productFilter'), true);
        // dd($productFilter);
        $this->tireType = $productFilter['tireType'] ? $productFilter['tireType'] : null;
        $this->tireWidth = isset($productFilter['tireWidth']) ? $productFilter['tireWidth'] : null;
        $this->tireProfile = isset($productFilter['tireProfile']) ? $productFilter['tireProfile'] : null;
        $this->tireInch = isset($productFilter['tireInch']) ? $productFilter['tireInch'] : null;
        $this->tireBrand = isset($productFilter['tireBrand']) ? $productFilter['tireBrand'] : null;
        $this->tireSearch = isset($productFilter['tireSearch']) ? $productFilter['tireSearch'] : null;
        $this->tirePriority = isset($productFilter['tirePriority']) ? $productFilter['tirePriority'] : null;
        $this->rimET = isset($productFilter['rimET']) ? $productFilter['rimET'] : null;
        $this->rimWidth = isset($productFilter['rimWidth']) ? $productFilter['rimWidth'] : null;
        $this->rimInch = isset($productFilter['rimInch']) ? $productFilter['rimInch'] : null;
        $this->rimBrand = isset($productFilter['rimBrand']) ? $productFilter['rimBrand'] : null;
        $this->rimModel = isset($productFilter['rimModel']) ? $productFilter['rimModel'] : null;
        $this->rimSearch = isset($productFilter['rimSearch']) ? $productFilter['rimSearch'] : null;
        $this->rimPriority = isset($productFilter['rimPriority']) ? $productFilter['rimPriority'] : null;
        $this->accessoryType = isset($productFilter['accessoryType']) ? $productFilter['accessoryType'] : null;
        $this->accessorySearch = isset($productFilter['accessorySearch']) ? $productFilter['accessorySearch'] : null;
        
    }

    public function storeSearchInCookie()
    {
        $productFilter['tireType'] = $this->tireType;
        $productFilter['tireWidth'] = $this->tireWidth;
        $productFilter['tireProfile'] = $this->tireProfile;
        $productFilter['tireInch'] = $this->tireInch;
        $productFilter['tireBrand'] = $this->tireBrand;
        $productFilter['tireSearch'] = $this->tireSearch;
        $productFilter['tirePriority'] = $this->tirePriority;
        $productFilter['rimET'] = $this->rimET;
        $productFilter['rimWidth'] = $this->rimWidth;
        $productFilter['rimInch'] = $this->rimInch;
        $productFilter['rimBrand'] = $this->rimBrand;
        $productFilter['rimModel'] = $this->rimModel;
        $productFilter['rimSearch'] = $this->rimSearch;
        $productFilter['rimPriority'] = $this->rimPriority;
        $productFilter['accessoryType'] = $this->accessoryType;
        $productFilter['accessorySearch'] = $this->accessorySearch;
        $productFilter = json_encode($productFilter);
        Cookie::queue('productFilter', $productFilter, 60*24);
    }

    public function isDecimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    public function storeTire(Request $request)
    {
    	// dd($request->all());
        parse_str($request->formData);
        // dd($request->all(), $request->formData, $InputName, $InputModel);
         


        $profitProductType = $DDTireType == 1 ? 1 : 2;

        // if($InputInch < 12) {
        //     $profit = Profit::where('size', -1)
        //                 ->where('product_type', $profitProductType)
        //                 ->first(['id']);
        // } elseif ($InputInch > 22 || $this->isDecimal($InputInch)) {
        //     $profit = Profit::where('size', 1)
        //                 ->where('product_type', $profitProductType)
        //                 ->first(['id']);
        // } else {
        //     $profit = Profit::where('size', $InputInch)
        //                 ->where('product_type', $profitProductType)
        //                 ->first(['id']);
        // } 

  		$brand = $DDBrand !== "-" ? $DDBrand : $InputBrand;
  		$isCTyre = isset($isCTyre) ? 1 : 0;
  		$isRunflat =  isset($isRunflat) ? 1 : 0;

        $labelArr = explode('-', $InputLabel);

        $productName = $brand." ".$InputWidth."/".$InputProfile."R".$InputInch." ".$InputLoadIndex.$InputSpeedIndex." ".$InputModel;
  
    	$createTire = new Product;
    	$createTire->product_category_id = 1;
        $createTire->main_supplier_id = 1;
        $createTire->main_supplier_product_id = $InputArticleId;
    	$createTire->product_type_id = $DDTireType;
        $createTire->profit_id = 1;
    	$createTire->product_brand = $brand;
        $createTire->product_name = $productName;
        $createTire->product_model = $InputModel;
    	$createTire->product_label = $InputLabel;
        $createTire->rolling_resistance = isset($labelArr[0]) ? $labelArr[0] : null;
        $createTire->wet_grip = isset($labelArr[1]) ? $labelArr[1] : null;
        $createTire->noise_emission_rating = isset($labelArr[2]) ? $labelArr[2] : null;
        $createTire->noise_emission_decibel = isset($labelArr[3]) ? $labelArr[3] : null;
        $createTire->product_dimension = $InputWidth.'/'.$InputProfile.'R'. $InputInch;
    	$createTire->product_width = $InputWidth;
    	$createTire->product_profile = $InputProfile;
    	$createTire->product_inch = $InputInch;
        $createTire->product_code = $InputModel . " " . $createTire->product_dimension . " " . $InputLoadIndex . $InputSpeedIndex . " " .$brand;
    	$createTire->load_index = $InputLoadIndex;
    	$createTire->speed_index = $InputSpeedIndex;
    	$createTire->tire_manufactor_date = !empty($InputManuDate) ? $InputManuDate : null;
    	$createTire->price = $InputWebPrice * 0.8;
    	$createTire->original_price = $InputOriginalPrice * 0.8;
    	// $createTire->storage_price = $InputStoragePrice;
    	$createTire->quantity = $InputQuantity;
        $createTire->min_orderble_quantity = $InputMinOrderbleQuantity < 1 ? 1 : $InputMinOrderbleQuantity;
    	$createTire->storage_location = $InputStorageLocation;
    	$createTire->discount1 = $InputDiscount1;
    	$createTire->discount2 = $InputDiscount2;
    	$createTire->discount3 = $InputDiscount3;
        $createTire->discount4 = $InputDiscount4;
    	$createTire->priority = $DDPriority;
    	$createTire->is_ctyre  = $isCTyre;
    	$createTire->is_runflat = $isRunflat;
        $createTire->video_link = $InputVideoLink;
        // $createTire->three_d_link = $InputThreeDLink;
        $createTire->product_description = $description;
        $createTire->available_at = !empty($InputAvailableAt) ? $InputAvailableAt : null;
        $createTire->delivery_time = $InputDeliveryTime;
        $createTire->is_shown = 1;
    	$createTire->save();

        $files = $request->file('file');

        if($files) {
            foreach ($files as $key => $file) {
                
                $fileName = $file->getClientOriginalName();
                
                $path = 'images/product/local/'. $fileName;
                $thumbnail_path = 'images/product/local/thumb/tn-'. $fileName;

                if (!is_dir('images/product/local/')) {
                    // dir doesn't exist, make it
                    mkdir('images/product/local/');
                    mkdir('images/product/local/thumb/');
                }

                $existImg = ProductImage::where('name', $fileName)->get(); 
                if(sizeof($existImg) === 0) {
                    $file->move('images/product/local/', $fileName);                
                }

                $absolute_thumbnail_path = public_path($thumbnail_path);

                Image::make($path)->resize(600, 600)->save();
                Image::make($path)->resize(170, 170)->save($thumbnail_path);
                
                if($existImg) {
                    $insertProductImage = new ProductImage();
                    $insertProductImage->product_id = $createTire->id;
                    $insertProductImage->name = $fileName;
                    $insertProductImage->path = $path;
                    $insertProductImage->thumbnail_path = $thumbnail_path;
                    $insertProductImage->priority = $key + 1;
                    $insertProductImage->save(); 
                }
            }
        } else {
            $insertProductImage = new ProductImage();
            $insertProductImage->product_id = $createTire->id;
            $insertProductImage->name = "noTireImg.jpg";
            $insertProductImage->path = "images/product/noTireImg.jpg";
            $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
            $insertProductImage->priority = 1;
            $insertProductImage->save(); 
        }
        // foreach($request->file as $key => $img) {
        //     $fileName = $img['originalName'];
        //     // $url = $product['image-url'];
        //     // $file = file_get_contents($url);
        //     // $fileName = basename($url);
        //     // dd($fileName, $url);
        //     $path = 'images/product/'. $createRim->product_brand . '/';
        //     $thumb_path = $path . 'thumb/';

        //     if (!is_dir($path)) {
        //         // dir doesn't exist, make it
        //         mkdir($path);
        //         mkdir($thumb_path);
        //     }

        //     $existImg = ProductImage::where('name', $fileName)->get(); 
        //     if(sizeof($existImg) === 0) {
        //         $existImg = file_put_contents($path . $fileName, $file);
        //         file_put_contents($thumb_path . $fileName, $file);
        //     }

        //     // Image::make($path . $fileName)->resize(600, 600)->save();
        //     // Image::make($thumb_path . $fileName)->resize(170, 170)->save();
            
        //     if($existImg) {
        //         $insertProductImage = new ProductImage();
        //         $insertProductImage->product_id = $insertProduct->id;
        //         $insertProductImage->name = $fileName;
        //         $insertProductImage->path = $path;
        //         $insertProductImage->thumbnail_path = $thumb_path;
        //         $insertProductImage->priority = 1;
        //         $insertProductImage->save(); 
        //     }
        // }
 

        session()->flash('message', 'Ett nytt däck har skapats. Du kan undersöka produktsidan via <a href="'. URL::to('/'. $createTire->productType->name . '/' . $createTire->id) .  '"> <b>Länk</b></a>');
        
        return;

    	// $productCount = $products = Product::where('product_category_id', 1)
	    // 				->where('is_deleted', 0)
	    // 				->paginate(10)
	    // 				->total();

    	// return Response::json([
    	// 		'message' => 'Ett nytt däck har skapats. Du kan undersöka produktsidan via <a href="'. URL::to('/'. $createTire->productType->name . '/' . $createTire->id) .  '"> <b>Länk</b></a>',
    	// 		'productCount' => $productCount,
    	// ]);
    }

    public function showUpdateTireModal(Request $request)
    {
        $product = Product::find($request->id);
        $images = $product->productImages;

    	$brands = Product::distinct()->select('product_brand')
    					->where('product_category_id', 1)
	    				->where('is_deleted', 0)
	    				->orderBy('product_brand')
    				 	->get();

    	return [
            'updateTireModal' => view('admin/partials/form/update_tire_modal', compact('brands'))->render(),
            'product' => $product,
            'images' => $images,
    		'brands' => $brands,
    	];
    }

    public function updateTire(Request $request)
    {
        // dd($request->all());
        parse_str($request->formData);
        // dd($request->all(), $request->formData, $EditInputBrand, $EditInputName);
         

        $brand = $EditDDBrand !== "-" ? $EditDDBrand : $EditInputBrand;
        $isCTyre = isset($EditIsCTyre) ? 1 : 0;
        $isRunflat =  isset($EditIsRunflat) ? 1 : 0;

        $labelArr = explode('-', $EditInputLabel);

        $productName = $brand." ".$EditInputWidth."/".$EditInputProfile."R".$EditInputInch." ".$EditInputLoadIndex.$EditInputSpeedIndex." ".$EditInputModel;
  
        $updateTire = Product::find( $EditProductID );
        $updateTire->product_category_id = 1;
        $updateTire->product_type_id = $EditDDTireType;
        // $updateTire->main_supplier_id = 1;
        $updateTire->main_supplier_product_id = $EditInputArticleId;
        $updateTire->product_brand = $brand;

        if($updateTire->main_supplier_id == 1) {
            $updateTire->product_name = $productName;
        }
        $updateTire->product_model = $EditInputModel;
        $updateTire->product_label = $EditInputLabel;
        $updateTire->rolling_resistance = isset($labelArr[0]) ? $labelArr[0] : null;
        $updateTire->wet_grip = isset($labelArr[1]) ? $labelArr[1] : null;
        $updateTire->noise_emission_rating = isset($labelArr[2]) ? $labelArr[2] : null;
        $updateTire->noise_emission_decibel = isset($labelArr[3]) ? $labelArr[3] : null;
        $updateTire->product_dimension = $EditInputWidth.'/'.$EditInputProfile.'R'. $EditInputInch;
        $updateTire->product_width = $EditInputWidth;
        $updateTire->product_profile = $EditInputProfile;
        $updateTire->product_inch = $EditInputInch;
        $updateTire->load_index = $EditInputLoadIndex;
        $updateTire->speed_index = $EditInputSpeedIndex;
        $updateTire->tire_manufactor_date = !empty($EditInputManuDate) ? $EditInputManuDate : null;
        $updateTire->price = $EditInputWebPrice * 0.8;
        $updateTire->original_price = $EditInputOriginalPrice * 0.8;
        // $updateTire->storage_price = $EditInputStoragePrice;
        $updateTire->quantity = $EditInputQuantity;
        $updateTire->min_orderble_quantity = $EditInputMinOrderbleQuantity < 1 ? 1 : $EditInputMinOrderbleQuantity;
        $updateTire->storage_location = $EditInputStorageLocation;
        $updateTire->discount1 = $EditInputDiscount1;
        $updateTire->discount2 = $EditInputDiscount2;
        $updateTire->discount3 = $EditInputDiscount3;
        $updateTire->discount4 = $EditInputDiscount4;
        $updateTire->priority = $EditDDPriority;
        $updateTire->is_ctyre  = $isCTyre;
        $updateTire->is_runflat = $isRunflat;
        $updateTire->video_link = $EditInputVideoLink;
        // $updateTire->three_d_link = $EditInputThreeDLink;
        $updateTire->product_description = $EditDescription;
        $updateTire->available_at = !empty($EditInputAvailableAt) ? $EditInputAvailableAt : null;
        $updateTire->delivery_time = $EditInputDeliveryTime;
        $updateTire->save();

        $files = $request->file('file');
        if($files) {
            $countCurrentImages = sizeof(ProductImage::where('product_id', $updateTire->id)->get()); 
            // max 1 images per product is allowed
            if($countCurrentImages < 1) { 
                
                foreach ($files as $key => $file) {
                    
                    $fileName = $file->getClientOriginalName();

                    $path = 'images/product/local/'. $fileName;
                    $thumbnail_path = 'images/product/local/thumb/tn-'. $fileName;

                    if (!is_dir('images/product/local/')) {
                        // dir doesn't exist, make it
                        mkdir('images/product/local/');
                        mkdir('images/product/local/thumb/');
                    }

                    $existImg = ProductImage::where('name', $fileName)->get(); 
                    if(sizeof($existImg) === 0) {
                        $file->move('images/product/local/', $fileName);                
                    }

                    $absolute_thumbnail_path = public_path($thumbnail_path);

                    Image::make($path)->resize(600, 600)->save();
                    Image::make($path)->resize(170, 170)->save($thumbnail_path);

                    if($existImg) {
                        $insertProductImage = new ProductImage();
                        $insertProductImage->product_id = $updateTire->id;
                        $insertProductImage->name = $fileName;
                        $insertProductImage->path = $path;
                        $insertProductImage->thumbnail_path = $thumbnail_path;
                        $insertProductImage->priority = 1;
                        $insertProductImage->save(); 
                    }
                }
            }
        }

        $existImg = ProductImage::where('product_id', $updateTire->id)->get();

        if(empty($files) && sizeOf($existImg) == 0 ) {
            $insertProductImage = new ProductImage();
            $insertProductImage->product_id = $updateTire->id;
            $insertProductImage->name = "noTireImg.jpg";
            $insertProductImage->path = "images/product/noTireImg.jpg";
            $insertProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
            $insertProductImage->priority = 1;
            $insertProductImage->save(); 
        }

        // $productCount = $products = Product::where('product_category_id', 1)
        //                 ->where('is_deleted', 0)
        //                 ->paginate(10)
        //                 ->total();

        $this->getSearchCookie();

        $products = $this->getSavedTireList();
        return [
            'table' => view('admin.partials.table.tire_table', compact('products'))->render(),
            'message' => 'Produkten har uppdaterats. Du kan undersöka produktsidan via <a href="'. URL::to('/'. $updateTire->productType->name . '/' . $updateTire->id) .  '"> <b>Länk</b></a>',
            // 'productCount' => $productCount,
        ];
    }


    public function storeRim(Request $request)
    {
        // $this->validate($request, [
        //     'image' => 'mime:jpeg,jpg,png,gif'
        // ]);
        
        // dd(count($request->file('file')));
        // dd(key($request->file('file')));

        parse_str($request->formData);
    	// dd($request->all(), $request->formData, $InputName, $InputModel);
        
        $profitProductType = 3;

        // if($InputInch < 12) {
        //     $profit = Profit::where('size', -1)
        //                 ->where('product_type', $profitProductType)
        //                 ->first(['id']);
        // } elseif ($InputInch > 22 || $this->isDecimal($InputInch)) {
        //     $profit = Profit::where('size', 1)
        //                 ->where('product_type', $profitProductType)
        //                 ->first(['id']);
        // } else {
        //     $profit = Profit::where('size', $InputInch)
        //                 ->where('product_type', $profitProductType)
        //                 ->first(['id']);
        // }  

  		$brand = $DDBrand !== "-" ? $DDBrand : $InputBrand;

    	$createRim = new Product;
    	$createRim->product_category_id = 2;
        $createRim->product_type_id = 4;
        $createRim->main_supplier_id = 1;
        $createRim->main_supplier_product_id = $InputArticleId;
    	$createRim->profit_id = 1; //$profit->id;
    	$createRim->product_brand = $brand;
        $createRim->product_dimension = $InputWidth.'x'.$InputInch;
        // if(!empty($InputMinET)) {
        // 	$createRim->product_name = $brand." ".$InputModel." ".$createRim->product_dimension." ET".$InputMinET."-".$InputMaxET." ".$InputColor;
        // } else {
            $createRim->product_name = $brand." ".$InputModel." ".$createRim->product_dimension." ET".$InputMaxET." ".$InputColor;
        // }
        $createRim->product_model = $InputModel;
    	$createRim->product_code = $InputModel;
    	$createRim->product_color = $InputColor;
    	$createRim->pcd1 = $InputPCD1;
    	$createRim->pcd2 = $InputPCD2;
    	$createRim->pcd3 = $InputPCD3;
    	$createRim->pcd4 = $InputPCD4;
    	$createRim->pcd5 = $InputPCD5;
    	$createRim->pcd6 = $InputPCD6;
    	$createRim->pcd7 = $InputPCD7;
    	$createRim->pcd8 = $InputPCD8;
        $createRim->pcd9 = $InputPCD9;
        $createRim->pcd10 = $InputPCD10;
        $createRim->pcd11 = $InputPCD11;
        $createRim->pcd12 = $InputPCD12;
        $createRim->pcd13 = $InputPCD13;
        $createRim->pcd14 = $InputPCD14;
    	$createRim->pcd15 = $InputPCD15;
    	$createRim->product_width = $InputWidth;
    	$createRim->product_inch = $InputInch;
        $createRim->et = $InputMaxET;
    	$createRim->et_min = !empty($InputMinET) ? $InputMinET : $InputMaxET;
    	// $createRim->bore_min = $InputBoreMin;
    	$createRim->bore_max = $InputBoreMax;
    	$createRim->price = $InputWebPrice * 0.8;
    	$createRim->original_price = $InputOriginalPrice * 0.8;
    	// $createRim->storage_price = $InputStoragePrice;
    	$createRim->quantity = $InputQuantity;
        $createRim->min_orderble_quantity = $InputMinOrderbleQuantity < 1 ? 1 : $InputMinOrderbleQuantity;
    	// $createRim->storage_location = $InputStorageLocation;
    	$createRim->discount1 = $InputDiscount1;
    	$createRim->discount2 = $InputDiscount2;
    	$createRim->discount3 = $InputDiscount3;
    	$createRim->discount4 = $InputDiscount4;
        $createRim->video_link = $InputVideoLink;
        // $createRim->three_d_link = $InputThreeDLink;
        $createRim->product_description = $description;
        $createRim->priority = $DDPriority;
        $createRim->available_at = !empty($InputAvailableAt) ? $InputAvailableAt : null;
        $createRim->delivery_time = $InputDeliveryTime;
    	$createRim->is_shown = 1;
    	$createRim->save();


        $files = $request->file('file');

        if($files) {
            $countImages = ProductImage::where('product_id', $createRim->id)->count();
            $allowedCount = 3 - $countImages; // bara 3 bilder tillåtes

            foreach ($files as $key => $file) {
                if($allowedCount <= $key) {
                    break;
                }

                $fileName = $file->getClientOriginalName();
                $path = 'images/product/local/'. $fileName;
                $thumbnail_path = 'images/product/local/thumb/tn-'. $fileName;
                $absolute_path = public_path($path);
                $absolute_thumbnail_path = public_path($thumbnail_path);

                if (!is_dir('images/product/local/')) {
                    // dir doesn't exist, make it
                    mkdir('images/product/local/');
                    mkdir('images/product/local/thumb/');
                }


                // $existImg = ProductImage::where('name', $fileName)->get();
                // if(sizeof($existImg) === 0) {
                //     $file->move('images/product/local/', $fileName);                
                // }
                $existImg = File::exists($path); 
                if(!$existImg) {
                    $file->move('images/product/local/', $fileName);
                    // $existImg = file_put_contents('images/product/local/', $file);
                    $existImg = true;
                }

                Image::make($path)->resize(600, 600)->save();
                Image::make($path)->resize(170, 170)->save($thumbnail_path);
                
                if($existImg) {
                    $insertProductImage = new ProductImage();
                    $insertProductImage->product_id = $createRim->id;
                    $insertProductImage->name = $fileName;
                    $insertProductImage->path = $path;
                    $insertProductImage->thumbnail_path = $thumbnail_path;
                    $insertProductImage->priority = $key + $countImages + 1;
                    $insertProductImage->save(); 
                }
            }
        } else {
            $insertProductImage = new ProductImage();
            $insertProductImage->product_id = $createRim->id;
            $insertProductImage->name = "noRimImg.jpg";
            $insertProductImage->path = "images/product/noRimImg.jpg";
            $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
            $insertProductImage->priority = 1;
            $insertProductImage->save(); 
        }
        // foreach($request->file as $key => $img) {
        //     $fileName = $img['originalName'];
        //     // $url = $product['image-url'];
        //     // $file = file_get_contents($url);
        //     // $fileName = basename($url);
        //     // dd($fileName, $url);
        //     $path = 'images/product/'. $createRim->product_brand . '/';
        //     $thumb_path = $path . 'thumb/';

        //     if (!is_dir($path)) {
        //         // dir doesn't exist, make it
        //         mkdir($path);
        //         mkdir($thumb_path);
        //     }

        //     $existImg = ProductImage::where('name', $fileName)->get(); 
        //     if(sizeof($existImg) === 0) {
        //         $existImg = file_put_contents($path . $fileName, $file);
        //         file_put_contents($thumb_path . $fileName, $file);
        //     }

        //     // Image::make($path . $fileName)->resize(600, 600)->save();
        //     // Image::make($thumb_path . $fileName)->resize(170, 170)->save();
            
        //     if($existImg) {
        //         $insertProductImage = new ProductImage();
        //         $insertProductImage->product_id = $insertProduct->id;
        //         $insertProductImage->name = $fileName;
        //         $insertProductImage->path = $path;
        //         $insertProductImage->thumbnail_path = $thumb_path;
        //         $insertProductImage->priority = 1;
        //         $insertProductImage->save(); 
        //     }
        // }
 


        session()->flash('message', 'En ny fälg har skapats. Du kan undersöka produktsidan via <a href="'. URL::to('/falgar/' . $createRim->id) .  '"> <b>Länk</b></a>');
        
        return;
        
    	// $productCount = $products = Product::where('product_category_id', 2)
	    // 				->where('is_deleted', 0)
	    // 				->paginate(10)
	    // 				->total();
        // return Response::json([
    	// 		'message' => 'Ett nytt däck har skapats. Du kan undersöka produktsidan via <a href="'. URL::to('/falgar/' . $createRim->id) .  '"> <b>Länk</b></a>',
    	// 		'productCount' => $productCount,
    	// ]);
    }

    public function showUpdateRimModal(Request $request)
    {
        $id = $request->id;
        $product = Product::find($request->id);
        $images = ProductImage::where('product_id', $request->id)->orderBy('priority')->get();

        $brands = Product::distinct()->select('product_brand')
                        ->where('product_category_id', 2)
                        ->where('is_deleted', 0)
                        ->orderBy('product_brand')
                        ->get();

        return [
            'updateRimModal' => view('admin/partials/form/update_rim_modal', compact('brands'))->render(),
            'product' => $product,
            'images' => $images,
            'brands' => $brands,
        ];
    }

    public function updateRim(Request $request)
    {
        // dd($request->all());
        
        parse_str($request->formData);
        // dd($request->all(), $request->formData, $EditDDBrand, $EditInputBrand);
        

        $brand = $EditDDBrand !== "-" ? $EditDDBrand : $EditInputBrand;
  
        $updateRim = Product::find( $EditProductID );
        $updateRim->product_category_id = 2;
        $updateRim->product_type_id = 4;
        // $updateRim->main_supplier_id = 1;
        $updateRim->main_supplier_product_id = $EditInputArticleId;
        $updateRim->product_brand = $brand;
        $updateRim->product_dimension = $EditInputWidth.'x'.$EditInputInch;
        if($updateRim->main_supplier_id == 1) {
            // if($EditInputMinET !== $EditInputMaxET ) {
            //     $updateRim->product_name = $brand." ".$EditInputModel." ".$updateRim->product_dimension." ET".$EditInputMinET."-".$EditInputMaxET." ".$EditInputColor;
            // } else {
                // $updateRim->product_name = $brand." ".$EditInputModel." ".$updateRim->product_dimension." ET".$EditInputMaxET." ".$EditInputColor;
            // }
        }
        $updateRim->product_name = $EditInputName;
        $updateRim->product_model = $EditInputModel;
        $updateRim->product_color = $EditInputColor;
        $updateRim->pcd1 = $EditInputPCD1;
        $updateRim->pcd2 = $EditInputPCD2;
        $updateRim->pcd3 = $EditInputPCD3;
        $updateRim->pcd4 = $EditInputPCD4;
        $updateRim->pcd5 = $EditInputPCD5;
        $updateRim->pcd6 = $EditInputPCD6;
        $updateRim->pcd7 = $EditInputPCD7;
        $updateRim->pcd8 = $EditInputPCD8;
        $updateRim->pcd9 = $EditInputPCD9;
        $updateRim->pcd10 = $EditInputPCD10;
        $updateRim->pcd11 = $EditInputPCD11;
        $updateRim->pcd12 = $EditInputPCD12;
        $updateRim->pcd13 = $EditInputPCD13;
        $updateRim->pcd14 = $EditInputPCD14;
        $updateRim->pcd15 = $EditInputPCD15;
        $updateRim->product_width = $EditInputWidth;
        $updateRim->product_inch = $EditInputInch;
        $updateRim->et = $EditInputMaxET;
        $updateRim->et_min = !empty($EditInputMinET) ?  $EditInputMinET : $EditInputMaxET;
        // $updateRim->bore_min = $EditInputBoreMin;
        $updateRim->bore_max = $EditInputBoreMax;
        $updateRim->price = $EditInputWebPrice * 0.8;
        $updateRim->original_price = $EditInputOriginalPrice * 0.8;
        // $updateRim->storage_price = $EditInputStoragePrice;
        $updateRim->quantity = $EditInputQuantity;
        $updateRim->min_orderble_quantity = $EditInputMinOrderbleQuantity < 1 ? 1 : $EditInputMinOrderbleQuantity;
        // $updateRim->storage_location = $EditInputStorageLocation;
        $updateRim->discount1 = $EditInputDiscount1;
        $updateRim->discount2 = $EditInputDiscount2;
        $updateRim->discount3 = $EditInputDiscount3;
        $updateRim->discount4 = $EditInputDiscount4;
        $updateRim->video_link = $EditInputVideoLink;
        // $updateRim->three_d_link = $EditInputThreeDLink;
        $updateRim->product_description = $EditDescription;
        $updateRim->priority = $EditDDPriority;
        $updateRim->available_at = !empty($EditInputAvailableAt) ? $EditInputAvailableAt : null;
        $updateRim->delivery_time = $EditInputDeliveryTime;
        $updateRim->save();

        $files = $request->file('file');

        if($files) {
            $countImages = ProductImage::where('product_id', $updateRim->id)->count();
            $allowedCount = 3 - $countImages; // bara 3 bilder tillåtes

            if($allowedCount) {
                $counter = 1;
                $images = ProductImage::where('product_id', $updateRim->id)->get();
                foreach ($images as $image) {
                    $image->priority = $counter;
                    $image->save();
                    $counter++;
                }
            }

            foreach ($files as $key => $file) {
                if($allowedCount <= $key) {
                    break;
                }

                $fileName = $file->getClientOriginalName();
                $path = 'images/product/local/'. $fileName;
                $thumbnail_path = 'images/product/local/thumb/tn-'. $fileName;
                $absolute_path = public_path($path);
                $absolute_thumbnail_path = public_path($thumbnail_path);

                if (!is_dir('images/product/local/')) {
                    // dir doesn't exist, make it
                    mkdir('images/product/local/');
                    mkdir('images/product/local/thumb/');
                }


                // $existImg = ProductImage::where('name', $fileName)->get();
                // if(sizeof($existImg) === 0) {
                //     $file->move('images/product/local/', $fileName);                
                // }
                $existImg = File::exists($path); 
                if(!$existImg) {
                    $file->move('images/product/local/', $fileName);
                    // $existImg = file_put_contents('images/product/local/', $file);
                    $existImg = true;
                }

                Image::make($path)->resize(600, 600)->save();
                Image::make($path)->resize(170, 170)->save($thumbnail_path);
                
                if($existImg) {
                    $insertProductImage = new ProductImage();
                    $insertProductImage->product_id = $updateRim->id;
                    $insertProductImage->name = $fileName;
                    $insertProductImage->path = $path;
                    $insertProductImage->thumbnail_path = $thumbnail_path;
                    $insertProductImage->priority = $key + $countImages + 1;
                    $insertProductImage->save(); 
                }
            }
        } 

        $existImg = ProductImage::where('product_id', $updateRim->id)->get();

        if(empty($files) && sizeOf($existImg) == 0 ) {
            $insertProductImage = new ProductImage();
            $insertProductImage->product_id = $updateRim->id;
            $insertProductImage->name = "noRimImg.jpg";
            $insertProductImage->path = "images/product/noRimImg.jpg";
            $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
            $insertProductImage->priority = 1;
            $insertProductImage->save(); 
        }

        // if($files) {
        //     $countCurrentImages = sizeof(ProductImage::where('product_id', $updateRim->id)->get()); 
        //     // max 1 images per product is allowed
        //     if($countCurrentImages < 1) { 
                
        //         foreach ($files as $key => $file) {
                    
        //             $fileName = $file->getClientOriginalName();
        //             $path = 'images/product/local/'. $fileName;
        //             $thumbnail_path = 'images/product/local/thumb/tn-'. $fileName;
        //             $absolute_path = public_path($path);
        //             $absolute_thumbnail_path = public_path($thumbnail_path);

        //             if (!is_dir('images/product/local/')) {
        //                 // dir doesn't exist, make it
        //                 mkdir('images/product/local/');
        //                 mkdir('images/product/local/thumb/');
        //             }

        //             $existImg = ProductImage::where('name', $fileName)->get(); 
        //             if(sizeof($existImg) === 0) {
        //                 $file->move('images/product/local/', $fileName);                
        //             }

        //             Image::make($path)->resize(600, 600)->save();
        //             Image::make($path)->resize(170, 170)->save($thumbnail_path);
                    
        //             if($existImg) {
        //                 $insertProductImage = new ProductImage();
        //                 $insertProductImage->product_id = $updateRim->id;
        //                 $insertProductImage->name = $fileName;
        //                 $insertProductImage->path = $path;
        //                 $insertProductImage->thumbnail_path = $thumbnail_path;
        //                 $insertProductImage->priority = 1;
        //                 $insertProductImage->save(); 
        //             }
        //         }
        //     }
        // }

        // $existImg = ProductImage::where('product_id', $updateRim->id)->get();

        // if(empty($files) && sizeOf($existImg) == 0 ) {
        //     $insertProductImage = new ProductImage();
        //     $insertProductImage->product_id = $updateRim->id;
        //     $insertProductImage->name = "noRimImg.jpg";
        //     $insertProductImage->path = "images/product/noRimImg.jpg";
        //     $insertProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
        //     $insertProductImage->priority = 1;
        //     $insertProductImage->save(); 
        // }

        $products = $this->getSavedRimList();
        return [
            'table' => view('admin.partials.table.rim_table', compact('products'))->render(),
            'message' => 'Produkten har uppdaterats. Du kan undersöka produktsidan via <a href="'. URL::to('/falgar/' . $updateRim->id) .  '"> <b>Länk</b></a>',
            // 'productCount' => $productCount,
        ];

        // return Response::json([
        //         'message' => 'Produkten har uppdaterats. Du kan undersöka produktsidan via <a href="'. URL::to('/falgar/' . $updateRim->id) .  '"> <b>Länk</b></a>',
        //         'productCount' => $productCount,
        // ]);
    }

    public function storeAccessory(Request $request)
    {
        // $this->validate($request, [
        //     'image' => 'mime:jpeg,jpg,png,gif'
        // ]);

        parse_str($request->formData);
        // dd($request->all(), $request->formData, $InputName, $InputDimension);
        
        $fileName = "wrench.png";

        if($DDType == 7) {
            $fileName = "TPMS_1.jpg";
            $productCode = "TPMS DÄCKTRYCKSSENSOR BILANPASSAD EFTER REG.NUMMER";
        }
        if($DDType == 8){
            $fileName = "nuts.jpg";
            $productCode = "MUTTRAR";
        }
        if($DDType == 9) {
            $fileName = "bolts.jpg";
            $productCode = "BULTAR";
        }
        if($DDType == 10) {
            $fileName = "rings.jpg";
            $productCode = "Navringar";
        }
        if($DDType == 11) {
            $fileName = "monteringsKit.jpg";
            $productCode = "MONTERINGSKIT: BULT/MUTTER/NAVRING";
        }
        if($DDType == 12) {
            $fileName = "lock-kit.jpg";
            $productCode = "LÅSBULT/ MUTTER KIT";
        }
        if($DDType == 13) {
            $fileName = "spacer.jpg";
            $productCode = "SPACERS";
        }
        if($DDType == 14) {
            $fileName = "wrench.png";
            $productCode = "TJÄNSTER";
        }
        if($DDType == 16) {
            $fileName = "wrench.png";
            $productCode = "SPECIAL BULT / MUTTER";
        }
        if($DDType == 17) {
            $fileName = "wrench.png";
            $productCode = "TÄCKKÅPOR BULT / MUTTER";
        }

        if($DDType == 18) {
            $fileName = "wrench.png";
            $productCode = "Övrigt";
        }
  

        $createAccessory = new Product;
        $createAccessory->product_category_id = 3;
        $createAccessory->product_type_id = $DDType;
        $createAccessory->main_supplier_id = 1;
        $createAccessory->profit_id = 1;
        $createAccessory->main_supplier_product_id = $InputArticleId;
        $createAccessory->product_name = $InputName;
        $createAccessory->product_code = $productCode;
        $createAccessory->product_color = $InputName;
        $createAccessory->product_dimension = $InputDimension;
        $createAccessory->product_inner_dimension = isset($InputDimension2) ? $InputDimension2 : "";
        $createAccessory->price = $InputWebPrice * 0.8;
        $createAccessory->original_price = $InputOriginalPrice * 0.8;
        $createAccessory->storage_price = $InputStoragePrice * 0.8;
        $createAccessory->quantity = $InputQuantity;
        // $createAccessory->storage_location = $InputStorageLocation;
        $createAccessory->discount1 = $InputDiscount1;
        $createAccessory->discount2 = $InputDiscount2;
        $createAccessory->discount3 = $InputDiscount3;
        $createAccessory->discount4 = $InputDiscount4;
        $createAccessory->product_description = $description;
        // $createAccessory->priority = $DDPriority;
        // $createAccessory->available_at = !empty($InputAvailableAt) ? $InputAvailableAt : null;
        $createAccessory->delivery_time = "5-7 dagars leveranstid";
        $createAccessory->is_shown = 1;
        $createAccessory->save();

        
        
        $path = 'images/product/accessories/'. $fileName;
        // $thumbnail_path = 'images/product/local/thumb/tn-'. $fileName;
        $thumbnail_path = $path;
        $absolute_path = public_path($path);
        $absolute_thumbnail_path = public_path($thumbnail_path);

        $insertProductImage = new ProductImage();
        $insertProductImage->product_id = $createAccessory->id;
        $insertProductImage->name = $fileName;
        $insertProductImage->path = $path;
        $insertProductImage->thumbnail_path = $thumbnail_path;
        $insertProductImage->priority = 1;
        $insertProductImage->save(); 

        session()->flash('message', 'Ett nytt tillbehör har skapats. Du kan undersöka produktsidan via <a href="'. URL::to('/'. $createAccessory->productType->name.'/' . $createAccessory->id) .  '"> <b>Länk</b></a>');
        
        return response()->json();
    }

    public function showUpdateAccessoryModal(Request $request)
    {
        $product = Product::find($request->id);
        $productType = $product->productType->label;

        return [
            'product' => $product,
            'productType' => $productType
        ];
    }

    public function updateAccessory(Request $request)
    {
        // $this->validate($request, [
        //     'image' => 'mime:jpeg,jpg,png,gif'
        // ]);

        parse_str($request->formData);
        // dd($request->all(), $request->formData, $EditInputName, $EditInputDimension, $EditProductID);
  

        $updateAccessory = Product::findOrFail($EditProductID);
        $updateAccessory->main_supplier_product_id = $EditInputArticleId;
        $updateAccessory->product_name = $EditInputName;
        $updateAccessory->product_color = $EditInputName;
        $updateAccessory->product_dimension = $EditInputDimension;
        $updateAccessory->product_inner_dimension = isset($EditInputDimension2) ? $EditInputDimension2 : "";
        $updateAccessory->price = $EditInputWebPrice * 0.8;
        $updateAccessory->original_price = $EditInputOriginalPrice * 0.8;
        $updateAccessory->storage_price = $EditInputStoragePrice * 0.8;
        $updateAccessory->quantity = $EditInputQuantity;
        // $updateAccessory->storage_location = $EditInputStorageLocation;
        $updateAccessory->discount1 = $EditInputDiscount1;
        $updateAccessory->discount2 = $EditInputDiscount2;
        $updateAccessory->discount3 = $EditInputDiscount3;
        $updateAccessory->discount4 = $EditInputDiscount4;
        $updateAccessory->product_description = $EditDescription;
        // $updateAccessory->priority = $DDPriority;
        // $updateAccessory->available_at = !empty($InputAvailableAt) ? $InputAvailableAt : null;
        $updateAccessory->delivery_time = "5-7 dagars leveranstid";
        $updateAccessory->is_shown = 1;
        $updateAccessory->save();

        $products = $this->getSavedAccessoryList();


        return [
            'message' => 'Produkten har uppdaterats. Du kan undersöka produktsidan via  <a href="'. URL::to('/'. $updateAccessory->productType->name.'/' . $updateAccessory->id) .  '"> <b>Länk</b></a>',
            'table' => view('admin.partials.table.accessory_table', compact('products'))->render()
        ];
    }


    public function showUpdateMediaTireModal(Request $request)
    {
        $path = 'images/product/media/tires/';
        $directory = public_path($path);
        $images = File::files($directory);

        // dd($directory, $images, basename($images[0]));

        return [
            'mediaImages' => view('admin/partials/form/media_images', compact('images', 'path'))->render(),
        ];
    }

    public function updateMediaTire(Request $request)
    {
        // dd($request->all());

        $path = 'images/product/media/tires/'.$request->fileName;
        $thumbnail_path = 'images/product/media/tires/thumb/tn-'.$request->fileName;

        if(!File::exists($path))
            return;

        if(empty($request->fileName))
            return;

        $this->filterProducts = Product::where('product_category_id', 1);

        if(!empty($request->tireType)) {
            $this->filterByProductType($request->tireType);
        }

        if(!empty($request->brand)) {
            $this->filterByBrand($request->brand);
        }

        if(!empty($request->search)) {
            $this->filterBySearch($request->search);
        }

        $products =  $this->filterProducts->get();
        
        foreach ($products as $product) {
            ProductImage::where('product_id', $product->id)->delete();

            $insertProductImage = new ProductImage();
            $insertProductImage->product_id = $product->id;
            $insertProductImage->name = $request->fileName;
            $insertProductImage->path = $path;
            $insertProductImage->thumbnail_path = $thumbnail_path;
            $insertProductImage->priority = 1;
            $insertProductImage->save(); 
        }

        return Response::json();
    }

    public function showUpdateMediaRimModal(Request $request)
    {
        $path = 'images/product/media/rims/';
        $directory = public_path($path);
        $images = File::files($directory);

        // dd($directory, $images, basename($images[0]));

        return [
            'mediaImages' => view('admin/partials/form/media_images', compact('images', 'path'))->render(),
        ];
    }

    public function updateMediaRim(Request $request)
    {
        // dd($request->all());

        $path = 'images/product/media/rims/'.$request->fileName;
        $thumbnail_path = 'images/product/media/rims/thumb/tn-'.$request->fileName;

        if(!File::exists($path))
            return;

        if(empty($request->fileName))
            return;

        $this->filterProducts = Product::where('product_category_id', 2);

        if(!empty($request->brand)) {
            $this->filterByBrand($request->brand);
        }

        if(!empty($request->model)) {
            $this->filterByModel($request->model);
        }

        if(!empty($request->search)) {
            $this->filterBySearch($request->search);
        }

        $products =  $this->filterProducts->get();
        
        foreach ($products as $product) {
            ProductImage::where('product_id', $product->id)->delete();

            $insertProductImage = new ProductImage();
            $insertProductImage->product_id = $product->id;
            $insertProductImage->name = $request->fileName;
            $insertProductImage->path = $path;
            $insertProductImage->thumbnail_path = $thumbnail_path;
            $insertProductImage->priority = 1;
            $insertProductImage->save(); 
        }

        return Response::json();
    }


    public function destroyImage($id)
    {
        //delete from database
        $image = ProductImage::findOrFail($id);

        $existImg = ProductImage::where('name', $image->name)->get(); 
        if(sizeOf($existImg) === 1) {
            \File::delete([
                $image->path,
                $image->thumbnail_path
            ]);
        }

        $image->delete();

        return Response::json();
    }
}
