<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yangiliklar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    
    <div class="container">
        <div class="card my-3">
            <div class="card-body">
                <div class="px-2 pb-2 bg-transparent fw-bold h3">{{ $news->title }}</div>
                <img class="img-fluid rounded-3 w-100" src="{{ asset($news->image) }}" alt="card-image">
                <p class="p-2 pt-3 text-secondary fs-5">{{ \Carbon\Carbon::parse($news->created_at)->format('H:m d/m/Y') }}</p>
                <p class="bg-transparent px-2 fs-4">{!! $news->content !!}</p>
            </div>
        </div>
    </div>









    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>