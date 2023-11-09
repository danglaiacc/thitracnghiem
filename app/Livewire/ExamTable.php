<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Exam;
use Illuminate\Support\Facades\Crypt;

class ExamTable extends DataTableComponent
{
    protected $model = Exam::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable(),
            Column::make("Time", "time")
                ->sortable(),
            Column::make("Allow shuffle", "allow_shuffle")
                ->sortable(),
            Column::make('Action', 'uuid')
                ->format(
                    function ($uuid, $row) {
                        $cryptedId = Crypt::encrypt($row->id);
                        $detailUrl = route('exam.upsert', [
                            $uuid,
                        ]);
                        return "<a href=\"#\" data-bs-toggle=\"modal\" data-bs-target=\"#upsert-product-modal-id\"  wire:loading.attr='disabled'
                        wire:click=\"\$emitTo('menu.index.upsert-product','setExamSelected','uuid','$cryptedId')\">View</a>|<a href='$detailUrl' class='text-success'>Details</a>";
                    }
                )
                ->html(),
        ];
    }
}
