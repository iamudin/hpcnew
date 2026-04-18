<?php 
namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class InfoLabWidget extends Widget
{
    protected string $view = 'filament.widgets.info-lab-widget';

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->isKalab();
    }

    public function getViewData(): array
    {
        $user = auth()->user();

        $lab =  $user?->labs?->load('laboran');

        return [
            'lab' => $lab,
        ];
    }
}