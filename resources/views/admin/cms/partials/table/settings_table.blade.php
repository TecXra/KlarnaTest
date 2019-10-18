<style>
    table.table tbody td { border:none; }
    table.table thead { border-bottom: 1px solid #ddd}
</style>
<table class="table" cellspacing="0" >
   {{--  <thead>
        <tr>
            <th style="width: 50%; border: none">Namn</th>
            <th style="width: 50%; border: none">Hantera</th>
        </tr>
    </thead> --}}
    <tbody>
        @foreach($settings as $setting)
            <tr >
                @if($setting->id == 1)
                    <td><label>{{ $setting->label }}</label></td>
                    <td style="padding-bottom: 20px;">
                        <select class="form-control" id="{{ $setting->name }}" name="{{ $setting->name }}">
                            <option>Ingen bloggsida</option>
                            @foreach($DDBlogPage as $page)
                                @if($setting->value == $page->id)
                                    <option selected {{ Request::input('paageId') ==  $page->id ? 'selected' : '' }} value="{{$page->id}}">{{$page->label}}</option>
                                @else
                                    <option {{ Request::input('paageId') ==  $page->id ? 'selected' : '' }} value="{{$page->id}}">{{$page->label}}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                @else
                    <td><label>{{ $setting->label }}</label></td>
                    <td><input class="form-control"  type="text" name="{{ $setting->name }}" value="{{ $setting->value }}" placeholder="{{$setting->name == 'order_mail' ? 'Om ej anges, visas support mail' : ''}}"></td>
                @endif
                <td>
            </tr>
        @endforeach

        <tr>
            <td><input type="submit" class="btn btn-defaul" name="updateSettings" value="Spara"></td>
            <td></td>
        </tr>
    </tbody>
</table>
{{-- <div class="pull-right">{{ $settings->links() }}</div> --}}