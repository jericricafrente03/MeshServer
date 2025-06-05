<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HexColorRule implements ValidationRule
{   
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Regular expression for validating hex color codes
        $pattern = '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';

        // Check if the value matches the pattern
        if (!preg_match($pattern, $value)) {
            // Call the fail closure with the error message
            $fail('The :attribute must be a valid hex color code. <br>(e.g., #FFF or #FFFFFF)');
        }
    }
}
