<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

/**
 * @codeCoverageIgnore
 */
abstract class Event
{
    use SerializesModels;
}
