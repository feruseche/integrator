<?php

namespace Core\Shared\Contracts;

interface CrudInterface
{
    public function All();
    public function Create();
    public function Store();
    public function Show(string $uuid);
    public function Search($textSearch);
    public function Edit(string $uuid);
    public function Update(string $uuid);
    public function Destroy(string $uuid);
}
