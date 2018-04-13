<!DOCTYPE html>
<html>
<body>

<form action="<?php echo base_url();?>Upload/insert_video" method="post" enctype="multipart/form-data">
  First name: <input type="file" name="commentFile"><br>
  First name: <input type="text" name="roomType"><br>
  <textarea rows="4" cols="50" name="commentText">
    At w3schools.com you will learn how to make a website. We offer free tutorials in all web development technologies. 
  </textarea><br/>
  <input type="submit" value="Submit">
</form>

</body>
</html>
