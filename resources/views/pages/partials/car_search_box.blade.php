
<div class="car-search-box col-md-4 col-sm-6" id="car-search-box">
    <div class="product-tab w100 clearfix">
        <ul id="search-tabs" class="nav nav-tabs nav-justified">
            <li class="active"><a href="#search-reg-mobile" data-toggle="tab">SÖK PÅ REG.NR</a></li>
            <li><a href="#search-model-mobile" data-toggle="tab">SÖK PÅ BILMODELL</a></li>
        </ul>
    </div>
    <!--/.product-tab-->

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="full-width-div">
            <img id="loading-image" class="center-block" src="{{ asset('images/loading.svg') }}">
        </div>
        <div class="tab-pane active" id="search-reg-mobile">
        <form action="{{ url('sok/storlek/'. $products->first()->productType->name) }}" method="GET">
            {{csrf_field()}}

            {{-- <input type="hidden" name="productTypeID" value=""> --}}
            
            <div class="inner-addon reg-search left-addon">
                <i class="glyphicon" aria-hidden="true"><img height="70" src="assets/img/regsearch.png"></i>
                <input id="regNrSearch" type="text" name="regnr" class="form-control" placeholder="ABC123" autocomplete="off" >
            </div><!-- /.inner-addon -->

            <div class="productFilter productFilterLook2">

    {{--             <div class="row">
                    <div class="col-sm-12">
                        <div class="radio product-label">
                            <label><input type="radio" name="optradio">Kompletta Hjul</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 padding-right-ajust">
                        <div class="radio product-label">
                            <label><input type="radio" name="optradio">Fälgar</label>
                        </div>
                    </div>
                    <div class="col-xs-6 padding-left-ajust">
                        <div class="radio product-label">
                            <label><input type="radio" name="optradio">Däck</label>
                        </div>
                    </div>    
                </div> --}}
                
                <div class="row">
                    <div class="col-xs-4 padding-right-ajust">
                        <div class="select-style">
                            <select id="product_width" name="product_width" class="form-control">
                                <option value="">Bredd</option>
                                @foreach ($widths as $width)
                                    @if($width->product_width == $searchFilter['filterTireByWidth'])
                                        <option selected value="{{$width->product_width}}">{{$width->product_width}}</option>
                                    @else
                                        <option value="{{$width->product_width}}">{{$width->product_width}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust padding-right-ajust">
                        <div class="select-style">
                            <select id="product_profile" name="product_profile" class="form-control">
                                <option value="">Profil</option>
                                @foreach ($profiles as $profile)
                                    @if($profile->product_profile == $searchFilter['filterTireByProfile'])
                                        <option selected value="{{$profile->product_profile}}">{{$profile->product_profile}}</option>
                                    @else
                                        <option value="{{$profile->product_profile}}">{{$profile->product_profile}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-4 padding-left-ajust">
                        <div class="select-style">
                            <select id="product_inch" name="product_inch" class="form-control">
                                <option value="">Tum</option>
                                @foreach ($inches as $inch)
                                    @if($inch->product_inch == $searchFilter['filterTireByInch'])
                                        <option selected value="{{$inch->product_inch}}">{{$inch->product_inch}}</option>
                                    @else
                                        <option value="{{$inch->product_inch}}">{{$inch->product_inch}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 padding-right-ajust">
                        <div class="select-style">
                            <select id="product_brand" name="product_brand" class="form-control">
                                <option value="">Alla märken</option>
                                @foreach ($brands as $brand)

                                    @if(!empty($brand->product_brand) && $brand->product_brand == $searchFilter['filterTireByBrand'])
                                        <option selected value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                    @else
                                        <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 padding-left-ajust">
                        <div id="product_model" class="select-style">
                            <select name="product_model" class="form-control">
                                <option value="">Alla mönster</option>
                                 @foreach ($models as $model)
                                    @if(!empty($model->product_model) && $model->product_model == $searchFilter['filterTireByModel'])
                                        <option selected="" value="{{$model->product_model}}">{{$model->product_model}}</option>
                                    @else
                                        <option value="{{$model->product_model}}">{{$model->product_model}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-7 col-xs-offset-5">
                        <div class="check-btn">
                            {{-- <label class="checkbox-inline"><input type="checkbox" name="cdack">Mer än 4st</label> --}}
                            <label class="checkbox-inline"><input type="checkbox" name="is_runflat" >Runflat</label>
                            <label class="checkbox-inline"><input type="checkbox" name="is_ctyre">C-Däck</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- productFilter -->

            {{-- <button class="btn" type="submit">
                Sök
                <i class="fa fa-long-arrow-right" > </i>
            </button> --}}
            <div class="cart-actions">
                 <button class="button btn-block btn-cart cart first" type="submit">Sök
                <i class="fa fa-long-arrow-right" > </i>
                </button>
            </div>
        </form>
        </div>
        
        <div class="tab-pane" id="search-model-mobile">
        </div>

    </div>
    <!-- /.tab content -->

</div>
<!-- /.car-search-box -->