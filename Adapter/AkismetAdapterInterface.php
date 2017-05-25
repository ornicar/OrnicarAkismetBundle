<?php

namespace Ornicar\AkismetBundle\Adapter;

interface AkismetAdapterInterface
{
    /**
     * Tells if the data looks like spam
     */
    function isSpam(array $data): bool;

    /**
     * This call is for submitting comments that weren’t marked as spam but should have been.
     */
    function submitSpam(array $data);
}
