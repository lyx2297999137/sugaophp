<?php

namespace App\Api\Controller;
use sugaophp\Api\Restful;
//http://www.sugaophp12.com/?module=api&controller=restful&action=index
//http://www.sugaophp12.com/?module=api&controller=annotate&action=index&username=123456
//http://www.sugaophp12.com/restful/index
class RestfulController {

    function index(){
        $resuful = new Restful();
        $resuful->run();
    }

}
