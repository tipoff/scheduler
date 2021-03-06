<?php 

declare(strict_types=1);

namespace Tipoff\Scheduler\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Scheduler\Models\ScheduleEraser;

class ScheduleEraserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScheduleEraser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $days = rand(1, 30);

        return [
            'start_at'   => now()->addDays($days),
            'end_at'     => now()->addDays($days + 1),
            'room_id'    => randomOrCreate(app('room')),
            'creator_id' => randomOrCreate(app('user')),
        ];
    }
}
