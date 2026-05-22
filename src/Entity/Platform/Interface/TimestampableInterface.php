<?php

// src/Entity/Interface/TimestampableInterface.php
namespace App\Entity\Platform\Interface;

use App\Entity\Platform\User;

interface TimestampableInterface
{
    public function setCreatedBy(User $user): void;
    public function setUpdatedBy(?User $user): void;
    public function setCreatedAt(\DateTimeImmutable $dt): void;
    public function setUpdatedAt(?\DateTimeImmutable $dt): void;
    public function getCreatedAt(): \DateTimeImmutable;
}
