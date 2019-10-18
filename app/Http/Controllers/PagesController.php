<?php

namespace App\Http\Controllers;


use App\ABSWheel;
use App\AdTracking;
use App\Http\Requests;
use App\Page;
use App\Product;
use App\ProductImage;
use App\Search;
use App\Setting;
use App\Upsteps;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Cache;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class PagesController extends Controller
{
    public function __construct()
    {
        // error_reporting(0);
        // @ini_set('display_errors', 0);
        if(isset($_GET['utm_source'])) {
            session_start();
            // dd($_GET['utm_source'], $this->get_ip_address(), session_id());
            $adTrack = new AdTracking;
            $adTrack->ip_number = $this->get_ip_address();
            $adTrack->user_session_id = session_id();
            $adTrack->utm_source = $_GET['utm_source'];
            $adTrack->utm_medium = isset($_GET['utm_medium']) ? $_GET['utm_medium']: "";
            $adTrack->utm_campaign = isset($_GET['utm_campaign']) ? $_GET['utm_campaign']: "";
            $adTrack->save();
        }
    }

    // private function storeHome()
    // {
    //     $this->putInCache('home', $this->getHome(), 'home');
    // }

    // private function putInCache($key, $content, $tag)
    // {
    //     Cache::put($key, $content, 43200);
    // }

    // public function index()
    // {
    //     // Cache::forget('home');
    //     // Check cache first
    //     // Cache::put('home', "hej", 43200);
    //     // dd(Cache::get('home'));
    //     $page = Cache::get('home');
    //     if ($page != null) {
    //         return $page;
    //     }

    //     //Get from the database
    //     // $this->storeHome();
    //     return $this->getHome();
    // }


    public function index()
    {
        // Cart::destroy();
        // return view('under_construction'); 
        // $value = Cookie::get('carSearch');
        // if(isset($value)) {
        //     return redirect('/searchRegNr');
        // } 
        
        // \SEO::setTitle('Kompletta hjul från Sveriges billigaste aktör - Hjulonline');

        // \SEO::setDescription('Handla kompletta hjul till bästa pris på hjulonline.se. Fynda hjul, bildäck och fälg till rekordlåga priser. Fri frakt gäller vid köp av 4 hjul. @hjulonline');
        
        
        $page = Page::where('name', '/')->first();
        SEOMeta::setTitle($page->meta_title)
            ->setDescription($page->meta_description)
            ->setKeywords($page->meta_keywords);

        OpenGraph::setTitle("Kompletta hjul");
        OpenGraph::setDescription("Hjul online saluför marknadens billiga kompletta hjul.");

		$rims = Product::where('product_category_id', 2)
                        ->where('priority', '>', 0)
                        ->where('priority', '<=', 24)
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('priority')->get();
		$rims->load(['productImages' => function ($query) {
            $query->where('priority', 1);
		}]);

        $tires = Product::where('product_category_id', 1)
                        ->where('priority', '>', 0)
                        ->where('priority', '<=', 24)
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('priority')->get();
        $tires->load(['productImages' => function ($query) {
            $query->where('priority', 1);
        }]);

        // $widths = Product::getWidths(1);
        // $profiles = Product::getProfiles(1);
        // $inches = Product::getInches(1);
        // $brands = Product::getBrands(1);
        // $models = Product::getModels(1);
        // 
        


        $carData = Cookie::get('carSearch');
        if(isset($carData)) {
            $carSearch = json_decode(Cookie::get('carSearch'), true);
            $searchData = $carSearch['searchData'];
            $wheelSizes = $carSearch['wheelSizes'];
            
            return view('pages.complete_wheels', compact('rims', 'tires', 'searchData', 'wheelSizes', 'page'))->render();
        } else {
            return view('pages.complete_wheels', compact('rims', 'tires', 'page'))->render(); 
        }
    }

    public function rims(Request $request)
    {
        // return view('under_construction');
        $value = Cookie::get('carSearch');
        if(isset($value)) {
            return redirect('/sok/reg/falgar');
        } 

        $page = Page::where('name', 'falgar')->first();
        SEOMeta::setTitle($page->meta_title)
            ->setDescription($page->meta_description)
            ->setKeywords($page->meta_keywords)
            ->setCanonical( env('APP_URL') . "/falgar" );

        OpenGraph::setTitle("Fälgar");
        OpenGraph::setDescription("Här handlar man Fälgar, aluminiumfälgar och snygga fäljar.");

        $searchFilter = json_decode(Session::get('searchFilter'), true);

        $inches = Product::getRimInches();
        $widths = isset($searchFilter['dropDownRimWidth']) ? json_decode(json_encode($searchFilter['dropDownRimWidth'])) : Product::getRimWidths();
        $ets = isset($searchFilter['dropDownRimET']) ? json_decode(json_encode($searchFilter['dropDownRimET'])) : Product::getRimET();
        $brands = isset($searchFilter['dropDownRimBrand']) ? json_decode(json_encode($searchFilter['dropDownRimBrand'])) : Product::getRimBrands();
        $pcds = Product::getRimPCD();

        // $carList = $this->getCarManuf();

        // dd($carList[0]);
		// $products = Product::where('product_category_id', 2)
  //                       ->where('priority', '>', 0)
  //                       ->where('priority', '<=', 24)
  //                       ->where('is_shown', 1)
  //                       ->where('is_deleted', 0)
  //                       ->orderBy('priority')
  //                       ->get();
        // Cache::flush();
        $products = Cache::remember('rims_products_cache' . '_page_' . $request->page, 720, function() {
            return Product::where('product_category_id', 2)
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('price')
                        ->paginate(18);
        });

        // $products = Product::where('product_category_id', 2)
        //                 ->where('is_shown', 1)
        //                 ->where('is_deleted', 0)
        //                 ->orderBy('price')
        //                 ->paginate(18);

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.front_page_products', compact('products'))->render(),
            ];
        }

    	return view('pages.rims', compact('products', 'carList', 'inches', 'widths', 'ets', 'pcds', 'brands', 'searchFilter', 'page'));
    }

    public function summerTires(Request $request)
    {
        // return view('under_construction');
        $value = Cookie::get('carSearch');
        if(isset($value)) {
            return redirect('/sok/reg/sommardack');
        }  

        $page = Page::where('name', 'sommardack')->first();
        SEOMeta::setTitle($page->meta_title)
            ->setDescription($page->meta_description)
            ->setKeywords($page->meta_keywords)
            ->setCanonical( env('APP_URL') . "/sommardack" );

        OpenGraph::setTitle("Sommardäck");
        OpenGraph::setDescription("Hitta sommardäck och sommarhjul till billiga priser.");

        $searchFilter = json_decode(Session::get('searchFilter'), true);

        $widths =  Product::getTireWidths(1);
        $profiles =  isset($searchFilter['dropDownTireProfile']) ? json_decode(json_encode($searchFilter['dropDownTireProfile'])) : Product::getTireProfiles(1);
        $inches = isset($searchFilter['dropDownTireInch']) ? json_decode(json_encode($searchFilter['dropDownTireInch'])) : Product::getTireInches(1);
        $brands = isset($searchFilter['dropDownTireBrand']) ? json_decode(json_encode($searchFilter['dropDownTireBrand'])) : Product::getTireBrands(1);
        $models = isset($searchFilter['dropDownTireModel']) ? json_decode(json_encode($searchFilter['dropDownTireModel'])) : Product::getTireModels(1);

        // dd($searchFilter , $widths, $profiles, $inches, $brands, $models);
        // $products = Product::where('product_category_id', 1)
        //                     ->where('product_type_id', 1)
        //                     ->where('priority', '>', 0)
        //                     ->where('priority', '<=', 24)
        //                     ->where('is_shown', 1)
        //                     ->where('is_deleted', 0)
        //                     ->orderBy('priority')
        //                     ->get();

        $products = Cache::remember('summerTire_products_cache' . '_page_' . $request->page, 720, function() {
             return $products = Product::where('product_category_id', 1)
                        ->where('product_type_id', 1)
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('price')
                        ->paginate(18);
        });                    
        // $products = Product::where('product_category_id', 1)
        //                 ->where('product_type_id', 1)
        //                 ->where('is_shown', 1)
        //                 ->where('is_deleted', 0)
        //                 ->orderBy('price')
        //                 ->paginate(18);

        if(sizeOf($products) <= 0) {
            $products = Product::where('product_category_id', 1)
                            ->where('product_type_id', 1)
                            ->where('is_shown', 1)
                            ->where('is_deleted', 0)
                            ->orderBy('price')
                            ->take(6)->get();
        }    

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.front_page_products', compact('products'))->render(),
            ];
        }

        return view('pages.summer_tires', compact('products', 'widths', 'profiles', 'inches', 'brands', 'models', 'searchFilter', 'page'));
    }

    public function friktionTires(Request $request)
    {
        // return view('under_construction');
        $value = Cookie::get('carSearch');
        if(isset($value)) {
            return redirect('/sok/reg/friktionsdack');
        }  

        $page = Page::where('name', 'friktionsdack')->first();
        SEOMeta::setTitle($page->meta_title)
            ->setDescription($page->meta_description)
            ->setKeywords($page->meta_keywords)
            ->setCanonical( env('APP_URL') . "/friktionsdack" );

        OpenGraph::setTitle("Friktionsdäck");
        OpenGraph::setDescription("Friktionsdäck är vinterdäck utan dubbar. Så kallade dubbfria vinterdäck.");

        $searchFilter = json_decode(Session::get('searchFilter'), true);

        $widths =  Product::getTireWidths(2);
        $profiles =  isset($searchFilter['dropDownTireProfile']) ? json_decode(json_encode($searchFilter['dropDownTireProfile'])) : Product::getTireProfiles(2);
        $inches = isset($searchFilter['dropDownTireInch']) ? json_decode(json_encode($searchFilter['dropDownTireInch'])) : Product::getTireInches(2);
        $brands = isset($searchFilter['dropDownTireBrand']) ? json_decode(json_encode($searchFilter['dropDownTireBrand'])) : Product::getTireBrands(2);
        $models = isset($searchFilter['dropDownTireModel']) ? json_decode(json_encode($searchFilter['dropDownTireModel'])) : Product::getTireModels(2);

        // $products = Product::where('product_category_id', 1)
        //                     ->where('product_type_id', 2)
        //                     ->where('priority', '>', 0)
        //                     ->where('priority', '<=', 24)
        //                     ->where('is_shown', 1)
        //                     ->where('is_deleted', 0)
        //                     ->orderBy('priority')
        //                     ->get();
        

        $products = Cache::remember('friktionTire_products_cache' . '_page_' . $request->page, 720, function() {
             return $products = Product::where('product_category_id', 1)
                        ->where('product_type_id', 2)
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('price')
                        ->paginate(18);
        }); 

        // $products = Product::where('product_category_id', 1)
        //                 ->where('product_type_id', 2)
        //                 ->where('is_shown', 1)
        //                 ->where('is_deleted', 0)
        //                 ->orderBy('price')
        //                 ->paginate(18);

        if(sizeOf($products) <= 0) {
            $products = Product::where('product_category_id', 1)
                            ->where('product_type_id', 2)
                            ->where('is_shown', 1)
                            ->where('is_deleted', 0)
                            ->orderBy('price')
                            ->take(6)->get();
        }    

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.front_page_products', compact('products'))->render(),
            ];
        }

        return view('pages.friktion_tires', compact('products', 'widths', 'profiles', 'inches', 'brands', 'models', 'searchFilter', 'page'));
    }

    public function studdedTires(Request $request)
    {
        // return view('under_construction');
        $value = Cookie::get('carSearch');
        if(isset($value)) {
            return redirect('/sok/reg/dubbdack');
        }  
        $page = Page::where('name', 'dubbdack')->first();
        SEOMeta::setTitle($page->meta_title)
            ->setDescription($page->meta_description)
            ->setKeywords($page->meta_keywords)
            ->setCanonical( env('APP_URL') . "/dubbdack" );

        OpenGraph::setTitle("Dubbdäck");
        OpenGraph::setDescription("Dubbdäck är vinterdäck med dubbar. Så kallade dubbade däck.");


        $searchFilter = json_decode(Session::get('searchFilter'), true);

        $widths =  Product::getTireWidths(3);
        $profiles =  isset($searchFilter['dropDownTireProfile']) ? json_decode(json_encode($searchFilter['dropDownTireProfile'])) : Product::getTireProfiles(3);
        $inches = isset($searchFilter['dropDownTireInch']) ? json_decode(json_encode($searchFilter['dropDownTireInch'])) : Product::getTireInches(3);
        $brands = isset($searchFilter['dropDownTireBrand']) ? json_decode(json_encode($searchFilter['dropDownTireBrand'])) : Product::getTireBrands(3);
        $models = isset($searchFilter['dropDownTireModel']) ? json_decode(json_encode($searchFilter['dropDownTireModel'])) : Product::getTireModels(3);

        // $products = Product::where('product_category_id', 1)
        //                     ->where('product_type_id', 3)
        //                     ->where('priority', '>', 0)
        //                     ->where('priority', '<=', 24)
        //                     ->where('is_shown', 1)
        //                     ->where('is_deleted', 0)
        //                     ->orderBy('priority')
        //                     ->get();
            

        $products = Cache::remember('studdedTire_products_cache' . '_page_' . $request->page, 720, function() {
             return $products = Product::where('product_category_id', 1)
                        ->where('product_type_id', 3)
                        ->where('is_shown', 1)
                        ->where('is_deleted', 0)
                        ->orderBy('price')
                        ->paginate(18);
        }); 

        // $products = Product::where('product_category_id', 1)
        //                 ->where('product_type_id', 3)
        //                 ->where('is_shown', 1)
        //                 ->where('is_deleted', 0)
        //                 ->orderBy('price')
        //                 ->paginate(18);

        if(sizeOf($products) <= 0) {
            $products = Product::where('product_category_id', 1)
                            ->where('product_type_id', 3)
                            ->where('is_shown', 1)
                            ->where('is_deleted', 0)
                            ->orderBy('price')
                            ->take(6)->get();
        }    

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.front_page_products', compact('products'))->render(),
            ];
        }

        return view('pages.studded_tires', compact('products', 'widths', 'profiles', 'inches', 'brands', 'models', 'searchFilter', 'page'));
    }


    public function showTiresByBrand(Request $request, $brand)
    {

        $product['items'] = Product::where( function($query) use ($brand) {
                                return $query
                                         ->where('product_brand', $brand)
                                         ->orWhere('product_brand', str_replace('-', ' ', $brand));
                            })
                            ->where('product_category_id', 1)
                            ->where('is_shown', 1)
                            ->where('is_deleted', 0)
                            ->orderBy('price')
                            ->paginate(24);
        // dd(str_slug($brand), $brand, $product['items']);
        // dd($brand, $product['items']);

        if(sizeOf($product['items']) <= 0) 
            return redirect(''); 

        SEOMeta::setTitle(
                $product['items']->first()->product_brand. " däck  | Kompletta hjul på 3 sek - Hjulonline.se" 
            )->setDescription(
                "Brett sortiment av ". 
                $product['items']->first()->product_brand ." däck. Hitta passform med hjälp av registreringsnumret. Tar inte mer än 3 sekunder. Leverans inom 24h! @hjulonline "
            )
            ->setCanonical( env('APP_URL') . "/dack/" . str_slug($product['items']->first()->product_brand) );

        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
            ];
        }

        return view('search.tires_by_brand', compact('product'));
    }

    public function showRimsByBrand(Request $request, $brand)
    {

        $product['items'] = Product::where( function($query) use ($brand) {
                                return $query
                                         ->where('product_brand', $brand)
                                         ->orWhere('product_brand', str_replace('-', ' ', $brand));
                            })
                            ->where('product_category_id', 2)
                            ->where('is_shown', 1)
                            ->where('is_deleted', 0)
                            ->orderBy('price')
                            ->paginate(24);
        // dd($brand, $product['items']);

        if(sizeOf($product['items']) <= 0) 
            return redirect(''); 
        
        SEOMeta::setTitle(
                $product['items']->first()->product_brand. " fälgar  | Kompletta hjul på 3 sek - Hjulonline.se" 
            )
        // Handla Image Torino fälgar i storlek 5.5X14 hos hjulonline.se, Finns fler ä 1129 fälgar i lager. Leverans samma dag!
            ->setDescription(
                "Brett sortiment av ". 
                $product['items']->first()->product_brand ." fälgar. Hitta passform med hjälp av registreringsnumret. Tar inte mer än 3 sekunder. Leverans inom 24h! @hjulonline "
            )->setCanonical( env('APP_URL') . "/falg/" . str_slug($product['items']->first()->product_brand) );


        if($request->ajax()) {
            return [
                'searchResult' => view('search.partials.search_result.search_tires', compact('product'))->render(),
            ];
        }

        return view('search.rims_by_brand', compact('product'));
    }

    public function sitemapGenerator()
    {
        $tires = Product::distinct()->where('product_category_id', 1)->get(['product_brand']);
        $rims = Product::distinct()->where('product_category_id', 2)->get(['product_brand']);

        return response()->view('pages.sitemap_generator', compact('tires', 'rims'))->header('Content-Type', 'text/xml');
    }

    public function productFeedTiresGenerator()
    {
        // $products = Product::where('is_shown', 1)
        //                     ->where('is_deleted', 0)
        //                     ->where('product_category_id', 1)
        //                     // ->where('id', '!=', 4)
        //                     ->limit(1000)
        //                     ->get();

        // return response()->view('pages.product_feed_tires', compact('products'))->header('Content-Type', 'text/xml');

        $products = DB::select( DB::raw("
                        SELECT 
                            p.product_name AS 'ProductName', 
                            CONCAT(p.main_supplier_id, '-', p.main_supplier_product_id ) AS 'ArticleNr',
                            pt.label AS 'Category',
                            p.calculated_price AS 'Price (kr)',
                            CONCAT_WS('/', 'https://www.hjulonline.se', pt.name, p.product_brand, p.product_model, REPLACE(p.product_dimension, '/', '-'), p.id ) AS 'ProductUrl',
                            CONCAT('Märke: ', p.product_brand, 
                                   ', Modell: ', p.product_model, 
                                   ', Dimensioner: ', p.product_dimension, 
                                   ', Belastningsindex: ',p.load_index, 
                                   ', Hastighetsindex : ',p.speed_index ) AS 'ProductDescription',
                                   
                            CONCAT('https://www.hjulonline.se/', pi.path) AS 'ImageUrl',
                            
                            (CASE 
                                WHEN p.quantity < '21' THEN p.quantity
                                ELSE '+20'
                             END) AS 'Quantity',
                            
                            100 AS 'ShippingCost (kr)',
                            p.product_brand AS 'Brand',
                            p.product_model AS 'Model'    
                            
                        FROM `products` AS p
                        LEFT JOIN product_type AS pt ON pt.id = p.product_type_id
                        LEFT JOIN product_images AS pi ON pi.product_id = p.id
                        WHERE
                            p.product_category_id = 1 AND
                            p.is_shown = 1 AND
                            p.is_deleted = 0 AND
                            pi.priority = 1"
            )
        );

        // dd($products);
        return response()->json($products);
    }

    public function productFeedRimsGenerator()
    {
        // $products = Product::where('is_shown', 1)
        //                     ->where('is_deleted', 0)
        //                     ->where('product_category_id', 2)
        //                     // ->where('id', '!=', 4)
        //                     ->limit(2000)
        //                     ->get();

        // return response()->view('pages.product_feed_rims', compact('products'))->header('Content-Type', 'text/xml');

        $products = DB::select( DB::raw("
                        SELECT 
                            p.product_name AS 'ProductName', 
                            CONCAT(p.main_supplier_id, '-', p.main_supplier_product_id ) AS 'ArticleNr',
                            pt.label AS 'Category',
                            p.calculated_price AS 'Price (kr)',
                            CONCAT_WS('/', 'https://www.hjulonline.se', pt.name, p.product_brand, p.product_model, REPLACE(p.product_dimension, '/', '-'), p.id, 'pcd' ) AS 'ProductUrl',
                            CONCAT('Märke: ', p.product_brand, 
                                   ', Modell: ', p.product_model, 
                                   ', Dimensioner: ', p.product_dimension, 
                                   ', Bultmönster: ', CONCAT(p.pcd1, 
                                                             IF(p.pcd2, CONCAT(', ', p.pcd2), ''), 
                                                             IF(p.pcd3, CONCAT(', ', p.pcd3), ''),
                                                             IF(p.pcd4, CONCAT(', ', p.pcd4), ''), 
                                                             IF(p.pcd5, CONCAT(', ', p.pcd5), ''), 
                                                             IF(p.pcd6, CONCAT(', ', p.pcd6), ''), 
                                                             IF(p.pcd7, CONCAT(', ', p.pcd7), ''), 
                                                             IF(p.pcd8, CONCAT(', ', p.pcd8), ''), 
                                                             IF(p.pcd9, CONCAT(', ', p.pcd9), ''), 
                                                             IF(p.pcd10, CONCAT(', ', p.pcd10), ''), 
                                                             IF(p.pcd11, CONCAT(', ', p.pcd11), ''), 
                                                             IF(p.pcd12, CONCAT(', ', p.pcd12), ''),
                                                             IF(p.pcd13, CONCAT(', ', p.pcd13), ''),
                                                             IF(p.pcd14, CONCAT(', ', p.pcd14), ''),
                                                             IF(p.pcd15, CONCAT(', ', p.pcd15), '')
                                                            ),
                                   ', Inpressning: ', p.et,
                                   ', Navhål: ', p.bore_max) AS 'ProductDescription',
                                   
                            CONCAT('https://www.hjulonline.se/', pi.path) AS 'ImageUrl',
                            
                            (CASE 
                                WHEN p.quantity < '21' THEN p.quantity
                                ELSE '+20'
                             END) AS 'Quantity',
                            
                            100 AS 'ShippingCost (kr)',
                            p.product_brand AS 'Brand',
                            p.product_model AS 'Model'    
                            
                        FROM `products` AS p
                        LEFT JOIN product_type AS pt ON pt.id = p.product_type_id
                        LEFT JOIN product_images AS pi ON pi.product_id = p.id
                        WHERE
                            p.product_category_id = 2 AND
                            p.is_shown = 1 AND
                            p.is_deleted = 0 AND
                            pi.priority = 1"
            )
        );

        // dd($products);
        return response()->json($products);
    }

    public function cmsPage($slug)
    {

        $page = Page::where('name', $slug)->where('is_active', 1)->where('is_removable', 1)->first();
        if(sizeOf($page) <= 0)
            abort(404);

        SEOMeta::setTitle($page->meta_title)
            ->setDescription($page->meta_description)
            ->setKeywords($page->meta_keywords);
            // ->addKeyword($keyword)
            // ->addMeta($meta, $value);

        $blogSetting = Setting::find(1);

        // dd($page->id, $blogSetting->value);

        if($page->id == $blogSetting->value) {
            // dd($page->id, $blogSetting->value);
            $posts = Page::where('is_post', 1)->where('is_active', 1)->orderBy('updated_at', 'DESC')->paginate(5);
            return view('pages.cms_blog', compact('page', 'posts'));
        }

        return view('pages.cms_page', compact('page'));
    }


    public function showSimpleProduct($id = null)
    {

        $product = Product::where('id', $id)->get(); 
        if(sizeOf($product)<= 0)
            return redirect('');

        $qty = 4;
        
        SEOMeta::setTitle(
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " | " . 
                $product->first()->product_dimension. " " . 
                'Fler än '. $product->first()->id. " - hjulonline.se"
            )
        // Handla Image Torino fälgar i storlek 5.5X14 hos hjulonline.se, Finns fler ä 1129 fälgar i lager. Leverans samma dag!
            ->setDescription(
                "Handla ".
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " i storlek " . 
                $product->first()->product_dimension. " hos hjulonline.se, Finns fler än " . 
                $product->first()->id." ". $product->first()->productType->label ." i lager. Leverans samma dag!"
            );


        // dd($product, $id);

        $product->load(['productImages' => function ($query) {
            // $query->where('priority', 1);
            $query->orderBy('priority');
        }]);    

        return view('pages.product_details', compact('product', 'qty'));
    }


    public function showProduct($brand, $model, $dimension, $id = null)
    {
        $id = $id == 'pcd' || $id == null ? $dimension : $id;
        // dd($brand, $model, $dimension, $id);
        $product = Product::where('id', $id)->get(); 
        if(sizeOf($product)<= 0)
            return redirect('');

        $qty = 4;
         
        SEOMeta::setTitle(
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " | " . 
                $product->first()->product_dimension. " " . 
                'Fler än '. $product->first()->id. " - hjulonline.se"
            )
        // Handla Image Torino fälgar i storlek 5.5X14 hos hjulonline.se, Finns fler ä 1129 fälgar i lager. Leverans samma dag!
            ->setDescription(
                "Handla ".
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " i storlek " . 
                $product->first()->product_dimension. " hos hjulonline.se, Finns fler än " . 
                $product->first()->id." ". $product->first()->productType->label ." i lager. Leverans samma dag!"
            );


        // dd($product, $id);

        $product->load(['productImages' => function ($query) {
            // $query->where('priority', 1);
            $query->orderBy('priority');
        }]);    

        return view('pages.product_details', compact('product', 'qty'));
    }

    public function showProductWithPCD($brand, $model, $dimension, $id = null)
    {
        // $id = $id == 'pcd' || $id == null ? $dimension : $id;
        // dd($brand, $model, $dimension, $id);
        $product = Product::where('id', $id)->get(); 
        if(sizeOf($product)<= 0)
            return redirect('');

        $qty = 4;

        SEOMeta::setTitle(
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " | " . 
                $product->first()->product_dimension. " " . 
                'Fler än '. $product->first()->id. " - hjulonline.se"
            )
        // Handla Image Torino fälgar i storlek 5.5X14 hos hjulonline.se, Finns fler ä 1129 fälgar i lager. Leverans samma dag!
            ->setDescription(
                "Handla ".
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " i storlek " . 
                $product->first()->product_dimension. " hos hjulonline.se, Finns fler än " . 
                $product->first()->id." ". $product->first()->productType->label ." i lager. Leverans samma dag!"
            );

        $product->load(['productImages' => function ($query) {
            // $query->where('priority', 1);
            $query->orderBy('priority');
        }]);    

        $rim_dimension_search = true;

        return view('pages.product_details', compact('product', 'qty', 'rim_dimension_search'));
    }

    public function showProductWithPCD2($brand, $dimension, $id = null)
    {
        $id = $id == 'pcd' || $id == null ? $dimension : $id;
        // dd($brand, $model, $dimension, $id);
        $product = Product::where('id', $id)->get(); 
        if(sizeOf($product)<= 0)
            return redirect('');

        $qty = 4;

        SEOMeta::setTitle(
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " | " . 
                $product->first()->product_dimension. " " . 
                'Fler än '. $product->first()->id. " - hjulonline.se"
            )
        // Handla Image Torino fälgar i storlek 5.5X14 hos hjulonline.se, Finns fler ä 1129 fälgar i lager. Leverans samma dag!
            ->setDescription(
                "Handla ".
                $product->first()->product_brand. " ". 
                $product->first()->product_model. " ". 
                $product->first()->productType->label. " i storlek " . 
                $product->first()->product_dimension. " hos hjulonline.se, Finns fler än " . 
                $product->first()->id." ". $product->first()->productType->label ." i lager. Leverans samma dag!"
            );

        $product->load(['productImages' => function ($query) {
            // $query->where('priority', 1);
            $query->orderBy('priority');
        }]);    

        $rim_dimension_search = true;

        return view('pages.product_details', compact('product', 'qty', 'rim_dimension_search'));
    }

    public function bookAppointment()
    {
        return view('pages.book_appointment');
    }

    public function bookAppointmentYes()
    {
        return view('pages.book_appointment_yes');
    }

    public function rimFix()
    {
        return view('pages.rim_fix');
    }

    public function accessories()
    {
        $tpms = Product::where('product_type_id', 7)
                             ->where('is_shown', 1)
                             ->where('is_deleted', 0)
                             ->first();
        if(sizeOf($tpms) > 0)
            $products['tpms'] = $tpms;

        $nuts = Product::where('product_type_id', 8)
                             ->where('is_shown', 1)
                             ->where('is_deleted', 0)
                             ->first();
        if(sizeOf($nuts) > 0)
            $products['nuts'] = $nuts;

        $bolts = Product::where('product_type_id', 9)
                             ->where('is_shown', 1)
                             ->where('is_deleted', 0)
                             ->first();
        if(sizeOf($bolts) > 0)
            $products['bolts'] = $bolts;

        $rings = Product::where('product_type_id', 10)
                             ->where('is_shown', 1)
                             ->where('is_deleted', 0)
                             ->first();
        if(sizeOf($rings) > 0)
            $products['rings'] = $rings;

         $monteringskit = Product::where('product_type_id', 11)
                             ->where('is_shown', 1)
                             ->where('is_deleted', 0)
                             ->first();
        if(sizeOf($monteringskit) > 0)
            $products['monteringskit'] = $monteringskit;

        $wheelBolt = Product::where('product_type_id', 12)
                             ->where('is_shown', 1)
                             ->where('is_deleted', 0)
                             ->first();
        if(sizeOf($wheelBolt) > 0)
            $products['wheelBolt'] = $wheelBolt;

        $spacer = Product::where('product_type_id', 13)
                             ->where('is_shown', 1)
                             ->where('is_deleted', 0)
                             ->first();
        if(sizeOf($spacer) > 0)
            $products['spacer'] = $spacer;

        // $services = Product::where('product_type_id', 14)
        //                      ->where('is_shown', 1)
        //                      ->where('is_deleted', 0)
        //                      ->first();
        // if(sizeOf($services) > 0)
        //     $products['services'] = $services;

        // $other = Product::where('product_type_id', 18)
        //                      ->where('is_shown', 1)
        //                      ->where('is_deleted', 0)
        //                      ->first();
        // if(sizeOf($other) > 0)
        //     $products['other'] = $other;


        $page = Page::where('name', 'tillbehor')->first();
        SEOMeta::setTitle($page->meta_title)
            ->setDescription($page->meta_description)
            ->setKeywords($page->meta_keywords);

        return view('pages.accessories', compact('products', 'page'));
    }

    public function ourServices()
    {
        return view('pages.our_services');
    }

    public function showTPMS()
    {
        $product = Product::where('product_type_id', 7)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showNuts()
    {
        $product = Product::where('product_type_id', 8)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
    
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }


    public function showBolts()
    {
        $product = Product::where('product_type_id', 9)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showRings()
    {
        $product = Product::where('product_type_id', 10)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->orderBy(DB::raw('cast(product_dimension as unsigned)'), 'ASC')
                    ->orderBy('product_dimension', 'DESC')
                    ->first();
        
        $qty = 4;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showRingDetails($id)
    {
        $product = Product::find($id);
        
        $qty = 4;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showMonteringskit()
    {
        $product = Product::where('product_type_id', 11)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
        
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showLockBolts()
    {
        $product = Product::where('product_type_id', 12)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
        
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showSpacers()
    {
        $product = Product::where('product_type_id', 13)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
        
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showServices()
    {
        $product = Product::where('product_type_id', 14)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
        
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function showOther()
    {
        $product = Product::where('product_type_id', 18)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->first();
        
        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function filterOuterDimension(Request $request)
    {

        $product = Product::distinct('product_inner_dimension')->where('product_type_id', 10)
                    ->where('product_dimension', $request->outerD)
                    ->where('is_shown', 1)
                    ->where('is_deleted', 0)
                    ->get();
        // dd($request->outerD, $product);
        $html = "";
        foreach ($product as $innerD){
            $html .= "<option value='{$innerD->product_inner_dimension}'>{$innerD->product_inner_dimension}</option>";
        }

        return [
            'innerDimensions' => $html
        ];
    }

    public function filterNutDimension($id)
    {

        $product = Product::find($id);
        // dd($product, $id);

        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function filterBoltDimension($id)
    {
        $product = Product::find($id);
        // dd($product, $id);

        $qty = 1;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    // public function filterRingOuterDimension($outerID, $innerID)
    // {
    //     $product = Product::where('product_type_id', 10)
    //                 ->where('product_dimension', $outerID)
    //                 ->where('is_shown', 1)
    //                 ->first();

    //     $qty = 4;
    //     return view('pages.accessory_details', compact('product', 'qty'));
    // }

    public function filterRingDimension($outerID, $innerID)
    {
        if($innerID == '-') {
            $product = Product::where('product_type_id', 10)
                    ->where('product_dimension', $outerID)
                    ->where('is_shown', 1)
                    ->first();
        } else {
            $product = Product::where('product_type_id', 10)
                    ->where('product_dimension', $outerID)
                    ->where('product_inner_dimension', $innerID)
                    ->where('is_shown', 1)
                    ->first();
        }
        

        $qty = 4;
        return view('pages.accessory_details', compact('product', 'qty'));
    }

    public function contact()
    {
        $page = Page::where('name', 'kontakt')->first();
        return view('pages.contact', compact('page'));
    }

    // public function faq()
    // {
    //     return view('pages.faq');
    // }

    // public function paymentTerms()
    // {
    //     return view('pages.payment_terms');
    // }

    // public function deliveryTerms()
    // {
    //     return view('pages.delivery_terms');
    // }

    // public function terms()
    // {
    //     return view('pages.terms');
    // }

    // public function partners()
    // {
    //     return view('pages.partners');
    // }

    // public function about()
    // {
    //     return view('pages.about');
    // }

    function get_ip_address() {
        // check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        // check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // check if multiple ips exist in var
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if (validate_ip($ip))
                        return $ip;
                }
            } else {
                if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];

        // return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * a private network range.
     */
    function validate_ip($ip) {
        if (strtolower($ip) === 'unknown')
            return false;

        // generate ipv4 network address
        $ip = ip2long($ip);

        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647) return false;
            if ($ip >= 167772160 && $ip <= 184549375) return false;
            if ($ip >= 2130706432 && $ip <= 2147483647) return false;
            if ($ip >= 2851995648 && $ip <= 2852061183) return false;
            if ($ip >= 2886729728 && $ip <= 2887778303) return false;
            if ($ip >= 3221225984 && $ip <= 3221226239) return false;
            if ($ip >= 3232235520 && $ip <= 3232301055) return false;
            if ($ip >= 4294967040) return false;
        }
        return true;
    }

}
