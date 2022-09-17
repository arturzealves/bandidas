<?php

namespace App\Gamify;

use Exception;

trait HasLevelSystem
{
    public function level()
    {
        if (!config('gamify.enable_level_system', false)) {
            throw new Exception('The config gamify.enable_level_system must be enabled');
        }

        $levels = config('gamify.level_system');
        foreach ($levels as $level => $experience) {
            if ($this->reputation < $experience) {
                return $level;
            }
        }

        return array_key_last($levels);
    }
}
