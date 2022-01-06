<div class="row"><!-- BEGIN ROW -->

        {{ view('admin.templates.alert') }}

        <!-- Form -->
        <div class="col-md-6 mb-3"><!-- start col 6-->

            <form method="post" wire:submit.prevent="save">
                @csrf
                <div class="form-group">
                    <label class="form-label">VAT Number *</label>
                    <input wire:model.defer="vrn" id="obligation-vrn" type="number" value="{{ $vrn }}" class="form-control" name="vrn" required>{{----}}
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">From date <small class="text-danger">Required unless status set is open</small></label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input wire:ignore type="text" class="form-control" value="{{ $from_date }}" name="from_date" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">To date <small class="text-danger">Required unless status set is open</small></label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-left">
                                    <em class="icon ni ni-calendar"></em>
                                </div>
                                <input wire:ignore type="text" class="form-control" value="{{ $to_date }}" name="to_date" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="notes">Status </label>
                    <div class="form-control-wrap">
                        <select wire:model.defer="status" class="form-control" name="status" id="status" data-search="on">
                            <option value="all" selected="selected">Retrieve All</option>
                            <option value="O">Open</option>
                            <option value="F">Fulfilled</option>
                        </select>
                    </div>
                </div>

                <div class="form-group testing-field" style="display: block">{{--{{$environment->isLive() ? 'none' : ''}}--}}
                    <label class="form-label" for="notes">Gov Test Scenario </label>
                    <div wire:model.defer="gov_test_scenario" class="form-control-wrap">
                        <select class="form-control" name="gov_test_scenario" id="gov-test-scenario" data-search="on">

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn btn btn-lg btn-primary">Retrieve</button>
                </div>

            </form>
        </div><!-- end col 6-->

        <div class="col-md-6 mb-3"><!-- start col 6 -->

            <div wire:loading.delay class="loading text-center mt-3"><img width='100' src='{{ asset('assets/files/img/loading.gif') }}'></div>

            <?php if( isset($obligations) && !is_null($obligations) ) : ?>
            <table class="table table-striped">
                <thead class="thead-dark">
                    <th>Period</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Due</th>
                    <th>Status</th>
                </thead>
                <tbody>
                <?php foreach ($obligations as $obligation) : ?>
                    <tr>
                        <?php
                            if($obligation['status'] == 'O') {
                                $obligation['status'] = 'Open';
                            }
                            if($obligation['status'] == 'F') {
                                $obligation['status'] = 'Fulfilled';
                            }
                        ?>
                        <td>
                            <a href="#" data-period-key="{{ $obligation['periodKey'] }}" data-due-date="{{ format_date($obligation['due']) }}" class="period-link">
                                {{ $obligation['periodKey'] }}
                            </a>
                        </td>
                        <td>{{ format_date($obligation['start'],'dS M Y') }}</td>
                        <td>{{ format_date($obligation['end'],'dS M Y') }}</td>
                        <td>{{ format_date($obligation['due'],'dS M Y') }}</td>
                        <td>{{ $obligation['status'] }}</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php else : ?>

            <div class="alert alert-warning">
                Please select retrieve to fetch obligations
            </div>

            <?php endif; ?>


        </div><!-- end col 6-->

</div><!-- END ROW -->

