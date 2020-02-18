<?php

class BaseSMSApi {

    public $base_url = "";

    public function __construct($base_url){
        $this->base_url = $base_url; 
    }    

    /**
     * Call GET request
     * @param string $endpoint
     * @param string $options
     */
    public function get($endpoint, $options = null) {
        return $this->apiCall("get", $endpoint, $options);
    }

    /**
     * Call POST request
     * @param string $endpoint
     * @param string $options
     */
    public function post($endpoint, $options = null) {
        return $this->apiCall("post", $endpoint, $options);
    }

    /**
     * Create API query and execute a GET/POST request
     * @param string $httpMethod GET/POST
     * @param string $endpoint
     * @param string $options
     */
    public function apiCall($httpMethod, $endpoint, $options) {

        try{
            $url = $this->base_url;

            $curl = curl_init("$url");
            header("Content-Type: application/json; charset=UTF-8");
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if (!is_null($options)) {
                if (array_key_exists("headers", $options)) {
                    curl_setopt($curl, CURLOPT_HEADER, true);          
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $options["headers"]);
                }
    
                if ($httpMethod === "post") {
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                    if (array_key_exists("params", $options)) {                
                        if (array_key_exists("body", $options)) {
                            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array_merge($options["params"], $options["body"])));
                        }
                        else{
                            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options["params"]));
                        }
                    }
                } 
                elseif ($httpMethod === "get") {
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);                
                    curl_setopt($curl, CURLOPT_TIMEOUT, 80);
                    if (array_key_exists("params", $options)) {
                        $data = http_build_query($dataArray);
                        $getUrl = $url."?".$data;
                        curl_setopt($curl, CURLOPT_URL, $getUrl);
                    }                
                }
            }

            $response = curl_exec($curl);
            curl_close($curl);
            if ($response === false) {
                echo("Cannot resolve that host.");
            } else {
                echo json_encode($response);
            }         
            die();

        }catch (Exception $exception){
            return  $exception->getMessage();
        };

    }
}