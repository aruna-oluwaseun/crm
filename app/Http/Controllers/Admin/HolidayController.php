<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AddNationalHoliday;
use App\Mail\HolidayActioned;
use App\Mail\HolidayRequest;
use App\Models\Holiday;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HolidayController extends Controller
{

    /**
     * @param string $user_id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function fetch($user_id = 'all')
    {
        $events = Holiday::fetch($user_id);

        if(\request()->ajax())
        {
            return response()->json($events);
        }

        return $events;
    }

    /**
     * Add calendar event
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required',
            'start_date'=> 'required',
            'type'      => 'required'
        ]);

        $type = $request->input('type');

        $status = $type == 'holiday' ? 'pending' : 'approved';
        if($request->exists('status'))
        {
            $status = $request->input('status');
            $status = $type == 'holiday' ? $status : 'approved';
        }

        $start_date = $request->input('start_date');
        $start_time = '00:00:00';//$request->input('start_time');
        $start_type = $request->input('start_type');
        $end_date = $request->input('end_date');
        $end_time = '23:59:00';//$request->input('end_time');
        $end_type = $request->input('end_type');

        if(!$end_date) {
            $end_date = $start_date;
            $end_time = '23:59:00';
            $end_type = 'full_day';
        }

        $days_off = 1;
        $start_days_off = null;
        $end_days_off = null;

        if($start_type == 'full_day') {
            $start_time = '00:00:00';
        }

        if($end_type == 'full_day') {
            $end_time = '23:59:00';
        }

        if($start_type == 'quarter_day') {
            $days_off = 0.75;
            $start_time = '10:00:00';
        }

        if($end_type == 'quarter_day') {
            $end_time = '10:00:00';
        }

        if($start_type == 'half_day') {
            $days_off = 0.5;
            $start_time = '12:00:00';
        }

        if($end_type == 'half_day') {
            $end_time = '12:00:00';
        }

        $start  = $start_date.' '.$start_time;
        $end    = $end_date.' '.$end_time;

        if($start_date != $end_date) {

            $dt_start = new \DateTime($start_date.' 00:00:00');
            $dt_end = new \DateTime($end_date.' 23:59:00');

            $diff = $dt_start->diff($dt_end);

            if($start_type == 'full_day') {
                $start_days_off = 1;
            }

            if($start_type == 'half_day') {
                $start_days_off = 0.5;
            }

            if($start_type == 'quarter_day') {
                $start_days_off = 0.75; // left at 10am
            }

            if($end_type == 'full_day') {
                $end_days_off = 1;
            }

            if($end_type == 'half_day') {
                $end_days_off = 0.5;
            }

            if($end_type == 'quarter_day') {
                $end_days_off = 0.25; // comes in at 10am
            }

            if($diff->d == 1) {
                //info('Run 1');
                $days_off = $start_days_off+$end_days_off;
            }
            else
            {
                if($start_days_off == 1 && $end_days_off == 1)
                {
                    //info('Run 2');
                    $days_off = $diff->d + 1;
                }
                elseif($start_days_off != 1 && $end_days_off == 1)
                {
                    //info('Run 3');
                    $days_off = $diff->d + $start_days_off;
                }
                elseif($start_days_off == 1 && $end_days_off != 1)
                {
                    //info('Run 4');
                    $days_off = $diff->d + $end_days_off;
                }
                else
                {
                    //info('Run 5, the day difference is '. $diff->d);
                    $days_off = ($diff->d - 1) + ($start_days_off + $end_days_off);
                }
            }

        }

        $user_id = $request->user()->id;
        if($request->exists('user_id')) {
            $user_id = $request->input('user_id');
        }

        if($holiday = Holiday::create([
            'title'         => $request->input('title'),
            'user_id'       => $user_id,
            'date_start'    => $start,
            'date_end'      => $end,
            'start_type'    => $start_type,
            'end_type'      => $end_type,
            'time_off'      => $days_off,
            'description'   => $request->input('description'),
            'type'          => $type,
            'status'        => $status,
            'approved'      => $status == 'approved' ? date('Y-m-d H:i:s') : null,
            'approved_by'   => $status == 'approved' ? get_user()->id : null,
        ]))
        {
            // Send holiday request
            if(!$request->user()->can('approve-holidays'))
            {
                if($franchise_users = current_user_franchise()->users)
                {
                    foreach($franchise_users as $franchise_user)
                    {
                        if($franchise_user->can('approve-holidays'))
                        {
                            if($franchise_user->id == get_user()->id) {
                                continue; // dont send notification to self
                            }

                            $stat = Mail::to($franchise_user->email)->queue(new HolidayRequest($holiday));

                            if($start_time == '00:00:00') {
                                $from = format_date($start_date);
                            } else {
                                $from = format_date_time($start);
                            }

                            if($end_time == '23:59:00') {
                                $to = format_date($end_date);
                            } else {
                                $to = format_date_time($end);
                            }

                            if($from == $to) {
                                $to = '';
                            } else {
                                $to = ' - '.$to;
                            }

                            // Add in house notification
                            Notification::store([
                                'title'    => get_user()->getFullNameAttribute().' requested holiday : <span class="text-primary">'.$from.'  '.$to.'</span>',
                                'user_id'  => $franchise_user->id,
                                'payload'  => null,
                                'url'      => 'calendar?requests=yes',
                            ]);
                        }
                    }
                }
            }

            if($request->ajax())
            {
                return response()->json([
                    'success' => true
                ]);
            }
        }

        if($request->ajax())
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add event to calendar'
            ]);

            return back()->withInput()->with('success','Added event to calendar');
        }

        return back()->withInput()->with('error','Failed to add event to calendar');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id'        => 'required',
            'title'     => 'required',
            'start_date'=> 'required',
            'type'      => 'required'
        ]);

        $type = $request->input('type');

        $status = $type == 'holiday' ? 'pending' : 'approved';
        if($request->exists('status'))
        {
            $status = $request->input('status');
            $status = $type == 'holiday' ? $status : 'approved';
        }

        $start_date = $request->input('start_date');
        $start_time = '00:00:00';//$request->input('start_time');
        $start_type = $request->input('start_type');
        $end_date = $request->input('end_date');
        $end_time = '23:59:00';//$request->input('end_time');
        $end_type = $request->input('end_type');

        if(!$end_date) {
            $end_date = $start_date;
            $end_time = '23:59:00';
            $end_type = 'full_day';
        }

        $days_off = 1;
        $start_days_off = null;
        $end_days_off = null;

        if($start_type == 'full_day') {
            $start_time = '00:00:00';
        }

        if($end_type == 'full_day') {
            $end_time = '23:59:00';
        }

        if($start_type == 'quarter_day') {
            $days_off = 0.75;
            $start_time = '10:00:00';
        }

        if($end_type == 'quarter_day') {
            $end_time = '10:00:00';
        }

        if($start_type == 'half_day') {
            $days_off = 0.5;
            $start_time = '12:00:00';
        }

        if($end_type == 'half_day') {
            $end_time = '12:00:00';
        }

        $start  = $start_date.' '.$start_time;
        $end    = $end_date.' '.$end_time;

        if($start_date != $end_date) {

            $dt_start = new \DateTime($start_date.' 00:00:00');
            $dt_end = new \DateTime($end_date.' 23:59:00');

            $diff = $dt_start->diff($dt_end);

            if($start_type == 'full_day') {
                $start_days_off = 1;
            }

            if($start_type == 'half_day') {
                $start_days_off = 0.5;
            }

            if($start_type == 'quarter_day') {
                $start_days_off = 0.75; // left at 10am
            }

            if($end_type == 'full_day') {
                $end_days_off = 1;
            }

            if($end_type == 'half_day') {
                $end_days_off = 0.5;
            }

            if($end_type == 'quarter_day') {
                $end_days_off = 0.25; // comes in at 10am
            }

            if($diff->d == 1) {
                $days_off = $start_days_off+$end_days_off;
            }
            else
            {
                if($start_days_off == 1 && $end_days_off == 1)
                {
                    $days_off = $diff->d + 1;
                }
                elseif($start_days_off != 1 && $end_days_off == 1)
                {
                    $days_off = $diff->d + $start_days_off;
                }
                elseif($start_days_off == 1 && $end_days_off != 1)
                {
                    $days_off = $diff->d + $end_days_off;
                }
                else
                {
                    $days_off = ($diff->d - 1) + ($start_days_off + $end_days_off);
                }
            }

        }
        // Work out if its a full day half day or quarter

        $data = [
            'title'         => $request->input('title'),
            'date_start'    => $start,
            'date_end'      => $end,
            'start_type'    => $start_type,
            'end_type'      => $end_type,
            'time_off'      => $days_off,
            'description'   => $request->input('description'),
            'type'          => $type,
            'status'        => $status,
            'approved'      => $status == 'approved' ? date('Y-m-d H:i:s') : null,
            'approved_by'   => $status == 'approved' ? get_user()->id : null,
        ];

        if($request->exists('user_id')) {
            $data['user_id'] = $request->input('user_id');
        }

        if(Holiday::find($id)->update($data))
        {
            if($request->ajax())
            {
                return response()->json([
                    'success' => true
                ]);
            }
        }

        if($request->ajax())
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event in calendar'
            ]);

            return back()->withInput()->with('success','Event edited in calendar');
        }

        return back()->withInput()->with('error','Failed to edit event in calendar');
    }

    /**/
    public function destroy(Request $request, $id)
    {
        if( Holiday::destroy($id) )
        {
            if($request->ajax())
            {
                return response()->json([
                   'success' => true
                ], 200);
            }

            return back()->with('success','Event removed');
        }

        if($request->ajax())
        {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove event please try again.'
            ], 200);
        }

        return back()->with('success','Event removed');
    }

    /**/
    public function actionHolidayRequests(Request $request)
    {
        $holidays = $request->input('holiday');

        foreach($holidays as $id => $holiday)
        {

            if(!isset($holiday['status'])) {
                continue;
            }

            if($holiday['status'] == 'approved') {
                $holiday['approved_by'] = current_user_id();
                $holiday['approved'] = date('Y-m-d H:i:s');
            }

            $detail = Holiday::find($id);

            if(!$detail->update($holiday)) {
                info('Failed to update holiday ID : '.$id);
            }

            Mail::to($detail->user->email)->queue(new HolidayActioned($detail));

            Notification::store([
                'title'    => get_user()->getFullNameAttribute().' '.$detail->status.' your holiday request for : <span class="text-primary">'.format_date_time($detail->date_start).'  '.format_date_time($detail->date_end).'</span>',
                'user_id'  => $detail->user_id,
                'payload'  => null,
                'url'      => 'users/'.$detail->user_id.'#tab-holidays',
            ]);

        }

        return back()->with('success','Holiday requests updated');
    }

    /**
     * Job will process yearly automatically
     * but the user can call it manually here
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchNationalHolidays()
    {
        try {
            if(!AddNationalHoliday::dispatch())
            {
                Throw new \Exception();
            }

            return redirect('/')->with('success','National holidays manually updated');

        } catch (\Throwable $e) {
            report($e);
        }

        return redirect('/')->with('error','Sorry could not grab the national holidays');

    }
}
