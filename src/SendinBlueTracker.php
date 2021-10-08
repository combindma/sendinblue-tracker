<?php

namespace Combindma\SendinBlueTracker;

class SendinBlueTracker
{
    protected bool $enabled;
    protected string $trackerId;
    protected string $sessionKey;

    public function __construct()
    {
        $this->enabled = config('sendinblue-tracker.enabled');
        $this->trackerId = (config('sendinblue-tracker.tracker_id'));
        $this->sessionKey = config('sendinblue-tracker.sessionKey');
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function trackerId()
    {
        return $this->trackerId;
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

    public function identify(string $email)
    {
        session()->put($this->sessionKey().'.email', $email);
    }

    public function event($eventName, array $userData = [], array $eventData = [])
    {
        $event = "sendinblue.track('".$eventName."'";
        if (! empty($userData)) {
            $event .= ','.json_encode($userData);
        }
        if (! empty($eventData)) {
            $event .= ",{'event data':".json_encode($eventData)."}";
        }
        $event .= ");";
        session()->now($this->sessionKey().'.event', $event);
    }

    public function flash($eventName, array $userData = [], array $eventData = [])
    {
        $event = "sendinblue.track('".$eventName."'";
        if (! empty($userData)) {
            $event .= ','.json_encode($userData);
        }
        if (! empty($eventData)) {
            $event .= ",{'event data':".json_encode($eventData)."}";
        }
        $event .= ");";
        session()->flash($this->sessionKey().'.event', $event);
    }
}
