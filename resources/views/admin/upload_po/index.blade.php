<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Document</title>
    <style>
        .container {

            width: max-content;
            height: auto;
            border-radius: 1px solid black;
        }
      
        #comments{ display:none; }
    </style>
</head>

<body>




    <div class="row" style="margin: auto;margin-top:250px;padding:20px;">
        <div class="col-md-12 ">
            <div class="container mr-auto">
                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif

                <h5 class="text-center ">Upload Purchase Order </h5>
                <form action="{{ route("admin.quotegeneration.submit_po") }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <div class="form-group mt-1">
                        <label for="">Do you have purchase order number?</label>
                        <select name="order_type" class="form-control" id="order">
                            <option value="yes">YES</option>
                            <option value="no">NO</option>
                        </select>
                    </div>
                    <div id="file_div">
                        <div class="form-group" >
                           <label for="">PO Number</label>
                            <input type="text" class="form-control" name="po_no" id="po_order" required>
                            <input type="hidden" name="id" value="{{$quote_code}}">
                        </div>
                        <div class="form-group" id="">
                            <label for="">Upload File</label>
                            <input type="file" class="form-control" id="file_po" name="po_file" required>
                        </div>
                    </div>
                    <div  id="comments">
                        <div class="form-group">
                           <label for="">Enter Your Comments</label>
                           <textarea name="po_comment" id="" class="form-control" required></textarea>
                        </div>
                        
                    </div>
                    <button type="submit" class="btn btn-success "> save</button>
                </form>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            $(document).on('change', "#order", function(e) {

                var order = $('#order').val();
                var op_number = $('#po_order').val();
                // alert(op_number);

                if (order == 'yes') {
                    $('#file_div').show();
                    $('#comments').hide();
                } else {
                    $('#comments').show();
                    $('#file_div').hide();
                }
            });
        </script>
</body>

</html>