<?php

namespace App\Http\Controllers;

use App\CustomerSearch;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminCustomerSearchesController extends Controller
{
    public function index(Request $request)
    {
    	$searches = CustomerSearch::orderBy('created_at', 'DESC')->paginate(100);

    	if($request->ajax()) {
            return [
                'table' => view('admin.partials.table.search_table', compact('searches'))->render()
            ];
        }

    	return view('admin.customer_searches', compact('searches'));
    }

    public function filterCustomerSearches(Request $request)
    {
        // dd($request->all());

        // $this->filterCustomerSearches = CustomerSearch::whereNotNull('id');
        $this->filterCustomerSearches = DB::table('customer_searches')->whereNotNull('id');

        if(!empty($request->fromDate)) {
            $this->filterByFromDate($request->fromDate);
        }

        if(!empty($request->toDate)) {
            $this->filterByToDate($request->toDate);
        }

        // if(!empty($request->search)) {
        //     $this->filterBySearch($request->search);
        // }

        $searches =  $this->filterCustomerSearches->orderBy('id', 'DESC');
        if($request->ajax()) {
            $searches = $searches->paginate(100);
            return [
                'table' => view('admin.partials.table.search_table', compact('searches'))->render()
            ];
        }

        $searches = $searches->get(['id', 'reg_number', 'car_info', 'created_at']);
        // dd($searches);
        foreach($searches as $object)
        {
            $searchesArr[] =  (array) $object;
        }

        // dd($searchesArr);
        
        if( empty($request->fromDate) && empty($request->toDate)) {
            $fileName = 'Sökningar_alla';
        } else {
            $fileName = 'Sökningar_'.$request->fromDate."-".$request->toDate;
        }

        $timeStamp = date('Y-m-d');
        Excel::create($fileName, function($excel) use($searchesArr) {
            $excel->sheet('Sheet 1', function($sheet) use($searchesArr) {
                $sheet->fromArray($searchesArr, null, 'A1', true);
            });
        })->download('xls');

        return;
    }

    public function filterByFromDate($fromDate)
    {
        $this->filterCustomerSearches->where('created_at', '>=', $fromDate);
    }

    public function filterByToDate($toDate)
    {
        $this->filterCustomerSearches->where('created_at', '<=', $toDate);
    }

}
