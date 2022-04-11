<div>
    <x-jet-action-section>
        <x-slot name="title">
            Monitoring
        </x-slot>

        <x-slot name="description">
            Exports analysis.
        </x-slot>

        <x-slot name="content">
            <div>Total emails in list: <strong>{{ $this->rule->totalEmailsCount }}</strong></div>
            <div>Emails left: <strong>{{ $this->rule->emailsInPoolCount }}</strong></div>
            <div>Emails per action: <strong>{{ $this->rule->emails_count }}</strong></div>
            <div>Actions total: <strong>{{ $this->rule->actionsTotal }}</strong></div>
            <div>Actions performed: <strong>{{ $this->rule->actionsPerformed }}</strong></div>
            <div>Actions left: <strong>{{ $this->rule->actionsLeft }}</strong></div>
            <div>Estimate date: <strong>~{{ $this->rule->estimatedDate->format('d.m.Y') }}</strong></div>
        </x-slot>
    </x-jet-action-section>
</div>
