<?php

namespace app\tests\unit\data_provider;

use app\controller\loginController;
use app\models\ccModel;
use app\models\eventModel;
use app\models\logisticModel;
use app\models\managerModel;

class data_provider
{

    /**
     * @return array
     */
    public static function JSON_endcoded_data() : array {
        return [
            ['test','test'],
            ['',''],
            [[1,2,3],'1,2,3'],
            [['test' =>0 , 1, 2],'\{"test":0,"0":1,"1":2\}'],
        ];
    }

    /**
     * @return array
     */
    public static function random_data() : array {
        return [
            ['test'],
            [''],
            [[1,2,3]],
            [['test' =>0 , 1, 2]],
            [ new logisticModel()]
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return array[]
     */
    public static function random_user_details() : array {
        return [
            ['userID','manager6384af19361382.8','SinethManager','manager'],
            ['userID','logistic6384b4fac135d8','ViranLogistic','logistic'],
            ['userID','donee6384c832a74500.891','OshaniDonee','Donee'],
            ['userID','donor6384c8a0b0a9b2.8','KavinduDonor','donor'],
            ['userID','cho638396a40d1581.06551','ColomboCHO','cho']

        ];
    }

    /**
     * @return array[]
     */
    public static function random_user_details_with_valid_link_to_their_views() : array {
        return [
            ['/CommuSupport/manager/events','manager'],
            ['/CommuSupport/logistic/deliveries','logistic'],
            ['/CommuSupport/donee/requests','donee'],
            ['/CommuSupport/donor/donations','donor'],
            ['/CommuSupport/cho/communitycenters','cho'],
            ['/admin/employees','admin']
        ];
    }

    /**
     * @return array[]
     */
    public static function random_user_details_with_invalid_link_to_their_views() : array {
        return [
            ['/CommuSupport/manager/events','logistic'],
            ['/CommuSupport/logistic/deliveries','donee'],
            ['/CommuSupport/donee/requests','donor'],
            ['/CommuSupport/donor/donations','cho'],
            ['/CommuSupport/cho/communitycenters','manager'],
            ['/admin/employees','logistic']
        ];
    }

    /**
     * @return array
     */
    public static function random_arrays() : array {
        return [
            [[1,2,3]],
            [['test' =>0 , 1, 2]],
            [[ 'hello',true,'gender' => 'male']]
        ];
    }

    /**
     * @return array[]
     */
    public static function test_Valid_data_for_Model() : array {
        // params are in the form [model, array of data]
        return [
            [new eventModel(),[
                                'eventID' => 'event12345678901234567890',
                                'eventCategoryID' => 'eventCategory12345678901234567890',
                                'theme' => 'test',
                                'organizedBy' => 'test',
                                'contact' => 'test',
                                'date' => '2021-01-01',
                                'time' => '12:00',
                                'location' => 'test',
                                'description' => 'test',
                                'status' => 'Upcoming',
                                'participationCount' => 0,
                                'ccID' => 'cc12345678901234567890'
                                ]
            ],
            [new eventModel(),[
                                'requestID' => 'event12345678901234567890',
                                'eventCategoryID' => 'eventCategory12345678901234567890',
                                'theme' => 'test',
                                'organizedBy' => 'test',
                                'contact' => 'test',
                                'completedDate' => '2021-01-01',
                                'time' => '12:00',
                                'location' => 'test',
                                'notes' => 'test',
                                'status' => 'Upcoming',
                                'participationCount' => 0,
                                'ccID' => 'cc12345678901234567890'
                                ]
            ]
        ];
    }

    /**
     * @return array[]
     */
    public static function test_Invalid_data_for_Model() : array {
        // params are in the form [model, array of data]
        return [
            [
                new eventModel(),[
                'eventID' => 'event12345678901234567890',
                'eventCategoryID' => 'eventCategory12345678901234567890',
                'theme' => '',
                'organizedBy' => 'test',
                'contact' => 'test',
                'date' => '2021-01-01',
                'time' => '14fkjseaf2',
                'location' => 'test',
                'description' => 'test',
            ]
            ,false,[
                'theme' => ['This field is required'],
                'contact' => ['This field must be a valid contact number'],
                'date' => ['This field must be a future date'],
            ] ],
            [
                new eventModel(),[
                'eventID' => 'event12345678901234567890',
                'eventCategoryID' => 'eventCategory12345678901234567890',
                'theme' => 'test',
                'organizedBy' => 'test',
                'contact' => '0714790994',
                'date' => '2024-01-01',
                'time' => '12:00',
                'location' => 'test',
                'description' => 'test',
            ]
            ,true,[]]
        ];
    }

    public static function test_Valid_data_for_reset() : array {
        // params are in the form [model, array of data]
        return [
            [new eventModel(),[
                'eventID' => 'event12345678901234567890',
                'eventCategoryID' => 'eventCategory12345678901234567890',
                'theme' => 'test',
                'organizedBy' => 'test',
                'contact' => 'test',
                'date' => '2021-01-01',
                'time' => '12:00',
                'location' => 'test',
                'description' => 'test',
                'status' => 'Upcoming',
                'participationCount' => 0,
                'ccID' => 'cc12345678901234567890'
            ]
            ],
            [new eventModel(),[
                'requestID' => 'event12345678901234567890',
                'eventCategoryID' => 'eventCategory12345678901234567890',
                'theme' => 'test',
                'organizedBy' => 'test',
                'contact' => 'test',
                'date' => '2021-01-01',
                'time' => '12:00',
                'location' => 'test',
                'description' => 'test',
                'status' => 'Upcoming',
                'participationCount' => 0,
                'ccID' => 'cc12345678901234567890'
            ]
            ]
        ];
    }

}