@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.marketingcampaign.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.marketingcampaign.fields.mk_campign_id') }}
                        </th>
                        <th>
                            {{ trans('cruds.marketingcampaign.fields.campaign_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.marketingcampaign.fields.campaign_email') }}
                        </th>
                        <th>
                            {{ trans('cruds.marketingcampaign.fields.campaign_contact') }}
                        </th>
						<th>
                            {{ trans('cruds.marketingcampaign.fields.campaign_organization') }}
                        </th>

						<th>
                            {{ trans('cruds.marketingcampaign.fields.target_language') }}
                        </th>
						
						<th>
                            {{ trans('cruds.marketingcampaign.fields.campaign_status') }}
                        </th>
						
						<th>
                            {{ trans('cruds.marketingcampaign.fields.created_at') }}
                        </th>				
						
						
                        <!-- <th style="width:15% !important;">
                            &nbsp;
                        </th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($marketing_campaign as $key => $mcampaign)
                         <tr data-entry-id="{{ $mcampaign->mk_campign_id }}">
                            <td>

                            </td>  
                            <td>
                                {{ $mcampaign->reference_id ?? '' }}
                            </td>
                            <td>
                                {{ $mcampaign->visitor_name ?? '' }}
                            </td>
                            <td>
                                {{ $mcampaign->visitor_email ?? '' }}
                            </td>
                            <td>
                                {{ $mcampaign->visitor_contact ?? '' }}
                            </td>
                            <td>
                                {{ $mcampaign->visitor_organization ?? '' }}
                            </td>
                            <td>
                                {{ $mcampaign->target_language ?? '' }}
                            </td>
                            <td>
								<div class="dropdown">
									<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
									{{ $mcampaign->campaign_status ?? '' }}
									</button>
									<div class="dropdown-menu">
										@if($mcampaign->campaign_status != 'In-progress')
										<a class="dropdown-item" href="#">In-progress</a>
										@endif
										@if($mcampaign->campaign_status != 'Translation')
										<a class="dropdown-item" href="#">Translation</a>
										@endif
										@if($mcampaign->campaign_status != 'Review')
										<a class="dropdown-item" href="#">Review</a>
										@endif
										@if($mcampaign->campaign_status != 'Completed')
										<a class="dropdown-item" href="#">Completed</a>
										@endif
										@if($mcampaign->campaign_status != 'Done')
										<a class="dropdown-item" href="#">Done</a>
										@endif
									</div>
								</div>
                            </td>
                            <td>
                                {{ $mcampaign->created_on ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
</script>
@endsection
