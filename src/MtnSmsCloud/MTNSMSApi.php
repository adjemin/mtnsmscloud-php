<?php

namespace MtnSmsCloud;

use MtnSmsCloud\Exception\MtnSmsCloudException;

/**
 * This class is for for performing Api call on MTN SMS CLOUD server.
 * You can use it for:
 *  - Create new sms campaign
 *  - Retrieves a campaign
 *
 * @license MIT
 * @author Franck BROU <franckbrou@adjemin.com>
 */
class MTNSMSApi extends BaseApi
{
    /**
     * Define The base URL
     *
     * @var string
     */
    private $base_api_url = "https://api.smscloud.ci/v1";

    /**
     * Define sender ID
     *
     * @var string
     */
    private $sender_id;

    /**
     * Define the Bearer Token
     *
     * @var string
     */
    private $auth_header;

    /**
     * MTNSMSApi constructor
     *
     * @param string $sender_id
     * @param string $auth_header
     * @return void
     */
    public function __construct($sender_id, $auth_header)
    {
        parent::__construct($this->getBaseApiUrl());
        $this->setSenderID($sender_id);
        $this->setAuthHeader($auth_header);
    }

    /**
     * Set the Sender ID property
     *
     * @param string $sender_id
     * @return void
     */
    public function setSenderID($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    /**
     * Set the Authorization Bearer token
     *
     * @param string $auth
     * @return void
     */
    public function setAuthHeader($auth_header)
    {
        $this->auth_header = $auth_header;
    }

    /**
     * Return the base api url property
     *
     * @return string
     */
    public function getBaseApiUrl()
    {
        return $this->base_api_url;
    }

    /**
     * Returns the provided sender ID
     *
     * @return string
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
        return [
            'sender_id' => $this->getSenderID(),
            'auth_header' => $this->getAuthHeader()
        ];
    }

    /**
     * Send a new SMS Campaign
     *
     * @param array $recipients
     * @param integer $content
     * @param array $body
     * @return mixed
     */
    public function newCampaign(array $recipients, $content)
    {
        if (count($recipients) == 0) {
            throw new MtnSmsCloudException("No phone number provided.", 400);
        }
        
        // Scafolding the request's params
        $params = [
            "sender" => $this->getSenderID(),
            "recipients" => $recipients,
            "content" => $content
        ];

        // Scafolding request's options
        $options = [
            'headers'=> [
                'Authorization: Bearer '.$this->getAuthHeader(),
                'Accept: application/json',
                'Content-Type: application/json',
                'Cache-Control: no-cache'
            ],
            'params' => $params
        ];

        // Sending POST Request
        return $this->post('campaigns', $options);
    }

    /**
     * Get a Campaign
     *
     * @param string $campaign_id
     * @return mixed
     */
    public function getCampaign($campaign_id)
    {
        if (is_null($campaign_id) || $campaign_id == "") {
            return new MtnSmsCloudException("No campaign ID provided.", 400);
        }

        // Scafolding request's options
        $options = [
            'headers'=> [
                'Authorization: Bearer '.$this->getAuthHeader(),
                'Accept: application/json',
                'Content-Type: application/json',
                'Cache-Control: no-cache'
            ]
        ];

        // Sending Get Request
        return $this->get('campaigns/'.$campaign_id, $options);
    }

    /**
     * Retrieves all messages associated to the provided authentification Bearer token
     *
     * @param string $campaign_id
     * @param string $status
     * @param array $params
     * @return mixed
     */
    public function getMessages($campaign_id, $status, $params = [])
    {
        if (is_null($campaign_id) || $campaign_id == "") {
            return new MtnSmsCloudException("No campaign ID provided.", 400);
        }

        $default_params = [
            'dispatchedAt_before' => null,
            'dispatchedAt_after' => null,
            'updatedAt_before' => null,
            'updatedAt_after' => null,
            'page' => 1,
            'length' => 2
        ];

        foreach ($default_params as $key => $value) {
            if (!is_isset($params[$key])) {
                $params[$key] = $value;
            }
        }

        $params['campaingId'] = $campaign_id;
        $params['status'] = $status;
        $params['sender'] = $this->getSenderID();

        $options = [
            'headers'=> [
                'Authorization: Bearer '.$this->getAuthHeader(),
                'Accept: application/json',
                'Content-Type: application/json',
                'Cache-Control: no-cache'
            ],
            'params' => $params,
        ];

        // Sending Get Request
        return $this->get('messages/outbox', $options);
    }
}
