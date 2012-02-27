<?php
require 'bencode.php';

class Response
{
    private $failure_reason;
    private $interval;
    private $complete;
    private $incomplete;
    private $peers;
    
    public function __construct()
    {
        
    }
    
    public function send()
    {
        $response = array();
        
        if ($this->failure_reason) {
            $response['failure reason'] = $this->failure_reason;
            echo Bencode::encode($response);
        } else {
            $response['interval'] = $this->interval;
            $response['complete'] = $this->complete;
            $response['incomplete'] = $this->incomplete;
            $response['peers'] = $this->peers;
            echo Bencode::encode($response);
        }
    }
    
    public function setFailureReason($failure_reason)
    {
        $this->failure_reason = $failure_reason;
    }
    
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }
    
    public function setComplete($complete)
    {
        $this->complete = $complete;
    }
    
    public function setIncomplete($incomplete)
    {
        $this->incomplete = $incomplete;
    }
    
    public function setPeers($peers)
    {
        $this->peers = $peers;
    }
}