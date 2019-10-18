<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderComment;

class AdminOrderCommentsController extends Controller
{
	// public function showOrderCommentModal(Request $request)
 //    {
 //        $orderComment = OrderComment::where('order_id', $request->orderId)->first();

 //        return [
 //            // 'commentOrderModal' => view('admin/partials/form/comment_order_modal')->render(),
 //            'orderComment' => $orderComment,
 //        ];
 //    }

 //    public function commentOrder(Request $request)
 //    {
 //    	dd($request->all());
 //    	$createComment = new OrderComment;
 //    	$createComment->order_id = $request->order_id; 
 //    	$createComment->comment = $request->comment; 
 //    	$createComment->save();

 //    	return back();
 //    }
}
