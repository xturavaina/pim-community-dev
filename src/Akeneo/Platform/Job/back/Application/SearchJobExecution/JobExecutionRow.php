<?php

declare(strict_types=1);

namespace Akeneo\Platform\Job\Application\SearchJobExecution;

/**
 * @author Pierre Jolly <pierre.jolly@akeneo.com>
 * @copyright 2021 Akeneo SAS (https://www.akeneo.com)
 * @license https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
final class JobExecutionRow
{
    private int $jobExecutionId;
    private string $jobName;
    private string $type;
    private ?\DateTimeImmutable $startedAt;
    private ?string $username;
    private string $status;
    private int $warningCount;
    private int $errorCount;
    private int $currentStep;
    private int $totalStep;
    private bool $isStoppable;

    public function __construct(
        int $jobExecutionId,
        string $jobName,
        string $type,
        ?\DateTimeImmutable $startedAt,
        ?string $username,
        string $status,
        int $warningCount,
        int $errorCount,
        int $currentStep,
        int $totalStep,
        bool $isStoppable
    ) {
        $this->jobExecutionId = $jobExecutionId;
        $this->jobName = $jobName;
        $this->type = $type;
        $this->startedAt = $startedAt;
        $this->username = $username;
        $this->status = $status;
        $this->warningCount = $warningCount;
        $this->errorCount = $errorCount;
        $this->currentStep = $currentStep;
        $this->totalStep = $totalStep;
        $this->isStoppable = $isStoppable;
    }

    public function normalize(): array
    {
        return [
            'job_execution_id' => $this->jobExecutionId,
            'job_name' => $this->jobName,
            'type' => $this->type,
            'started_at' => $this->startedAt ? $this->startedAt->format(DATE_ATOM) : null,
            'username' => $this->username,
            'status' => $this->status,
            'warning_count' => $this->warningCount,
            'error_count' => $this->errorCount,
            'tracking' => [
                'current_step' => $this->currentStep,
                'total_step' => $this->totalStep,
                'steps' => [
                    [
                        'has_error' => false,
                        'has_warning' => false,
                        'is_trackable' => true,
                        'duration' => 34,
                        'processed_items' => 10,
                        'total_items' => 10,
                        'status' => 'COMPLETED',
                    ],
                    [
                        'has_error' => false,
                        'has_warning' => true,
                        'is_trackable' => true,
                        'duration' => 34,
                        'processed_items' => 2,
                        'total_items' => 10,
                        'status' => 'STARTED',
                    ],
                    [
                        'has_error' => false,
                        'has_warning' => false,
                        'is_trackable' => true,
                        'duration' => 34,
                        'processed_items' => 0,
                        'total_items' => 10,
                        'status' => 'STARTING',
                    ],
                ],
            ],
            'is_stoppable' => $this->isStoppable,
        ];
    }
}
