<?php

namespace App\Services;

use App\Orders;
use Mockery\Exception as MockeryException;
use Illuminate\Support\Facades\App;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use CURLFile;
use Mockery;

/* 
  Класс сервис для таблицы Order
*/
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

    $file = self::pdf($data);
    self::sendDocument($file);
  }


  /*
    Создает PDF документ, принимает $data из post, возвращает url до файла
  */
  private static function pdf($data)
  {
    view()->share('pdf', $data);
    $pdf = PDF::loadView('pdf', compact('data'));
     
    $content = $pdf->download('order.pdf')->getOriginalContent();
    Storage::put('order.pdf', $content);
    $url = Storage::url('app/order.pdf');
    $path = base_path() . $url;
  }

  /*
    Отправляет файл в telegram.
    в .env необходимы TELEGRAM_CHAT, TELEGRAM_TOKEN
  */
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
     $result = curl_exec($curl);
     curl_close($curl);
     if (!$result) {
      throw new MockeryException('File not uploaded', 500);
     }
 }
}
