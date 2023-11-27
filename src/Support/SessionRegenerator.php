<?php

namespace Support;

use App\Events\AfterSessionRegenerated;
use Closure;

class SessionRegenerator
{
    public static function run(Closure $callback = null): void
    {
        $old = request()->session()->getId();

        // TODO: Fix auth

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        if (!is_null($callback)) {
            $callback();
        }

        event(
            new AfterSessionRegenerated(
                $old,
                request()->session()->getId()
            )
        );
    }
}
