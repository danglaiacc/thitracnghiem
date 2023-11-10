<?php

namespace Tests\Unit\Exam;

use App\Livewire\Exam\Upsert;
use Livewire\Livewire;
use Tests\Unit\BaseTestcase;

class UpsertTest extends BaseTestcase
{
    public function test_remove_question_without_save()
    {
        $this->createExamAndQuestion();
        Livewire::test(Upsert::class, [
            'exam' => $this->exam
        ])
            ->call('removeQuestion', $this->question1->uuid, 0);

        $this->assertDatabaseHas(
            'questions',
            [
                'id' => $this->question1->id
            ],
        );
    }
}
