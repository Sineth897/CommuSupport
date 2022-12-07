<?php

namespace app\core;

abstract class Model
{
    public static string $REQUIRED = 'required';
    public static string $EMAIL = 'email';
    public static string $MIN = 'min';
    public static string $MAX = 'max';
    public static string $MATCH = 'match';
    public static string $UNIQUE = 'unique';
    public static string $CONTACT = 'contact';
    public array $errors = [];


    public function getData($data): void {

        foreach ($data as $key => $value) {
            if( property_exists($this, $key) ) {
                $this->{$key} = $value;
            }
        }

    }

    abstract public function rules(): array;

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
            }
        }

        return empty($this->errors);
    }

    private function addRuleError($attribute, $rule, $params = []): void {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError($attribute, $message): void {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages(): array {
        return [
            self::$REQUIRED => 'This field is required',
            self::$EMAIL => 'This field must be a valid email address',
            self::$MIN => 'Min length of this field must be equal or greater than {min}',
            self::$MAX => 'Max length of this field must be equal or less than {max}',
            self::$MATCH => 'This field must be the same as {match}',
            self::$UNIQUE => '{field} already exists',
            self::$CONTACT => 'This field must be a valid contact number'
        ];
    }

    public function hasError($attribute): bool {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute): string {
        return $this->errors[$attribute][0] ?? '';
    }

    public function reset() {
        foreach ($this->rules() as $attribute => $rules) {
            if( is_int($this->{$attribute})) {
                $this->{$attribute} = 0;
            } else {
                $this->{$attribute} = '';
            }
        }
    }
}