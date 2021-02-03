<form method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">

    <input type="date" name="date">

    <input type="submit">
</form>
