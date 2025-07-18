<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;

trait Base
{
    public function prepareProviderDataFetching($data, $moduleName, $moduleStartTxt, $moduleEndTxt, $count = 1)
    {
        if($data)
        {

            if($data->name == Vars::ADMITAD)
            {
                Methods::customAdmitad($moduleName, $moduleStartTxt);
                $this->handleAdmitad($count);
                Methods::customAdmitad(null, $moduleEndTxt);
            }
            elseif ($data->name == Vars::AWIN)
            {
                Methods::customAwin($moduleName, $moduleStartTxt);
                $this->handleAwin($count);
                Methods::customAwin(null, $moduleEndTxt);
            }
            elseif ($data->name == Vars::IMPACT_RADIUS)
            {
                Methods::customImpactRadius($moduleName, $moduleStartTxt);
                $this->handleImpactRadius($count);
                Methods::customImpactRadius(null, $moduleEndTxt);
            }
            elseif ($data->name == Vars::RAKUTEN)
            {
                Methods::customRakuten($moduleName, $moduleStartTxt);
                $this->handleRakuten($count);
                Methods::customRakuten(null, $moduleEndTxt);
            }
            elseif ($data->name == Vars::TRADEDOUBLER)
            {
                Methods::customTradedoubler($moduleName, $moduleStartTxt);
                $this->handleTradedoubler($count);
                Methods::customTradedoubler(null, $moduleEndTxt);
            }
        }

    }

}
