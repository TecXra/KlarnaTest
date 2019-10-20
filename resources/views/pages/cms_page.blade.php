@extends('layout')

@if($page->name == 'faq')
    @section('header')
        <META NAME="ROBOTS" CONTENT="NOINDEX, FOLLOW">
    @endsection
@endif

@section('content')


<div class="wrapper whitebg containerOffset" style="">
    <div class="container main-container">
        <div class="row innerPage">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-offset-1">
        		<h1 class="title-big text-left section-title-style2">
                    <span>{{$page->label}}</span>
                </h1>


                <div style="padding-left:15px">
                    <style>
                        h3 {
                            font-weight: 600;
                            margin-bottom: -10px;
                        }

                        a:hover {
                            color: #333;
                        }
                    </style>
                        @if($page->is_post == 1)
                            <span class="meta"> {{ $page->updated_at }} | av: {{ $page->author }}</span>
                        @endif
                        <p class="lead">
                            {!! $page->content !!}
                        </p>
            	</div>
            </div>
        </div>
        <!--/row || innerPage end-->
    </div>
    <!-- ./main-container -->
    <div class="gap"></div>
    <div class="gap"></div>
</div>
<!-- /main-container -->

@endsection