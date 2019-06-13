<?php

namespace ProcessMaker;

use ProcessMaker\Models\Process;
use ProcessMaker\Models\ProcessRequest;
use ProcessMaker\Models\ProcessRequestToken;
use ProcessMaker\Models\User;

class PmqlHelper {
    private $type;
    
    private $statusMap = [
        'In Progress' => 'ACTIVE',
        'Completed' => 'COMPLETED',
        'Error' => 'ERROR',
        'Canceled' => 'CANCELED',
    ];

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function aliases()
    {
        return function($expression) {
            $field = $expression->field->field();
            $method_name = $this->type . ucfirst($field);

            if (method_exists($this, $method_name)) {
                $value = $expression->value->value();
                return $this->$method_name($value);
            }
        };
    }

    private function requestRequest($value)
    {
        return function($query) use ($value) {
            $processes = Process::where('name', $value)->get();
            $query->whereIn('process_id', $processes->pluck('id'));
        };
    }

    private function requestStatus($value)
    {
        return function($query) use ($value) {
            if (array_key_exists($value, $this->statusMap)) {
                $query->where('status', $this->statusMap[$value]);
            } else {
                $query->where('status', $value);
            }
        };
    }

    private function requestRequester($value)
    {
        $user = User::where('username', $value)->get()->first();
        $requests = ProcessRequest::where('user_id', $user->id)->get();

        return function($query) use ($requests) {
            $query->whereIn('id', $requests->pluck('id'));
        };
    }
    
    private function requestParticipant($value)
    {
        $user = User::where('username', $value)->get()->first();
        $tokens = ProcessRequestToken::where('user_id', $user->id)->get();

        return function($query) use ($tokens) {
            $query->whereIn('id', $tokens->pluck('process_request_id'));
        };
    }
}