<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('pegnet.market_cap_history') }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-bordered--dataTable table-paged table-paged--sortable" style="width:100%;" data-ajax="{{ route('market-cap-ajax') }}">
                        <thead class="text-primary">
                        <tr>
                            <th data-type="date">{{ __('generic.date') }}</th>
                            <th data-type="num-fmt">{{ __('pegnet.market_cap') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ mix('js/dataTables.js') }}" defer></script>
@endpush
