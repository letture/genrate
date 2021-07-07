<?php


namespace Lettrue\Genrate\Traits;


use DateTimeInterface;

trait DateFormat
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}