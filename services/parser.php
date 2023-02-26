<?php
require_once '../boot.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://www.cbr.ru/scripts/XML_daily.asp?date_req=".date("d/m/Y"));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);


$result = curl_exec($ch);
$xml_obj = new SimpleXMLElement($result);

foreach ($xml_obj->Valute as $currency) {
    $num_code = (int)$currency->NumCode;
    $char_code = (string)$currency->CharCode;
    $nominal = (int)$currency->Nominal;
    $name = (string)$currency->Name;
    $value = (float)str_replace(',', '.', $currency->Value);

    $query = "INSERT INTO currency (NumCode, CharCode, Nominal, Name, Value) 
          VALUES (:num_code, :char_code, :nominal, :name, :value)";
    $stmt = pdo()->prepare($query);
    $stmt->bindParam(':num_code', $num_code, PDO::PARAM_INT);
    $stmt->bindParam(':char_code', $char_code, PDO::PARAM_STR);
    $stmt->bindParam(':nominal', $nominal, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);

    $stmt->execute();
}

?>



