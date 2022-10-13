<?php

namespace Tests\Feature;

use App\Models\Glossary\City;
use Tests\TestCase;

class AccountTest extends TestCase
{
    /**
     * Тестируем страницу "Изменить персональные данные"
     *
     * @return void
     */
    public function testEditingPersonalInfo()
    {
        $uri = route("account.personal");
        $updateUri = route('account.personal.update');
        // Если пользователь не авторизован, ожидаем код 302
        $this
            ->get($uri)
            ->assertStatus(302);

        $user = $this->createUser();
        $this->actingAs($user);

        // Открываем страницу, должен быть код 200
        $this
            ->get($uri)
            ->assertStatus(200);

        // Валидируем данные
        $this->update([], [
            'firstname' => __("validation.required", ['attribute' => __('validation.attributes.firstname')]),
            'lastname'  => __("validation.required", ['attribute' => __('validation.attributes.lastname')]),
            'phone'     => __("validation.required", ['attribute' => __('validation.attributes.phone')]),
            'email'     => __("validation.required", ['attribute' => __('validation.attributes.email')]),
        ]);

        $this->update(['firstname' => 'J', 'lastname' => 'D', 'email' => 'wqwesdasdasd', 'phone' => 'qwe'], [
            'email' => __("validation.email", ['attribute' => __('validation.attributes.email')]),
            'phone' => __("validation.numeric", ['attribute' => __('validation.attributes.phone')]),
        ]);


        $data = [
            'firstname' => 'John', 'lastname' => 'Doe', 'email' => 'johndoe@test.com', 'phone' => '7912345678'
        ];
        $this
            ->post($updateUri, $data)
            ->assertRedirect(route('account.personal'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@test.com',
            'id'    => $user->id
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'phone'     => '7912345678'
        ]);
    }

    public function testGeo()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $cities = City::factory()->count(3)->create();
        $city = $cities[0];
        $country = $city->country;

        $dataWithGeo = $this->validData([
            'country_id' => $country->id,
            'city_id'    => $city->id
        ]);

        $this->update($dataWithGeo)
            ->assertRedirect(route('account.personal'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('user_profiles', [
            'firstname'  => 'John',
            'lastname'   => 'Doe',
            'phone'      => '7912345678',
            'country_id' => $country->id,
            'city_id'    => $city->id,
            'user_id'    => $user->id
        ]);

        $dataWithGeo['country_id'] = 100000;
        $dataWithGeo['city_id'] = 100000;
        $this->update($dataWithGeo, [
            'country_id' => __('validation.exists', ['attribute' => 'Country']),
            'city_id'    => __('validation.exists', ['attribute' => 'City']),
        ]);

        $city = $cities[1];
        $dataWithGeo['country_id'] = $country->id;
        $dataWithGeo['city_id'] = $city->id;
        $this->update($dataWithGeo, [
            'city_id' => __('validation.exists', ['attribute' => 'City']),
        ]);
    }

    private function update(array $data, array $invalidData = [])
    {
        $response = $this->post(route('account.personal.update'), $data);

        if ($invalidData) {
            $response
                ->assertStatus(302)
                ->assertInvalid($invalidData);
        }

        return $response;
    }

    private function validData(array $array = []): array
    {
        return array_merge([
            'firstname' => 'John',
            'lastname'  => 'Doe',
            'email'     => 'johndoe@test.com',
            'phone'     => '7912345678'
        ], $array);
    }
}
