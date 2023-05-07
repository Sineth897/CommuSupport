<?php

namespace app\core;

use DateTime;

abstract class Model
{
    public static string $REQUIRED = 'required';
    public static string $EMAIL = 'email';
    public static string $MIN = 'min';
    public static string $MAX = 'max';
    public static string $MATCH = 'match';
    public static string $UNIQUE = 'unique';
    public static string $CONTACT = 'contact';
    public static string $PASSWORD = 'password';
    public static string $nic = 'nic';
    public static string $DATE = 'date';

    public static string $TIME = 'time';

    public static string $POSITIVE = 'positive';
    public static string $NOTZERO = 'notzero';

    public static string $LONGITUDE = 'longitude';
    public static string $LATITUDE = 'latitude';
    public array $errors = [];


    /**
     * @param $data
     * @return void
     */
    public function getData($data): void {
        foreach ($data as $key => $value) {
            if( property_exists($this, $key) ) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return array
     */
    abstract public function rules(): array;

    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool {

        foreach ($this->rules() as $attribute => $rules) {

            $value = $data[$attribute] ?? '';

            foreach ($rules as $rule) {
                $ruleName = $rule;
                if( !is_string($ruleName) ) {
                    $ruleName = $rule[0];
                }
                if( $ruleName === self::$REQUIRED && !$value ) {
                    $this->addRuleError($attribute, self::$REQUIRED);
                }
                if( $ruleName === self::$EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL) ) {
                    $this->addRuleError($attribute, self::$EMAIL);
                }
                if( $ruleName === self::$MIN && strlen($value) < $rule['min'] ) {
                    $this->addRuleError($attribute, self::$MIN, $rule);
                }
                if( $ruleName === self::$MAX && strlen($value) > $rule['max'] ) {
                    $this->addRuleError($attribute, self::$MAX, $rule);
                }
                if( $ruleName === self::$MATCH && $value !== $data[$rule['match']] ) {
                    $this->addRuleError($attribute, self::$MATCH, $rule);
                }
                if( $ruleName === self::$UNIQUE ) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::table();
                    $statement = Application::$app->database->pdo->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if( $record ) {
                        $this->addRuleError($attribute, self::$UNIQUE, ['field' => $attribute]);
                    }
                }
                if( $ruleName === self::$CONTACT && !preg_match('/^0[0-9]{9}$/', $value) ) {
                    $this->addRuleError($attribute, self::$CONTACT);
                }
                if( $ruleName === self::$PASSWORD && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $value) ) {
                    $this->addRuleError($attribute, self::$PASSWORD);
                }
                if( $ruleName === self::$nic && !(preg_match('/^[0-9]{9}[vV]$/', $value) || preg_match('/^[0-9]{12}$/', $value)) ) {
                    $this->addRuleError($attribute, self::$nic);
                }
                if( $ruleName === self::$DATE && date('Y-m-d') >= $value && $this->validateDateorTime('Y-m-d',$value)) {
                    $this->addRuleError($attribute, self::$DATE);
                }
                if( $ruleName === self::$TIME && $this->validateDateorTime('H:i:s',$value) ) {
                    $this->addRuleError($attribute, self::$TIME);
                }
                if( $ruleName === self::$POSITIVE && $value < 0 ) {
                    $this->addRuleError($attribute, self::$POSITIVE);
                }
                if( $ruleName === self::$NOTZERO && $value == 0 ) {
                    $this->addRuleError($attribute, self::$NOTZERO);
                }
                if( $ruleName === self::$LONGITUDE && ($value > 81.8914 || $value < 79.695) ) {
                    $this->addRuleError($attribute, self::$LONGITUDE);
                }
                if( $ruleName === self::$LATITUDE && ($value > 9.8167 || $value < 5.9167) ) {
                    $this->addRuleError($attribute, self::$LATITUDE);
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * @param $attribute
     * @param $rule
     * @param $params
     * @return void
     */
    private function addRuleError($attribute, $rule, $params = []): void {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    /**
     * @param $attribute
     * @param $message
     * @return void
     */
    public function addError($attribute, $message): void {
        $this->errors[$attribute][] = $message;
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array {
        return [
            self::$REQUIRED => 'This field is required',
            self::$EMAIL => 'This field must be a valid email address',
            self::$MIN => 'Min length of this field must be equal or greater than {min}',
            self::$MAX => 'Max length of this field must be equal or less than {max}',
            self::$MATCH => 'This field must be the same as {match}',
            self::$UNIQUE => '{field} already exists',
            self::$CONTACT => 'This field must be a valid contact number',
            self::$nic => 'This field must be a valid NIC number',
            self::$DATE => 'This field must be a future date',
            self::$TIME => 'This field must be a valid time',
            self::$POSITIVE => 'This field must be a positive number',
            self::$NOTZERO => 'This field must be a non-zero number',
            self::$LONGITUDE => 'Longitude must belong to Sri Lanka',
            self::$LATITUDE => 'Latitude must belong to Sri Lanka',
        ];
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function hasError($attribute): bool {
        return $this->errors[$attribute] ?? false;
    }

    /**
     * @param $attribute
     * @return string
     */
    public function getFirstError($attribute): string {
        return $this->errors[$attribute][0] ?? '';
    }

    /**
     * @return void
     */
    public function reset(): void {
        foreach ($this->rules() as $attribute => $rules) {
            if( is_int($this->{$attribute})) {
                $this->{$attribute} = 0;
            }
            else if( is_float($this->{$attribute})) {
                $this->{$attribute} = 0.0;
            }
            else if( is_string($this->{$attribute})) {
                $this->{$attribute} = '';
            }
            else if( is_array($this->{$attribute})) {
                $this->{$attribute} = [];
            }
            else {
                $this->{$attribute} = null;
            }
        }
    }

    /**
     * @param string $format
     * @param mixed $value
     * @return bool
     */
    private function validateDateorTime(string $format, mixed $value): bool
    {
        $d = DateTime::createFromFormat($format, $value);
        return $d && $d->format($format) === $value;
    }
}