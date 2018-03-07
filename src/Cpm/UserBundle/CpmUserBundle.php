<?php

namespace Cpm\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CpmUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
