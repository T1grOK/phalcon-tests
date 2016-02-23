<?php

namespace Fixtures;

class UserFixture extends \Phalcon\Cli\Task
{
    public function mainAction($rowsCount = null)
    {
        $config = $config = $this->di->getShared('config');

        if(null === $rowsCount){
            $rowsCount = $config->fixture->count;
        }
        $rowsCount = max(0, (int)$rowsCount);

        $faker = \Faker\Factory::create($config->facker->locale);
        while($rowsCount--)
        {
            $user = new \Models\User();
            $user->save([
                'email' => $faker->email,
                'username' => $faker->userName,
                'fname' => $faker->firstName,
                'lname' => $faker->lastName,
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'credit_card' => $faker->creditCardNumber,
                'balance' => $faker->randomFloat(4, 0, 10000),
                'timezone' => $faker->timezone,
                'birthday' => $faker->date('Y-m-d', '2000-12-31'),
                'registered_at' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'),
                'logins' => $faker->numberBetween(0, 10000),
            ]);
        }

        echo "Done!\n";
    }
}



