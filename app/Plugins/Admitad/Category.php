<?php

namespace App\Plugins\Admitad;

use App\Helper\Static\Vars;
use App\Models\Mix;

class Category extends Base
{
    public function callApi($offset)
    {
        $categories = $this->sendAdmitadCategoryRequest(intval($offset));

        foreach ($categories['results'] as $category) {
            $this->storeData($category);
        }

        $this->changeJobTime();
    }

    private function storeData($category)
    {
        $var = $this->getAdmitadCategoryStaticVar();
        $mix = Mix::select('id')->where('type', $var['type'])->where('external_id', $category['parent'])->first();

        Mix::updateOrCreate([
            'external_id' => $category['id'],
            'source'      => $var['source'],
            'type'        => $var['type'],
        ],[
            'name'        => $category['name'],
            'parent_id'   => $mix->id ?? null,
            "created_by"  => $var['source'],
            "updated_by"  => $var['source']
        ]);
    }
}
