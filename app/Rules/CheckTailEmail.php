<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class CheckTailEmail implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
//        $Email = explode('@',$value);
//        if($Email[1] != "fpt.edu.vn"){
//            $fail('Email không đúng định dạng fpt ');
//        }

        if(str_contains($value, '@') != true){
            $fail('Email không đúng định dạng FPT ');
        }else {
            $Email = explode('@',$value);
            if($Email[1] != "fpt.edu.vn"){
                $fail('Email không đúng định dạng FPT ');
            }
        }
    }
}
