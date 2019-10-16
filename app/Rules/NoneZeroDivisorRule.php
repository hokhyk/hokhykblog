<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

//use Illuminate\Support\MessageBag;
//use Prettus\Validator\Exceptions\ValidatorException;


class NoneZeroDivisorRule implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return 0 !== $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The given divisor is zero.';
    }
//
//    /**
//     * Pass the data and the rules to the validator or throws ValidatorException
//     *
//     * @throws ValidatorException
//     * @param string $action
//     * @return boolean
//     */
//    public function passesOrFail($value = null)
//    {
//        if (0 === $value) {
//            throw new ValidatorException(new MessageBag( ['error' => $this->message()]));
//        }
////         try{
////             return true;
////         }
////         catch(\Exception $e)
////         {
////             throw new \DivisionByZeroError($this->message());
////         }
//    }
}
