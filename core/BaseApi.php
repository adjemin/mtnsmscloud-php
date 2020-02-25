<?php
/**
 * This class is for for performing Api call.
 * Actually, only POST and GET methods are embedded for an Http request.
 * 
 * @license MIT
 * @author Franck BROU <franckbrou@adjemin.com>
 */
class BaseApi {

    // Base API's url
    public $base_url = "";

    /**
     * Inits the class
     *
     * @param string $base_url
     */
    public function __construct($base_url){
        $this->base_url = $base_url; 
    }    

    /**
     * Send an error message
     *
     * @param int $code
     * @param boolean $success
     * @param string $message
     * 
     */
    public function sendError($code, $success, $message){
        return json_encode(array('code' => $code, 'success' => $success, 'message' => $message));
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
            $url = $this->base_url."/".$endpoint;
            
            // Initialize curl session
            $curl = curl_init("$url");
            // Disable SSL verification
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            // Returns the data/output as a string instead of raw data
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            // Check if request as options
            if (!is_null($options) && is_array($options)) {                
                // Scafolding request's headers
                if (array_key_exists("headers", $options)) {         
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $options["headers"]);
                }                
                // Scafolding a POST request
                if ($httpMethod === "post") {
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                    if (array_key_exists("params", $options)) {                
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options["params"]));
                    }
                }
                // Scafolding a GET request
                elseif ($httpMethod === "get") {
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);                
                    curl_setopt($curl, CURLOPT_TIMEOUT, 80);
                    if (array_key_exists("params", $options)) {
                        $data = http_build_query($options["params"]);
                        $getUrl = $url."?".$data;
                        echo $getUrl;
                        curl_setopt($curl, CURLOPT_URL, $getUrl);
                    }else {
                        curl_setopt($curl, CURLOPT_URL, $url);
                    }               
                }
            }
            // Performing request
            $response = curl_exec($curl);
            // Closing the session
            curl_close($curl);
            // Rendering the response
            if ($response === false) {
                // An error occurs when processing
                echo("Oups, Cannot connect to remote server.");
            } else {
                return $response;
            }     

        }
        catch (Exception $exception){
            return  $exception->getMessage();
        };

    }
}