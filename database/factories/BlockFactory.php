<?php 

declare(strict_types=1);

namespace Tipoff\Scheduler\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Scheduler\Models\Block;

class BlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Block::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slot_id'      => randomOrCreate(app('slot')),
            'participants' => $this->faker->numberBetween(1, 10),
            'type'         => $this->faker->word,
            'creator_id'   => randomOrCreate(app('user')),
        ];
    }
}
