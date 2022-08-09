<?php

class Notification
{
    private $title;
    private $body;
    private $url;
    public $result;
    
    function __construct($title="", $body="", $url = "")
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
        
    }
    function sendNotification()
    {
        if ($this->checkLast()){
            $this->send();
            $this->updateJson();
        }
    }
    public function checkLast()
    {
        if (file_exists("last_sent.json")){
            $json = json_decode(file_get_contents("last_sent.json"));
            return $json->last_sent + (24 * 60 * 60)  < time();
            //return $json->last_sent + (10*60)  < time();
        } else {
            return true;
        }
    }
    private function updateJson()
    {
        file_put_contents("last_sent.json", json_encode([
            "last_sent" => time()
        ]));
        
    }
    private function send()
    {
        $ch = \curl_init();

        $headers = array(
            'Content-type:application/json',
            'Authorization:Basic MmQ1NjEyZmItMTEzOC00ZmUzLTg5NTktYzNjMTM3YzY0M2Vl'
        );


        $data = [
            "app_id" => "68e02778-65e5-4785-a0a7-f893de596849",
            "headings" =>  [
                "en" => $this->title
            ],
            "contents" => [
                "en" => $this->body
            ],
            "included_segments" =>  ["Subscribed Users"],
            "big_picture" => $this->url

        ];

        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        $this->result = $server_output;

        curl_close($ch);
    }
}
$data = json_decode(file_get_contents('php://input'), true);
// $notify = (new Notification($data["title"], $data["body"]))->sendNotification();

// $notify = (new Notification("בדיקה","תמונה",null))->sendNotification();
if($data["title"])
{
    $notify = new Notification($data["title"], $data["body"], $data["url"]);
    $notify->sendNotification();
    echo $notify->result;

}else{
    $answer = (new Notification())->checkLast();
    echo $answer ? "1" : "0";
}