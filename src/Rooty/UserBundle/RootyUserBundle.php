<?php

namespace Rooty\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RootyUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}