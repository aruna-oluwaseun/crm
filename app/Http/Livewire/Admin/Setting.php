<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Setting extends Component
{
    public $settings = [
        'default_holiday_days' => 28,
        'inclusive_of_bank_holidays'=> 1,
        'testing_payments' => 1
    ];

    public function mount()
    {
        $this->settings['default_holiday_days'] = get_setting('default_holiday_days');
        $this->settings['inclusive_of_bank_holidays'] = get_setting('inclusive_of_bank_holidays');
        $this->settings['testing_payments'] = get_setting('testing_payments');
    }

    public function render()
    {
        return view('livewire.admin.setting');
    }

    public function save()
    {
        $success = 0;
        foreach ($this->settings as $key => $value)
        {
            if(is_bool($value)) {
                $value = strval(intval($value));
            }

            if(\App\Models\Setting::find($key)->update(['value' => $value]))
            {
                $success++;
            }
        }

        if($success) {
            return session()->flash('success','Settings updated');
        }

        return session()->flash('error', 'Failed to save settings, please try again');

    }
}
