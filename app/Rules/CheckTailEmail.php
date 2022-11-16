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
//        if(str_contains($value, '@') == false){
//            $Email = explode('@',$value);
//            if($Email[1] != "fpt.edu.vn"){
//                $fail('Email không đúng định dạng fpt ');
//            }
//            $fail('Email không đúng định dạng fpt ');
//        }

        if(str_contains($value, '@') == true){
            $Email = explode('@',$value);
            if($Email[1] != "fpt.edu.vn"){
                $fail('Email không đúng định dạng fpt ');
            }
            $fail('Email không đúng định dạng fpt ');
        }

    }
}
