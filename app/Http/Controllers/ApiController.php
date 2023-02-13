<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Mockery\Exception as MockeryException;

class ApiController extends Controller
{
  /* 
    Создание строки
    POST params [ painting, film, width, height, opening, price, accessories? ]
  */
  public function create(Request $request)
  {
    try {
      OrderService::save($request->all());
      return response()->json('Saved');
    } catch (MockeryException $e) {
      return response()->json($e->getMessage(), $e->getCode());
    }
  }
}
