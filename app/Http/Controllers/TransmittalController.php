<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransmittalController extends Controller
{
    public function index(Request $request)
    {
        return view('hoa_approvers3.hoa_approvers_transmittal');
    }

}