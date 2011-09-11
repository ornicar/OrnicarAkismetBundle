<?php

namespace Ornicar\AkismetBundle\Adapter;

interface AkismetAdapterInterface
{
    /**
     * Tells if the data looks like spam
     *
     * @param array $data
     * @return boolean
     */
    function isSpam(array $data);
}
