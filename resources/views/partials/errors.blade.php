@if(count($errors))
<ul style='background: tomato; color: white;'>
    @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
    @endforeach
</ul>
@endif
