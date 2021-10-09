<?php

namespace Combindma\SendinBlueTracker;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Traits\Macroable;

class SendinBlueTracker
{
    use Macroable;

    protected bool $enabled;
    protected $trackerId;
    protected $sessionKey;
    protected $baseUri;

    public function __construct()
    {
        $this->enabled = config('sendinblue-tracker.enabled');
        $this->trackerId = (config('sendinblue-tracker.tracker_id'));
        $this->sessionKey = config('sendinblue-tracker.sessionKey');
        $this->baseUri = 'https://in-automate.sendinblue.com/api/v2/';
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function trackerId()
    {
        return $this->trackerId;
    }

    public function baseUri()
    {
        return $this->baseUri;
    }

    public function sessionKey()
    {
        return $this->sessionKey;
    }

    public function getEmail()
    {
        if (session()->has($this->sessionKey().'.email')) {
            return session()->get($this->sessionKey().'.email');
        }

        return null;
    }

    public function getEvent()
    {
        if (session()->has($this->sessionKey().'.event')) {
            return session()->get($this->sessionKey().'.event');
        }

        return null;
    }

    public function setEvent($eventName, array $eventData = [], array $userData = []): string
    {
        $event = "sendinblue.track('".$eventName."'";
        if (! empty($userData)) {
            $event .= ','.json_encode($userData);
        }
        if (! empty($eventData)) {
            $event .= ",".json_encode($eventData);
        }
        $event .= ");";

        return $event;
    }

    public function identify(string $email)
    {
        session()->put($this->sessionKey().'.email', $email);
    }

    public function event(string $eventName, array $eventData = [], array $userData = [])
    {
        session()->now($this->sessionKey().'.event', $this->setEvent($eventName, $eventData, $userData));
    }

    public function flash(string $eventName, array $eventData = [], array $userData = [])
    {
        session()->flash($this->sessionKey().'.event', $this->setEvent($eventName, $eventData, $userData));
    }

    public function identifyPost($email, array $userData = [])
    {
        $data = [
            'email' => $email,
        ];
        if (! empty($userData)) {
            $data['attributes'] = $userData;
        }

        return $this->sendRequest($data, 'POST', 'identify');
    }

    public function eventPost(string $email, string $eventName, array $eventData = [], array $userData = [])
    {
        $data = [
            'email' => $email,
            'event' => $eventName,
        ];
        if (! empty($userData)) {
            $data['properties'] = $userData;
        }
        if (! empty($eventData)) {
            $data['eventdata'] = $eventData;
        }

        return $this->sendRequest($data, 'POST', 'trackEvent');
    }

    protected function sendRequest($data, $type, $action)
    {
        try {
            if (! $this->isEnabled() or empty($this->trackerId())) {
                return null;
            }
            $client = new Client();
            $body = (string)$client->request($type, $this->baseUri() . $action, [
                'body' => json_encode($data),
                'headers' => [
                    'ma-key' => $this->trackerId(),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ])->getBody();

            return json_decode($body, true);
        } catch (GuzzleException | Exception $e) {
            if ($e->getCode() !== 404) {
                Log::error($e);
            }

            return null;
        }
    }
}
