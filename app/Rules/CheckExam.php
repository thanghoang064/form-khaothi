<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\KyHoc;

class CheckExam implements InvokableRule
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
        // value = name kì thi
        $model = new KyHoc();
        $count = $model->where("name",strtolower($value))->count();
        if($count == 2){
            $fail('Đã tồn tại 2 kỳ học ' . '['.$value.']');
        }
    }
}
