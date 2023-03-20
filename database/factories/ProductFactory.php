<?php
  
namespace Database\Factories;
  
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
  
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;
  
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'name_ar_qa' => $this->faker->name,            
            'description' => $this->faker->text,
            'description_ar_qa' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'merchant_price' => $this->faker->randomFloat(2, 10, 100),
            'status' => 'active',
        ];
    }
}