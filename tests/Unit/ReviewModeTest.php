<?php

namespace Tests\Unit;

use App\Enums\ResultMessage;
use App\Livewire\ReviewMode;
use Livewire\Livewire;

class ReviewModeTest extends BaseTestcase
{
    /**
     * enter to new exam => user_exams table will create new record
     */
    public function test_create_user_exam_record_when_enter_new_exam()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ]);
        $this->assertDatabaseHas(
            'user_exams',
            [
                'user_id' => 1,
                'exam_id' => $this->exam->id,
            ]
        );

        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('saveExamResult');
        $this->assertDatabaseHas(
            'user_exams',
            [
                'user_id' => 1,
                'exam_id' => $this->exam->id,
                'record' => $this->castAsJson([
                    [
                        'option_ids' => [8, 7, 9, 6],
                        'question_id' => 2,
                        'user_answers' => [],
                    ],
                    [
                        'option_ids' => [1, 2, 5, 3, 4],
                        'question_id' => 1,
                        'user_answers' => [],
                    ],
                ]),
            ]
        );
    }

    public function test_should_new_answer_store_to_user_exams_table()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1) // load to multi choice question
            ->set('selectedOptions', ["1", "2"])
            ->call('submitAnswer')
            ->call('saveExamResult');

        $this->assertDatabaseHas(
            'user_exams',
            [
                'user_id' => 1,
                'exam_id' => $this->exam->id,
                'record' => $this->castAsJson([
                    [
                        'option_ids' => [8, 7, 9, 6],
                        'question_id' => 2,
                        'user_answers' => [],
                    ],
                    [
                        'option_ids' => [1, 2, 5, 3, 4],
                        'question_id' => 1,
                        'user_answers' => ['1', '2'],
                    ],
                ]),
            ]
        );
    }

    /**
     * create record with answer has been stored in user_exams table
     * enter to exam, load to this question
     * assert see this result: incorrect answer
     */
    // public function test_answer_can_show_after_pause_exam()
    // {
    //     $this->createExamAndQuestion();
    //     UserExam::factory([
    //         'user_id' => 1,
    //         'exam_id' => $this->exam->id,
    //         'record' => json_encode([
    //             [
    //                 'option_ids' => [8, 7, 9, 6],
    //                 'question_id' => 2,
    //                 'user_answers' => [],
    //             ],
    //             [
    //                 'option_ids' => [1, 2, 5, 3, 4],
    //                 'question_id' => 1,
    //                 'user_answers' => ['1', '2'],
    //             ],
    //         ]),
    //     ])->create();

    //     $component = Livewire::test(ReviewMode::class, [
    //         'exam' => $this->exam->uuid
    //     ])->set('questions', $this->getQuestionRandom())
    //         ->call('loadQuestion', 1); // load to multi choice question
            
    //         $component->assertSet('selectedOptions', ['1', '2']);
    // }

    public function test_show_explaination_when_come_back_previous_question()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->set('selectedOptions', '8')
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER)
            ->call('loadQuestion', 1)
            ->assertDontSee(ResultMessage::IN_CORRECT_ANSWER)
            ->call('loadQuestion', 0)
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER)
            ->call('loadQuestion', 1)
            ->assertDontSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_incorrect_answer_single_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->set('selectedOptions', "7")
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_correct_answer_single_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->set('selectedOptions', "6")
            ->call('submitAnswer')
            ->assertSee(ResultMessage::CORRECT_ANSWER);
    }

    public function test_select_incorrect_answer_multi_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', ["2", "3"])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_incorrect_answer_multi_choice_dont_have_any_option()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', [])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_incorrect_answer_multi_choice_select_all_options()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', ["1", "5", "2", "3", "4"])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_correct_answer_multi_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', ["1", "5"])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::CORRECT_ANSWER);
    }
}
