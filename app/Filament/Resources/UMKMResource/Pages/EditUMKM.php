<?php

namespace App\Filament\Resources\UMKMResource\Pages;

use App\Filament\Resources\UMKMResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUMKM extends EditRecord
{
    protected static string $resource = UMKMResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
