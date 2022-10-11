@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
    
    
      <h3>Currencies</h3>
    </div>

<div class="card-body">
    <div class="row">
 
       <div class="col-sm-6">
          <form action="{{ route('admin.currency.store') }}" method="post"  enctype="multipart/form-data">
          @csrf
         <div class="form-group">
             <lable>Currency Name</lable>
             <input type="text" class="form-control" name="currency_name">
         </div>
         <div class="form-group">
             <lable>Currency Symbol</lable>
             <input type="text" class="form-control" name="currency_symbol">
         </div>
         <div class="form-group">
             <lable>Currency Code</lable>
             <input type="text" class="form-control" name="currency_code">
         </div>
         
         <br>

         <button class="btn btn-primary" type="submit">submit</button>
        </form>

         </div>
      <div class="col-sm-6">
          
          </div>
     </div>
     <br>
     <div class="card-header text-center bg-dark">
      <h4 >All Currencies</h4>
      </div>
      <br>
     <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th>
                          id  
                        </th>
                        <th>
                          Currency Name 
                        </th>
                        <th>
                           Currency Symbol
                        </th>
                        <th>
                            Currency Code
                        </th>
                        <th>
                            Unit
                        </th>
                        <th>
                            INR
                        </th>
                        <th >
                           Status
                        </th>
                        <th >
                           Options
                        </th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($currencies as $currency)
                  
                   <tr>
                <form action="{{ route('admin.currency.update',[$currency->id]) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                @csrf
                    @method('PUT')   
                   <td>{{ $currency->id}}</td>
                    <td>{{ $currency->currency_name}}</td>
                    <td>{{ $currency->currency_symbol }}</td>
                    <td>{{ $currency->currency_code }}</td>
                    <td>
                      <input type="hidden" name="id" value="{{ $currency->id }}"/>
                      <input type="number" name="unit"  step=0.01 value="{{ $currency->unit }}" required/>
                    </td>
                    <td>
                      <input type="number" name="inr"  step=0.01 value="{{ $currency->inr }}" required/>
                    </td>
                    <td>
                      <select name="status">
                        <option value="Active" {{(($currency->status == 'Active')? 'selected':'')}}>Active</option>
                        <option value="Inactive" {{(($currency->status == 'Inactive')? 'selected':'')}}>Inactive</option>
                      </select>
                    </td>
                    <td><input class="btn btn-sm btn-success" type="submit" value="Save"></td>
                   </form>
                   </tr>
                  @endforeach
                       
                </tbody>
            </table>

</div>
</div>
@endsection
@section('scripts')
@parent
<script>
</script>
@endsection
