<div class="container containerOffset">
    <div class="row" >
        <div class="col-sm-12">

            <form action="#" method="POST">

                {{-- <input type="hidden" name="productTypeID" value="1"> --}}
                
                <div id="searchByDimensions" class="productFilter productFilterLook2">
                    
                    <div class="row selectDimensionForm row-fluid" >
                        <div class="col-sm-2">
                            <div class="select-style">
                                <select id="width" name="width" class="form-control">
                                    <option value="">Bredd</option>
                                    @foreach ($widths as $width)
                                        @if( $width->product_width == $searchFilter['filterTireByWidth'])
                                            <option selected value="{{$width->product_width}}">{{$width->product_width}}</option> 
                                            <?php continue; ?>
                                        @endif
                                        <option value="{{$width->product_width}}">{{$width->product_width}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="select-style">
                                <select id="profile" name="profile" class="form-control">
                                    <option value="">Profil</option>
                                    @foreach ($profiles as $profile)
                                        @if( $profile->product_profile == $searchFilter['filterTireByProfile'])
                                            <option selected value="{{$profile->product_profile}}">{{$profile->product_profile}}</option> 
                                            <?php continue; ?>
                                        @endif
                                        <option value="{{$profile->product_profile}}">{{$profile->product_profile}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="select-style">
                                <select id="inch" name="inch" class="form-control">
                                    <option value="">Tum</option>
                                    @foreach ($inches as $inch)
                                        @if( $inch->product_inch == $searchFilter['filterTireByInch'])
                                            <option selected value="{{$inch->product_inch}}">{{$inch->product_inch}}</option> 
                                            <?php continue; ?>
                                        @endif
                                        <option value="{{$inch->product_inch}}">{{$inch->product_inch}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                
                        <div class="col-sm-2">
                            <div class="select-style">
                                <select id="brand" name="brand" class="form-control">
                                    <option value="">Alla märken</option>
                                    @foreach ($brands as $brand)
                                        @if(!empty($brand->product_brand) && $brand->product_brand == $searchFilter['filterTireByBrand'])
                                            <option selected value="{{$brand->product_brand}}">{{$brand->product_brand}}</option> 
                                            <?php continue; ?>
                                        @endif
                                        <option value="{{$brand->product_brand}}">{{$brand->product_brand}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div id="model" class="select-style">
                                <select name="model" class="form-control">
                                    <option value="">Alla mönster</option>
                                     @foreach ($models as $model)
                                        @if(!empty($model->product_model) &&  $model->product_model == $searchFilter['filterTireByModel'])
                                            <option selected value="{{$model->product_model}}">{{$model->product_model}}</option> 
                                            <?php continue; ?>
                                        @endif
                                        <option value="{{$model->product_model}}">{{$model->product_model}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-10">
                            <div class="check-btn">
                                <br>
                                {{-- <label class="checkbox-inline"><input type="checkbox" name="qty">Mer än 4st</label> --}}
                                <label class="checkbox-inline"><input type="checkbox" name="runflat" >Runflat</label>
                                <label class="checkbox-inline"><input type="checkbox" name="cdack">C-Däck</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- <div class="cart-actions">
                     <button class="button btn-block btn-cart cart first" type="submit">Sök
                    <i class="fa fa-long-arrow-right" > </i>
                    </button>
                </div> --}}
            </form>



        </div>
    </div>   
</div>