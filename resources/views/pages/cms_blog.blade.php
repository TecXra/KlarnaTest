@extends('layout')

@section('content')
<style>
    h3 {
        font-weight: 600;
        margin-bottom: -10px;
    }

    a:hover {
        color: #333;
    }

    .meta {
        color: #777;
        /*font-size: 0em;*/
    }
    .content {
        font-size: 1.5em;
    }
</style>

<div class="wrapper whitebg containerOffset" style="">
    <div class="container main-container">
        <div class="row innerPage">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-offset-1">
{{--         		<h1 class="title-big text-left section-title-style2">
                    <span>{{$page->label}}</span>
                </h1> --}}

                @foreach($posts as $post)
                    <div style="padding-left:15px">
                        
                        {{-- {{ dd($posts) }} --}}
                        <div class="col-sm-10">
{{--                             <h2 style="font-size: 2.5em; font-weight: 500; border-bottom: 1px solid #555; padding-bottom: 0; margin-bottom: 10px"><a href="{{ url($post->name) }}">{!! $post->label !!}</a></h2> --}}

                            <h2 class="title-medium-big text-left section-title-style2">
                                <span><a href="{{ url($post->name) }}">{!! $post->label !!}</a></span>
                            </h2>
                            <span class="meta"> {{ $post->updated_at }} | av: {{ $post->author }}</span>
                            <p>  
                                
                                <br>
                                {!! substr($post->content, 0, 450) !!}
                                <br>
                                 <i><a style="color: #D74D42" href="{{ url($post->name) }}">Läs vidare...</a></i>
                                
                            </p>
                           

                        </div>

                        <div class="gap"></div>
                        
                    </div>
                @endforeach

                <div class="gap"></div>
                <div style="margin-left: 30px">{{ $posts->links() }}</div>
                
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