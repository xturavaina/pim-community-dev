<?php

declare(strict_types=1);

namespace spec\Akeneo\Connectivity\Connection\Application\Audit\Query;

use Akeneo\Connectivity\Connection\Application\Audit\Query\CountDailyEventsByConnectionHandler;
use Akeneo\Connectivity\Connection\Application\Audit\Query\CountDailyEventsByConnectionQuery;
use Akeneo\Connectivity\Connection\Domain\Audit\Model\EventTypes;
use Akeneo\Connectivity\Connection\Domain\Audit\Model\Read\PeriodEventCount;
use Akeneo\Connectivity\Connection\Domain\Audit\Persistence\Query\SelectConnectionsEventCountByDayQuery;
use PhpSpec\ObjectBehavior;

/**
 * @author Romain Monceau <romain@akeneo.com>
 * @copyright 2019 Akeneo SAS (http://www.akeneo.com)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class CountDailyEventsByConnectionHandlerSpec extends ObjectBehavior
{
    public function let(SelectConnectionsEventCountByDayQuery $selectConnectionsEventCountByDayQuery): void
    {
        $this->beConstructedWith($selectConnectionsEventCountByDayQuery);
    }

    public function it_is_initializable(): void
    {
        $this->shouldBeAnInstanceOf(CountDailyEventsByConnectionHandler::class);
    }

    public function it_handles_the_event_count($selectConnectionsEventCountByDayQuery): void
    {
        $fromDateTime = new \DateTimeImmutable('2020-01-01 00:00:00', new \DateTimeZone('UTC'));
        $upToDateTime = new \DateTimeImmutable('2020-01-02 00:00:00', new \DateTimeZone('UTC'));

        $hourlyEventCountsPerConnection = [
            '<all>' => [
                [new \DateTimeImmutable('2020-01-01 00:00:00', new \DateTimeZone('UTC')), 1],
            ],
            'sap' => [
                [new \DateTimeImmutable('2020-01-02 00:12:00', new \DateTimeZone('UTC')), 2],
            ],
            'bynder' => [
                [new \DateTimeImmutable('2020-01-03 00:23:00', new \DateTimeZone('UTC')), 3],
            ]
        ];
        $selectConnectionsEventCountByDayQuery->execute(EventTypes::PRODUCT_CREATED, $fromDateTime, $upToDateTime)
            ->willReturn($hourlyEventCountsPerConnection);

        $expectedResult = [
            new PeriodEventCount('<all>', $fromDateTime, $upToDateTime, $hourlyEventCountsPerConnection['<all>']),
            new PeriodEventCount('sap', $fromDateTime, $upToDateTime, $hourlyEventCountsPerConnection['sap']),
            new PeriodEventCount('bynder', $fromDateTime, $upToDateTime, $hourlyEventCountsPerConnection['bynder']),
        ];

        $query = new CountDailyEventsByConnectionQuery(EventTypes::PRODUCT_CREATED, $fromDateTime, $upToDateTime);
        $this->handle($query)->shouldIterateLike($expectedResult);
    }
}
