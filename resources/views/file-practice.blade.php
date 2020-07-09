<br><br>

<h1>Upload a file</h1>

<form action="files/people" method="post" enctype="multipart/form-data">
{{--    @csrf()--}}
    <input type="file" name="people_file">
    <button type="submit">upload</button>
</form>