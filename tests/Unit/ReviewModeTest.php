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
        ])
            ->call('saveExamResult');
        $this->assertDatabaseHas(
            'user_exams',
            [
                'user_id' => 1,
                'exam_id' => $this->exam->id,
                'record' => $this->castAsJson([
                    [
                        'option_ids' => [
                            $this->option11->id,
                            $this->option12->id,
                            $this->option13->id,
                            $this->option14->id,
                            $this->option15->id,
                        ],
                        'question_id' => $this->question1->id,
                        'user_answers' => [],
                        'is_review' => false,
                    ],
                    [
                        'option_ids' => [
                            $this->option21->id,
                            $this->option22->id,
                            $this->option23->id,
                            $this->option24->id,
                        ],
                        'question_id' => $this->question2->id,
                        'user_answers' => [],
                        'is_review' => false,
                    ],
                ]),
            ]
        );
    }

    public function test_store_new_answer_and_is_review_to_user_exams_table()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 0) // load to multi choice question
            ->set('selectedOptions', [$this->option13->id, $this->option12->id])
            ->call('submitAnswer')
            ->call('setReview', true)
            ->call('saveExamResult');

        $this->assertDatabaseHas(
            'user_exams',
            [
                'user_id' => 1,
                'exam_id' => $this->exam->id,
                'record' => $this->castAsJson([
                    [
                        'option_ids' => [
                            $this->option11->id,
                            $this->option12->id,
                            $this->option13->id,
                            $this->option14->id,
                            $this->option15->id,
                        ],
                        'question_id' => $this->question1->id,
                        'user_answers' => [$this->option13->id, $this->option12->id],
                        'is_review' => true,
                    ],
                    [
                        'option_ids' => [
                            $this->option21->id,
                            $this->option22->id,
                            $this->option23->id,
                            $this->option24->id,
                        ],
                        'question_id' => $this->question2->id,
                        'user_answers' => [],
                        'is_review' => false,
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
    public function test_answer_can_show_after_pause_exam()
    {
        $this->createExamAndQuestion();

        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 0) // load to multi choice question
            ->set('selectedOptions', [$this->option13->id, $this->option12->id])
            ->call('submitAnswer')
            ->call('setReview', true)
            ->call('saveExamResult');

        $component = Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', $this->question1->id); // load to multi choice question

        $component->assertSet('selectedOptions', [$this->option13->id, $this->option12->id]);
    }

    public function test_show_explanation_when_come_back_previous_question()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 0) // load to multi choice question
            ->set('selectedOptions', [$this->option13->id, $this->option12->id])
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
        ])
            ->call('loadQuestion', 1)
            ->set('selectedOptions', [$this->option21->id])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_correct_answer_single_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 1)
            ->set('selectedOptions', [$this->option22->id])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::CORRECT_ANSWER);
    }

    public function test_select_incorrect_answer_multi_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 0)
            ->set('selectedOptions', [$this->option11->id, $this->option12->id])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_incorrect_answer_multi_choice_dont_have_any_option()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 0)
            ->set('selectedOptions', [])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_incorrect_answer_multi_choice_select_all_options()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 0)
            ->set('selectedOptions', [
                $this->option11->id,
                $this->option12->id,
                $this->option13->id,
                $this->option14->id,
                $this->option15->id,
            ])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::IN_CORRECT_ANSWER);
    }

    public function test_select_correct_answer_multi_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])
            ->call('loadQuestion', 0)
            ->set('selectedOptions', [
                $this->option14->id,
                $this->option11->id,
            ])
            ->call('submitAnswer')
            ->assertSee(ResultMessage::CORRECT_ANSWER);
    }
}
