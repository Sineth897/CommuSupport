<?php

namespace app\tests\unit\data_provider;

use app\controller\loginController;
use app\models\logisticModel;

class data_provider
{

    public static function JSON_endcoded_data() : array {
        return [
            ['test','test'],
            ['',''],
            [[1,2,3],'1,2,3'],
            [['test' =>0 , 1, 2],'\{"test":0,"0":1,"1":2\}'],
        ];
    }

    public static function random_data() : array {
        return [
            ['test'],
            [''],
            [[1,2,3]],
            [['test' =>0 , 1, 2]],
            [ new logisticModel()]
        ];
    }

    public static function random_key_value_pair() : array {
        return [
            [ 'testString' , 'This is a test string' ],
            [ 'testInt', 252 ],
            [ 'testArray' , [1,2,3] ],
            [ 'testAssociativeArray', ['test' =>0 , 1, 2] ],
            [ 'testBool', true ],
            [ 'testObject', new logisticModel() ],
        ];
    }

    public static function random_user_details() : array {
        return [
            ['userID','manager6384af19361382.8','SinethManager','manager'],
            ['userID','logistic6384b4fac135d8','ViranLogistic','logistic'],
            ['userID','donee6384c832a74500.891','OshaniDonee','Donee'],
            ['userID','donor6384c8a0b0a9b2.8','KavinduDonor','donor'],
            ['userID','cho638396a40d1581.06551','ColomboCHO','cho']

        ];
    }

}