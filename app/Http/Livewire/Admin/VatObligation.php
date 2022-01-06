<?php

namespace App\Http\Livewire\Admin;

use App\Models\Franchise;
use App\Repositories\HMRC\VAT\RetrieveVATObligationsRequest;
use Livewire\Component;
use function Livewire\str;

class VatObligation extends Component
{

    // Initial
    public $franchise;
    public $obligations = null;

    // Form vars
    public $vrn;
    public $from_date;
    public $to_date;
    public $status;
    public $gov_test_scenario;

    protected $rules = [
        'vrn'               => 'required|min:9',
        'from_date'         => 'nullable|required_if:status,all,F',
        'to_date'           => 'nullable|required_if:status,all,F',
        'status'            => 'nullable',
        'gov_test_scenario' => 'nullable',
    ];

    protected $listeners = [
        'set:from-date' => 'setFromDate',
        'set:to-date' => 'setToDate',
        'init:date-picker' => 'addDatePickers'
    ];

    public function mount($franchise = null)
    {
        if(!$franchise) {
            $franchise = Franchise::find(current_user_franchise_id());
        }

        $this->franchise = $franchise;
        if($franchise->vat_number) {
            $this->vrn = str_replace(' ','',$franchise->vat_number);
        }

        $this->addDatePickers();
    }

    public function render()
    {
        return view('livewire.admin.vat-obligation');
    }

    public function setFromDate($date)
    {
        $this->from_date = $date;
    }

    public function setToDate($date)
    {
        $this->to_date = $date;
    }

    public function addDatePickers()
    {
        $this->dispatchBrowserEvent('add-date-picker', []);
    }

    public function save()
    {
        $validated = $this->validate();

        $validated['status'] = $validated['status'] == 'all' ? null : $validated['status'];
        $govTestScenario = null;

        try {
            $request = new RetrieveVATObligationsRequest(
                $validated['vrn'],
                $validated['from_date'],
                $validated['to_date'],
                $validated['status']
            );

            if (!is_null($validated['gov_test_scenario'])) {
                $request->setGovTestScenario($govTestScenario);
            }
            $response = $request->fire();

            if($response->isSuccess())
            {
                $this->addDatePickers();
                session()->flash('success', 'Obligations retrieved.');
                return $this->obligations = $response->getArray()['obligations'];
            }


        } catch (\Throwable $exception) {
            custom_reporter($exception);
        }

        $this->addDatePickers();
        return session()->flash('error', 'Failed to retrieve vat obligations '.(isset($exception) ? $exception->getMessage() : ''));

    }
}
