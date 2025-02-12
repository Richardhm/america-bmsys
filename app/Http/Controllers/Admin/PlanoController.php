<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planos;



class PlanoController extends Controller
{
    private $repository;

    public function __construct(Planos $planos)
    {
        $this->repository = $planos;
    }

    public function list(Request $request)
    {
        if($request->ajax()) {
            return $this->repository->all();
        }
    }

    public function index()
    {
       return view('admin.pages.planos.index');     
    }

    public function store(Request $request)
    {
        // dd($request->all());

        //if($request->ajax()) {
        $this->repository->create($request->all());  
        return redirect()->route('planos.index');  
        //}
        
    }



}
