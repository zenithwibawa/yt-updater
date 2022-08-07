<?php

class Telegram {

    private $_url;
    private $_chat_id;

    public function __construct($url, $chat_id){
        $this->_url = $url;
        $this->_chat_id = $chat_id;
    }
    
    public function sendMessage($text){

        $ch = curl_init($this->_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'chat_id' => $this->_chat_id,
            'text' => $text,
            'parse_mode' => 'html'
        ]);
        curl_exec($ch);

        if (curl_errno($ch))
            $response = curl_error($ch);

        curl_close($ch);

        if (isset($response))
            return $response;
        else
            return true;

    }

}