<?php

namespace Combindma\SendinBlueTracker;

use Exception;
use Illuminate\View\View;

class ScriptViewCreator
{
    protected $sendinBlueTracker;

    public function __construct(SendinBlueTracker $sendinBlueTracker)
    {
        $this->sendinBlueTracker = $sendinBlueTracker;
    }

    public function create(View $view)
    {
        if ($this->sendinBlueTracker->isEnabled() && empty($this->sendinBlueTracker->trackerId())) {
            throw new Exception('You need to set a tracker Id');
        }

        $view
            ->with('enabled', $this->sendinBlueTracker->isEnabled())
            ->with('trackerId', $this->sendinBlueTracker->trackerId())
            ->with('email', $this->sendinBlueTracker->getEmail())
            ->with('event', $this->sendinBlueTracker->getEvent());
    }
}
