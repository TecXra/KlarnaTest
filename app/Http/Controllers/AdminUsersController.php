<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Mail\SendUserPassword;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class AdminUsersController extends Controller
{
    protected $userType;
    protected $userSearch;

    public function users(Request $request)
    {
    	$userTypes = UserType::all();
        $users = $this->getSavedUserList();
        $savedType = $this->userType;
        $savedSearch = $this->userSearch;
        // dd($this->userType, $this->userSearch, $users);

    	if($request->ajax()) { 
	    	
	    	if($request->action) {
	    		$user = User::findOrFail($request->id);
				if($request->action == "activate")
					$user->is_active = 0;

				if($request->action == "unActivate")
					$user->is_active = 1;

				$user->save();
			}

	    	
			return [
				'table' => view('admin/partials/table/user_table', compact('users', 'userTypes'))->render()
			];
		}

		
    	// $users = User::orderBy('id', 'DESC')->paginate(15);

    	return view('admin.users', compact('users', 'userTypes', 'savedType', 'savedSearch'));
    }

    public function getSavedUserList()
    {
        $this->getSearchCookie();
        $this->filterUsers = User::where('id', '<>', 0);
        isset($this->userType) ? $this->filterByUserType() : '';
        isset($this->userSearch) ? $this->filterBySearch() : '';
        return $this->filterUsers->orderBy('created_at', 'DESC')->paginate(15);
    }

    public function storeUser(Request $request)
    {
    	// dd($request->all());

    	$user = User::where('email', $request->InputEmail)->first();

    	if(sizeOf($user) > 0) {
        	return [
        		"status" => false,
        		'error' => 'Det finns redan en användare med den angivna E-post adressen.'
        	];
    	}

    	$password = str_random(8);

    	$createUser = new User;
    	$createUser->user_type_id = $request->DDUserType ? $request->DDUserType : 1;
    	$createUser->first_name = $request->InputFirstName;
        $createUser->last_name = $request->InputLastName;
    	$createUser->company_name = isset($request->InputCompanyName) ? $request->InputCompanyName : null;
    	$createUser->email = $request->InputEmail;
    	$createUser->password = bcrypt($password);
        $createUser->date_of_birth = $request->InputSSN;
    	$createUser->org_number = $request->InputSSN;
    	$createUser->billing_phone = $request->InputPhone;
    	$createUser->billing_full_name = $request->InputFirstName. " " .$request->InputLastName;
    	$createUser->billing_street_address = $request->InputBillingStreet;
    	$createUser->billing_postal_code = $request->InputBillingPostalCode;
    	$createUser->billing_city = $request->InputBillingCity;
    	$createUser->billing_country = $request->InputBillingCountry;
		
		$createUser->shipping_full_name = $request->InputFirstName. " " .$request->InputLastName;

    	if(isset($request->isShippingAddress)) {
	    	$createUser->shipping_street_address = $request->InputShippingStreet;
	    	$createUser->shipping_postal_code = $request->InputShippingPostalCode;
	    	$createUser->shipping_city = $request->InputShippingCity;
	    	$createUser->shipping_country = $request->InputShippingCountry;
    	} else {
    		$createUser->shipping_street_address = $request->InputBillingStreet;
	    	$createUser->shipping_postal_code = $request->InputBillingPostalCode;
	    	$createUser->shipping_city = $request->InputBillingCity;
	    	$createUser->shipping_country = $request->InputBillingCountry;
    	}
		
		$createUser->is_company = $request->isCompany;
		$createUser->is_active = 1;
		$createUser->save();

		// Skicka en bekräftelse mail till användare med lösenord och andra uppgifter.
		Mail::to($createUser->email)->send(new SendUserPassword($createUser, $password));


		session()->flash('message', 'En ny användare har skapats');
		return [
			'status' => true
		];
        // return redirect('admin/anvandare');
    }

    public function showUpdateUserModal(Request $request)
    {
        $user = User::find($request->id);
        $userTypes = UserType::all();

    	// $brands = Product::distinct()->select('product_brand')
    	// 				->where('product_category_id', 1)
	    // 				
	    // 				->orderBy('product_brand')
    	// 			 	->get();

    	return [
            // 'updateUserModal' => view('admin/partials/form/update_user_modal')->render(),
            'user' => $user,
            'userTypes' => $userTypes,
    		// 'brands' => $brands,
    	];
    }

    public function updateUser(Request $request)
    {
    	// dd($request->all());

    	$user = User::findOrFail($request->userId);
    	if($user->email != $request->EditInputEmail) {

    		$user = User::where('email', $request->EditInputEmail);
	    	if(sizeOf($user) > 0) {
	        	return [
	        		"status" => false,
	        		'error' => 'Det finns redan en användare med den angivna E-post adressen.'
	        	];
	    	}
    	}

    	$updateUser = User::where('email', $request->EditInputEmail)->first();
    	$updateUser->user_type_id = $request->EditDDUserType;
    	$updateUser->first_name = $request->EditInputFirstName;
    	$updateUser->last_name = $request->EditInputLastName;
        $updateUser->company_name = isset($request->EditInputCompanyName) ? $request->EditInputCompanyName : null;
    	$updateUser->email = $request->EditInputEmail;
        $updateUser->date_of_birth = $request->EditInputSSN;
        $updateUser->org_number = $request->EditInputSSN;
    	$updateUser->billing_phone = $request->EditInputPhone;
    	$updateUser->billing_full_name = $request->EditInputFirstName. " " .$request->EditInputLastName;
    	$updateUser->billing_street_address = $request->EditInputBillingStreet;
    	$updateUser->billing_postal_code = $request->EditInputBillingPostalCode;
    	$updateUser->billing_city = $request->EditInputBillingCity;
    	$updateUser->billing_country = $request->EditInputBillingCountry;
		$updateUser->shipping_full_name = $request->EditInputFirstName. " " .$request->EditInputLastName;
    	$updateUser->shipping_street_address = $request->EditInputShippingStreet;
    	$updateUser->shipping_postal_code = $request->EditInputShippingPostalCode;
    	$updateUser->shipping_city = $request->EditInputShippingCity;
    	$updateUser->shipping_country = $request->EditInputShippingCountry;
		$updateUser->is_company = $request->EditIsCompany;
		$updateUser->save();

		// session()->flash('message', 'Användare har uppdaterats');
		$userTypes = UserType::all();
		$users = $this->getSavedUserList();

		return [
			'table' => view('admin/partials/table/user_table', compact('users', 'userTypes'))->render(),
			'message' => 'Användaren har uppdaterats.',
		];
    }

    public function updateUserType(Request $request)
    {
        $user = User::find($request->id);
        $user->user_type_id = $request->userType;
        $user->save();

        $userTypes = UserType::all();
		$users = $this->getSavedUserList();

		return [
			'table' => view('admin/partials/table/user_table', compact('users', 'userTypes'))->render(),
			'message' => 'Användaren har uppdaterats.',
		];
    }

    public function getSearchCookie()
    {
        $userFilter = json_decode(Cookie::get('userFilter'), true);
        // dd($userFilter);
        $this->userType = $userFilter['userType'] ? $userFilter['userType'] : null;
        $this->userSearch = isset($userFilter['userSearch']) ? $userFilter['userSearch'] : null;        
    }

    public function storeSearchInCookie()
    {
        $userFilter['userType'] = $this->userType;
        $userFilter['userSearch'] = $this->userSearch;
        $userFilter = json_encode($userFilter);
        Cookie::queue('userFilter', $userFilter, 60*24);
    }

    public function filterUsers(Request $request)
    {
    	$this->filterUsers = User::whereNotNull('id');

        if(!empty($request->userType)) {
            $this->userType = $request->userType;
            $this->filterByUserType();
        }

        if(!empty($request->search)) {
            $this->userSearch = $request->search;
            $this->filterBySearch();
        }

        $users =  $this->filterUsers->orderBy('created_at', 'DESC')->paginate(15);
    	$userTypes = UserType::all();
        $this->storeSearchInCookie();

        return [
            'table' => view('admin/partials/table/user_table', compact('users', 'userTypes'))->render()
        ];
    }

    public function filterByUserType()
    {
        $this->filterUsers->where('user_type_id', $this->userType);
    }


    public function filterBySearch()
    {
        $this->filterUsers->where(function($query) {
            return $query
                ->where('first_name', 'like', "%{$this->userSearch}%")
                ->orWhere('last_name', 'like', "%{$this->userSearch}%")
                ->orWhere('email', 'like', "%{$this->userSearch}%")
                ->orWhere('date_of_birth', 'like', "%{$this->userSearch}%")
                ->orWhere('org_number', 'like', "%{$this->userSearch}%")
                ->orWhere('billing_phone', 'like', "%{$this->userSearch}%")
                ->orWhere('billing_full_name', 'like', "%{$this->userSearch}%")
                ->orWhere('billing_street_address', 'like', "%{$this->userSearch}%")
                ->orWhere('billing_postal_code', 'like', "%{$this->userSearch}%")
                ->orWhere('billing_city', 'like', "%{$this->userSearch}%")
                ->orWhere('billing_country', 'like', "%{$this->userSearch}%");
        });
    }

    public function loginUser($id)
    {
    	Auth::loginUsingId($id);
    	return redirect('konto');
    }
}
