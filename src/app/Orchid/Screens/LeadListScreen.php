<?php

namespace App\Orchid\Screens;

use App\Lead;
use App\Orchid\Layouts\LeadListLayout;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;

class LeadListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Лиды';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Список всех лидов';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'leads' => Lead::paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::name('Добавить')
                ->icon('icon-plus')
                ->link(route('platform.lead.new'))
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            LeadListLayout::class
        ];
    }
}
