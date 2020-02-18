<?php

require_once "./core/BaseApi.php";

class MTNSMSApi extends BaseApi{

    /**
     * The base URL for the MTN SMS Cloud Api
     */
    private $base_api_url = "https://api.smscloud.ci/v1/";
    //private $base_api_url = "https://api.adjemin.com/v2/customers";

    /**
     * The SENDER ID of an existing sender
     *
     * @var string $sender_id
     */
    private $sender_id;

    /**
     * The Bearer Token of an existing MTN SMS Cloud account 
     *
     * @var string $auth_header
     */
    private $auth_header;

    /**
     * Initializinz the Class
     * 
     * @param string $sender_id
     * @param string $auth_header
     */
    public function __construct($sender_id, $auth_header){
        parent::__construct($this->getBaseApiUrl());
        $this->setSenderID($sender_id);
        $this->setAuthHeader($auth_header);
    }

    /**
     * Set the Sender ID property
     * 
     * @param string $var
     * 
     * @return void
     */
    public function setSenderID($var)
    {
        $this->sender_id = $var;
    }

    /**
     * Set the Authorization Bearer token
     * 
     * @param string $var
     * 
     * @return void
     */
    public function setAuthHeader($var)
    {
        $this->auth_header = $var;
    }

    /**
     * Return the base api url property
     *
     * @return void
     */
    public function getBaseApiUrl()
    {
        return $this->base_api_url;
    }
    /**
     * Returns the provided sender ID
     *
     * @return string
     * 
     * @return void
     */
    public function getSenderID()
    {
        return $this->sender_id;
    }

    /**
     * Return the Athorization Bearer Token
     *
     * @return string
     * 
     * @return void
     */
    public function getAuthHeader()
    {
        return $this->auth_header;
    }

    /**
     * Return an array representation
     *
     * @return array
     */
    public function toArray()
    {
        return ['sender_id' => $this->getSenderID(), 'auth_header' => $this->getAuthHeader()];
    }

    /**
     * Send a new SMS Campaign
     *
     * @param array $recipients
     * @param integer $content
     * @param array $body
     *
     * @return Request
     */
    public function newCampaing($recipients, $content, $body)
    {
        // Scafolding the request's body
        $b = [
            "sender"=> $this->getSenderID(),
            "recipients"=> $recipients,
            "content"=> $content
        ];

        // Merging the request's body with a optional one
        $b = array_merge($b, $body);
        $b = json_encode($b);

        // Scafolding request's options
        $options = [
            'headers'=> [
                'Authorization' => 'Bearer '.$this->getAuthHeader().'',
                'Accept'=>'application/json',
                'Content-Type'=>'application/json',
                'Cache-Control'=>'no-cache'
            ],
            'body' => $b
        ];
        // Sending POST Request
        return $this->post('campaigns', $options);
    }

    /**
     * Get a Campaign
     *
     * @param string $campaign_id
     *
     * @return Request
     */
    public function getCampaign($campaign_id)
    {
        if (is_null($campaign_id) || $campaign_id == "") {
            return response()->json(['success' => false, 'message'=> "Please, provide a compaign id."], 400);
        }
        // Scafolding request's options
        $options = [
            'headers'=> [
                'Authorization' => 'Bearer '.$this->getAuthHeader().'',
                'Accept'=>'application/json',
                'Content-Type'=>'application/json',
                'Cache-Control'=>'no-cache'
            ]
        ];
        // Sending Get Request
        $this->get('campaigns/'.$campaign_id, $options);
    }

    /**
     * Retrieves all messages associated to the provided authentification Bearer token
     * 
     * @param string $status,
     * @param string $campaign_id,
     * @param string $dispatchedAt_before { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param string $dispatchedAt_after { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param string $updatedAt_before { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param string $updatedAt_after { (Datetime) 2020-02-14 14:14:00 => 20200214141400}
     * @param int $page { Page number }
     * @param int $length { Nomber of messages per pages }
     * 
     * @return Request
     * 
     */
    public function getMessages($status, 
                                $campaign_id, 
                                $dispatchedAt_before, 
                                $dispatchedAt_after, 
                                $updatedAt_before,
                                $updatedAt_after,
                                $page,
                                $length)
    {
        $options = [
            'headers'=> [
                'Authorization' => 'Bearer '.$this->getAuthHeader(),
                'Accept'=>'application/json',
                'Content-Type'=>'application/json',
                'Cache-Control'=>'no-cache'
            ],
            'params' => [
                'sender' => $this->getSenderID(),
                'status' => '',
                'campaingId' => '',
                'dispatchedAt_before' => '',
                'dispatchedAt_after' => '',
                'updatedAt_before' => '',
                'updatedAt_after' => '',
                'page' => '',
                'length' => '',
            ],
        ];

        // Sending Get Request
        $this->get('messages/outbox', $options);
    }
}