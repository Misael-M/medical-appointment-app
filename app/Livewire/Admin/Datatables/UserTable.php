<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Correo", "email")
                ->sortable(),
            Column::make("Rol") //Columna para los roles
                ->label(function($row){
                    return $row->roles->pluck('name')->implode(', ');
                }),
                
            Column::make("Acciones")
                ->label(function($row){
                return view('admin.usuarios.actions', ['usuario' => $row])->render();
            })
            ->html(),
        ];
    }
}
