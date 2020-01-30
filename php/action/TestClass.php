<?php
include('class.adminAction.php');

echo "Hello";
$postArray = ['firstname' => "Jaryd", 'lastname' => "Fisherman", 'email' => "jf@percept.system", 'password' => "GTI123ph"];
echo "Hello";

$hell = new adminAction();
$hell->form_submition($postArray);
echo $hell;
// $pr = $hell->getEmail();

echo "<h1>YO</h1>";
?>


<?php
class foo
{
    function do_foo()
    {
        echo "Doing foo."; 
    }
}

$bar = new foo;
$bar->do_foo();
?>