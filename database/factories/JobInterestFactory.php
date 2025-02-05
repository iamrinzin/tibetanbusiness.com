<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Job\JobBasicInfo;
use App\Job\JobInterest;
use App\Model;
use App\User;
use Faker\Generator as Faker;

$factory->define(JobInterest::class, function (Faker $faker) {
    return [
        //
        'job_basic_info_id' =>  function () {
            return JobBasicInfo::all()->random();
        },
        // 'event_basic_info_id' => "23432432safasdf sadf",
        'user_id' => function () {
            return User::all()->random();
        },
    ];
});
