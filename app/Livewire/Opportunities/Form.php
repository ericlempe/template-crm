<?php

namespace App\Livewire\Opportunities;

use App\Models\Opportunity;
use Livewire\Form as BaseForm;

class Form extends BaseForm
{
    public ?Opportunity $opportunity = null;

    public ?string $title = '';

    public ?string $status = '';

    public ?int $amount = 0;

    public function rules(): array
    {
        return [
            'title'  => ['required', 'min:3', 'max:255'],
            'status' => ['required'],
            'amount' => ['required'],
        ];
    }

    public function create(): void
    {
        $this->validate();

        Opportunity::create([
            'title'  => $this->title,
            'status' => $this->status,
            'amount' => $this->amount,
        ]);
    }

    public function update(): void
    {
        $this->validate();

        $this->opportunity->title  = $this->title;
        $this->opportunity->status = $this->status;
        $this->opportunity->amount = $this->amount;

        $this->opportunity->update();
    }

    public function setOpportunity(Opportunity $opportunity): void
    {
        $this->opportunity = $opportunity;
        $this->title       = $opportunity->title;
        $this->status      = $opportunity->status;
        $this->amount      = $opportunity->amount;
    }
}
