<?php

namespace App\Plugins\Admitad;

use App\Helper\Static\Vars;
use App\Models\Mix;

class PromotionalMethod extends Base
{
    public function callApi($offset)
    {
        $methods = $this->sendAdmitadPromotionalMethodRequest(intval($offset));

        foreach ($methods['results'] as $method) {
            $this->storeData($method);
        }

        $this->changeJobTime();
    }

    private function storeData($method): void
    {
        $var = $this->getAdmitadPromotionStaticVar();

        Mix::updateOrCreate([
            'external_id' => $method['id'],
            'source'      => $var['source'],
            'type'        => $var['type']
        ],[
            'name'        => $method['name'],
            "created_by"  => $var['source'],
            "updated_by"  => $var['source']
        ]);
    }
}
