<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    
    public function getCacheDir(): string
    {
        if (in_array($this->environment, ['dev', 'test'])) {
            return '/tmp/cache/' .  $this->environment;
        }
        
        return parent::getCacheDir();
    }
    
    public function getLogDir(): string
    {
        if (in_array($this->environment, ['dev', 'test'])) {
            return '/var/log/symfony/logs';
        }
        
        return parent::getLogDir();
    }
}
