<?php

namespace App\Http\Livewire;

use App\Models\Rule;
use Livewire\Component;

class RuleActionsHistory extends Component
{
    public Rule $rule;

    public function mount(Rule $rule)
    {
        $this->rule = $rule;
    }
}
