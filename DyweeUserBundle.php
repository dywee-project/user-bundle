<?php

namespace Dywee\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DyweeUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
