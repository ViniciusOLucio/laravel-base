<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Livewire\Form;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Filament\Resources\EventResource;
use App\Models\Event;
use Blueprint\Models\Model;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;

class CalendarWidget extends FullCalendarWidget
{
    public \Illuminate\Database\Eloquent\Model | string | null $model = Event::class;

    public function fetchEvents(array $fetchInfo): array
    {

        return Event::query()
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Event $event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'color' => $event->color,
                    'start' => $event->start_at,
                    'end' => $event->end_at,
//                    'url' => EventResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => false
                ]
            )
            ->all();

    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('title'),
            RichEditor::make('description'),
            TextInput::make('url'),
            ColorPicker::make('color'),


            Grid::make()
                ->schema([
                    DateTimePicker::make('start_at'),
                    DateTimePicker::make('end_at'),
                ]),
        ];
    }
    protected function headerActions(): array
    {
        return [
           CreateAction::make()
            ->label('Agendar Reunião'),
        ];
    }

    protected function modalActions(): array
    {
        return [
            \Saade\FilamentFullCalendar\Actions\CreateAction::make()
            ->mountUsing(
              function (\Filament\Forms\Form $form, array $arguments) {
                  $form->fill(state: [
                     'start_at' => $arguments['start'] ?? null,
                     'end_at' => $arguments['end'] ?? null,
                  ]);

              }
            ),
            \Saade\FilamentFullCalendar\Actions\EditAction::make()
                ->mountUsing(
                    function (Event $record, \Filament\Forms\Form $form, array $arguments) {
                        $form->fill([
                            'title' => $record->title,
                            'description' => $record->description,
                            'url' => $record->url,
                            'color' => $record->color,
                            'start_at' => $arguments['event']['start'] ?? $record->start_at,
                            'end_at' => $arguments['event']['end'] ?? $record->end_at
                        ]);
                    }
                ),
            \Saade\FilamentFullCalendar\Actions\DeleteAction::make(),
        ];
    }

//    titulo visivel
    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
        }
    JS;
    }

    protected function viewAction(): Action
    {
        return ViewAction::make()
            ->modalFooterActions(fn (ViewAction $action)=> [
                EditAction::make()
                ->label('Editar'),
                DeleteAction::make()
                ->label('Desmarcar'),
                $action->getModalCancelAction()
                ->label('Cancelar'),

            ]);

    }



}
