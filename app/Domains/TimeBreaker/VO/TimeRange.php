<?php

declare(strict_types=1);

namespace App\Domains\TimeBreaker\VO;

use Belamov\PostgresRange\Ranges\TimestampRange;
use Carbon\CarbonPeriod;

final class TimeRange
{
    private TimePoint $from;
    private TimePoint $to;

    public function __construct(TimePoint $from, TimePoint $to)
    {
        if ($from->isGreater($to)) {
            throw new \InvalidArgumentException('wrong range');//@todo
        }
        $this->from = $from;
        $this->to = $to;
    }

    public static function fromStrings(string $from, string $to): self
    {
        return new self(
            TimePoint::fromString($from),
            TimePoint::fromString($to),
        );
    }

    public function toPeriod(): CarbonPeriod
    {
        return new CarbonPeriod(
            $this->from->getValue(),
            $this->to->getValue(),
        );
    }

    public function toTimestampRange(): TimestampRange
    {
        return new TimestampRange(
            $this->from->getValue(),
            $this->to->getValue(),
            '[',
            ']'
        );
    }

    public function getFrom(): TimePoint
    {
        return $this->from;
    }

    public function getTo(): TimePoint
    {
        return $this->to;
    }
}
