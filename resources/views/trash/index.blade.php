@extends('layout.master')
@section('breadcrumb', 'Applications')
@section('style')
<style>
#table_length{
    display: none;
}
</style>
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
                    <h4 class="card-title">Trash Application</h4>
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{ url('js/custom/trash/index.js')}}"></script>
@endsection
