<?php

namespace App\Libraries;

class TenantContext
{
    private ?int $organizationId = null;
    private $user = null;

    /**
     * MODOS DE EJECUCIÓN
     * strict = obligatorio tenant
     * soft = opcional
     * disabled = no filtrar
     */
    private string $mode = 'strict';

    public function setTenantId(int $organizationId): self
    {
        $this->organizationId = $organizationId;
        return $this;
    }

    public function tenantId(): ?int
    {
        return $this->organizationId;
    }

    public function requireTenantId(): int
    {
        if (!$this->organizationId && $this->mode === 'strict') {
            throw new \RuntimeException('Tenant not initialized (STRICT MODE)');
        }

        return (int) $this->organizationId;
    }

    public function hasTenant(): bool
    {
        return $this->organizationId !== null;
    }

    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    public function user()
    {
        return $this->user;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function mode(): string
    {
        return $this->mode;
    }

    public function isStrict(): bool
    {
        return $this->mode === 'strict';
    }

    public function isDisabled(): bool
    {
        return $this->mode === 'disabled';
    }

    public function clear(): void
    {
        $this->organizationId = null;
        $this->user = null;
        $this->mode = 'soft';
    }
}