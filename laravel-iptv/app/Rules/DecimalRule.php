<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DecimalRule implements ValidationRule
{
    protected $precision;

    /**
     * Constructor to define precision.
     *
     * @param  int  $precision
     */
    public function __construct(int $precision = 2)
    {
        $this->precision = $precision;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value) || floor($value * pow(10, $this->precision)) != $value * pow(10, $this->precision)) {
            $fail("The $attribute must be a numeric value with up to {$this->precision} decimal places.");
        }
    }
}
