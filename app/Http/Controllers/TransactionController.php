<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if ($request->session()->exists('cart')) {
            $data = $request->session()->get('cart');
        }
        return view('transaction', compact('data'));
    }
    public function action(Request $request)
    {
        $data =  $request->all();
        $data['id'] = rand(0, 1000);
        if ($request->session()->exists('cart')) {
            echo 'exitst';
            $request->session()->push('cart', $data);
        } else {
            $request->session()->put('cart', [$data]);
            echo 'ok';
        }
        return redirect()->back();
    }
    public function delete(Request $request, $id)
    {
        if ($request->session()->exists('cart')) {
            $data = $request->session()->get('cart');
            foreach ($data as $index => $dt) {
                if ($dt['id'] == $id) {
                    unset($data[$index]);
                }
            }
            $request->session()->put('cart', $data);
        }
        return redirect()->back();
    }
}
