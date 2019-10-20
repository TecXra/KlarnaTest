<style>
    .left {
        width: 45%;
        float:left;
        padding-right: 5%;
    }

    .right {
        width: 45%;
        float:right;
        padding-left: 5%;
    }

    .order-box-content {
        border-top:1px solid #333;
    }

    .pull-right {
        float: right;
        padding-right: 10px
    }

</style>
<div class="row">
    <div class="col-sm-12 ">
        <div class="row userInfo">

            <div class="thanxContent">

                <h1> Tips på däck och fälgar </h1>

            </div>

            <div class="statusContent">


                   <p>
                       {{ $mailMessage }}
                   </p>

                    <div style="clear: both"></div>

                    <br>
                    <br>
            </div>
            <div class="col-sm-10">
                <div class="order-box-content">
                    @if (sizeof(Cart::content()) > 0)
                    <div class="">
                        <table class="" cellspacing="0" width="100%">
                            <tbody>

                            <tr class="CartProduct cartTableHeader">
                                <td colspan="4"> <h3>Rekommenderade produkter</h3> </td>
                            </tr>

                            @foreach(Cart::content() as $item)

                                <tr class="">
                                    <td class="">

                                        <div>
                                            @if($item->model->product_type_id <= 10)
                                                <a href="{{ url($item->model->productType->name .'/'. $item->model->id) }}"> 
                                            @endif
                                            @if (isset($item->model->productImages->first()->name))
                                               <img height="100" src="{{ $message->embed($item->model->productImages->first()->thumbnail_path) }}" alt="img">
                                            @elseif($item->model->productType->name == 'falgar')
                                                    <img height="100" src="{{ $message->embed('images/product/noRimImg.jpg') }}" alt="img">
                                            @else
                                                <img height="100"  src="{{ $message->embed('images/product/noTireImg.jpg') }}" alt="img">
                                            @endif
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="miniCartDescription">
                                            <h4>
                                                @if($item->model->product_type_id <= 10)
                                                    <a href="{{ url($item->model->productType->name .'/'. $item->model->id) }}">
                                                        {{$item->model->product_name}} 
                                                     </a>
                                                @else
                                                    {{$item->model->product_name}} 
                                                @endif


                                                 <div class="price"><span>{{ $item->model->price * 1.25 }}  {{ $cartCalculator->getCurrency() }}</span></div>
                                             </h4>
                                        </div>
                                    </td>
                                    <td class=""><a> x{{ $item->qty }} </a></td>
                                    <td class=""><span>{{ $item->total }} {{ $cartCalculator->getCurrency() }}</span></td>
                                </tr>

                            @endforeach
                            <tr class="cartTotalTr blank">
                                <td class=""></td>
                                <td></td>
                                <td class=""></td>
                                <td class=""><span>  </span></td>

                            </tr>

                            <tr class="cartTotalTr">
                                <td class=""></td>
                                <td></td>
                                <td class="pull-right"><b>Totalt produkter: <b></td>
                                <td class=""><span> {{ $cartCalculator->totalPriceProducts() }} {{ $cartCalculator->getCurrency() }}</span></td>

                            </tr>
                            <tr class="cartTotalTr">
                                <td class=""></td>
                                <td></td>
                                <td class="pull-right"><b>Frakt: <b></td>
                                <td class=""><span> {{ $cartCalculator->totalPriceShipping() }} {{ $cartCalculator->getCurrency() }}</span></td>

                            </tr>
                            <tr class="cartTotalTr">
                                <td class=""></td>
                                <td></td>
                                <td class="pull-right"><b>Totalt (exkl moms.): </b></td>
                                <td class=""><span> {{ $cartCalculator->totalPriceExTax() }} {{ $cartCalculator->getCurrency() }}</span></td>

                            </tr>
                            <!-- @if($cartCalculator->getDiscount())
                            <tr class="cartTotalTr">
                                <td class=""></td>
                                <td></td>
                                <td class="pull-right"><b>Rabatt: </b></td>
                                <td class=""><span class="price"> {{$cartCalculator->getDiscount()}} {{ $cartCalculator->getCurrency() }}</span></td>

                            </tr>
                            @endif -->
                            <tr class="cartTotalTr">
                                <td class=""></td>
                                <td></td>
                                <td class="pull-right"><b>Totalt: </b></td>
                                <td class=""><span class="price">{{ $cartCalculator->totalPriceIncTax() }} {{ $cartCalculator->getCurrency() }}</span></td>

                            </tr>

                            </tbody>
                        </table>
                    </div> 
                    @endif

                </div>
            </div>
        <!--/row end-->
            <div class="thanxContent">
                <br>

                <hr>
                <p>
                    Med Vänliga Hälsningar
                    <br>
                    {{env('APP_NAME')}}<br>
                </p>

                <table cellpadding="0" cellspacing="0" border="0" style="background: none; border-width: 0px; border: 0px; margin: 0; padding: 0;">
                <tr><td valign="top" style="padding-top: 0; padding-bottom: 0; padding-left: 0; padding-right: 7px; border-top: 0; border-bottom: 0: border-left: 0; border-right: solid 3px #F7751F"><img id="preview-image-url" src="https://www.hjulonline.se/images/hjulonline_logo2.png"></td>
                <td style="padding-top: 0; padding-bottom: 0; padding-left: 12px; padding-right: 0;">
                <table cellpadding="0" cellspacing="0" border="0" style="background: none; border-width: 0px; border: 0px; margin: 0; padding: 0;">
                <tr><td colspan="2" style="padding-bottom: 5px; color: #F7751F; font-size: 18px; font-family: Arial, Helvetica, sans-serif;">Hjulonline Kundtjänst</td></tr>
                <tr><td colspan="2" style="color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><strong>{{env('APP_NAME')}}</strong></td></tr>
                <tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">m:</td><td style="color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">{{App\Setting::getPhone()}}</td></tr>
                <tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">a:</td><td valign="top" style="vertical-align: top; color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">{{App\Setting::getStreetAddress()}}, {{App\Setting::getPostalCode()}} {{App\Setting::getCity()}}</td></tr>
                <tr><td width="20" valign="top" style="vertical-align: top; width: 20px; color: #F7751F; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">w:</td><td valign="top" style="vertical-align: top; color: #333333; font-size: 14px; font-family: Arial, Helvetica, sans-serif;"><a href="http://www.hjulonline.se" style=" color: #1da1db; text-decoration: none; font-weight: normal; font-size: 14px;">{{env('APP_URL')}}</a>&nbsp;&nbsp;<span style="color: #F7751F;">e:&nbsp;</span><a href="mailto:{{ App\Setting::getOrderMail() }}" style="color: #1da1db; text-decoration: none; font-weight: normal; font-size: 14px;">{{ App\Setting::getOrderMail() }}</a></td></tr>
                </table>
                </td></tr></table>
            </div>

        </div>

    <!--/rightSidebar-->
    </div>
</div>
