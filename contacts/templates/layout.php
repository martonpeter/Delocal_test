<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <title>Insert and Retrieve data from MySQL database with ajax</title>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="wrapper">
            <div id="display_area">                               
            </div>
            <form class="contact_form">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input name="email" id="email" cols="30" rows="5" required></input>
                </div>
                <div>
                    <label for="phone_number">Phone number:</label>
                    <input name="phone_number" id="phone_number" cols="30" rows="5" required></input>
                </div>
                <div>
                    <label for="address">Address:</label>
                    <input name="address" id="address" cols="30" rows="5" required></input>
                </div>
                <button type="button" id="submit_btn">POST</button>
                <button type="button" id="update_btn" style="display: none;">UPDATE</button>
            </form>
            <form class="contact_form">
                <div>
                    <label for="name">Id:</label>
                    <input type="text" name="id" id="id" required>
                </div>
                <button type="button" id="search_btn">SEARCH</button>
            </form>
        </div>
    </body>
</html>
<!-- Add JQuery -->
<script src="js/jquery.min.js"></script>
<script src="js/scripts.js"></script>
