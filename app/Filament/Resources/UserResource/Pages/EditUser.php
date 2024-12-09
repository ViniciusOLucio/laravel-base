<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Verifica se o usuário atual é admin antes de salvar
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }

        return $data;
    }

    public static function canEdit($record): bool
    {
        // Permite edição apenas para admins
        return Auth::user()->hasRole('admin');
    }
}
