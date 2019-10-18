<?php

namespace App;

use App\Profit;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class CartCalculator extends Model
{
	private $currencyMultiplier = 1;
	private $currency = "kr";
	private $discount = 0;
	private $shippingCost = 0;

	public function __construct()
	{
		// $this->setCurrency();
		$this->setDiscount();
		$this->setTotalPriceShipping();
	}

	public function setCurrency()
	{
		$customerCountry = Session::get('orderInfo.billingCountry');
    
        // if($customerCountry == 'SE') {
            $this->currencyMultiplier = 1;
            $this->currency = "kr";
        // }

        // if($customerCountry == 'FI') {
        //     $this->currencyMultiplier = 0.102710;
        //     $this->currency = "€";
        // }

        // if($customerCountry == 'NO') {
        //     $this->currencyMultiplier = 0.928602;
        //     $this->currency = "NKr";
        // }
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	private function setDiscount()
	{
        if(Session::has('campaign.discount')) {
            $this->discount = Session::get('campaign.discount');
        }
	}

	public function getDiscount()
	{
		return $this->discount;
	}


    public function totalPriceProducts()
    {
        return str_replace(' ', '', Cart::total());
    }

    private function setTotalPriceShipping($items = null)
    {
    	if(is_null($items)) 
    		$items = Cart::content();

        $shippingCost = 0;
        $accessoriesShippingCost = false;
        $gotRims = false;
        $gotTires = false;
        $gotCompleteRims = false;
        $gotCompleteTires = false;
        $gotMonteringsKit = false;
        $gotBalansering = false;
        $balanseringPrice = 0;
        $rowId = "";
        $inchArr = [];
        $qtyArr = [];
        

        if(sizeof($items) > 0) {
            foreach($items as $item) {
                $inchArr[] = $item->model->product_inch;
                // $qtyArr[] = $item->qty;

                if($item->options->product_category_id == 2)
                    $gotRims = true;

                if($item->options->product_category_id == 1)
                    $gotTires = true;

                if($item->options->product_category_id == 2 && $item->qty >= 4)
                    $gotCompleteRims = true;

                if($item->options->product_category_id == 1 && $item->qty >= 4)
                    $gotCompleteTires = true;

                if($item->id == 2)
                    $gotMonteringsKit = true;

    
                if($item->id == 4) {
                    $gotBalansering = true;
                    $balanseringPrice = $item->price;
                    $rowId = $item->rowId;
                }


                if($item->options->product_category_id === 3 && $item->options->product_type_id !== 10) {
                    $accessoriesShippingCost = true;
                }
                $shippingCost += $item->options->shipping_cost;
            }

            // dd($gotCompleteRims, $gotCompleteTires, $gotMonteringsKit);

            // if($gotCompleteRims && $gotCompleteTires)
            //     $shippingCost = 400;

            if($accessoriesShippingCost)
                $shippingCost += 100; //100 kr total frakt för tillbehör, oavsett produkttillbehör och kvantitet

            if($accessoriesShippingCost && !$gotTires && !$gotRims)
                $shippingCost = 100; //100 kr total frakt för tillbehör, oavsett produkttillbehör och kvantitet

            // if($gotMonteringsKit && !$gotRims && !$gotTires)
            //     $shippingCost = 100; //100 kr total frakt för tillbehör, oavsett produkttillbehör och kvantitet

            if($gotRims && $gotTires && $gotBalansering)
                $shippingCost = 0;

            if(($gotCompleteRims && $gotCompleteTires))
                $shippingCost = 495;

            if(($gotCompleteRims && $gotCompleteTires && $gotBalansering)) {               
                $inch = max($inchArr);

                if(!empty($inch) && !empty($rowId)) {
                    $mountPrice = 0;
                    // $qty = max($qtyArr);
                    $mountPrice = Profit::where('product_type', 3)
                            ->where('size', $inch)
                            ->first()->mount;
                    Cart::update($rowId, [ 
                        'price' => $mountPrice
                    ]);
                }
            } else {
                if(!empty($inch) && !empty($rowId)) {
                    Cart::remove($rowId);
                }
                // Cart::update($rowId, [ 
                //     'price' => 0,
                // ]);
            }
        }

        // if(Session::get('orderInfo.shippingCountry') == 'FI' || Session::get('orderInfo.shippingCountry') == 'NO') {
        //     $shippingCost = $shippingCost * 2;
        // }

        $this->shippingCost = $shippingCost;
        Session::put('orderInfo.deliveryCost', $shippingCost);

        if(Session::has('orderInfo.deliveryId')) {
            if(Session::get('orderInfo.deliveryId') == 2 )
                // $this->shippingCost = $shippingCost;
                $this->shippingCost = $this->shippingCost * 0.8;
        }
    }

    public function totalPriceShipping()
    {
    	return $this->shippingCost;
    }

    public function totalTax()
    {
        return (str_replace(array(' ',','), '', Cart::total()) + $this->shippingCost - $this->discount) * 0.2;
    }

    public function totalPriceExTax()
    {
        return (str_replace(array(' ',','), '', Cart::total()) + $this->shippingCost - $this->discount) * 0.8;
    }

    public function totalPriceIncTax()
    {
        return (str_replace(array(' ',','), '', Cart::total()) + $this->shippingCost - $this->discount);
    }
}
