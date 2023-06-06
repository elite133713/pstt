<?php

namespace Pstt\Interfaces;

interface BinProviderInterface
{
    public function searchBin(string $bin): bool|string;
}
