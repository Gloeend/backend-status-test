<?php

namespace App\Services;

use App\Orders;
use Mockery\Exception as MockeryException;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use CURLFile;

class OrderService
{


  public static function save($data)
  {
    $model = Orders::create([
      'painting' => $data['painting'],
      'film' => $data['film'],
      'handler' => $data['handle'],
      'width' => $data['width'],
      'height' => $data['height'],
      'opening' => $data['opening'],
      'accessories' => json_encode($data['accessories']),
      'price' => $data['price']
    ]);

    if (!$model) {
      throw new MockeryException('Not saved', 422);
    }

    self::pdf($data);
  }

  private static function pdf($data)
  {
    view()->share('pdf', $data);
    $pdf = PDF::loadView('pdf', compact('data'));
     
    $content = $pdf->download('order.pdf')->getOriginalContent();
    Storage::put('order.pdf', $content);
    $url = Storage::url('app/order.pdf');
    $path = base_path() . $url;
    self::sendDocument($path);
  }

  private static function sendDocument($file){
    $chat = getenv('TELEGRAM_CHAT');
    $apiKey = getenv('TELEGRAM_TOKEN');  
    $url =  "https://api.telegram.org/bot" . $apiKey . "/sendDocument";
    $fields = [
      'chat_id' => $chat,
      'document' => new CURLFile($file),
      'caption' => 'Новый заказ',
    ];
     $curl = curl_init();
     curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
     curl_setopt($curl, CURLOPT_URL, $url);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
     curl_exec($curl);
     curl_close($curl);
 }
}
