<?php

namespace Thiktak\FilamentBookmarks\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Thiktak\FilamentBookmarks\Models\Bookmark;

class BookmarkAction extends Action
{
    //use CanCustomizeProcess;

    protected ?string $pathUrl = null;

    protected bool $activatePageFeatures = true;

    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'bookmark';
    }

    public function activatePageFeatures(bool $activatePageFeatures): static
    {
        $this->activatePageFeatures = $activatePageFeatures;
        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->pathUrl = str_ireplace(URL::to(''), '', request()->url());
        if (substr($this->pathUrl, 0, 10) == '/livewire/') {
            $this->pathUrl = str_ireplace(URL::to(''), '', $_SERVER['HTTP_REFERER']);
        }

        $this
            ->label(false)

            ->icon(fn () => $this->getDataForThis($this->pathUrl)->count() ? 'heroicon-s-bookmark' : 'heroicon-o-bookmark')
            ->badge(fn () => $this->getDataForThis($this->pathUrl)->count())

            ->form($this->getFormContent())

            ->action(function (array $data): void {

                $data['url'] = str_ireplace(URL::to(''), '', $data['url']);

                Bookmark::query()
                    ->updateOrCreate([
                        'user_id' => auth()->id() ?: 0,
                        'url' => $data['url']
                    ], [
                        'group' => $data['group'],
                        'icon'  => $data['icon'],
                        'color' => $data['color'],
                        'title' => $data['title']
                    ]);

                $this->success();
            });

        $this->modalHeading(fn (): string => __('Bookmarks'))
            ->stickyModalHeader()
            ->stickyModalFooter()
            ->slideOver()
            ->outlined(fn () => !$this->activatePageFeatures)
            ->extraAttributes(fn () => !$this->activatePageFeatures ? [
                'class' => 'border-0 !ring-0',
                'style' => 'box-shadow: none'
            ] : []);

        /*$this->label(false); //__('filament-actions::edit.single.label'));

        $this->icon('heroicon-o-bookmark');

        //$this->modalHeading(fn (): string => __('filament-actions::edit.single.modal.heading', ['label' => $this->getRecordTitle()]));

        

        //$this->modalSubmitActionLabel(__('filament-actions::edit.single.modal.actions.save.label'));

        //$this->successNotificationTitle(__('filament-actions::edit.single.notifications.saved.title'));





        $this
            ->form([
                TextInput::make('title')
            ]);


        $this->action(fn ($record) => dd($record->advance()))
            ->modalContent(fn ($record): View => (view(
                'thiktak-filament-bookmarks::actions.bookmark',
                //['record' => $record],
            )));


        $this->slideOver();
*/
        /*$this->fillForm(function (HasActions $livewire, Model $record): array {
            if ($translatableContentDriver = $livewire->makeFilamentTranslatableContentDriver()) {
                $data = $translatableContentDriver->getRecordAttributesToArray($record);
            } else {
                $data = $record->attributesToArray();
            }

            if ($this->mutateRecordDataUsing) {
                $data = $this->evaluate($this->mutateRecordDataUsing, ['data' => $data]);
            }

            return $data;
        });

        $this->action(function (): void {
            $this->process(function (array $data, HasActions $livewire, Model $record) {
                if ($translatableContentDriver = $livewire->makeFilamentTranslatableContentDriver()) {
                    $translatableContentDriver->updateRecord($record, $data);
                } else {
                    $record->update($data);
                }
            });

            $this->success();
        });*/
    }

    public function getFormContent()
    {
        return [
            Tabs::make('tabs')
                ->tabs([
                    Tabs\Tab::make('history')
                        ->label('History')
                        ->icon('heroicon-o-clock')
                        ->schema(function () {
                            return [
                                ViewField::make('rating')
                                    ->view('thiktak-filament-bookmarks::actions.bookmark')
                                    ->viewData([
                                        'url' => $this->pathUrl,
                                        'links' => \Thiktak\FilamentBookmarks\Models\History::query()
                                            ->whereIn('user_id', [0, auth()->id()])
                                            ->orderByDesc('updated_at')
                                            ->get()
                                            ->map(fn ($row) => $row->updateTitle())
                                            ->groupBy('group'), // group does not exists :)
                                    ])
                            ];
                        }),

                    Tabs\Tab::make('bookmarks')
                        ->label('All bookmarks')
                        ->icon('heroicon-o-bookmark')
                        ->schema(function () {
                            return [
                                ViewField::make('rating')
                                    ->view('thiktak-filament-bookmarks::actions.bookmark')
                                    ->viewData([
                                        'url' => $this->pathUrl,
                                        'links' => \Thiktak\FilamentBookmarks\Models\Bookmark::query()
                                            ->whereIn('user_id', [0, auth()->id()])
                                            ->orderBy('group')
                                            ->orderByDesc('user_id')
                                            ->get()
                                            ->groupBy('group'),
                                    ])
                            ];
                        }),

                    Tabs\Tab::make('this')
                        ->label('Variants (this page)')
                        ->icon('heroicon-o-document-check')
                        ->schema(function () {

                            return [
                                ViewField::make('rating')
                                    ->view('thiktak-filament-bookmarks::actions.bookmark')
                                    ->viewData([
                                        'url' => $this->pathUrl,
                                        'links' => $this->getDataForThis($this->pathUrl)
                                            ->get()
                                            ->groupBy('group'),
                                    ])
                            ];
                        }),

                    Tabs\Tab::make('create')
                        ->label('Create bookmark')
                        ->icon('heroicon-o-document-plus')
                        ->schema(function () {
                            return [
                                TextInput::make('url')
                                    ->default($this->pathUrl)
                                    ->required()
                                    ->live(),

                                TextInput::make('title')
                                    ->required(),

                                ColorPicker::make('color')
                                    ->default('primary'),

                                TextInput::make('icon')
                                    ->default($this->getLivewire()->getResource()::getNavigationIcon()),

                                TextInput::make('group'),
                            ];
                        })
                ])
        ];
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }

    public function getDataForThis($url)
    {
        return \Thiktak\FilamentBookmarks\Models\Bookmark::query()
            ->whereIn('user_id', [0, auth()->id()])
            ->where('url', 'LIKE', $url . '%')
            ->orderBy('group')
            ->orderByDesc('user_id');
    }
}
