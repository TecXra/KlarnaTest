@extends('layout')

@section('content')
<!-- /.Fixed navbar  -->
<div class="container main-container containerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('') }}">Hem</a></li>
                <li><a href="{{ url('varukorg') }}">Varukorg</a></li>
                <li class="active"> Kassa</li>
            </ul>
        </div>
    </div>
    <!--/.row-->

    @if (session()->has('error_message'))
        <div class="alert alert-danger">
            {{ session()->get('error_message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span>
            <i class="fa fa-th-list" aria-hidden="true"></i> Du har valt att betala i butik på Norra Grängesbergsgatan 14, Malmö.</span></h1>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
            {{-- <h4 class="caps"><a href="category.html"><i class="fa fa-chevron-left"></i> Tillbaka till produktsida </a></h4> --}}
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-md-9 col-sm-7 clearfix cartContent checkoutReview">
            @if (sizeof(Cart::content()) > 0)
            <table class="cartTable table-responsive" style="width: 100%" >
                <tbody>
                <tr class="CartProduct cartTableHeader order-box-header" >
                    <td style="width:15%"> Produkt</td>
                    <td style="width:30%">info</td>
                    <td style="width:10%">Antal</td>
                    {{-- <td style="width:10%">Rabatt</td> --}}
                    <td style="width:15%">Totalt</td>
                </tr>
                
                @foreach (Cart::content() as $item)
                <tr class="CartProduct">
                    <td class="CartProductThumb"> 
                        <div>
                        @if($item->model->product_category_id !== 4)
                            <a href="{{ url($item->model->productType->name .'/'. $item->id) }}">
                        @else
                            <a>
                        @endif
                        @if(isset($item->model->productImages->first()->thumbnail_path))
                           <img src="{{ asset($item->model->productImages->first()->thumbnail_path)}}" alt="img">
                        @endif
                       
                        </div>
                    </td>
                    <td>
                        <div class="CartDescription"> 
                            @if($item->model->product_category_id !== 4)
                                <h4><a href="{{ url($item->model->productType->name .'/'. $item->model->id) }}">{{$item->model->product_name}} </a></h4>
                            @else
                                <h4> {{$item->model->product_name}} </h4>
                            @endif

                            {{-- <span class="size">{{ $item->model->product_width .'x'. $item->model->product_inch }}</span> --}}

                            <div class="price"><span>{{ $item->price }} kr</span></div>
                        </div>
                    </td>
                    <td>x{{ $item->qty }}</td>
                    <td class="price">{{ $item->total }}kr</td>
                    {{-- <td class="price">{{ $item->total }}kr</td> --}}
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif

           {{--  <br>
            <br>
            <br>
            
            <div class="col-sm-6">
                <table style="font-size: 1.1em">
                    <h2 class="block-title-2">Faktura Adress</h2>
                    <tr >
                        <td style="width:30%"></td>
                        <td style="width:50%"></td>
                    </tr>
                    <tr >
                        <td> <b>Namn:</b> </td>
                        <td> {{ Session::get('orderInfo.billingFullName')}}</td>
                    </tr>
                    <tr >
                        <td> <b>Gata:</b> </td>
                        <td> {{ Session::get('orderInfo.billingAddress')}}</td>
                    </tr>  
                    <tr>
                        <td> <b>Postadress:</b> </td>
                        <td> {{ Session::get('orderInfo.billingPostalCode') .', '. Session::get('orderInfo.billingCity')}}</td>
                    </tr>  
                    </tbody>
                </table>
            </div> --}}

        </div>

        <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
            <div class="contentBox">
                <div class="w100 costDetails">
                    <div class="table-block" id="order-detail-content">

                        <div class="w100 cartMiniTable">
                            <table id="cart-summary" class="std table">
                                <tbody>
                                <tr>
                                    <td>Totalt produkter</td>
                                    <td class="price">{{ $cartCalculator->totalPriceProducts() }} {{ $cartCalculator->getCurrency() }}</td>
                                </tr>
                                <tr style="">
                                    <td>Leverans</td>
                                    @if(Session::get('orderInfo.deliveryId') == 2 && empty($cartCalculator->totalPriceShipping()))
                                        <td class="price shippingCost"><span class="success"> Fri leverans </span></td>
                                    @elseif(Session::get('orderInfo.deliveryId') == 1 )
                                        <td class="price shippingCost"> 0 kr </span></td>
                                    @else
                                        <td class="price shippingCost">{{ $cartCalculator->totalPriceShipping() }} {{ $cartCalculator->getCurrency() }}</td>

                                    @endif
                                </tr>
                                <tr class="cart-total-price ">
                                    <td>Totalt (ex moms)</td>
                                    <td id="totalPriceExTax" class="price totalPriceExTax">{{ $cartCalculator->totalPriceExTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                </tr>
                                
                                @if(Session::has('campaign'))
                                    <tr>
                                        <td>Moms</td>
                                        <td class="price totalTax" id="totalTax">{{ ($cartCalculator->totalTax() - Session::get('campaign.discount')) * 0.2 }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                    <tr>
                                        <td> Rabatt</td>
                                        <td class="" style="color: #D14339;">- {{ Session::get('campaign.discount') }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                    <tr>
                                        <td> Totalt</td>
                                        <td class="site-color total-price" id="total-price">{{  $cartCalculator->totalPriceIncTax() - Session::get('campaign.discount') }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>Moms</td>
                                        <td class="price totalTax" id="totalTax">{{ $cartCalculator->totalTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                    <tr>
                                        <td> Totalt</td>
                                        <td class="site-color total-price" id="total-price">{{  $cartCalculator->totalPriceIncTax() }} {{ $cartCalculator->getCurrency() }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <hr>
    
    <form action="{{ url('storeInStorePayment')}}" method="POST">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-lg-12 clearfix">
                <ul class="pager">
                    <li class="previous pull-right"><button type="submit" class="btn btn-primary btn-xlarge pull-right" role="button">Bekräfta ordern</button>
                    </li>
                    <li class="next pull-left"><a href="{{ url('varukorg') }}"> ← Tillbaka till Varukorg</a></li>
                </ul>
                
            </div>
        </div>
    </form>

</div>
<!-- /.main-container-->
<div class="gap"></div>

@endsection
