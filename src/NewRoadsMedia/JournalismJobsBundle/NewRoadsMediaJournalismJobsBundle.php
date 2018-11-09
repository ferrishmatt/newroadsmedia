<?php

namespace NewRoadsMedia\JournalismJobsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NewRoadsMediaJournalismJobsBundle extends Bundle
{
    public function getParent()
    {
        return 'NewRoadsMediaFrontendBundle';
    }
}