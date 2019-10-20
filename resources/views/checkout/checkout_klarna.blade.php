@extends('layout')

@section('content')
<!-- /.Fixed navbar  -->
<div class="container main-container headerOffset">
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

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i
                    class="glyphicon glyphicon-shopping-cart"></i> KASSA</span></h1>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
            {{-- <h4 class="caps"><a href="category.html"><i class="fa fa-chevron-left"></i> Tillbaka till produktsida </a></h4> --}}
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">


                    <div class="w100 clearfix">
                        <div class="row userInfo">

                            <div style="clear: both"></div>
                            <div id="klarana_checkout">
                                {!! $snippet !!}
                            </div>

                        </div>
                        <!--/row end-->

                    </div>


                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 rightSidebar">
            <div class="w100 cartMiniTable">
                <table id="cart-summary" class="std table">
                    <tbody>
                    <tr>
                        <td>Totalt produkter</td>
                        <td class="price">{{ Cart::total() }} kr</td>
                    </tr>
                    <tr style="">
                        <td>Leverans</td>
                        @if(empty($shippingCost))
                            <td class="price"><span class="success"> Fri leverans </span></td>
                        @else
                            <td class="price">{{ $shippingCost }} kr</td>

                        @endif
                    </tr>
                    <tr class="cart-total-price ">
                        <td>Totalt (ex moms)</td>
                        <td class="price">{{ str_replace(' ', '', Cart::total()) * 0.8}} kr</td>
                    </tr>
                    <tr>
                        <td>Moms</td>
                        <td class="price" id="total-tax">{{ str_replace(' ', '', Cart::total()) * 0.2}} kr</td>
                    </tr>
                    <tr>
                        <td> Totalt</td>
                        <td class=" site-color" id="total-price">{{  str_replace(' ', '', Cart::total()) + $shippingCost }} kr</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!--  /cartMiniTable-->

        </div>
        <!--/rightSidebar-->

    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /.main-container-->
<div class="gap"></div>

@endsection

@section('footer')

<script>


    $(document).ready(function () {

        $('input#newAddress').on('ifChanged', function (event) {
            //alert(event.type + ' callback');
            $('#newBillingAddressBox').collapse("show");
            $('#exisitingAddressBox').collapse("hide");

        });

        $('input#exisitingAddress').on('ifChanged', function (event) {
            //alert(event.type + ' callback');
            $('#newBillingAddressBox').collapse("hide");
            $('#exisitingAddressBox').collapse("show");
        });


        $('input#newShippingAddress').on('ifChanged', function (event) {
            //alert(event.type + ' callback');
            $('#newShippingAddressBox').collapse("show");

        });

        $('input#existingShippingAddress').on('ifChanged', function (event) {
            //alert(event.type + ' callback');
            $('#newShippingAddressBox').collapse("hide");

        });


        $('input#creditCard').on('ifChanged', function (event) {
            //alert(event.type + ' callback');
            $('#creditCardCollapse').collapse("toggle");

        });


        $('input#CashOnDelivery').on('ifChanged', function (event) {
            //alert(event.type + ' callback');
            $('#CashOnDeliveryCollapse').collapse("toggle");

        });


    });


</script>
@endsection


