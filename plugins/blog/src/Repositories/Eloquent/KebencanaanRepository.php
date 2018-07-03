<?php

namespace Botble\Blog\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Blog\Models\Kebencanaan;
use Botble\Blog\Repositories\Interfaces\KebencanaanInterface;

class KebencanaanRepository extends RepositoriesAbstract implements KebencanaanInterface
{
    /**
     * @param $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
	 
	public function getDefinisiBencana()
	{
        $data = Kebencanaan::where('type', 'definisi')->first();
        $this->resetModel();
        return $data;
    }	

    public function getPotensiBencana()
	{
		$data = Kebencanaan::where('type', 'potensi')->first();
        $this->resetModel();
        return $data;
    }	

    public function getPenanggulanganBencana()
	{
		$data = Kebencanaan::where('type', 'penanggulangan')->first();
        $this->resetModel();
        return $data;
    }	
}
