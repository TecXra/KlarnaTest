@extends('layout')

@section('content')

<div class="container main-container containerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}">Hem</a></li>
                <li class="active"> Order bekräftelse</li>
            </ul>
        </div>
    </div>
    <!--/.row-->

    <div class="row">
        <div class="col-lg-12 ">
            <div class="row userInfo">

                <!--  <div class="thanxContent text-center">

                    <h1> Tack för din order <a href="order-status.html">#6469</a></h1>
                    <h4>vi meddelar när dina varor är på väg</h4>

                </div> -->

                <div>
                    {!! $snippet !!}
                </div>

                <!-- <div class="col-lg-7 col-center">
                    <h4></h4>

                    <div class="cartContent table-responsive  w100">
                        <table style="width:100%" class="cartTable cartTableBorder">
                            <tbody>

                            <tr class="CartProduct  cartTableHeader">
                                <td colspan="4"> Varor som kommer levereras </td>


                                <td style="width:15%"></td>
                            </tr>

                            <tr class="CartProduct">
                                <td class="CartProductThumb">
                                    <div><a href="#"><img alt="img" src="{{ asset('images/product/atrezzo-zsr.jpg') }}"></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="CartDescription">
                                        <h4><a href="#"> ABS302 MK </a></h4>
                                        <span class="size"> 12 x 1.5 L </span>

                                        <div class="price"><span> 4234kr </span></div>
                                    </div>
                                </td>


                                <td class="price">4234kr</td>
                            </tr>

                            <tr class="CartProduct">
                                <td class="CartProductThumb">
                                    <div><a href="#"><img alt="img" src="{{ asset('images/product/KF550.jpg') }}"></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="CartDescription">
                                        <h4><a href="#"> ABS302 MK</a></h4>
                                        <span class="size"> 12 x 1.5 L </span>

                                        <div class="price"><span> 788kr </span></div>
                                    </div>
                                </td>


                                <td class="price">788kr</td>
                            </tr>

                            </tbody>
                        </table>
                    </div> 

                </div> -->
            </div>
            <!--/row end-->

        </div>

        <!--/rightSidebar-->

    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /.main-container -->

<div class="gap"></div>
@endsection