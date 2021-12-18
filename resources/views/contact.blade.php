<!DOCTYPE html>
<html>

<head>
    <title>Laravel 8 Form Example Tutorial</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(session('danger'))
        <div class="alert alert-danger">
            {{ session('danger') }}
        </div>
        @endif
        <div class="card">
            <div class="card-header text-center font-weight-bold">
                LaraHub - Please provide following detatils!
            </div>

            <div class="card-body">
                <form name="add-contact-data" id="add-contact-data" method="post" action="{{url('store-contact-data')}}">
                    @csrf
                    <div class="form-group">
                        <label>First name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Please provide your first name" required="">
                    </div>
                    <div class="form-group">
                        <label>Last name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Please provide your last name" required="">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Please provide your email" required="">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>
            </div>
        </div>
        <div><a href="{{url('get-contact-data')}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Contacts list</a></div>
    </div>

</body>

</html>