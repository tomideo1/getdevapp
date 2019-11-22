@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <h1 class="mt-4 mb-5">My Expenses </h1>
        @if(Session::has('success'))
            <div class="alert alert-success">
                <p>{{Session::get('success')}}</p>
            </div>
            @elseif(Session::has('error'))
            <div class="alert alert-danger">
                <p>{{Session::get('error')}}</p>
            </div>
            @elseif($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <div class="table-responsive">
            <table  class="table table-bordered ">
                <thead>
                <th>Expense</th>
                <th>VAT</th>
                <th>Reason</th>
                <th>Date</th>
                </thead>
                <tbody>
                @foreach($all_expenses as $expenses)
                    <tr>
                        <td>{{$expenses->expense}}</td>
                        <td>{{$expenses->vat}} %</td>
                        <td>{{$expenses->reason}}</td>
                        <td>{{$expenses->date}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{$all_expenses->links()}}
        </div>
    </div>

    <a href="#" class="float" title="Add Expense" data-toggle="modal" data-target="#exampleModal">
        <i class="fa fa-plus my-float"></i>
    </a>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Expense</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{route('expenses.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Expense</label>
                           <div class="form-row">
                               <input type="text" class="form-control col-md-4 mr-3 mb-1" id="expense_input"  placeholder="Enter expense" >
                               <input type="text" class=" form-control col-md-6" id="expense" name="expense" readonly>
                           </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">VAT</label>
                            <input type="text" class="form-control" id="vat"   value="20%"  name="vat" readonly>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Reason</label>
                            <input type="text" class="form-control" id="reason"  placeholder="Enter Reason" name="reason">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Date</label>
                            <input type="date" class="form-control" id="date"  placeholder="Enter date" name="date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
        var vat= parseInt($("#vat").val());
        var output= $("#expense");
        $("#expense_input").keyup(function() {
            // if($(this).val().indexOf('EUR') > -1){
            //     console.log('exists');
            //     let amount = parseFloat($(this).val());
            //     let result = convert('EUR','GBP',amount);
            //     $(this).val(result)
            // }
            var value = parseFloat($(this).val());
            let result = value * (vat /100);
            result = value + result;
            result = formatNumber(result);
            output.val(result)

        });

        
        function convert(from_,to,amount) {
            let endpoint = 'convert';
            let access_key = '2d6391c8c8b3d32ffabc8799011f5ff1'
            $.ajax({
                url: 'http://data.fixer.io/api/' + endpoint + '?access_key=' + access_key + '&from=' + from_ + '&to=' + to + '&amount=' + amount,
                dataType: 'json',
                success: function (json) {

                    // access the conversion result in json.result
        