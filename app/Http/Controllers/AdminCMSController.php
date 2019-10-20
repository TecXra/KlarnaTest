<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Page;
use App\Setting;
use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

class AdminCMSController extends Controller
{
	public function sortMenu(Request $request)
	{
		// dd($request->all());
		
		$menu = Menu::find($request->menuId);
		if($request->firstMenu) {
			foreach ($request->firstMenu as $key => $pageId) {
				$menu->pages()->detach($pageId);
				// $saveMenu = Menu::find(1);
				// $saveMenu->pages()->attach($pageId, ['priority' => $key] );
				// $menu->pages()->updateExistingPivot($pageId, ['priority' => $key, 'menu_id' => 1]);
			}
		} 
		
		Menu::find($request->menuId)->pages()->detach();
		if($request->secondMenu) {	
			foreach ($request->secondMenu as $key => $pageId) {
				// $menu->pages()->detach($pageId);
				$saveMenu = Menu::find($request->menuId);
				$saveMenu->pages()->attach($pageId, ['priority' => $key] );
			}
		} 

		return [
			'firstMenu' => $request->firstMenu,
			'secondMenu' => $request->secondMenu,
			'menuId' => $request->menuId
		];
	}

	public function menuList(Request $request)
	{
		// dd($request->all());
		$menus = Menu::all();
		$menuId = isset($request->menuId) ? $request->menuId : 1;
        
        $pages = Page::where('is_post', 0)
	        	->whereNotIn('id', function($query) use ($menuId) {
	        		$query->select('page_id')
	        			->from('menu_page')
	        			->where('menu_id', $menuId);
	        	})
	        	->get();

		$menuPages = Menu::find($menuId)->pages()->orderBy('menu_page.priority')->get();


		if($request->ajax()) { 
			return [ 'status' => true ];
		}

		return view('admin.cms.menus', compact('menus', 'pages', 'menuPages', 'menuId'));
	}

    public function pageList(Request $request)
    {
    	// $priorityList = Page::select('priority')->distinct()->get();


    	if($request->ajax()) { 
	    	
	    	if($request->action) {
	    		$page = Page::findOrFail($request->id);
				if($request->action == "activate") {
					$page->is_active = 0;
					$page->save();
				}

				if($request->action == "unActivate") {
					$page->is_active = 1;
					$page->save();
				}

				if($request->action == "delete") {
					foreach ($page->sliders as $slider) {
						$slider->page_id = 0;
					}

					$page->menus()->detach();
					$page->delete();
				}
			}

	    	$pages = Page::where('is_post', 0)->orderBy('id', 'DESC')
    				->paginate(15);
			return [
				'table' => view('admin/cms/partials/table/pages_table', compact('pages'))->render()
			];
		}


    	$pages = Page::where('is_post', 0)->orderBy('id', 'desc')->paginate(15);
    	return view('admin.cms.pages', compact('pages'));
    }

    public function postList(Request $request)
    {
    	if($request->ajax()) { 
	    	
	    	if($request->action) {
	    		$post = Page::findOrFail($request->id);
				if($request->action == "activate") {
					$post->is_active = 0;
					$post->save();
				}

				if($request->action == "unActivate") {
					$post->is_active = 1;
					$post->save();
				}

				if($request->action == "delete") {
					$post->delete();
				}
			}

	    	$posts = Page::where('is_post', 1)->orderBy('label', 'ASC')
    				->paginate(15);

			return [
				'table' => view('admin/cms/partials/table/posts_table', compact('posts'))->render()
			];
		}


    	$posts = Page::where('is_post', 1)->orderBy('label', 'ASC')->paginate(15);
    	return view('admin.cms.posts', compact('posts'));
    }

    public function sliderList(Request $request)
    {
    	// dd($request->all());
    	$ddPages = Page::where('name', '/')
    				->orWhere('name', 'falgar')
    				->orWhere('name', 'sommardack')
    				->orWhere('name', 'friktionsdack')
    				->orWhere('name', 'dubbdack')
    				->get();
    	
    	$page = isset($request->pageId) && !empty($request->pageId) ? Page::find($request->pageId) : Page::first();
    	$sliders = $page->sliders()->orderBy('priority')->paginate(15);


    	if($request->ajax()) { 
	    	
	    	if($request->action) {
	    		$slider = Slider::findOrFail($request->id);
				if($request->action == "activate") {
					$slider->is_active = 0;
					$slider->save();
				}

				if($request->action == "unActivate") {
					$slider->is_active = 1;
					$slider->save();
				}

				if($request->action == "delete") {
					$slider->delete();
				}
			}

	    	$sliders = $page->sliders()->orderBy('priority')->paginate(15);

			return [
				'table' => view('admin/cms/partials/table/sliders_table', compact('sliders', 'page', 'ddPages'))->render()
			];
		}

		return view('admin.cms.sliders', compact('sliders', 'page', 'pages', 'posts', 'ddPages'));
	}

	public function settingList(Request $request, $settings_type)
    {

    	$settings = Setting::where('settings_type', $settings_type)->paginate(15);
    	$DDBlogPage = Page::where('is_post', 0)->where('is_removable', 1)->get();

    	return view('admin.cms.settings', compact('settings', 'DDBlogPage', ''));
    }

    public function sortSlider(Request $request)
    {
    	// dd($request->sliderList[0]);
    	if($request->sliderList) {	
			foreach ($request->sliderList as $key => $sliderId) {
				// $menu->pages()->detach($pageId);
				$updateSlider = Slider::find($sliderId);
				$updateSlider->priority = $key;
				$updateSlider->save();
			}
		} 
    }

    public function storePage(Request $request)
    {
    	$existPage = Page::where('name', $request->InputPageName)->get();
    	if(sizeOf($existPage) > 0) {
    		return [
    			'message' => 'URLen är tagen. Var snäll och ange en unik URL',
    			'status' => false,
    		];
    	}

    	// dd($request->all());
    	$name = str_replace([' ', 'å', 'ä', 'ö'], ['', 'a', 'a', 'o'], $request->InputPageName);
    	$createPage = new Page;
    	$createPage->name = strtolower($name);
    	$createPage->label = $request->InputPageLabel;
    	$createPage->meta_title = $request->InputMetaTitle;
    	$createPage->meta_description = $request->InputMetaDescription;
    	$createPage->meta_keywords = $request->InputMetaKeywords;
    	$createPage->content = $request->content;
    	// $createPage->priority = $request->DDPriority;
		$createPage->is_post = 0;
		$createPage->is_removable = 1;
		$createPage->is_active = 1;
		$createPage->save();

		// $menu = Menu::find(1);
		// $menu->pages()->attach($createPage);

		session()->flash('message', 'En ny sida har skapats');
		return [
			'status' => true
		];
        // return redirect('admin/anvandare');
    }

    public function showUpdatePageModal(Request $request)
    {
        $page = Page::find($request->id);
    	// $brands = Product::distinct()->select('product_brand')
    	// 				->where('product_category_id', 1)
	    // 				->where('is_deleted', 0)
	    // 				->orderBy('product_brand')
    	// 			 	->get();

    	return [
            // 'updateUserModal' => view('admin/partials/form/update_user_modal')->render(),
            'page' => $page
    		// 'brands' => $brands,
    	];
    }

    public function updatePage(Request $request)
    {
    	$existPage = Page::where('id', '<>', $request->pageId)->where('name', $request->EditInputPageName)->get();
    	if(sizeOf($existPage) > 0) {
    		return [
    			'message' => 'URLen är tagen. Var snäll och ange en unik URL',
    			'status' => false,
    		];
    	}
    	// dd($request->all());

    	$name = str_replace([' ', 'å', 'ä', 'ö'], ['', 'a', 'a', 'o'], $request->EditInputPageName);
    	$updatePage = Page::findOrFail($request->pageId);
    	$updatePage->name = $request->pageId != 1 ? strtolower($name) : '/';
    	$updatePage->label = $request->EditInputPageLabel;
    	$updatePage->meta_title = $request->EditInputMetaTitle;
    	$updatePage->meta_description = $request->EditInputMetaDescription;
    	$updatePage->meta_keywords = $request->EditInputMetaKeywords;
    	$updatePage->content = $request->EditContent;
		$updatePage->save();

		// session()->flash('message', 'Sidan har uppdaterats');
		// $userTypes = UserType::all();
		$pages = Page::where('is_post', 0)
				->orderBy('id', 'DESC')
				->paginate(15);

		return [
			'table' => view('admin/cms/partials/table/pages_table', compact('pages'))->render(),
			'message' => 'Sidan har uppdaterats.',
			'status' => true,
		];
    }

    public function storePost(Request $request)
    {
    	$existPost = Page::where('name', $request->InputPostName)->get();
    	if(sizeOf($existPost) > 0) {
    		return [
    			'message' => 'URLen är tagen. Var snäll och ange en unik URL',
    			'status' => false,
    		];
    	}
    	// dd($request->all());

		$name = str_replace([' ', 'å', 'ä', 'ö'], ['', 'a', 'a', 'o'], $request->InputPostName);
    	$createPost = new Page;
    	$createPost->name = strtolower($name);
    	$createPost->label = $request->InputPostLabel;
    	$createPost->author = $request->InputAuthor;
    	$createPost->meta_title = $request->InputMetaTitle;
    	$createPost->meta_description = $request->InputMetaDescription;
    	$createPost->meta_keywords = $request->InputMetaKeywords;
    	$createPost->content = $request->content;
    	// $createPost->priority = $request->DDPriority;
		$createPost->is_post = 1;
		$createPost->is_removable = 1;
		$createPost->is_active = 1;
		$createPost->save();

		session()->flash('message', 'Ett nytt inlägg har skapats');
		return [
			'status' => true
		];

        // return redirect('admin/anvandare');
    }

    public function showUpdatePostModal(Request $request)
    {
        $post = Page::find($request->id);
    	// $brands = Product::distinct()->select('product_brand')
    	// 				->where('product_category_id', 1)
	    // 				->where('is_deleted', 0)
	    // 				->orderBy('product_brand')
    	// 			 	->get();

    	return [
            // 'updateUserModal' => view('admin/partials/form/update_user_modal')->render(),
            'post' => $post
    		// 'brands' => $brands,
    	];
    }

    public function updatePost(Request $request)
    {
    	$existPost = Page::where('id', '<>', $request->postId)->where('name', $request->EditInputPostName)->get();
    	if(sizeOf($existPost) > 0) {
    		return [
    			'message' => 'URLen är tagen. Var snäll och ange en unik URL',
    			'status' => false,
    		];
    	}

    	$name = str_replace([' ', 'å', 'ä', 'ö'], ['', 'a', 'a', 'o'], $request->EditInputPostName);
    	$updatePost = Page::findOrFail($request->postId);
    	$updatePost->name =  strtolower($name);
    	$updatePost->label = $request->EditInputPostLabel;
    	$updatePost->author = $request->EditInputAuthor;
    	$updatePost->meta_title = $request->EditInputMetaTitle;
    	$updatePost->meta_description = $request->EditInputMetaDescription;
    	$updatePost->meta_keywords = $request->EditInputMetaKeywords;
    	$updatePost->content = $request->EditContent;
		$updatePost->save();

		$posts = Page::where('is_post', 1)->orderBy('label', 'ASC')
    				->paginate(15);

		return [
			'table' => view('admin/cms/partials/table/posts_table', compact('posts'))->render(),
			'message' => 'Sidan har uppdaterats.',
			'status' => true,
		];
    }


     public function storeSlider(Request $request)
    {
    	parse_str($request->formData);
    	// dd($request->all(), $request->formData, $InputSliderTitle ,$request->file('file'));

    	

    	$files = $request->file('file');

        if($files) {
            $countSliders = Slider::where('page_id', $pageId)->count();
            $allowedCount = 1;

            // foreach ($files as $key => $file) {
            $file = $files[0];
                // if($allowedCount <= $key) {
                //     break;
                // }

                $fileName = $file->getClientOriginalName();
                $path = 'images/slider/cms/'. $fileName;
                $absolute_path = public_path($path);

                if (!is_dir('images/slider/cms/')) {
                    // dir doesn't exist, make it
                    mkdir('images/slider/cms/');
                }


                // $existImg = ProductImage::where('name', $fileName)->get();
                // if(sizeof($existImg) === 0) {
                //     $file->move('images/product/local/', $fileName);                
                // }
                $existImg = File::exists($path); 
                if(!$existImg) {
                    $file->move('images/slider/cms/', $fileName);
                    // $existImg = file_put_contents('images/product/local/', $file);
                    $existImg = true;
                }

                // Image::make($path)->resize(600, 600)->save();
                // Image::make($path)->resize(170, 170)->save($thumbnail_path);
                
                if($existImg) {
                    $createSlider = new Slider;
			    	$createSlider->page_id = $pageId;
			    	$createSlider->title = $InputSliderTitle;
			    	$createSlider->img_name = $fileName;
			    	$createSlider->path = $path;
			    	$createSlider->priority = $countSliders;
					$createSlider->is_active = 1;
					$createSlider->save();
                }
            // }
        } else {
           session()->flash('message', 'Error: Gick inte att ladda upp bilden.');
        }

        session()->flash('message', 'En ny slider har skapats.');
        
        return;

    }
 

    public function showUpdateSliderModal(Request $request)
    {
        $slider = Slider::find($request->id);
    	// $brands = Product::distinct()->select('product_brand')
    	// 				->where('product_category_id', 1)
	    // 				->where('is_deleted', 0)
	    // 				->orderBy('product_brand')
    	// 			 	->get();

    	return [
            // 'updateUserModal' => view('admin/partials/form/update_user_modal')->render(),
            'slider' => $slider,
            // 'pages' => $pages
    		// 'brands' => $brands,
    	];
    }

    public function updateSlider(Request $request)
    {
    	parse_str($request->formData);
    	$updateSlider = Slider::findOrFail($EditSliderId);
		$updateSlider->title = $EditInputSliderTitle;
    	$files = $request->file('file');
    	// dd($request->all(), $updateSlider, $files );

        if($files) {
            $countSliders = Slider::all()->count();
            $allowedCount = 1;

            // foreach ($files as $key => $file) {
            $file = $files[0];
                // if($allowedCount <= $key) {
                //     break;
                // }

                $fileName = $file->getClientOriginalName();
                $path = 'images/slider/cms/'. $fileName;
                $absolute_path = public_path($path);

                if (!is_dir('images/slider/cms/')) {
                    // dir doesn't exist, make it
                    mkdir('images/slider/cms/');
                }


                // $existImg = ProductImage::where('name', $fileName)->get();
                // if(sizeof($existImg) === 0) {
                //     $file->move('images/product/local/', $fileName);                
                // }
                $existImg = File::exists($path); 
                if(!$existImg) {
                    $file->move('images/slider/cms/', $fileName);
                    // $existImg = file_put_contents('images/product/local/', $file);
                    $existImg = true;
                }

                // Image::make($path)->resize(600, 600)->save();
                // Image::make($path)->resize(170, 170)->save($thumbnail_path);
                
                if($existImg) {
                	
			    	$updateSlider->img_name = $fileName;
			    	$updateSlider->path = $path;
			    	$updateSlider->is_active = 1;
                }
            // }
        }
        $updateSlider->save();
        
		// session()->flash('message', 'Sidan har uppdaterats');
		// $userTypes = UserType::all();
		$page = isset($EditPageId) ? Page::find($EditPageId) : Page::first();
    	$sliders = $page->sliders()->orderBy('priority')->paginate(15);

		return [
			'table' => view('admin/cms/partials/table/sliders_table', compact('sliders'))->render(),
			'message' => 'Slidern har uppdaterats.',
		];
    }

    public function destroySliderImage($id)
    {
    	// dd($id);
    	//delete from database
        $slider = Slider::findOrFail($id);

        $existImg = Slider::where('img_name', $slider->img_name)->get(); 
        if(sizeOf($existImg) === 1) {
            \File::delete([
                $slider->path
            ]);
        }

        $slider->img_name = '';
        $slider->path = '';
        $slider->is_active = 0;
        $slider->save();


        return Response::json();
    }

    public function updateSettings(Request $request)
    {
        // dd($request->all());

        foreach ($request->all() as $settingName => $settingValue) {
            
            if($settingName == '_token' || $settingName == 'updateSettings')
                continue;
            
            $updateSetting = Setting::where('name', $settingName)->first();
            $updateSetting->value = $settingValue;
            $updateSetting->save();
        }


    	session()->flash('message', 'Inställningarna har uppdaterats' );

    	return redirect()->back();
    	
    }
}
