<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints\DateTime;

class AddOpinionDTO
{
    public ?int $score;
    public ?\DateTimeInterface $endDate;
    public ?string $workerTitle;
    public ?string $title;
    public ?string $groupName;
    public ?string $comment = null;
}