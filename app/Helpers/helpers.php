<?php

use App\Models\Log as CustomLog;

if (! function_exists('addLog')) {
    function addLog(String $action, String $description, String $platform="dashboard") {
        return CustomLog::create([
            "action" => $action,
            "description" => $description,
            "platform" => $platform,
            "user_id" => Auth::user()->id
        ]);
    }
}