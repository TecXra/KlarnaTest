<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Profit;
use App\ShippingCost;
use Illuminate\Http\Request;

class AdminProfitController extends Controller
{
    public function summerTires()
    {
    	$profits  = Profit::where('product_type', 1)->get();
        $shippingCost['tires'] = ShippingCost::where('product_category_id', 1)->first();
        $shippingCost['rims'] = ShippingCost::where('product_category_id', 2)->first();

    	return view('admin.profit_tires', compact('profits', 'shippingCost'));
    }

    public function updateSummerTires(Request $request)
    {
    	// dd($request->all());

    	foreach($request->rows as $key => $row) {
    		$updateProfit = Profit::find($key);
    		$updateProfit->in_procent = $row['procent'];
    		$updateProfit->in_cash = $row['cash'];
    		$updateProfit->save();
    	}
        // dd($request->all());

        $updateShippingCost = ShippingCost::where('product_category_id', 1)->get();
        foreach ($updateShippingCost as $updateCost) {
            $updateCost->cost = $request->shippingCostTires;
            $updateCost->save();
        }

        $updateShippingCost = ShippingCost::where('product_category_id', 2)->get();
        foreach ($updateShippingCost as $updateCost) {
            $updateCost->cost = $request->shippingCostRims;
            $updateCost->save();
        }

    	$profits  = Profit::where('product_type', 1)->get();
        $shippingCost['tires'] = ShippingCost::where('product_category_id', 1)->first();
        $shippingCost['rims'] = ShippingCost::where('product_category_id', 2)->first();

    	return view('admin.profit_tires', compact('profits', 'shippingCost'));
    }

	public function winterTires()
    {
    	$profits  = Profit::where('product_type', 2)->get();
        $shippingCost['tires'] = ShippingCost::where('product_category_id', 1)->first();
        $shippingCost['rims'] = ShippingCost::where('product_category_id', 2)->first();

    	return view('admin.profit_tires', compact('profits', 'shippingCost'));
    }

    public function updateWinterTires(Request $request)
    {
    	foreach($request->rows as $key => $row) {
    		$updateProfit = Profit::find($key);
    		$updateProfit->in_procent = $row['procent'];
    		$updateProfit->in_cash = $row['cash'];
    		$updateProfit->save();
    	}

        $updateShippingCost = ShippingCost::where('product_category_id', 1)->get();
        foreach ($updateShippingCost as $updateCost) {
            $updateCost->cost = $request->shippingCostTires;
            $updateCost->save();
        }

        $updateShippingCost = ShippingCost::where('product_category_id', 2)->get();
        foreach ($updateShippingCost as $updateCost) {
            $updateCost->cost = $request->shippingCostRims;
            $updateCost->save();
        }

    	$profits  = Profit::where('product_type', 2)->get();
        $shippingCost['tires'] = ShippingCost::where('product_category_id', 1)->first();
        $shippingCost['rims'] = ShippingCost::where('product_category_id', 2)->first();

    	return view('admin.profit_tires', compact('profits', 'shippingCost'));

    }

    public function rims()
    {
    	$profits  = Profit::where('product_type', 3)->get();
        $shippingCost['tires'] = ShippingCost::where('product_category_id', 1)->first();
        $shippingCost['rims'] = ShippingCost::where('product_category_id', 2)->first();

    	return view('admin.profit_rims', compact('profits', 'shippingCost'));
    }

    public function updateRims(Request $request)
    {
    	foreach($request->rows as $key => $row) {
    		$updateProfit = Profit::find($key);
    		$updateProfit->in_procent = $row['procent'];
    		$updateProfit->in_cash = $row['cash'];
    		$updateProfit->mount = $row['mount'];
    		$updateProfit->save();
    	}

        $updateShippingCost = ShippingCost::where('product_category_id', 1)->get();
        foreach ($updateShippingCost as $updateCost) {
            $updateCost->cost = $request->shippingCostTires;
            $updateCost->save();
        }

        $updateShippingCost = ShippingCost::where('product_category_id', 2)->get();
        foreach ($updateShippingCost as $updateCost) {
            $updateCost->cost = $request->shippingCostRims;
            $updateCost->save();
        }

        $profits  = Profit::where('product_type', 3)->get();
        $shippingCost['tires'] = ShippingCost::where('product_category_id', 1)->first();
        $shippingCost['rims'] = ShippingCost::where('product_category_id', 2)->first();

    	return view('admin.profit_rims', compact('profits', 'shippingCost'));
    }
}
