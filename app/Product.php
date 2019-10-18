<?php

namespace App;

use App\UpstepsTmp;
use Carbon\Carbon;
use App\Search\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Product extends Model
{
    use Searchable;
    
	/**
	 * Referencing to table in DB
	 * 
	 * @var string
	 */
	protected $table = 'products';
    
    protected $selectedSize;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_category_id',
        'product_type_id',
        'main_supplier_product_id',
        'main_supplier_id',
        'profit_id',
        'product_brand',
        'product_type',
        'product_code',
        'product_color',
        'product_model',
        'product_dimension',
        'product_name',
        'product_width',
        'product_size',
        'product_profile',
        'product_e_code',
        'ean_code',
        'et',
        'et_min',
        'storage_et',
        'is_ctyre',
        'is_runflat',
        'tire_manufactor_date',
        'product_Load',
        'load_index',
        'speed_index',
        'pcd1', 'pcd2', 'pcd3', 'pcd4', 'pcd5', 'pcd6', 'pcd7', 'pcd8', 'pcd9', 'pcd10', 'pcd11', 'pcd12', 'pcd13', 'pcd14', 'pcd15',
        'storage_pcd',
        'storage_location',
        'bore_max',
        'bore_min',
        'original_price',
        'price',
        'storage_price',
        'quantity',
        'discount1', 'discount2', 'discount3', 'discount4',
        'video',
        'three_d_link',
        'environmental_fee',
        'is_euros_storage',
        'is_in_storage',
        'is_order_item',
        'is_featured'
    ];

	/**
	 * Has many Product Images
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function productImages()
    {
    	return $this->hasMany('App\ProductImage', 'product_id', 'id')->orderBy('priority');
    }

    public function productType()
    {
        return $this->belongsTo('App\ProductType', 'product_type_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany('App\OrderDetail', 'product_id', 'id');
    }

    public function profit()
    {
        return $this->belongsTo('App\Profit', 'profit_id', 'id');
    }

    /**
     *  Product belongs to mainSupplier
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mainSupplier()
    {
    	return $this->belongsTo('App\Supplier', 'main_supplier_id', 'id');
    }

    public function webPrice()
    {
        if($this->profit->in_cash != 0) {
            $amount = ($this->price + $this->profit->in_cash) * 1.25;

            if(Auth::check()) {
                if(Auth::user()->user_type_id == 1) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount1;
                } 

                if(Auth::user()->user_type_id == 2) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount2;
                } 

                if(Auth::user()->user_type_id == 3) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount3;
                } 

                if(Auth::user()->user_type_id == 4) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount4;
                } 
            }
            return round($amount);
        }
        $amount = ($this->price + ($this->price * $this->profit->in_procent/100) ) * 1.25;

        if(Auth::check()) {
            if(Auth::user()->user_type_id == 1) {
                $amount = ($amount /*/ 1.25*/) * $this->discount1;
            } 

            if(Auth::user()->user_type_id == 2) {
                $amount = ($amount /*/ 1.25*/) * $this->discount2;
            } 

            if(Auth::user()->user_type_id == 3) {
                $amount = ($amount /*/ 1.25*/) * $this->discount3;
            } 

            if(Auth::user()->user_type_id == 4) {
                $amount = ($amount /*/ 1.25*/) * $this->discount4;
            } 
        }
        return round($amount);
    }

    public function webPriceWithoutTax()
    {
        if($this->profit->in_cash != 0) {
            $amount = ($this->price + $this->profit->in_cash);

            if(Auth::check()) {
                if(Auth::user()->user_type_id == 1) {
                    $amount = $amount * $this->discount1;
                } 

                if(Auth::user()->user_type_id == 2) {
                    $amount = $amount * $this->discount2;
                } 

                if(Auth::user()->user_type_id == 3) {
                    $amount = $amount * $this->discount3;
                } 

                if(Auth::user()->user_type_id == 4) {
                    $amount = $amount * $this->discount4;
                } 
            }

            return round($amount);
        }
        $amount = ($this->price + ($this->price * $this->profit->in_procent/100));

        if(Auth::check()) {
            if(Auth::user()->user_type_id == 1) {
                $amount = $amount * $this->discount1;
            } 

            if(Auth::user()->user_type_id == 2) {
                $amount = $amount * $this->discount2;
            } 

            if(Auth::user()->user_type_id == 3) {
                $amount = $amount * $this->discount3;
            } 

            if(Auth::user()->user_type_id == 4) {
                $amount = $amount * $this->discount4;
            } 
        }

        return round($amount);
    }

    public function originalPrice()
    {
        if($this->profit->in_cash != 0) {
            $amount = ($this->original_price + $this->profit->in_cash) * 1.25;

            if(Auth::check()) {
                if(Auth::user()->user_type_id == 1) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount1;
                } 

                if(Auth::user()->user_type_id == 2) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount2;
                } 

                if(Auth::user()->user_type_id == 3) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount3;
                } 

                if(Auth::user()->user_type_id == 4) {
                    $amount = ($amount /*/ 1.25*/) * $this->discount4;
                } 
            }
            return round($amount);
        }
        $amount = ($this->original_price + ($this->original_price * $this->profit->in_procent/100) ) * 1.25;

        if(Auth::check()) {
            if(Auth::user()->user_type_id == 1) {
                $amount = ($amount /*/ 1.25*/) * $this->discount1;
            } 

            if(Auth::user()->user_type_id == 2) {
                $amount = ($amount /*/ 1.25*/) * $this->discount2;
            } 

            if(Auth::user()->user_type_id == 3) {
                $amount = ($amount /*/ 1.25*/) * $this->discount3;
            } 

            if(Auth::user()->user_type_id == 4) {
                $amount = ($amount /*/ 1.25*/) * $this->discount4;
            } 
        }

        return round($amount);
    }

    // public function productUrl()
    // {
    //     return $this->productType->name .'/'. $this->brand .'/'. $this->model .'/'. $this->product_dimension .'/'. $this->id;
    // }
    
    public function productUrl()
    {
        $brand = str_replace(['/', ' ', '#'], ['-', '-', ''], $this->product_brand);
        $model = str_replace(['/', ' ', '#'], ['-', '-', ''], $this->product_model);
        $product_dimension = str_replace(['/', ' ', '#'], ['-', '-', ''], $this->product_dimension);
        if($model) {
            $url = $this->productType->name .'/'. $brand .'/'. $model .'/'. $product_dimension .'/'. $this->id . "#productsection";
        } else {
            $url = $this->productType->name .'/'. $brand .'/'. $product_dimension .'/'. $this->id  . "#productsection";
        }

        return strtolower($url);
    }

    public function brandModel()
    {
        return $this->product_brand .' '. $this->product_model;
    }

    public static function getRimInches($columns = null)
    {
         if(is_null($columns))
            return self::distinct()->where('product_category_id', 2)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->orderBy('product_inch')
                    ->get(['product_inch']);
        
        // return self::where($column, $columnValue)->distinct()->orderBy('product_inch')->get(['product_inch']);
        // 
        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'pcd' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_category_id', 2)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_inch')->get(['product_inch']);
    }

    public static function getRimWidths($columns = null)
    {
        if(is_null($columns))
            return self::distinct()->where('product_category_id', 2)->orderBy('product_width')->get(['product_width']);
        // return self::where($column, $columnValue)->distinct()->orderBy('product_width')->get(['product_width']);
        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'pcd' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_category_id', 2)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_width')->get(['product_width']);
    }

    public static function getRimET($columns = null)
    {
        if(is_null($columns))
            return self::distinct()->where('product_category_id', 2)->orderBy('et')->get(['et']);
        // return self::where($column, $columnValue)->distinct()->orderBy('product_width')->get(['product_width']);
        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'pcd' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_category_id', 2)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('et')->get(['et']);
    }

    public static function getRimPCD()
    {
        // return self::distinct()->where('product_category_id', 2)->get();
        // $all = self::where('product_category_id', 2)
        //         ->where('is_shown', 1)
        //         ->where('is_deleted', 0)
        //         ->get();

        // $pcds = [];
        // foreach ($all as $pcd) {
        //     for ($i=1; $i < 15 ; $i++) { 
        //         $pcds[] = $pcd['pcd'.$i];
        //     }
        // }

        // $pcds = array_unique($pcds);

        // // remove empty string array element
        // $pcds = array_diff($pcds, array(''));
        // sort($pcds);

        // dd($pcds);
        // 
        
        $pcds = [
            0 => "4x100",
            1 => "4x108",
            2 => "4x114.3",
            3 => "4x98",
            4 => "5x100",
            5 => "5x105",
            6 => "5x108",
            7 => "5x110",
            8 => "5x110.65",
            9 => "5x112",
            10 => "5x114,5",
            11 => "5x114.3",
            12 => "5x115",
            13 => "5x118",
            14 => "5x12",
            15 => "5x120",
            16 => "5x120.65",
            17 => "5x127",
            18 => "5x130",
            19 => "5x160",
            20 => "5x98",
            21 => "6x114.3",
            22 => "6x139.7",
            23 => "blank",
        ];

        return $pcds;
    }

    public function getPCDs()
    {
        $rim = $this->where('id', $this->id)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();

        $pcds = [];
        foreach ($rim as $pcd) {
            for ($i=1; $i < 15 ; $i++) { 
                $pcds[] = $pcd['pcd'.$i];
            }
        }

        $pcds = array_unique($pcds);

        // remove empty string array element
        $pcds = array_diff($pcds, array(''));
        sort($pcds);

        return $pcds;
    }

    public static function getRimBrands($columns = null)
    {
        // DB::enableQueryLog();
        if(is_null($columns))
            return self::distinct()->where('product_category_id', 2)->orderBy('product_brand')->get(['product_brand']);
        // return self::where($column, $columnValue)->distinct()->orderBy('product_width')->get(['product_width']);
        return  self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'pcd' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_category_id', 2)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_brand')->get(['product_brand']);
        // dd($columns);
        // $query = \DB::getQueryLog();
        // $lastQuery = end($query);
        // dd($query);
    }

    public function getNutsDimensions()
    {
        $dimensions = $this->distinct('product_dimension')->select('product_dimension', 'id')->where('product_type_id', 8)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();
       
        return $dimensions;
    }

    public function getBoltsDimensions()
    {
        $dimensions = $this->distinct('product_dimension')->select('product_dimension', 'id')->where('product_type_id', 9)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();
       

        return $dimensions;
    }

    public function getRingsOuterDimensions()
    {
        $dimensions = $this->distinct('product_dimension')->select('product_dimension')->where('product_type_id', 10)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->orderBy(DB::raw('cast(product_dimension as unsigned)'), 'ASC')
                ->orderBy('product_dimension', 'DESC')
                ->get();       

        return $dimensions;
    }

    public function getRingsInnerDimensions($outerD)
    {
        // $outerD = $outerD ? $outerD : $this->getRingsOuterDimensions()->first()->product_dimension;
        $dimensions = $this->distinct('product_inner_dimension')->select('product_inner_dimension')->where('product_type_id', 10)
                ->where('product_dimension', $outerD)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();
       

        return $dimensions;
    }

    public function getMonteringskit()
    {
        $dimensions = $this->distinct('product_dimension')->select('product_dimension', 'id')->where('product_type_id', 11)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();
        return $dimensions;
    }

    public function getSpacersDimensions()
    {
        $dimensions = $this->distinct('product_dimension')->select('product_dimension', 'id')->where('product_type_id', 13)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();
       

        return $dimensions;
    }

    public function getDropDownServices()
    {
        $dimensions = $this->distinct('product_dimension')->select('product_dimension', 'id')->where('product_type_id', 14)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();
        return $dimensions;
    }

    public function getDropDownOther()
    {
        $dimensions = $this->distinct('product_dimension')->select('product_dimension', 'id')->where('product_type_id', 18)
                ->where('is_shown', 1)
                ->where('is_deleted', 0)
                ->get();
        return $dimensions;
    }



    public static function getTireWidths($productTypeID, $columns = null)
    {
        if(is_null($columns))
            return self::distinct()->where('product_type_id', $productTypeID)->orderBy('product_width')->get(['product_width']);
        // return self::where($column, $columnValue)->distinct()->orderBy('product_width')->get(['product_width']);
        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'page' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_type_id', $productTypeID)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_width')->get(['product_width']);
    }

    public static function getTireProfiles($productTypeID, $columns= null)
    {
        if(is_null($columns))
            return self::distinct()->where('product_type_id', $productTypeID)->orderBy('product_profile')->get(['product_profile']);
        
        // return self::where($column, $columnValue)->distinct()->orderBy('product_profile')->get(['product_profile']);
        // 
        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'page' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_type_id', $productTypeID)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_profile')->get(['product_profile']);
    }

    public static function getTireInches($productTypeID, $columns = null)
    {
        if(is_null($columns))
            return self::distinct()->where('product_type_id', $productTypeID)->orderBy('product_inch')->get(['product_inch']);
            
        // return self::where($column, $columnValue)->distinct()->orderBy('product_inch')->get(['product_inch']);
        
        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'page' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_type_id', $productTypeID)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_inch')->get(['product_inch']);
    }

    public static function getTireBrands($productTypeID, $columns = null)
    {
        if(is_null($columns))
            return self::distinct()->where('product_type_id', $productTypeID)->orderBy('product_brand')->get(['product_brand']);
        
        // return self::where($column, $columnValue)->distinct()->orderBy('product_brand')->get(['product_brand']);

        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'page' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_type_id', $productTypeID)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_brand')->get(['product_brand']);
    }

    public static function getTireModels($productTypeID, $columns = null)
    {
        if(is_null($columns))
            return self::distinct()->where('product_type_id', $productTypeID)->orderBy('product_model')->get(['product_model']);
        
        // return self::where($column, $columnValue)->distinct()->orderBy('product_model')->get(['product_model']);
        // 
        return self::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'page' || $colName == 'textSearch')
                    continue;
                $query->where($colName, $colValue);
            }
        })
        ->where('product_type_id', $productTypeID)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->distinct()->orderBy('product_model')->get(['product_model']);
    }

    public function storeUpstepsTmp($upsteps, $token)
    {
        // UpstepsTmp::truncate();
        // $dt = Carbon::now();
        // $dt->subWeek(); 
        // UpstepsTmp::where('created_at', '<=', $dt)->delete();

        // $dt = Carbon::now();
        // $dt->subSeconds(3); 
        // UpstepsTmp::where('created_at', '>=', $dt)->where('ip', '<=', $ip)->get();
        foreach ($upsteps as $upstep) {
            $storeUpstep = new UpstepsTmp;
            $storeUpstep->RimSize = $upstep['RimSize'];
            $storeUpstep->RimWide = $upstep['RimWide'];
            $storeUpstep->Tyre1 = $upstep['Tyre1'];
            $storeUpstep->Tyre2 = $upstep['Tyre2'];
            $storeUpstep->Tyre3 = $upstep['Tyre3'];
            $storeUpstep->Tyre4 = $upstep['Tyre4'];
            $storeUpstep->Tyre5 = $upstep['Tyre5'];
            $storeUpstep->Tyre6 = $upstep['Tyre6'];
            $storeUpstep->Tyre7 = $upstep['Tyre7'];
            $storeUpstep->Tyre8 = $upstep['Tyre8'];
            $storeUpstep->MinOffset = $upstep['MinOffset'];
            $storeUpstep->MaxOffset = $upstep['MaxOffset'];
            $storeUpstep->MaxOffset = $upstep['MaxOffset'];
            $storeUpstep->Token = $token;
            // $storeUpstep->ip = $ip;
            $storeUpstep->save();
        }
    }

    public function getWheelSizes($searchData, $token)
    {
        // dd($searchData, "getWheelSizes");
        $this->storeUpstepsTmp($searchData['Upsteps'], $token);

        $wheelSize = DB::table('upsteps_tmp AS upsteps')->distinct()->select('upsteps.RimSize') 
                        ->leftJoin('products', function($join) {
                            $join->on('upsteps.RimSize', '=', 'products.product_inch');
                            $join->on('products.et', '>=', 'upsteps.MinOffset');
                            // $join->on('products.et_min', '<=', 'upsteps.MaxOffset');
                        })       
                        ->where('products.bore_max', '>=', $searchData['MatchCenterBore'])        
                        ->where('products.product_width', '>=', $searchData['MinRimWidth'])
                        ->where('products.product_width', '<=', $searchData['MaxRimWidth'])
                        ->where('upsteps.token', $token)
                        // ->where('products.IsShown', 1)
                        // ->where('upsteps.ChassisID', $searchData['ChassisID'])
                        ->where(function($query) use ($searchData){
                            return $query
                                ->where('products.pcd1',   $searchData['PCD'])
                                ->orWhere('products.pcd2', $searchData['PCD'])
                                ->orWhere('products.pcd3', $searchData['PCD'])
                                ->orWhere('products.pcd4', $searchData['PCD'])
                                ->orWhere('products.pcd5', $searchData['PCD'])
                                ->orWhere('products.pcd6', $searchData['PCD'])
                                ->orWhere('products.pcd7', $searchData['PCD'])
                                ->orWhere('products.pcd8', $searchData['PCD'])
                                ->orWhere('products.pcd9', $searchData['PCD'])
                                ->orWhere('products.pcd10', $searchData['PCD'])
                                ->orWhere('products.pcd11', $searchData['PCD'])
                                ->orWhere('products.pcd12', $searchData['PCD'])
                                ->orWhere('products.pcd13', $searchData['PCD'])
                                ->orWhere('products.pcd14', $searchData['PCD'])
                                ->orWhere('products.pcd15', $searchData['PCD'])
                                ->orWhere('products.pcd1', 'Blank');
                        })
                        // ->groupBy('rims.ProductID')
                        ->where('products.is_shown', 1)
                        ->where('products.is_deleted', 0)
                        ->orderBy('upsteps.RimSize')
                        ->get();

        return $wheelSize;

        // $wheelSize = DB::table('upstepwheels_tmp AS upsteps')->distinct()->select('upsteps.WheelSize_2') 
        //                 ->leftJoin('products', function($join) {
        //                     $join->on('upsteps.WheelSize_2', '=', 'products.product_inch');
        //                     $join->on('products.et', '>=', 'upsteps.MinOffset');
        //                     // $join->on('products.et_min', '<=', 'upsteps.MaxOffset');
        //                 })       
        //                 ->where('products.bore_max', '>=', $searchData['MatchCenterBore'])        
        //                 ->where('products.product_width', '>=', $searchData['MinRimWidth'])
        //                 ->where('products.product_width', '<=', $searchData['MaxRimWidth'])
        //                 // ->where('products.IsShown', 1)
        //                 ->where('upsteps.ChassisID', $searchData['ChassisID'])
        //                 ->where(function($query) use ($searchData){
        //                     return $query
        //                         ->where('products.pcd1',   $searchData['PCD'])
        //                         ->orWhere('products.pcd2', $searchData['PCD'])
        //                         ->orWhere('products.pcd3', $searchData['PCD'])
        //                         ->orWhere('products.pcd4', $searchData['PCD'])
        //                         ->orWhere('products.pcd5', $searchData['PCD'])
        //                         ->orWhere('products.pcd6', $searchData['PCD'])
        //                         ->orWhere('products.pcd7', $searchData['PCD'])
        //                         ->orWhere('products.pcd8', $searchData['PCD'])
        //                         ->orWhere('products.pcd9', $searchData['PCD'])
        //                         ->orWhere('products.pcd10', $searchData['PCD'])
        //                         ->orWhere('products.pcd11', $searchData['PCD'])
        //                         ->orWhere('products.pcd12', $searchData['PCD'])
        //                         ->orWhere('products.pcd13', $searchData['PCD'])
        //                         ->orWhere('products.pcd14', $searchData['PCD'])
        //                         ->orWhere('products.pcd15', $searchData['PCD'])
        //                         ->orWhere('products.pcd1', 'Blank');
        //                 })
        //                 // ->groupBy('rims.ProductID')
        //                 ->where('products.is_shown', 1)
        //                 ->where('products.is_deleted', 0)
        //                 ->orderBy('upsteps.WheelSize_2')
        //                 ->get();

        


        // SELECT rims.* FROM cars_tyrefit_upstepwheels AS upsteps
        // LEFT JOIN abs_wheels AS rims ON 
        // upsteps.WheelSize_2 = rims.rim_size AND
        // rims.ET >= upsteps.MinOffset AND 
        // rims.ET <= upsteps.MaxOffset
        // WHERE
        //     rims.Bore_max >= 71 AND
        //     rims.rim_wide >= 7.5 AND
        //     rims.rim_wide <= 11.5 AND
        //     rims.status = 1 AND
        //     (
        //         rims.PCD_1 = '5x130' OR
        //         rims.PCD_2 = '5x130' OR
        //         rims.PCD_3 = '5x130' OR
        //         rims.PCD_4 = '5x130' OR
        //         rims.PCD_5 = '5x130' OR
        //         rims.PCD_6 = '5x130' OR
        //         rims.PCD_7 = '5x130' OR
        //         rims.PCD_8 = '5x130' OR
        //         rims.PCD_9 = '5x130' OR
        //         rims.PCD_1 = 'Blank'
        //     ) AND
        //     upsteps.ChassisID = 5041
        // GROUP BY rims.buytype, rims.abs_id
    }

    public function getRimsBySearch($searchData, $selectedSize, $brand, $directNav, $textSearch, $token/*, $secondHand, $plate*/)
    {
        // dd($searchData);
        DB::enableQueryLog();
        // $products = DB::table('upsteps_tmp AS upsteps')->select('rims.*', 'product_images.path', 'product_images.thumbnail_path', 'product_images.name', 'in_cash', 'in_procent')
        $products = Product::select('products.*', 'product_images.path', 'product_images.thumbnail_path', 'product_images.name', 'in_cash', 'in_procent')
                        ->leftJoin('upsteps_tmp AS upsteps', function($join) {
                            $join->on('upsteps.RimSize', '=', 'product_inch');
                            $join->on('et', '>=', 'upsteps.MinOffset');
                            $join->on('et', '<=', 'upsteps.MaxOffset');
                        })       
                        ->leftJoin('product_images', 'product_images.product_id', '=', 'products.id')
                        ->leftJoin('profits', 'profit_id', '=', 'profits.id')
                        ->where('product_category_id', 2)
                        ->where('product_images.name', '!=', 'Noproduct.jpg')
                        ->where('upsteps.token', $token)
                        ->where(function($query) use ($searchData, $directNav, $textSearch/*, $secondHand, $plate*/){
                            if($directNav) {
                                //MatchCenterBore
                                $query->where('bore_max', '>=', $searchData['ShowCenterBore']-0.1);
                                $query->where('bore_max', '<=', $searchData['ShowCenterBore']+0.1);
                            } else {
                                $query->where('bore_max', '>=', $searchData['MatchCenterBore']);
                            }

                            if($textSearch) {
                                $query->where('product_name', 'like', "%{$textSearch}%");
                            }

                            // if($secondHand){
                            //     $query->where('product_brand', 'BEG.');
                            // } else {
                            //     $query->where('product_brand', '<>', 'BEG.');
                            // }

                            // if($plate){
                            //     $query->where('product_brand', 'Plåt');
                            // } else {
                            //     $query->where('product_brand', '<>', 'Plåt');
                            // }
                        })    
                        ->where('product_width', '>=', $searchData['MinRimWidth'])
                        ->where('product_width', '<=', $searchData['MaxRimWidth'])
                        ->where('upsteps.RimSize', '=', $selectedSize)
                        // ->where('IsShown', 1)
                        // ->where('upsteps.ChassisID', '=', $searchData['ChassisID'])
                        ->where(function($query) use ($searchData){
                            $query
                                ->where('pcd1',   $searchData['PCD'])
                                ->orWhere('pcd2', $searchData['PCD'])
                                ->orWhere('pcd3', $searchData['PCD'])
                                ->orWhere('pcd4', $searchData['PCD'])
                                ->orWhere('pcd5', $searchData['PCD'])
                                ->orWhere('pcd6', $searchData['PCD'])
                                ->orWhere('pcd7', $searchData['PCD'])
                                ->orWhere('pcd8', $searchData['PCD'])
                                ->orWhere('pcd9', $searchData['PCD'])
                                ->orWhere('pcd10', $searchData['PCD'])
                                ->orWhere('pcd11', $searchData['PCD'])
                                ->orWhere('pcd12', $searchData['PCD'])
                                ->orWhere('pcd13', $searchData['PCD'])
                                ->orWhere('pcd14', $searchData['PCD'])
                                ->orWhere('pcd15', $searchData['PCD'])
                                ->orWhere('pcd1', 'Blank');
                        })
                        ->where(function($query) use ($brand){
                            !$brand ?: $query->where('product_brand', $brand);
                        })
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->groupBy('products.id')
                        ->orderBy('priority_supplier', 'ASC')
                        ->orderby('price');                        //->get();

        return $products;
    }

    public function getTiresBySearch($searchData, $selectedSize, $productTypeID, $brand, $textSearch, $token, $selectedDimension = null)
    {
        $carSearch = json_decode(Cookie::get('carSearch'), true);
        $this->selectedSize = $selectedSize !== null? $selectedSize : $carSearch['selectedSize'];

        $dimensions = $this->getTireDimensions($searchData, $this->selectedSize, $token); 
        // dd($dimensions);
        
        foreach($dimensions as $dimension) {
            $count = Product::where('product_category_id', 1)
                        ->where('product_type_id', $productTypeID)
                        ->where('product_dimension', $dimension)
                        ->where(function($query) use($brand, $textSearch) {
                            !$brand ?: $query->where('product_brand', $brand);

                            if($textSearch) {
                                $query->where('product_name', 'like', "%{$textSearch}%");
                            }
                        })
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->count();
            // dd($count, $dimensions, $dimension);
            if($count == 0) {
                $dimensions = array_diff($dimensions, array($dimension));
            }
        }

        if($selectedDimension == null ) {
            $selectedDimension = empty($dimensions) ? null : array_values($dimensions)[0];
            // $selectedDimension = empty($dimensions) ? null : $carSearch['selectedDimension'];
        }

        $tires = Product::where('product_category_id', 1)
                        ->where('product_type_id', $productTypeID)
                        ->where('product_dimension', $selectedDimension)
                        ->where(function($query) use($brand, $textSearch) {
                            !$brand ?: $query->where('product_brand', $brand);

                            if($textSearch) {
                                $query->where('product_name', 'like', "%{$textSearch}%");     
                            }
                        })
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('priority_supplier', 'ASC')
                        ->orderby('price');
                        // ->where('product_width', $width)
                        // ->where('product_profile', $profile)
                        // ->where('product_inch', $inch);
                        // ->get();
        
        
        $products = ['items' => $tires, 'dimensions' => $dimensions];

        $carSearch['selectedDimension'] = $selectedDimension;
        $carSearch = json_encode($carSearch);
        Cookie::queue('carSearch', $carSearch, 60*24*7);

        return $products;
    }

    public function getTireDimensions($searchData, $selectedSize, $token)
    {
        DB::enableQueryLog();
        $upsteps = UpstepsTmp::where('RimSize', '=', (int) $selectedSize)
                        // ->where('RimWide', '>=', (int) $searchData['MinRimWidth'])
                        ->where('Token', $token)
                        // ->where('WheelSize_1', '<=', (int) $searchData['MaxRimWidth'])
                        // ->where('MinOffset', '<=', (int) $searchData['Offset'])
                        // ->where('MaxOffset', '>=', (int) $searchData['Offset'])
                        ->get()->toArray();

        $dimensions = [];
        foreach ($upsteps as $upstep) {
            for ($i=1; $i < 9 ; $i++) { 
                $dimensions[] = $upstep['Tyre'.$i];
            }
        }

        // $upsteps = Upsteps::where('ChassisID', '=', (int) $searchData['ChassisID'])
        //                 ->where('WheelSize_2', '=', (int) $selectedSize)
        //                 ->where('WheelSize_1', '>=', (int) $searchData['MinRimWidth'])
        //                 // ->where('WheelSize_1', '<=', (int) $searchData['MaxRimWidth'])
        //                 ->where('MinOffset', '<=', (int) $searchData['Offset'])
        //                 ->where('MaxOffset', '>=', (int) $searchData['Offset'])
        //                 ->get();

        // select * from `cars_tyrefit_upstepwheels`
        // where `ChassisID` = 5041 AND     
        //       `WheelSize_2` = 18 and 
        //       `WheelSize_1` >= 7.5 and 
        //       `WheelSize_1` <= 11.5 and 
        //       `MinOffset` <= 53  and 
        //       `MaxOffset` >= 53     

        // $upsteps = (array) $upsteps;
        // dd(DB::getQueryLog());
        // dd($upsteps);

        // $dimensions = [];
        // foreach ($upsteps as $upstep) {
        //     for ($i=1; $i < 9 ; $i++) { 
        //         $dimensions[] = $upstep['Tyre'.$i];
        //     }
        // }

        // dd($dimensions);
        $dimensions = array_unique($dimensions);

        // remove empty string array element
        $dimensions = array_diff($dimensions, array(''));

        return $dimensions;
    }

    public function getTiresByDimensionSearch($productTypeID )
    {
        // DB::enableQueryLog();
        // // $query = \DB::getQueryLog();
        // $lastQuery = end($query);
        // dd($lastQuery);
        $columns = json_decode(Session::get('searchFilter'), true);
        // dd($columns);
        $products = Product::where(function($query) use ($columns) {
            // foreach ($columns as $colName => $colValue) {
            //     if(empty($colValue) || $colName == 'page' || $colName == 'productTypeID' || $colName == '_token' || $colName == 'regnr')
            //         continue;
                
            //     if($colName == 'is_runflat' || $colName == 'is_ctyre')
            //         $colValue = 1;
                
            //     $query->where($colName, $colValue);
            // }
            !empty($columns['filterTireByWidth']) ? $query->where('product_width', $columns['filterTireByWidth']) : '';
            !empty($columns['filterTireByProfile']) ? $query->where('product_profile', $columns['filterTireByProfile']) : '';
            !empty($columns['filterTireByInch']) ? $query->where('product_inch', $columns['filterTireByInch']) : '';
            !empty($columns['filterTireByBrand']) ? $query->where('product_brand', $columns['filterTireByBrand']) : '';
            !empty($columns['filterTireByModel']) ? $query->where('product_model', $columns['filterTireByModel']) : '';
            $query->where('is_shown', 1);
            $query->where('is_deleted', 0);
        })
        ->where('product_type_id', $productTypeID)
        ->orderBy('priority_supplier', 'ASC')
        ->orderBy('price');
        
        // $query = \DB::getQueryLog();
        // $lastQuery = end($query);
        // dd($lastQuery);
        
        return $products;

           // public function getTiresBySize($searchData)
    // {
    //     // dd($searchData);
    //     // DB::enableQueryLog();
    //     $runflat = isset($searchData['runflat']) ? 1 : 0;
    //     $cdack = isset($searchData['cdack']) ? 1 : 0;
    //     $products = Product::where(function($query) use ($searchData) {
    //         if(!empty($searchData['width'])) $query->where('product_width', (int) $searchData['width']);
    //         if(!empty($searchData['profile'])) $query->where('product_profile', (int) $searchData['profile']);
    //         if(!empty($searchData['inch'])) $query->where('product_inch', (int) $searchData['inch']);
    //         if(!empty($searchData['brand'])) $query->where('product_brand', $searchData['brand']);
    //         if(!empty($searchData['model'])) $query->where('product_model', $searchData['model']);
    //     })
    //     ->where('is_runflat', $runflat)
    //     ->where('is_ctyre', $cdack)
    //     ->where('product_category_id', 1)
    //     ->where('product_type_id', $searchData['productTypeID']);

    //     \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
    //         var_dump($query->sql);
    //         var_dump($query->bindings);
    //         var_dump($query->time);
    //     });
    //     // ->get();


    //     // where('product_width', (int) $searchData['width'])        
    //     //                 ->where('product_profile', (int) $searchData['profile'])
    //     //                 ->where('product_inch', (int) $searchData['inch'])
    //     //                 ->where('product_brand', $searchData['brand'])
    //     //                 ->where('product_model', $searchData['model'])
    //     //                 ->where('is_runflat', $runflat)
    //     //                 ->where('is_ctyre', $cdack)
    //     //                 ->where('product_category_id', 1);
    //                     // ->get();
    //     // dd(DB::getQueryLog());
    //     // dd($products);
    //     return $products;
    // }
    }

    public function getRimsByDimensionSearch()
    {
        // dd($searchData);
        // DB::enableQueryLog();
        $searchData = json_decode(Session::get('searchFilter'), true);
        $products = Product::where(function($query) use ($searchData) {
            if(!empty($searchData['filterRimsByInch'])) $query->where('product_inch', $searchData['filterRimsByInch']);
            if(!empty($searchData['filterRimsByWidth'])) $query->where('product_width', $searchData['filterRimsByWidth']);
            if(!empty($searchData['filterRimsByET'])) $query->where('et', $searchData['filterRimsByET']);
            if(!empty($searchData['filterRimsByBrand'])) $query->where('product_brand', $searchData['filterRimsByBrand']);
        })
        ->where(function($query) use ($searchData) {
            if(!empty($searchData['filterRimsByPCD']) && $searchData['filterRimsByPCD'] != 'BLANK') {
                return $query
                    ->where('pcd1',   $searchData['filterRimsByPCD'])
                    ->orWhere('pcd2', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd3', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd4', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd5', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd6', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd7', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd8', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd9', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd10', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd11', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd12', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd13', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd14', $searchData['filterRimsByPCD'])
                    ->orWhere('pcd15', $searchData['filterRimsByPCD'])
                    ->orwhere('pcd1',  'BLANK');
            }
        })
        ->where('product_category_id', 2)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->orderBy('priority_supplier', 'ASC')
        ->orderBy('price');

        return $products;
    }

    // public function regenerateUpsteps()
    // {
    //     $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=getallupsteps";
        
    //     $curl = curl_init($service_url);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     $curl_response = curl_exec($curl);
        
    //     if ($curl_response === false) {
    //         $info = curl_getinfo($curl);
    //         curl_close($curl);
    //         die('error occured during curl exec. Additioanl info: ' . var_export($info));
    //     }
    //     curl_close($curl);
    //     $searchData = json_decode($curl_response);

    //     return $searchData[0];
    // }
}
