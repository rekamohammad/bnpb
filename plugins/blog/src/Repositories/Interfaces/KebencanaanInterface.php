<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface KebencanaanInterface extends RepositoryInterface
{
	
    public function getDefinisiBencana();
    
    public function getPotensiBencana();

    public function getPenanggulanganBencana();

    public function getAnnouncementBencana();
}
