<h1>Contact</h1>

<form action="/contact" method="post">
    <div>
        <label for="subject">Subject</label><br>
        <input type="text" name="subject"><br>
    </div>
    <div>
        <label for="email">Email address</label><br>
        <input type="email" name="email">
    </div>
    <div>
        <label for="body">Body</label><br>
        <textarea name="body" id="body" cols="30" rows="10"></textarea>
    </div>
    <button type="submit">Submit</button>
</form>