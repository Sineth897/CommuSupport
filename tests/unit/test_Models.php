<?php

namespace app\tests\unit;

use app\models\eventModel;
use app\models\userModel;
use PHPUnit\Framework\TestCase;

class test_Models extends TestCase
{

    // here we will be using a subclass of DbModel to test the functions of the parent class  and itself

    /**
     * @param mixed $model
     * @param array $data
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::test_Valid_data_for_Model()
     */
    public function test_model_getData_function(mixed $model, array $data) : void {

        // calling the function
        $model->getData($data);

        // assert whether each exiting property has been assigned
        foreach ($data as $key => $value) {


            // if property exists assert whether the provided data and the property value are same
            if( property_exists($model, $key))
                $this->assertSame($value, $model->{$key});

            // if property does not exist assert whether the provided data and the property value are not same
            else
                $this->assertObjectNotHasProperty($key, $model);

        }

    }

    /**
     * @return void
     */
    public function test_addError_function() : void {

        $model = new userModel();

        // simply add an error
        $model->addError('test', 'test error');

        // assert whether the error has been added
        $this->assertArrayHasKey('test', $model->errors);
        $this->assertSame('test error', $model->errors['test'][0]);
    }

    /**
     * @param mixed $model
     * @param array $data
     * @param bool $returnValue
     * @param array $expectedErrors
     * @return void
     * @depends test_model_getData_function
     * @depends test_addError_function
     * @dataProvider \app\tests\unit\data_provider\data_provider::test_Invalid_data_for_Model()
     */
    public function test_validate_function(mixed $model, array $data, bool $returnValue,array $expectedErrors) : void {

        $model->getData($data);

        // calling the function
        $result = $model->validate($data);

        // assert whether the return value is same as expected
        $this->assertSame($returnValue, $result);

        // if no errors , assert whether the errors array is empty
        if( $result ) {
            $this->assertEmpty($model->errors);
        }

        // if errors , assert whether the errors array is not empty and the errors are same as expected
        else {
            $errors = $model->errors;
            $this->assertNotEmpty($errors);

            foreach ($expectedErrors as $key => $value) {
                $this->assertArrayHasKey($key, $errors);
                $this->assertSame($value, $errors[$key]);
            }

        }

    }

    /**
     * @param mixed $model
     * @param array $data
     * @return void
     * @depends test_model_getData_function
     * @dataProvider \app\tests\unit\data_provider\data_provider::test_Valid_data_for_reset()
     */
    public function test_reset(mixed $model, array $data) : void {

        $model->getData($data);

        // assert whether the data has been assigned
        foreach ($model->rules() as $key => $value) {
//            echo $key;
            $this->assertNotEmpty($model->{$key});
        }

        // calling the function
        $model->reset();

        // assert whether the data has been reset
        foreach ($model->rules() as $key => $value) {
            $this->assertEmpty($model->{$key});
        }


    }

}