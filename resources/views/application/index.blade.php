@extends('layout.master')
@section('breadcrumb', 'Applications')
@section('style')
<style>
#table_length{
    display: none;
}
</style>
@endsection
@section('dashboard_cards')
@include('layout.dashboard_cards')
@endsection
@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Applications</h4>
                    <div class="d-flex justify-content-between">
                        <div class="col-md-11">
                            <a href="/apps/create" class="btn btn-primary"> <i class="fas fa-plus"></i> Add New Application</a>
                            <button class="btn btn-primary" onclick="sync()" id="myButton" disabled><i class="fas fa-redo"></i> Sync</button>
                        </div>
                        <div class="col-md-1 ml-auto">
                            <div class="dropdown mb-4">
                                <select class="form-control status-dropdown">
                                    <option value="">All</option>
                                    <option value="Published">Published</option>
                                    <option value="NotPublish">NotPublish</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <form action="{{url('app-check')}}" id="sync_form" method="POST">@csrf
                        <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="" id="all_check" /></th>
                                    <th> SR.No </th>
                                    <th> Image </th>
                                    <th> Application Name </th>
                                    <th>Account Name</th>
                                    <th> Status </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{ url('js/custom/application/index.js')}}"></script>
<script>
    $('.status-dropdown').on('change', function(e){
      var status = $(this).val();
    //   if(status == 'All'){
    //     table.ajax.reload();
    //   }
      $('.status-dropdown').val(status)
      table.column(5).search(status).draw();
    })
</script>
@endsection
