<!-- resources/views/people/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Person: {{ $person->name }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Edit Person: {{ $person->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('people.update', $person->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $person->name }}" required>
        </div>
        <div class="form-group">
            <label for="birth_date">Birth Date:</label>
            <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ $person->birth_date }}" required>
        </div>
        <div class="form-group">
            <label for="family_id">Family:</label>
            <select name="family_id" id="family_id" class="form-control">
                <option value="">Select Family</option>
                @foreach ($families as $family)
                    <option value="{{ $family->id }}" {{ $person->family_id == $family->id ? 'selected' : '' }}>{{ $family->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Person</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
