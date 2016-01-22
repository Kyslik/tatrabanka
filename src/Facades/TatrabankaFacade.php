<?php

namespace SudoAgency\TatraBanka\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class TatrabankaFacade extends IlluminateFacade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
    	return 'tatrabanka';
    }

}
