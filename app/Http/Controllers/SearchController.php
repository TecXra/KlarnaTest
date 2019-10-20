<?php

namespace App\Http\Controllers;

use App\CustomerSearch;
use App\Http\Requests;
use App\Http\Utilities\UUID;
use App\Product;
use App\Search;
use App\UpstepsTmp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\Repositories\Products\ProductsRepository;

class SearchController extends Controller
{
    
    protected $productTypeID;
    protected $searchData;
    protected $token;
    protected $wheelSizes;
    protected $dimensions;
    protected $selectedSize;
    protected $selectedDimension;
    protected $completeTiresProcess;
    protected $filterRimsByInch;
    protected $filterRimsByWidth;
    protected $filterRimsByPCD;
    protected $filterRimsByET;
    protected $filterRimsByBrand;
    protected $filterTireByWidth;
    protected $filterTireByProfile;
    protected $filterTireByInch;
    protected $filterTireByBrand;
    protected $filterTireByModel;
    protected $dropDownRimWidth;
    protected $dropDownRimET;
    protected $dropDownRimBrand;
    protected $dropDownTireProfile;
    protected $dropDownTireInch;
    protected $dropDownTireBrand;
    protected $dropDownTireModel;

    public function getCookieValues()
    {
        $carSearch = json_decode(Cookie::get('carSearch'), true);
        $this->searchData = $carSearch['searchData'];
        $this->token = isset($carSearch['token']) ? $carSearch['token'] : '';
        $this->wheelSizes = $carSearch['wheelSizes'];
        $this->selectedSize = isset($carSearch['selectedSize']) ? $carSearch['selectedSize'] : null;
        $this->selectedDimension = isset($carSearch['selectedDimension']) ? $carSearch['selectedDimension'] : null;
        $this->productTypeID = isset($carSearch['productTypeID']) ? $carSearch['productTypeID'] : null;
        $this->completeTiresProcess = isset($carSearch['completeTiresProcess']) ? $carSearch['completeTiresProcess'] : 0;
        $this->selectedRimBrand = null;
        $this->selectedTireBrand = null;
        $this->directNav = null;
        $this->textSearch = null;

        // $this->filterRimsByInch = isset($carSearch['filterRimsByInch']) ? $carSearch['filterRimsByInch'] : null;
        // $this->filterRimsByWidth = isset($carSearch['filterRimsByWidth']) ? $carSearch['filterRimsByWidth'] : null;
        // $this->filterRimsByPCD = isset($carSearch['filterRimsByPCD']) ? $carSearch['filterRimsByPCD'] : null;
        // $this->filterRimsByET = isset($carSearch['filterRimsByET']) ? $carSearch['filterRimsByET'] : null;
        // $this->filterRimsByBrand = isset($carSearch['filterRimsByBrand']) ? $carSearch['filterRimsByBrand'] : null;
        
        // $this->secondHand = null;
        // $this->plate = null;
    }     

    public function storeValuesInCookie()
    {   
        $carSearch['searchData'] = $this->searchData; 
        $carSearch['token'] = $this->token; 
        $carSearch['wheelSizes'] = $this->wheelSizes; 
        $carSearch['selectedSize'] = $this->selectedSize; 
        $carSearch['selectedDimension'] = $this->selectedDimension; 
        $carSearch['productTypeID'] = $this->productTypeID;
        $carSearch['completeTiresProcess'] = $this->completeTiresProcess;
        $carSearch = json_encode($carSearch);
        Cookie::queue('carSearch', $carSearch, 60*24*7);
    }

    public function getSearchFilterValues()
    {
        $searchFilter = json_decode(Session::get('searchFilter'), true);
        $this->filterRimsByInch = isset($searchFilter['filterRimsByInch']) ? $searchFilter['filterRimsByInch'] : null;
        $this->filterRimsByWidth = isset($searchFilter['filterRimsByWidth']) ? $searchFilter['filterRimsByWidth'] : null;
        $this->filterRimsByET = isset($searchFilter['filterRimsByET']) ? $searchFilter['filterRimsByET'] : null;
        $this->filterRimsByBrand = isset($searchFilter['filterRimsByBrand']) ? $searchFilter['filterRimsByBrand'] : null;
        $this->filterRimsByPCD = isset($searchFilter['filterRimsByPCD']) ? $searchFilter['filterRimsByPCD'] : null;

        $this->dropDownRimWidth = isset($searchFilter['dropDownRimWidth']) ? $searchFilter['dropDownRimWidth'] : null;
        $this->dropDownRimET = isset($searchFilter['dropDownRimET']) ? $searchFilter['dropDownRimET'] : null;
        $this->dropDownRimBrand = isset($searchFilter['dropDownRimBrand']) ? $searchFilter['dropDownRimBrand'] : null;

        $this->filterTireByWidth = isset($searchFilter['filterTireByWidth']) ? $searchFilter['filterTireByWidth'] : null;
        $this->filterTireByProfile = isset($searchFilter['filterTireByProfile']) ? $searchFilter['filterTireByProfile'] : null;
        $this->filterTireByInch = isset($searchFilter['filterTireByInch']) ? $searchFilter['filterTireByInch'] : null;
        $this->filterTireByBrand = isset($searchFilter['filterTireByBrand']) ? $searchFilter['filterTireByBrand'] : null;
        $this->filterTireByModel = isset($searchFilter['filterTireByModel']) ? $searchFilter['filterTireByModel'] : null;

        $this->dropDownTireProfile = isset($searchFilter['dropDownTireProfile']) ? $searchFilter['dropDownTireProfile'] : null;
        $this->dropDownTireInch = isset($searchFilter['dropDownTireInch']) ? $searchFilter['dropDownTireInch'] : null;
        $this->dropDownTireBrand = isset($searchFilter['dropDownTireBrand']) ? $searchFilter['dropDownTireBrand'] : null;
        $this->dropDownTireModel = isset($searchFilter['dropDownTireModel']) ? $searchFilter['dropDownTireModel'] : null;
    }

    public function storeSearchFilterInCookie()
    {   

        $searchFilter['filterRimsByInch'] = isset($this->filterRimsByInch) ? $this->filterRimsByInch : null;
        $searchFilter['filterRimsByWidth'] = isset($this->filterRimsByWidth) ? $this->filterRimsByWidth : null;
        $searchFilter['filterRimsByET'] = isset($this->filterRimsByET) ? $this->filterRimsByET : null;
        $searchFilter['filterRimsByBrand'] = isset($this->filterRimsByBrand) ? $this->filterRimsByBrand : null;
        $searchFilter['filterRimsByPCD'] = isset($this->filterRimsByPCD) ? $this->filterRimsByPCD : null;

        $searchFilter['dropDownRimWidth'] = isset($this->dropDownRimWidth) ? $this->dropDownRimWidth : null;
        $searchFilter['dropDownRimET'] = isset($this->dropDownRimET) ? $this->dropDownRimET : null;
        $searchFilter['dropDownRimBrand'] = isset($this->dropDownRimBrand) ? $this->dropDownRimBrand : null;

        $searchFilter['filterTireByWidth'] = isset($this->filterTireByWidth) ? $this->filterTireByWidth : null; 
        $searchFilter['filterTireByProfile'] = isset($this->filterTireByProfile) ? $this->filterTireByProfile : null; 
        $searchFilter['filterTireByInch'] = isset($this->filterTireByInch) ? $this->filterTireByInch : null; 
        $searchFilter['filterTireByBrand'] = isset($this->filterTireByBrand) ? $this->filterTireByBrand : null; 
        $searchFilter['filterTireByModel'] = isset($this->filterTireByModel) ? $this->filterTireByModel : null; 

        $searchFilter['dropDownTireProfile'] = isset($this->dropDownTireProfile) ? $this->dropDownTireProfile : null;
        $searchFilter['dropDownTireInch'] = isset($this->dropDownTireInch) ? $this->dropDownTireInch : null;
        $searchFilter['dropDownTireBrand'] = isset($this->dropDownTireBrand) ? $this->dropDownTireBrand : null;
        $searchFilter['dropDownTireModel'] = isset($this->dropDownTireModel) ? $this->dropDownTireModel : null;
        $searchFilter = json_encode($searchFilter);
        // Cookie::queue('searchFilter', $searchFilter, 60*24);
        Session::put('searchFilter', $searchFilter, 60*24);
    }

    public function getTiresBySize()
    {
        if(isset($this->searchData->Status)) {
            $product['status'] = $this->searchData;
        } else {
            $search = new Product();
            $data = $search->getTiresBySearch($this->searchData, $this->selectedSize, $this->productTypeID, $this->selectedTireBrand, $this->textSearch, $this->token);
            $product['items'] = $data['items']->paginate(24);
            $product['dimensions'] = $data['dimensions'];
            $product['searchData'] = $this->searchData;
            $product['wheelSizes'] = $this->wheelSizes;
            $product['selectedSize'] = $this->selectedSize;
            $product['productTypeID'] = $this->productTypeID;
            $product['brands'] = Product::getTireBrands($this->productTypeID);
        }

        return $product;
    }

    public function getTiresByDimensions()
    {
        if(isset($this->searchData['Status'])) {
            $product['status'] = $this->searchData;
        } else {
            $search = new Product();
            $data = $search->getTiresBySearch($this->searchData, $this->selectedSize, $this->productTypeID, $this->selectedTireBrand, $this->textSearch, $this->token, $this->selectedDimension);
            $product['items'] = $data['items']->paginate(24);
            $product['dimensions'] = $data['dimensions'];
            $product['searchData'] = $this->searchData;
            $product['wheelSizes'] = $this->wheelSizes;
            $product['selectedSize'] = $this->selectedSize;
            $product['productTypeID'] = $this->productTypeID;
            $product['brands'] = Product::getTireBrands($this->productTypeID);
        }

        return $product;
    }

    public function getRimsBySize()
    {
        if(isset($this->searchData['Status'])) {
            $product['status'] = $this->searchData;
        } else {
            $search = new Product();
            $product['items'] = $search->getRimsBySearch($this->searchData, $this->selectedSize, $this->selectedRimBrand, $this->directNav, $this->textSearch, $this->token /*, $this->secondHand, $this->plate*/)->paginate(24);
            $product['searchData'] = $this->searchData;
            $product['wheelSizes'] = $this->wheelSizes;
            $product['selectedSize'] = $this->selectedSize;
            $product['productTypeID'] = $this->productTypeID;
            $product['brands'] = Product::getRimBrands();
        }

        return $product;
    }

    public function showSummerTireSearch(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('sok/reg/sommardack');
        }
        // dd($request->all());
        $this->getCookieValues();
        $this->productTypeID = 1;

        if($request->brand) {
            $this->selectedTireBrand = $request->brand;
        }

        if($request->textSearch) {
            $this->textSearch = $request->textSearch;
        }
        
        if($request->dimension) {
            $this->selectedDimension =  $request->dimension;
            $product = $this->getTiresByDimensions();
        } 
        elseif($request->size) {
            $this->selectedSize = $request->size;
            $product = $this->getTiresBySize();
        } 
        else {
            $product = $this->getTiresBySize();
        }
        $product['page'] = 'sommardack';
        
        $this->completeTiresProcess = 0;
        $this->storeValuesInCookie();

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
                'dimensions' => view('search.partials.search_data.tire_dimensions', compact('product'))->render(),
                'productCount' => $product['items']->total(),
            ]; 
        }

        return view('search.search_tires', compact('product'));
    }

    public function showFriktionTireSearch(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('sok/reg/friktionsdack');
        }

        $this->getCookieValues();
        $this->productTypeID = 2;

        if($request->brand) {
            $this->selectedTireBrand = $request->brand;
        }

        if($request->textSearch) {
            $this->textSearch = $request->textSearch;
        }
        
        if($request->dimension) {
            $this->selectedDimension =  $request->dimension;
            $product = $this->getTiresByDimensions();
        } 
        elseif($request->size) {
            $this->selectedSize = $request->size;
            $product = $this->getTiresBySize();
        } 
        else {
            $product = $this->getTiresBySize();
        }

        $product['page'] = 'friktionsdack';
        
        $this->completeTiresProcess = 0;
        $this->storeValuesInCookie();

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
                'dimensions' => view('search.partials.search_data.tire_dimensions', compact('product'))->render(),
                'productCount' => $product['items']->total(),
            ]; 
        }

        return view('search.search_tires', compact('product'));
    }

    public function showStuddedTireSearch(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('sok/reg/dubbdack');
        }

        $this->getCookieValues();
        $this->productTypeID = 3;

        if($request->brand) {
            $this->selectedTireBrand = $request->brand;
        }

        if($request->textSearch) {
            $this->textSearch = $request->textSearch;
        }

        if($request->dimension) {
            $this->selectedDimension =  $request->dimension;
            $product = $this->getTiresByDimensions();
        } 
        elseif($request->size) {
            $this->selectedSize = $request->size;
            $product = $this->getTiresBySize();
        } 
        else {
            $product = $this->getTiresBySize();
        }

        $product['page'] = 'dubbdack';
        
        $this->completeTiresProcess = 0;
        $this->storeValuesInCookie();

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
                'dimensions' => view('search.partials.search_data.tire_dimensions', compact('product'))->render(),
                'productCount' => $product['items']->total(),
            ]; 
        }

        return view('search.search_tires', compact('product'));
    }

    public function showRimSearch(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('sok/reg/falgar');
        }
        $this->productTypeID = 4;
        $this->getCookieValues();
        

        if($request->size) {
            $this->selectedSize = $request->size;
        }

        if($request->brand) {
            $this->selectedRimBrand = $request->brand;
        }

        if($request->directNav) {
            $this->directNav = $request->directNav;
        }

        if($request->textSearch) {
            $this->textSearch = $request->textSearch;
        }

        // if($request->secondHand) {
        //     $this->secondHand = $request->secondHand;
        // }

        // if($request->plate) {
        //     $this->plate = $request->plate;
        // }
        $product = $this->getRimsBySize();
        $product['page'] = 'falgar';

        $this->completeTiresProcess = 0;
        $this->storeValuesInCookie();


        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_rims', compact('product'))->render(),
                'productCount' => $product['items']->total(),
            ];
        }

        return view('search.search_rims', compact('product'));
    }

    public function showCompleteRims(Request $request)
    {
        // $this->productTypeID = 4;
        $this->getCookieValues();
        $tireType = $request->tireType;

        if($request->size) {
            $this->selectedSize = $request->size;
        }

        if($request->brand) {
            $this->selectedRimBrand = $request->brand;
        }

        if($request->directNav) {
            $this->directNav = $request->directNav;
        }

        if($request->textSearch) {
            $this->textSearch = $request->textSearch;
        }

        // if($request->secondHand) {
        //     $this->secondHand = $request->secondHand;
        // }

        // if($request->plate) {
        //     $this->plate = $request->plate;
        // }

        $product = $this->getRimsBySize();
        $product['page'] = 'falgar';

        $productTypeID=1;
        if($tireType == "sommardack"){
            $productTypeID = 1;
        }
        if($tireType == "friktionsdack"){
            $productTypeID = 2;
        }
        if($tireType == "dubbdack"){
            $productTypeID = 3;
        }

        $this->productTypeID = $productTypeID;
        $this->completeTiresProcess = 1;
        $this->storeValuesInCookie();

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_rims', compact('product'))->render(),
                'productCount' => $product['items']->total()
            ];
        }
        return view('search.search_complete_rims', compact('product'));
    }

    public function searchCompleteTires(Request $request)
    {
        $this->getCookieValues();
        // dd($this->productTypeID);
        
        if($request->brand) {
            $this->selectedTireBrand = $request->brand;
        }

        if($request->textSearch) {
            $this->textSearch = $request->textSearch;
        }

        if($request->dimension) {
            $this->selectedDimension =  $request->dimension;
            $product = $this->getTiresByDimensions();
        } 
        elseif($request->size) {
            $this->selectedSize = $request->size;
            $product = $this->getTiresBySize();
        } 
        else {
            $product = $this->getTiresBySize();
        }
        $product['page'] = 'sommardack';

        $this->completeTiresProcess = 2;
        $this->storeValuesInCookie();

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
                'dimensions' => view('search.partials.search_data.tire_dimensions', compact('product'))->render(),
                'productCount' => $product['items']->total(),
            ];
        }

        return view('search.search_complete_tires', compact('product'));
    }


    public function searchRimsBySize(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('/sok/storlek/falgar');
        }
        // $this->getCookieValues();
        // dd($request->all());
        // $cookie = json_decode(Cookie::get('cookie'), true);
        // isset($cookie['searchFilter']['filterByInch']) ? : $request->merge(['product_inch' => $cookie['searchFilter']['filterByInch']]);        
        // isset($cookie['searchFilter']['filterByWidth']) ? : $request->merge(['product_width' => $cookie['searchFilter']['filterByWidth']]);        
        // isset($cookie['searchFilter']['filterByPCD']) ? : $request->merge(['pcd' => $cookie['searchFilter']['filterByPCD']]);        
        // isset($cookie['searchFilter']['filterByET']) ? : $request->merge(['et' => $cookie['searchFilter']['filterByET']]);

        // if(isset($cookie['searchFilter']['filterByInch'])) {
        //     $request->merge(['product_inch' => $cookie['searchFilter']['filterByInch']]);   
        //     $request->merge(['product_width' => $cookie['searchFilter']['filterByWidth']]);
        //     $request->merge(['pcd' => $cookie['searchFilter']['filterByPCD']]);        
        //     $request->merge(['et' => $cookie['searchFilter']['filterByET']]);
        // }       
        $this->getSearchFilterValues();
        $searchFilter = json_decode(Session::get('searchFilter'), true);
        $search = new Product();
        $product['items'] = $search->getRimsByDimensionSearch()->paginate(24);
        // $product['selectedInch'] =  $request->product_inch;
        // $product['selectedWidth'] = $request->product_width;
        // $product['selectedPCD'] = $request->pcd;
        // $product['selectedET'] = $request->et;
        // $product['selectedBrand'] = $request->product_brand;

        // $cookie = json_encode($cookie);
        // Cookie::queue('cookie', $cookie, 60*24*7);
         
        // dd($product);
        
        $inches = Product::getRimInches();
        $widths = isset($this->dropDownRimWidth) ? json_decode(json_encode($this->dropDownRimWidth)) : Product::getRimWidths();
        $ets = isset($this->dropDownRimET) ? json_decode(json_encode($this->dropDownRimET)) : Product::getRimET();
        $brands = isset($this->dropDownRimBrand) ? json_decode(json_encode($this->dropDownRimBrand)) : Product::getRimBrands();
        $pcds = Product::getRimPCD();

        return view('search.search_by_dimensions_rims', compact('product', 'widths', 'inches', 'ets', 'pcds', 'brands', 'searchFilter'));
    }

    public function searchSummerTiresBySize(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('/sok/storlek/sommardack');
        }
        // $request->is_ctyre = !empty($request->is_ctyre) ? 1 : 0;
        // $request->is_runflat = !empty($request->is_runflat) ? 1 : 0;
        // dd($request->all());
        $product['productTypeID'] = 1;
        $search = new Product();
        $product['items'] = $search->getTiresByDimensionSearch($product['productTypeID'])->paginate(24);
        

        // print_r($searchFilter);
        // die();
        // $product['selectedWidth'] = $request->product_width;
        // $product['selectedProfile'] = $request->product_profile;
        // $product['selectedInch'] = $request->product_inch;
        // $product['selectedBrand'] = $request->product_brand;
        // $product['selectedModel'] = $request->product_model;
        // $product['isCTyre'] = $request->is_ctyre;
        // $product['isRunflat'] = $request->is_runflat;

        $this->getSearchFilterValues();
        $searchFilter = json_decode(Session::get('searchFilter'), true);

        $widths = Product::getTireWidths($product['productTypeID']);
        $profiles = isset($this->dropDownTireProfile) ? json_decode(json_encode($this->dropDownTireProfile)) : Product::getTireProfiles($product['productTypeID']);
        $inches = isset($this->dropDownTireInch) ? json_decode(json_encode($this->dropDownTireInch)) : Product::getTireInches($product['productTypeID']);
        $brands = isset($this->dropDownTireBrand) ? json_decode(json_encode($this->dropDownTireBrand)) : Product::getTireBrands($product['productTypeID']);
        $models = isset($this->dropDownTireModel) ? json_decode(json_encode($this->dropDownTireModel)) : Product::getTireModels($product['productTypeID']);

        return view('search.search_by_dimensions_tires', compact('product', 'widths', 'profiles', 'inches', 'brands', 'models', 'searchFilter'));
        
    }

    public function searchFriktionTiresBySize(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('/sok/storlek/friktionsdack');
        }

        $product['productTypeID'] = 2;
        $search = new Product();
        $product['items'] = $search->getTiresByDimensionSearch($product['productTypeID'])->paginate(24);

        // $product['selectedWidth'] = $request->product_width;
        // $product['selectedProfile'] = $request->product_profile;
        // $product['selectedInch'] = $request->product_inch;
        // $product['selectedBrand'] = $request->product_brand;
        // $product['selectedModel'] = $request->product_model;
        // $product['isCTyre'] = $request->is_ctyre;
        // $product['isRunflat'] = $request->is_runflat;

        $this->getSearchFilterValues();
        $searchFilter = json_decode(Session::get('searchFilter'), true);

        $widths = Product::getTireWidths($product['productTypeID']);
        $profiles = isset($this->dropDownTireProfile) ? json_decode(json_encode($this->dropDownTireProfile)) : Product::getTireProfiles($product['productTypeID']);
        $inches = isset($this->dropDownTireInch) ? json_decode(json_encode($this->dropDownTireInch)) : Product::getTireInches($product['productTypeID']);
        $brands = isset($this->dropDownTireBrand) ? json_decode(json_encode($this->dropDownTireBrand)) : Product::getTireBrands($product['productTypeID']);
        $models = isset($this->dropDownTireModel) ? json_decode(json_encode($this->dropDownTireModel)) : Product::getTireModels($product['productTypeID']);

        return view('search.search_by_dimensions_tires', compact('product', 'widths', 'profiles', 'inches', 'brands', 'models', 'searchFilter'));
        
    }

    public function searchStuddedTiresBySize(Request $request)
    {
        if( $request->has('_token') )
        {
            return redirect('/sok/storlek/dubbdack');
        }

        $product['productTypeID'] = 3;
        $search = new Product();
        $product['items'] = $search->getTiresByDimensionSearch($product['productTypeID'])->paginate(24);

        // $product['selectedWidth'] = $request->product_width;
        // $product['selectedProfile'] = $request->product_profile;
        // $product['selectedInch'] = $request->product_inch;
        // $product['selectedBrand'] = $request->product_brand;
        // $product['selectedModel'] = $request->product_model;
        // $product['isCTyre'] = $request->is_ctyre;
        // $product['isRunflat'] = $request->is_runflat;


        $this->getSearchFilterValues();
        $searchFilter = json_decode(Session::get('searchFilter'), true);

        $widths = Product::getTireWidths($product['productTypeID']);
        $profiles = isset($this->dropDownTireProfile) ? json_decode(json_encode($this->dropDownTireProfile)) : Product::getTireProfiles($product['productTypeID']);
        $inches = isset($this->dropDownTireInch) ? json_decode(json_encode($this->dropDownTireInch)) : Product::getTireInches($product['productTypeID']);
        $brands = isset($this->dropDownTireBrand) ? json_decode(json_encode($this->dropDownTireBrand)) : Product::getTireBrands($product['productTypeID']);
        $models = isset($this->dropDownTireModel) ? json_decode(json_encode($this->dropDownTireModel)) : Product::getTireModels($product['productTypeID']);

        return view('search.search_by_dimensions_tires', compact('product', 'widths', 'profiles', 'inches', 'brands', 'models', 'searchFilter'));
        
    }


    public function getCarManuf(Request $request)
    {
        // if (\App::environment('production')) {
        //     $username = 'wheelzone';
        //     $password = 'wheel@zone123';
        // } else {
        //     $username = 'ptest';
        //     $password = 'ptest';
        // }
        $username = env('API_SEARCH_USER');
        $password = env('API_SEARCH_PASS');

        $headers[] = 'Authorization: Basic ' .
        base64_encode($username.':'.$password);
        $headers[] = 'Content-Type: application/json';

        $host = "https://slimapi.abswheels.se/carData/Manufacturer/";
        
        // if($request->stext) {

        // } else {
        //     // $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=getresult&modelid={$request->modelid}&ktype={$request->ktype}";
        //     $host = "https://slimapi.abswheels.se/carData/Manufacturer/";
        // }

        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $search = json_decode($apiResponse);

        // if(isset($request->stext)) {
        //     $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=manufacturer&stext=" . $request->stext;
        // } else {
        //      $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=manufacturer";
        // }
        // $JSONResponse = file_get_contents($service_url);
        // $searchData = json_decode($JSONResponse);

        $html = "";
        foreach ($search->data as $carManuf) {
            $html .= '<button type="button" class="list-group-item" value=' . $carManuf->ID . ' data-search-item="'. $carManuf->Carbrand .'"><img height="20" src="' . $carManuf->ImageLink . '"> &nbsp;&nbsp;' . $carManuf->Carbrand . '</button>';
        }

        // dd($html);
        if(isset($request->stext)) {
            $html = '
                <div id="carManufactorers" class="list-group list">'
                    . $html . 
                '</div>';
        } else {
             $html = '
                <div class="inner-addon left-addon '.$request->stext .'">
                    <i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                    <input id="search-manuf-text" type="text" class="form-control" placeholder="Sök efter bil">
                </div><!-- /.inner-addon -->
                <div id="carManufactorers" class="list-group list">'
                    . $html . 
                '</div>';
        }

        return response()->json($html);
    }

    public function getCarModels(Request $request)
    {
        // if (\App::environment('production')) {
        //     $username = 'wheelzone';
        //     $password = 'wheel@zone123';
        // } else {
        //     $username = 'ptest';
        //     $password = 'ptest';
        // }
        $username = env('API_SEARCH_USER');
        $password = env('API_SEARCH_PASS');

        $headers[] = 'Authorization: Basic ' .
        base64_encode($username.':'.$password);
        $headers[] = 'Content-Type: application/json';

        $host = "https://slimapi.abswheels.se/carData/Getmodels/{$request->carBrand}/";

        // if(isset($request->stext)) {
        //     // $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=models&makeid=" . $request->carBrand . "&stext=" . $request->stext;
        // } else {
        //     // $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=models&makeid=" . $request->carBrand;
        //     $host = "https://slimapi.abswheels.se/carData/Getmodels/{$request->carBrand}/";
        // }

        // $JSONResponse = file_get_contents($service_url);
        // $searchData = json_decode($JSONResponse);
        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $search = json_decode($apiResponse);
        // dd($search);

        // dd($searchData);
        $html = "";
        foreach ($search->data as $carBrand) {
            $html .= '<button type="button" class="list-group-item" data-modelid="'. $carBrand->ID .'" data-ktype="'. $carBrand->Link_ID .'">' . $carBrand->Model_name . ' ' . $carBrand->Constructed_from . '-' . $carBrand->Constructed_to . '</button>';
        }

        // dd($html);
        if(isset($request->stext)) {
            $html = '
                <div id="carModels" class="list-group list">'
                    . $html . 
                '</div>';
        } else {
             $html = '
                <div class="inner-addon left-addon '.$request->stext .'">
                    <i class="glyphicon glyphicon-search" aria-hidden="true"></i>
                    <input data-carbrand="'. $request->carBrand .'" id="search-model-text" type="text" class="form-control" placeholder="Sök efter bil">
                </div><!-- /.inner-addon -->
                <div id="carModels" class="list-group list">'
                    . $html . 
                '</div>';
        }

        return response()->json($html);
    }

    // public function getAddress(Request $request)
    // {
        // if (\App::environment('production')) {
        //     $username = 'wheelzone';
        //     $password = 'wheel@zone123';
        // } else {
        //     $username = 'ptest';
        //     $password = 'ptest';
        // }
    //     $headers[] = 'Authorization: Basic ' .
    //     base64_encode($username.':'.$password);
    //     $headers[] = 'Content-Type: application/json';
    //     $host = "https://slimapi.abswheels.se/regNoSearch/$request->ssn/";
    //     $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
    //     $apiResponse = json_decode($apiResponse);
    //     dd($apiResponse);
    //     // return $apiResponse;
    //     $message = false;
    //     $customerInfoList = [];

    // }

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

    public function searchRegNr(Request $request)
    {
        if (\App::environment('production')) {
            $username = 'hjulonline';
            $password = 'hjul12@online';
        } else {
            $username = 'ptest';
            $password = 'ptest';
        }
        
        // $username = env('API_SEARCH_USER');
        // $password = env('API_SEARCH_PASS');

        $headers[] = 'Authorization: Basic ' .
        base64_encode($username.':'.$password);
        $headers[] = 'Content-Type: application/json';
        
        

        // dd($request->modeli§d, $request->ktype);
        if($request->regnr) {
            // $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=vrm&regnr={$request->regnr}";
            $host = "https://slimapi.abswheels.se/regNoSearch/$request->regnr/";

        } elseif ( $request->modelid && $request->ktype ) {
            // $service_url = "https://www.abswheels.se/abs_api/cardata/?user=sibar&pass=test123&query=getresult&modelid={$request->modelid}&ktype={$request->ktype}";
            $host = "https://slimapi.abswheels.se/carData/Getresult/{$request->modelid}/{$request->ktype}/";
        }

        // $JSONResponse = file_get_contents($service_url);
        // $searchData = json_decode($JSONResponse, true);
        $apiResponse = $this->CallAPIHeader("GET", $headers, $host);
        $search = json_decode($apiResponse, true);
        // dd($search);
        $search['data']['RegNumber'] = !isset($request->regnr)?: $request->regnr;
        $wheelSizes = [];
        $selectedSize = "";

        // $productTypeID = $request->productTypeID;
        //DB::enableQueryLog();
        if(isset($search['status']) && $search['status'] == "Ok" && !empty($search['data']['Upsteps'])) {
            $token = new UUID('regsearch_');
            // $ip = Request::ip();
            // $search['data']['Upsteps']['token'] = $token->uuid;
            // dd($request->all(), $search['data'], $token->uuid);
            $products = new Product();
            $wheelSizes = $products->getWheelSizes($search['data'], $token->uuid);
            $selectedSize = sizeOf($wheelSizes) > 0 ? $wheelSizes->first()->RimSize : null;

            foreach ($search['data'] as $key => $value) {
                if($key == 'Upsteps')
                    continue;

                $searchData[$key] = $value; 
            }

            // dd($searchData);

            // $selectedSize = sizeOf($wheelSizes) > 0 ? $wheelSizes{0}->WheelSize_2 : null;
            // dd($selectedSize, $wheelSizes);
        } else {
            $products = $search['data'];
        }

        if(empty($selectedSize) && $request->regnr) {
            return [
                'error' => 'Registreringsnumret kunde tyvärr inte matchas.<br> Här kan du istället söka manuellt efter rätt bil.'
            ];
        }

        if(empty($selectedSize) && $request->modelid) {
            return [
                'error' => 'Hittar inga produkter som matchar vald bilmodel.<br>Det finns dock produkter som går att anpassa. <br>Kontakta gärna info@hjulonline.se för hjälp.'
            ];
        }
        //Default selected size
        // session(['regSearchData' => $curl_response, 'wheelSizes' => $wheelSizes]);
        // $searchData = json_decode(session()->get('regSearchData'));

        
        // dd($searchData);

        $searchData['CarSearchTitle'] = $searchData['FoundVehical'] ? $searchData['FoundVehical']." ".$searchData['FoundYear'] : $searchData['Manufacturer']." ".$searchData['ModelName']." ".$searchData['ConstructedFrom']."-".$searchData['ConstructedTo'];

        if(isset($searchData['Manufacturer'])) {
            if($request->regnr) {
                $dt = Carbon::now();
                $dt->subSeconds(10); 

                $checkSearch = CustomerSearch::where('ip_number', $request->ip())
                                            ->where('created_at', '>=', $dt)
                                            ->get();

                if(sizeof($checkSearch) <= 0) {
                    $insertCustomerSerach = new CustomerSearch;
                    $insertCustomerSerach->reg_number = $request->regnr;
                    $insertCustomerSerach->car_info = $searchData['FoundVehical']." ".$searchData['FoundYear']." ".$searchData['FoundDackBack']." ".$searchData['PCD']." ".$searchData['ShowCenterBore']." ET:".$searchData['Offset'] ;
                    $insertCustomerSerach->ip_number = $request->ip();
                    $insertCustomerSerach->save();
                }
            }
                
            $carSearch = json_encode(['searchData' => $searchData , 'token' => $token->uuid, 'wheelSizes' => $wheelSizes, 'selectedSize' => $selectedSize, 'selectedDimension' => []]);
            Cookie::queue('carSearch', $carSearch, 60*24*7);
            
            return [
                'searchResult' => view('search.partials.form.search_reg_form', compact('searchData', 'wheelSizes'))->render(),
            ];
        }

        return Response::json();
        
    }

    public function getProductsByDimensions($productTypeID, $columns)
    {
        $products = Product::where(function($query) use ($columns) {
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'page' || $colName == 'productTypeID')
                    continue;
                
                if($colName == 'textSearch') {
                    $query->where('product_name', 'like', "%{$colValue}%");
                    continue;
                }

                $query->where($colName, $colValue);
            }
        })
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->where('product_type_id', $productTypeID)
        ->orderBy('priority_supplier')
        ->orderBy('price');

        return $products;
    }

    public function getRimsByDimensions($columns)
    {
        $products = Product::where(function($query) use ($columns) {
        
            foreach ($columns as $colName => $colValue) {
                if(empty($colValue) || $colName == 'pcd' || $colName == 'page')
                    continue;

                if($colName == 'textSearch') {
                    $query->where('product_name', 'like', "%{$colValue}%");
                    continue;
                }
                
                $query->where($colName, $colValue);
            }
        })
        ->where(function($query) use ($columns) {
            if(!empty($columns['pcd']) && $columns['pcd'] != 'BLANK') {
                return $query
                    ->where('pcd1',   $columns['pcd'])
                    ->orWhere('pcd2', $columns['pcd'])
                    ->orWhere('pcd3', $columns['pcd'])
                    ->orWhere('pcd4', $columns['pcd'])
                    ->orWhere('pcd5', $columns['pcd'])
                    ->orWhere('pcd6', $columns['pcd'])
                    ->orWhere('pcd7', $columns['pcd'])
                    ->orWhere('pcd8', $columns['pcd'])
                    ->orWhere('pcd9', $columns['pcd'])
                    ->orWhere('pcd10', $columns['pcd'])
                    ->orWhere('pcd11', $columns['pcd'])
                    ->orWhere('pcd12', $columns['pcd'])
                    ->orWhere('pcd13', $columns['pcd'])
                    ->orWhere('pcd14', $columns['pcd'])
                    ->orWhere('pcd15', $columns['pcd'])
                    ->orwhere('pcd1',  'BLANK');
            }
        })
        ->where('product_category_id', 2)
        ->where('is_shown', 1)
        ->where('is_deleted', 0)
        ->orderBy('priority_supplier', 'ASC')
        ->orderBy('price');

        // ->toSql();
        // dd($products);
        // 
        // \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
        //     var_dump($query->sql);
        //     var_dump($query->bindings);
        //     var_dump($query->time);
        // });

        return $products;
    }

    // public function getCookieValues()
    // {
    //     $carSearch = json_decode(Cookie::get('carSearch'), true);
    //     $this->searchData = $carSearch['searchData'];
    //     $this->wheelSizes = $carSearch['wheelSizes'];
    //     $this->selectedSize = isset($carSearch['selectedSize']) ? $carSearch['selectedSize'] : null;
    //     $this->selectedDimension = isset($carSearch['selectedDimension']) ? $carSearch['selectedDimension'] : null;
    //     $this->productTypeID = isset($carSearch['productTypeID']) ? $carSearch['productTypeID'] : null;
    //     $this->completeTiresProcess = isset($carSearch['completeTiresProcess']) ? $carSearch['completeTiresProcess'] : 0;
    // }     

    // public function storeValuesInCookie()
    // {   
    //     $carSearch['searchData'] = $this->searchData; 
    //     $carSearch['wheelSizes'] = $this->wheelSizes; 
    //     $carSearch['selectedSize'] = $this->selectedSize; 
    //     $carSearch['selectedDimension'] = $this->selectedDimension; 
    //     $carSearch['productTypeID'] = $this->productTypeID;
    //     $carSearch['completeTiresProcess'] = $this->completeTiresProcess;
    //     $carSearch = json_encode($carSearch);
    //     Cookie::queue('carSearch', $carSearch, 60*24*7);
    // }

    public function filterRimsByInch(Request $request)
    {
        // dd($request->all());
        // $cookie = json_decode(Cookie::get('cookie'), true);
        // $cookie['searchFilter']['filterByInch'] = $request->product_inch;
        // $cookie['searchFilter']['filterByWidth'] = "";
        // $cookie['searchFilter']['filterByPCD'] = $request->pcd;
        // $cookie['searchFilter']['filterByET'] = "";
        // $cookie['searchFilter']['filterByBrand'] = "";
        // $cookie = json_encode($cookie);
        // Cookie::queue('cookie', $cookie, 60*24*7);
        $this->getSearchFilterValues();
        $this->filterRimsByInch = $request->product_inch;
        $this->filterRimsByWidth = '';
        $this->filterRimsByET = '';
        $this->filterRimsByBrand = '';
        $this->filterRimsByPCD = $request->pcd;

        $widths = Product::getRimWidths($request->all());
        $ets = Product::getRimET($request->all());
        $brands = Product::getRimBrands($request->all());
        $this->dropDownRimWidth = $widths;
        $this->dropDownRimET = $ets;
        $this->dropDownRimBrand = $brands;
        $this->storeSearchFilterInCookie();

        // dd($this->dropDownRimWidth, $widths, $request->all());
        $dropdownWidth = '<option value="">Välj bredd</option>';
        foreach ($widths as $width) {
            $dropdownWidth .= '<option value="' . $width->product_width . '">' . $width->product_width . '</option>';
        }

        $dropdownET = '<option value="">Välj inpressning</option>';
        foreach ($ets as $et) {
            $dropdownET .= '<option value="' . $et->et . '">' . $et->et . '</option>';
        }

        $dropdownBrand = '<option value="">Välj Märke</option>';
        foreach ($brands as $brand) {
            if($brand->product_brand == "")
                continue;
                
            $dropdownBrand .= '<option value="' . $brand->product_brand . '">' . $brand->product_brand . '</option>';
        }

        $data = $this->getRimsByDimensions($request->all());
        $product['items'] = $data->paginate(24);
        $rim_dimension_search = true;

        $html = [
            'dropdownWidth' => $dropdownWidth,
            'dropdownET' => $dropdownET,
            'dropdownBrand' => $dropdownBrand
        ];

        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product', 'rim_dimension_search', 'carSearch'))->render(),
            'productCount' => $product['items']->total(),
            'html' => $html,
            // 'next_page' => $products->nextPageUrl()
        ];

        // return response()->json($html);
    }

    public function filterRimsByWidth(Request $request)
    {
        // dd($request->all());

        // $cookie = json_decode(Cookie::get('cookie'), true);
        // $cookie['searchFilter']['filterByInch'] = $request->product_inch;
        // $cookie['searchFilter']['filterByWidth'] = $request->product_width;
        // $cookie['searchFilter']['filterByPCD'] = $request->pcd;
        // $cookie['searchFilter']['filterByET'] = null;
        // $cookie['searchFilter']['filterByBrand'] = null;
        // $cookie = json_encode($cookie);
        // Cookie::queue('cookie', $cookie, 60*24*7);

        $this->getSearchFilterValues();
        $this->filterRimsByInch = $request->product_inch;
        $this->filterRimsByWidth = $request->product_width;
        $this->filterRimsByET = '';
        $this->filterRimsByBrand = '';
        $this->filterRimsByPCD = $request->pcd;
        
        $ets = Product::getRimET($request->all());
        $brands = Product::getRimBrands($request->all());
        $this->dropDownRimET = $ets;
        $this->dropDownRimBrand = $brands;
        $this->storeSearchFilterInCookie();
        // dd($brands);

        $dropdownET = '<option value="">Välj inpressning</option>';
        foreach ($ets as $et) {
            $dropdownET .= '<option value="' . $et->et . '">' . $et->et . '</option>';
        }

        $dropdownBrand = '<option value="">Välj Märke</option>';
        foreach ($brands as $brand) {
            if($brand->product_brand == "")
                continue;

            // dd($key, $brand->product_brand);
            $dropdownBrand .= '<option value="' . $brand->product_brand . '">' . $brand->product_brand . '</option>';
        }

        $data = $this->getRimsByDimensions($request->all());
        $product['items'] = $data->paginate(24);
        $rim_dimension_search = true;

        $html = [
            'dropdownET' => $dropdownET,
            'dropdownBrand' => $dropdownBrand
        ];
        
        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product', 'rim_dimension_search'))->render(),
            'productCount' => $product['items']->total(),
            'html' => $html,
        ];

        // return response()->json($html);
    }

    public function filterRimsByET(Request $request)
    {
        // dd($request->all());
        // $cookie = json_decode(Cookie::get('cookie'), true);
        // $cookie['searchFilter']['filterByInch'] = $request->product_inch;
        // $cookie['searchFilter']['filterByWidth'] = $request->product_width;
        // $cookie['searchFilter']['filterByPCD'] = $request->pcd;
        // $cookie['searchFilter']['filterByET'] = $request->et;
        // $cookie['searchFilter']['filterByBrand'] = "";
        // $cookie = json_encode($cookie);
        // Cookie::queue('cookie', $cookie, 60*24*7);

        $this->getSearchFilterValues();
        $this->filterRimsByInch = $request->product_inch;
        $this->filterRimsByWidth = $request->product_width;
        $this->filterRimsByET = $request->et;
        $this->filterRimsByBrand = '';
        $this->filterRimsByPCD = $request->pcd;
        
        
        $brands = Product::getRimBrands($request->all());
        $this->dropDownRimBrand = $brands;
        $this->storeSearchFilterInCookie();

        $dropdownBrand = '<option value="">Välj Märke</option>';
        foreach ($brands as $brand) {
            if($brand->product_brand == "")
                continue;

            $dropdownBrand .= '<option value="' . $brand->product_brand . '">' . $brand->product_brand . '</option>';
        }

        $data = $this->getRimsByDimensions($request->all());
        $product['items'] = $data->paginate(24);
        $rim_dimension_search = true;

        $html = [
            'dropdownBrand' => $dropdownBrand
        ];

        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product', 'rim_dimension_search'))->render(),
            'productCount' => $product['items']->total(),
            'html' => $html
        ];
        // return response()->json($html);
    }

    public function filterRimsByBrand(Request $request)
    {
        // dd($request->all());
        // $cookie = json_decode(Cookie::get('cookie'), true);
        // $cookie['searchFilter']['filterByInch'] = $request->product_inch;
        // $cookie['searchFilter']['filterByWidth'] = $request->product_width;
        // $cookie['searchFilter']['filterByPCD'] = $request->pcd;
        // $cookie['searchFilter']['filterByET'] = $request->et;
        // $cookie['searchFilter']['filterByBrand'] = $request->product_brand;
        // $cookie = json_encode($cookie);
        // Cookie::queue('cookie', $cookie, 60*24*7);
        
        $this->getSearchFilterValues();
        $this->filterRimsByInch = $request->product_inch;
        $this->filterRimsByWidth = $request->product_width;
        $this->filterRimsByET = $request->et;
        $this->filterRimsByBrand = $request->product_brand;
        $this->filterRimsByPCD = $request->pcd;
        $this->storeSearchFilterInCookie();

        $data = $this->getRimsByDimensions($request->all());
        $product['items'] = $data->paginate(24);
        $rim_dimension_search = true;

        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product', 'rim_dimension_search'))->render(),
            'productCount' => $product['items']->total(),
        ];
        // return response()->json($html);
    }


    public function filterByWidth(Request $request, $productTypeID)
    {
        // dd($request->all());
        $this->getSearchFilterValues();
        $this->filterTireByWidth = $request->product_width;
        $this->filterTireByProfile = '';
        $this->filterTireByInch = '';
        $this->filterTireByBrand = '';
        $this->filterTireByModel = '';

        $profiles = Product::getTireProfiles($productTypeID, $request->all());
        $inches = Product::getTireInches($productTypeID, $request->all());
        $brands = Product::getTireBrands($productTypeID, $request->all());
        $models = Product::getTireModels($productTypeID, $request->all());

        $this->dropDownTireProfile = $profiles;
        $this->dropDownTireInch = $inches;
        $this->dropDownTireBrand = $brands;
        $this->dropDownTireModel = $models;

    	$dropdownProfile = '<option value="">Välj profil</option>';
    	foreach ($profiles as $profile) {
    		$dropdownProfile .= '<option value="' . $profile->product_profile . '">' . $profile->product_profile . '</option>';
    	}

    	$dropdownInch = '<option value="">Välj tum</option>';
    	foreach ($inches as $inch) {
    		$dropdownInch .= '<option value="' . $inch->product_inch . '">' . $inch->product_inch . '</option>';
    	}

    	$dropdownBrand = '<option value="">Välj märke</option>';
    	foreach ($brands as $brand) {
    		$dropdownBrand .= '<option value="' . $brand->product_brand . '">' . $brand->product_brand . '</option>';
    	}

    	$dropdownModel = '<select name="product_model" class="form-control"><option value="">Välj mönster</option>';
    	foreach ($models as $model) {
    		$dropdownModel .= '<option value="' . $model->product_model . '">' . $model->product_model . '</option>';
    	}
    	$dropdownModel .= '</select>';


        $data = $this->getProductsByDimensions($productTypeID, $request->all());
        $product['items'] = $data->paginate(24);
        $this->storeSearchFilterInCookie();

    	$html = [
    		'dropdownProfile' => $dropdownProfile,
    		'dropdownInch' => $dropdownInch,
    		'dropdownBrand' => $dropdownBrand,
    		'dropdownModel' => $dropdownModel
    	];
        
        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
            'productCount' => $product['items']->total(),
            'html' => $html,
        ];

    	// return response()->json($html);
    }

    public function filterByProfile(Request $request, $productTypeID)
    {
        $this->getSearchFilterValues();
        $this->filterTireByWidth = $request->product_width;
        $this->filterTireByProfile = $request->product_profile;
        $this->filterTireByInch = '';
        $this->filterTireByBrand = '';
        $this->filterTireByModel = '';

        $inches = Product::getTireInches($productTypeID, $request->all());
        $brands = Product::getTireBrands($productTypeID, $request->all());
        $models = Product::getTireModels($productTypeID, $request->all());
        
        $this->dropDownTireInch = $inches;
        $this->dropDownTireBrand = $brands;
        $this->dropDownTireModel = $models;

    	$dropdownInch = '<option value="">Välj tum</option>';
    	foreach ($inches as $inch) {
    		$dropdownInch .= '<option value="' . $inch->product_inch . '">' . $inch->product_inch . '</option>';
    	}

    	$dropdownBrand = '<option value="">Välj märke</option>';
    	foreach ($brands as $brand) {
    		$dropdownBrand .= '<option value="' . $brand->product_brand . '">' . $brand->product_brand . '</option>';
    	}

    	$dropdownModel = '<select name="product_model" class="form-control"><option value="">Välj mönster</option>';
    	foreach ($models as $model) {
    		$dropdownModel .= '<option value="' . $model->product_model . '">' . $model->product_model . '</option>';
    	}
    	$dropdownModel .= '</select>';

    	
        $data = $this->getProductsByDimensions($productTypeID, $request->all());
        $product['items'] = $data->paginate(24);
        $this->storeSearchFilterInCookie();

        $html = [
            'dropdownInch' => $dropdownInch,
            'dropdownBrand' => $dropdownBrand,
            'dropdownModel' => $dropdownModel
        ];
        
        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
            'productCount' => $product['items']->total(),
            'html' => $html,
        ];
    	// return response()->json($html);
    }

    public function filterByInch(Request $request, $productTypeID)
    {
    	$this->getSearchFilterValues();
        $this->filterTireByWidth = $request->product_width;
        $this->filterTireByProfile = $request->product_profile;
        $this->filterTireByInch = $request->product_inch;
        $this->filterTireByBrand = '';
        $this->filterTireByModel = '';

        $brands = Product::getTireBrands($productTypeID, $request->all());
        $models = Product::getTireModels($productTypeID, $request->all());
        
        $this->dropDownTireBrand = $brands;
        $this->dropDownTireModel = $models;

    	$dropdownBrand = '<option value="">Välj märke</option>';
    	foreach ($brands as $brand) {
    		$dropdownBrand .= '<option value="' . $brand->product_brand . '">' . $brand->product_brand . '</option>';
    	}

    	$dropdownModel = '<select name="product_model" class="form-control"><option value="">Välj mönster</option>';
    	foreach ($models as $model) {
    		$dropdownModel .= '<option value="' . $model->product_model . '">' . $model->product_model . '</option>';
    	}
    	$dropdownModel .= '</select>';

    	
        $data = $this->getProductsByDimensions($productTypeID, $request->all());
        $product['items'] = $data->paginate(24);
        $this->storeSearchFilterInCookie();

        $html = [
            'dropdownBrand' => $dropdownBrand,
            'dropdownModel' => $dropdownModel
        ];
        
        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
            'productCount' => $product['items']->total(),
            'html' => $html,
        ];
    	// return response()->json($html);
    }

    public function filterByBrand(Request $request, $productTypeID)
    {
    	$this->getSearchFilterValues();
        $this->filterTireByWidth = $request->product_width;
        $this->filterTireByProfile = $request->product_profile;
        $this->filterTireByInch = $request->product_inch;
        $this->filterTireByBrand = $request->product_brand;
        $this->filterTireByModel = '';

        $models = Product::getTireModels($productTypeID, $request->all());
        $this->dropDownTireModel = $models;

    	$dropdownModel = '<select name="product_model" class="form-control"><option value="">Välj mönster</option>';
    	foreach ($models as $model) {
    		$dropdownModel .= '<option value="' . $model->product_model . '">' . $model->product_model . '</option>';
    	}
    	$dropdownModel .= '</select>';

    	
        $data = $this->getProductsByDimensions($productTypeID, $request->all());
        $product['items'] = $data->paginate(24);
        $this->storeSearchFilterInCookie();

        $html = [
            'dropdownModel' => $dropdownModel
        ];
        // dd($products);
        
        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
            'productCount' => $product['items']->total(),
            'html' => $html,
        ];
    	// return response()->json($html);
    }

    public function filterByModel(Request $request, $productTypeID)
    {
        // dd($request->all());
        
        $this->getSearchFilterValues();
        $this->filterTireByWidth = $request->product_width;
        $this->filterTireByProfile = $request->product_profile;
        $this->filterTireByInch = $request->product_inch;
        $this->filterTireByBrand = $request->product_brand;
        $this->filterTireByModel = $request->product_model;

        $data = $this->getProductsByDimensions($productTypeID, $request->all());
        $product['items'] = $data->paginate(24);
        $this->storeSearchFilterInCookie();

        return [
            'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
            'productCount' => $product['items']->total(),
        ];
        // return response()->json($html);
    }

    public function productNameSearchSuggestion(Request $request, ProductsRepository $repository)
    {
        $request->searchString = trim($request->searchString);
        // if(empty($request->searchString))
        //     return;

        $suggestedProducts = $repository->search( (string) $request->searchString, 5 );
        // dd($suggestedProducts);
        // $suggestedProducts = Product::where('product_name', 'LIKE', "%{$request->searchString}%")
        //                         ->where('is_shown', 1)
        //                         ->where('is_deleted', 0)
        //                         ->limit(5)
        //                         ->get();

        return [
            'suggestionBox' => view('search.partials.search_data.suggestion_box', compact('suggestedProducts'))->render()
        ];
    }

    public function searchAllProducts(Request $request, ProductsRepository $repository)
    {
        $request->soktxt = trim($request->soktxt);
        // $searchTxt = $request->soktxt;

        $product['items'] = $repository->search( (string) $request->soktxt, 96 );

        // $product['items'] = Product::where('product_name', 'LIKE', "%{$request->soktxt}%")
        //                         ->where('is_shown', 1)
        //                         ->where('is_deleted', 0)
        //                         ->paginate(24);
        
        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_result', compact('product'))->render(),
                'productCount' => $product['items']->total()
            ];
        }

        $rimInches = Product::getRimInches();
        $rimWidths = Product::getRimWidths();
        $rimEts = Product::getRimET();
        $rimPcds = Product::getRimPCD();
        $rimBrands = Product::getRimBrands();

        $productType = Product::distinct()->select('product_type_id')->where('product_type_id', '<=', 3)->get();
        $tireWidths = Product::getTireWidths(1);
        $tireProfiles = Product::getTireProfiles(1);
        $tireInches = Product::getTireInches(1);
        $tireBrands = Product::getTireBrands(1);
        $tireModels = Product::getTireModels(1);

        return view('search.search_all_products', compact('product', 'rimInches', 'rimWidths', 'rimEts', 'rimPcds', 'rimBrands', 'productType', 'tireWidths', 'tireProfiles', 'tireInches', 'tireBrands', 'tireModels' ));
    }

    public function deleteSearchCookie(Request $request)
    {
        // $carSearch = json_decode(Cookie::get('carSearch'), true);
        $this->getCookieValues();
        // dd($this->token);
        if(isset($this->token)) {
            UpstepsTmp::where('token', $this->token)->delete();
        }
        
        $dt = Carbon::now();
        $dt->subWeeks(2);
        UpstepsTmp::where('created_at', '<=', $dt)->delete();
        
        Cookie::queue(Cookie::forget('carSearch'));
        // session()->forget('regSearchData');
        // session()->forget('wheelSizes');
        if(isset($request->site))
        	return redirect($request->site);

        return redirect()->back();
    }
}
