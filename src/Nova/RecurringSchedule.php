<?php

declare(strict_types=1);

namespace Tipoff\Scheduler\Nova;

use Illuminate\Http\Request;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class RecurringSchedule extends BaseResource
{
    public static $model = \Tipoff\Scheduler\Models\RecurringSchedule::class;

    public static $title = 'id';

    public static $search = [
        'id',
    ];

    /** @psalm-suppress UndefinedClass */
    protected array $filterClassList = [
        \Tipoff\EscapeRoom\Filters\Room::class,
        \Tipoff\EscapeRoom\Filters\RoomLocation::class,
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->hasRole([
            'Admin',
            'Owner',
            'Accountant',
            'Executive',
            'Reservation Manager',
            'Reservationist',
        ])) {
            return $query;
        }

        /** @psalm-suppress UndefinedMagicMethod */
        return $request->withOrdering($request->withFilters(
            $query->select('recurring_schedules.*')
                ->join('rooms', 'rooms.id', '=', 'recurring_schedules.room_id')
        ));
    }

    public static $group = 'Operations Scheduler';

    public static $perPageViaRelationship = 30;

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            Text::make('Room', 'rooms.id', function () {
                return $this->room->name;
            })->sortable(),
            Select::make('Day')->options([
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday',
            ])->displayUsingLabels()->sortable(),
            TimeField::make('Time')->withTwelveHourTime()->sortable(),
            Text::make('Rate', 'rate.id', function () {
                return $this->rate->name;
            })->sortable(),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            nova('room') ? BelongsTo::make('Room', 'room', nova('room'))->required() : null,
            Select::make('Day of the Week', 'day')->options([
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday',
            ])->required(),
            TimeField::make('Time')->withTwelveHourTime()->required(),
            nova('rate') ? BelongsTo::make('Rate', 'rate', nova('rate'))->hideWhenCreating()->nullable() : null,

            new Panel('Optional Date Fields', $this->dateFields()),

            new Panel('Data Fields', $this->dataFields()),
        ]);
    }

    protected function dateFields()
    {
        return [
            Date::make('Valid from')->nullable(),
            Date::make('Valid until', 'expires_at')->nullable(),
        ];
    }

    protected function dataFields(): array
    {
        return array_filter([
            ID::make(),
            nova('user') ? BelongsTo::make('Created By', 'creator', nova('user'))->exceptOnForms() : null,
            DateTime::make('Created At')->exceptOnForms(),
            nova('user') ? BelongsTo::make('Updated By', 'updater', nova('user'))->exceptOnForms() : null,
            DateTime::make('Updated At')->exceptOnForms(),
        ]);
    }
}
