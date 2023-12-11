<?php

declare(strict_types=1);

namespace App\Model;

class AddOpinionDTO
{
    public ?int $score;
    public ?string $startDate;
    public ?string $endDate;
    public ?string $workerTitle;
    public ?string $lessonFormShort;
    public ?string $groupName;
    public ?string $comment;

    // public function __construct(
    //     ?int $score,
    //     ?string $startDate,
    //     ?string $endDate,
    //     ?string $workerTitle,
    //     ?string $lessonFormShort,
    //     ?string $groupName,
    //     ?string $comment,
    // ) {
    //     $this->score = $score;
    //     $this->startDate = $startDate;
    //     $this->endDate = $endDate;
    //     $this->workerTitle = $workerTitle;
    //     $this->lessonFormShort = $lessonFormShort;
    //     $this->groupName = $groupName;
    //     $this->comment = $comment;
    // }
}