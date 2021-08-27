<?php

namespace App\Logger;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;

class RequestProcessor
{
    protected $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    public function processRecord(array $record)
    {
        $req = $this->request->getCurrentRequest();
        if ($req !== null) {
            $record['extra']['client_ip']       = $req->getClientIp();
            $record['extra']['client_port']     = $req->getPort();
            $record['extra']['uri']             = $req->getUri();
            $record['extra']['query_string']    = $req->getQueryString();
            $record['extra']['method']          = $req->getMethod();
            $record['extra']['request']         = $req->request->all();

            // Add session token
            try {
                $session = $this->request->getSession();
            } catch (SessionNotFoundException $e) {
                return;
            }
            
            if (!$session->isStarted()) {
                $record['extra']['token'] = null;
                return $record;
            }

            $sessionId = substr($session->getId(), 0, 8) ?: '????????';
            $record['extra']['token'] = $sessionId.'-'.substr(uniqid('', true), -8);
        }

        return $record;
    }
}