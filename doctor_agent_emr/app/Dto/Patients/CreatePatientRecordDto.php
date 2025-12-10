<?php

declare(strict_types=1);

namespace App\Dto\Patients;

class CreatePatientRecordDto
{
    public function __construct(
        public string $currentComplains,
        public string $onDirectQuestions,
        public string|null $issues,
        public string|null $updates,
        public string $onExaminations,
        public string $investigations,
        public string $impression,
        public string $plan,
        public string $history_presenting_complains,
        /**
         * @var string[]
         */
        public array $vitals,
    ) {
    }

    /**
     * @return array<string, string|array<string>|null>
     */
    public function toArray(): array
    {
        return [
            'current_complains' => $this->currentComplains,
            'on_direct_questions' => $this->onDirectQuestions,
            'issues' => $this->issues,
            'updates' => $this->updates,
            'on_examinations' => $this->onExaminations,
            'vitals' => $this->vitals,
            'investigations' => $this->investigations,
            'impression' => $this->impression,
            'plan' => $this->plan,
            'history_presenting_complains' => $this->history_presenting_complains,
        ];
    }
}
