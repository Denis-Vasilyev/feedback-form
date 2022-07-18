<?php

class CFormProcessor
{
    private $request;
    private $fileName;

    const YOUR_RECAPTCHA_SECRET_KEY = "6LeRj_sgAAAAAEFTi9oC3aoy7BkT5ghBPXSaXtJi";
    const RECAPTCHA_URL = "https://www.google.com/recaptcha/api/siteverify";

    function __construct($request, $fileName = "logform.txt")
    {
        $this->request = $request;
        $this->fileName = $fileName;
    }

    public function run()
    {
        $isAjaxRequestContex = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

        //допускаем только ajax-запросы - защита от спама
        if (!$isAjaxRequestContex) {
            throw new Exception("Допустимы только ajax-запросы.");
        }

        //Проверка на спам (google recaptcha)
        $recaptchaResponse = $this->request["RECAPTCHA_RESPONSE"];

        if (empty($this->request["RECAPTCHA_RESPONSE"])) {
            throw new Exception("Ошибка при проверке на спам.");
        }

        $recaptcha = file_get_contents(self::RECAPTCHA_URL . '?secret=' . self::YOUR_RECAPTCHA_SECRET_KEY . '&response=' . $recaptchaResponse);
        $recaptcha = json_decode($recaptcha);

        if (!($recaptcha->score >= 0.5)) {
            throw new Exception("Не пройдена проверка на спам.");
        }

        $fieldErrorlist = [];

        preg_match("/^[a-zA-Zа-яА-Я\- ]{1,25}$/", $this->request["FIO"])
            ? $fio = $this->request["FIO"]
            : $fieldErrorlist[] = "FIO";
        preg_match("/^\+?\d{1,3}?[- ]?\(?(?:\d{2,3})\)?[- ]?\d{1,4}[- ]?\d{1,4}[- ]?\d{1,4}$/", $this->request["PHONE"])
            ? $phone = $this->request["PHONE"]
            : $fieldErrorlist[] = "PHONE";
        !empty($this->request["ADRESS"])
            ? $adress = htmlspecialchars($this->request["ADRESS"])
            : $fieldErrorlist[] = "ADRESS";
        !empty($this->request["ADRESS2"])
            ? $adress2 = htmlspecialchars($this->request["ADRESS2"])
            : $fieldErrorlist[] = "ADRESS2";

        //$fieldErrorlist = ["FIO", "PHONE", "ADRESS", "ADRESS2"];

        if (count($fieldErrorlist)) {
            return ["success" => false, "errorData" => $fieldErrorlist];
        }

        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = @$_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $senderIp = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $senderIp = $forward;
        } else {
            $senderIp = $remote;
        }

        $data = [
            "FIO" => $fio,
            "PHONE" => $phone,
            "ADRESS" => $adress,
            "ADRESS2" => $adress2,
            "SENDER_IP" => $senderIp
        ];

        $this->writeFormDataToFile($data);

        return ["success" => true];
    }

    private function writeFormDataToFile($data)
    {
        $file = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . $this->fileName;
        $mes = "[" . date("Y.m.d H:i:s") . "]";

        foreach ($data as $key => $val)
        {
            $mes .= " $key => $val |";
        }

        $mes = substr_replace($mes,";", strlen($mes) - 1);

        $mes .= PHP_EOL;

        file_put_contents($file, $mes, FILE_APPEND);
    }
}

try
{
    $fp = new CFormProcessor($_REQUEST);
    $res = $fp->run();
    echo json_encode($res);
}
catch (Exception $e)
{
    echo $e->getMessage();
}