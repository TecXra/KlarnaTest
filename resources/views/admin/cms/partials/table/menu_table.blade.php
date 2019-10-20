<div class="row">
    <div class="col-sm-6">
        <h3 class="block-title-2">Sidor</h3>

        <ul id="sortable1" class="connectedSortable">
            @foreach($pages as $page)
                @if($page->is_active == 1)
                    <li style="padding: 10px; margin: 3px; border: 1px solid #A9C8A3; background-color: #DCEACF" id="{{$page->id}}">{{ $page->label }} 
                        <span style="color: #A9C8A3; margin-left: 5px" class="glyphicon glyphicon-eye-open pull-right"></span>
                        {!! App\Setting::find(1)->value == $page->id ? '<span class="glyphicon glyphicon-list-alt pull-right" title="Blogg"></span> ' : '' !!}
                    </li>
                @else
                    <li style="padding: 10px; margin: 3px; border: 1px solid #cdcdcd; background-color: #eee"  id="{{$page->id}}">{{ $page->label }} 
                        <span style="color: #aaa; margin-left: 5px" class="glyphicon glyphicon-eye-close pull-right"></span>
                        {!! App\Setting::find(1)->value == $page->id ? '<span class="glyphicon glyphicon-list-alt pull-right" title="Blogg"></span> ' : '' !!}
                    </li>
                @endif
            @endforeach
        </ul>

    </div>


    <div class="col-sm-6">
        <h3 class="block-title-2">Vald meny</h3>

        <ul id="sortable2" class="connectedSortable list-group">
            @foreach($menuPages as $page)
                @if($page->is_active == 1)
                    <li style="padding: 10px; margin: 3px; border: 1px solid #A9C8A3; background-color: #DCEACF" id="{{$page->id}}">{{ $page->label }} 
                        <span style="color: #A9C8A3; margin-left: 5px" class="glyphicon glyphicon-eye-open pull-right"></span>
                        {!! App\Setting::find(1)->value == $page->id ? '<span class="glyphicon glyphicon-list-alt pull-right" title="Blogg"></span> ' : '' !!}
                    </li>
                @else
                    <li style="padding: 10px; margin: 3px; border: 1px solid #cdcdcd; background-color: #eee"  id="{{$page->id}}">{{ $page->label }} 
                        <span style="color: #aaa; margin-left: 5px" class="glyphicon glyphicon-eye-close pull-right"></span>
                        {!! App\Setting::find(1)->value == $page->id ? '<span class="glyphicon glyphicon-list-alt pull-right" title="Blogg"></span> ' : '' !!}
                    </li>
                @endif
            @endforeach
        </ul>

    </div>
</div>