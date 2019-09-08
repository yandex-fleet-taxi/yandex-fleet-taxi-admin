<?php

namespace App\Orchid\Screens;

use App\Lead;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layout;
use Orchid\Screen\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class LeadEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создание лида';

    private $exists = false;

    /**
     * Query data.
     *
     * @param Lead $lead
     * @return array
     */
    public function query(Lead $lead): array
    {
        $this->exists = $lead->exists;

        if ($this->exists){
            $this->name = 'Редактирование лида';
        }

        return [
            'lead' => $lead
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
            Link::name('Сохранить')
                ->icon('icon-save')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Link::name('Обновить')
                ->icon('icon-save')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Link::name('Удалить')
                ->icon('icon-trash')
                ->method('remove')
                ->canSee($this->exists),

            Link::name('Отмена')
                ->icon('icon-trash')
                ->link('platform.lead.list'),
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
            Layout::rows([
                // Общие сведения
                Input::make('lead.phone_work')
                    ->title('Номер рабочего телефон')
                    ->placeholder('Телефон для связи с пассажирами и для подключения Таксометра'),

                Input::make('lead.phone_whats_app')
                    ->title('Номер телефон для WhatsApp')
                    ->placeholder('Телефон для связи с таксопарком по WhatsApp'),

                Input::make('lead.city')
                    ->title('Город')
                    ->placeholder('Город, где вы планируете работать'),

                Input::make('lead.timezone')
                    ->title('Часовой пояс')
                    ->placeholder('Часовой пояс относительно Москвы для города, где вы планируете работать'),

                // Водительское удостоверение (ВУ)
                Input::make('lead.country')
                    ->title('Страна')
                    ->placeholder('Выберите страну выдачи ВУ'),

                Input::make('lead.last_name')
                    ->title('Фамилия')
                    ->placeholder('Как указано в ВУ'),

                Input::make('lead.first_name')
                    ->title('Имя')
                    ->placeholder('Как указано в ВУ'),


                Input::make('lead.moddle_name')
                    ->title('Отчество')
                    ->placeholder('Как указано в ВУ'),


                Input::make('lead.driver_license_number')
                    ->title('Серия и номер')
                    ->placeholder(null),


                Input::make('lead.driver_license_issue_date')
                    ->title('Страна')
                    ->placeholder('Дата выдачи'),


                Input::make('lead.driver_license_expiration_date')
                    ->title('Дата окончания')
                    ->placeholder(null),


                Input::make('lead.birth_date')
                    ->title('Дата рождения')
                    ->placeholder(null),


                // Car
                Input::make('lead.car_number')
                    ->title('Госномер автомобиля (только РФ)')
                    ->placeholder('Буквы только русские'),

                Input::make('lead.car_brand')
                    ->title('Марка автомобиля')
                    ->placeholder(null),

                Input::make('lead.car_model')
                    ->title('Модель автомобиля')
                    ->placeholder(null),

                Input::make('lead.car_year')
                    ->title('Год выпуска')
                    ->placeholder(null),

                Input::make('lead.car_color')
                    ->title('Цвет')
                    ->placeholder(null),

                Input::make('lead.car_vin')
                    ->title('VIN')
                    ->placeholder('Если есть'),

                Input::make('lead.registration_number')
                    ->title('Серия и номер СТС')
                    ->placeholder('Слитно и без пробелов'),

                Input::make('lead.branding')
                    ->title('Брендирование')
                    ->placeholder('Через запятую'),

            ])
        ];
    }

    /**
     * @param Lead $lead
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createOrUpdate(Lead $lead, Request $request)
    {
        $lead->fill($request->get('lead'))->save();

        $message = $this->exists ? 'Лид успешно обновлен' : 'Лид успешно создан';

        Alert::info($message);

        return redirect()->route('platform.lead.list');
    }

    /**
     * @param Lead $post
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Lead $post)
    {
        try {
            Alert::info('You have successfully deleted the post.');

        } catch (\Exception $exception) {
            Alert::warning('An error has occurred');
        }

        return redirect()->route('platform.lead.list');
    }
}
