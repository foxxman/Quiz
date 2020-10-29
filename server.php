<?php 
//не продолжит работать без файла
require './config.php';
require './func.php';
//продолжит работать без файла
//include './func.php';

//получение json-строки
$data = file_get_contents('php://input');
//file_put_contents('1.json', $data);//запись информации в файл 1.json

//проверка на передачу
if (!isset($data) || empty($data)) die('Ничего не передано')

//преобразование в массив
$json = json_decode($data,true);

$message = "Новая заявка с сайта Quiz\n";

//=======================================================================
//проверка на пустоту поля вопроса или поля ответа
//=======STEP1====================
if($json['step0']['question'] && $json['step0']['answers']){
//добавляем в строку сообщения
// .= склейка строк
$message .= "\n" . $json['step0']['question'] . "\n -" . implode(',' , $json['step0']['answers']);

} else{
$response = [
  'status' => 'error',
  'message' => 'что-то пошло не так ...'
];
}
//=======STEP2====================
if($json['step1']['question'] && $json['step1']['answers']){
  //добавляем в строку сообщения
  $message .= "\n" . $json['step1']['question'] . "\n -" . implode(',' , $json['step1']['answers']);

} else{
  $response = [
    'status' => 'error',
    'message' => 'что-то пошло не так ...'
];
}
//=======STEP3====================
if($json['step2']['question'] && $json['step2']['answers']){
    //добавляем в строку сообщения
    $message .= "\n" . $json['step2']['question'] . "\n -" . implode(',' , $json['step2']['answers']);

} else{
  $response = [
    'status' => 'error',
    'message' => 'что-то пошло не так ...'
  ];
}
//=======STEP4====================
if($json['step3']['question'] && $json['step3']['answers']){
      //добавляем в строку сообщения   
      $message .= "\n" . $json['step3']['question'] . "\n -" . implode(',' , $json['step3']['answers']);

} else{
  $response = [
    'status' => 'error',
    'message' => 'что-то пошло не так ...'
  ];
}
//проверка на пустоту поля вопроса или поля ответа
//=======================================================================

$data_empty = false;

foreach($json['step4'] as $item){
if (!$item) $data_empty = true;
}

if(!$data_empty){
  //добавляем в строку сообщения  
 
  $message .= "\n\n" . 'Имя: ' . $json['step4']['name'];
  $message .= "\n" . 'Телефон: ' . $json['step4']['phone'];
  $message .= "\n" . 'Email: ' . $json['step4']['email'];
  $message .= "\n" . 'Способ связи: ' . $json['step4']['call'];

//построить запрос
$my_data = [
'message' => $message
];

  get_data(BASE_URL . TOKEN . "/send?" . http_build_query($my_data));

  // echo BASE_URL . TOKEN . "/send?" . http_build_query($my_data);

  $response = [
    'status' => 'ok',
    'message' => 'Спасибо! Скоро мы с Вами свяжемся!'
    ]; 
} else{

if(!$json['step4']['name']){
$error_message = 'Введите имя';
} else if(!$json['step4']['phone']){
  $error_message = 'Введите номер телефона';
}else if(!$json['step4']['email']){
  $error_message = 'Введите e-mail';
}else if(!$json['step4']['call']){
  $error_message = 'Введите способ связи';
} else{
  $error_message = 'Что-то пошло не так...';
}
 
$response = [
'status' => 'error',
'message' => $error_message
];
}



//отправляем заголовок, должен отправляться первым
header("Content-Type: application/json; charset-utf-8");
echo json_encode($response);