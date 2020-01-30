window.onload = function() {

  // when the user clicks the test button...
  $('#testSendData').click(function() {
    var intervalId;

    // retrieve the username of the active session
    var username;
    $.post('action/getUsername.php',
      function(data, status) {
        username = data;
    });

    // send the dummy data asynchronously to a php script
    $.post('action/testSendData.php',
      {
        testNum: 5,
        testStr: 'hi'
      }
    );

    // notify the user that data is being processed
    var loadingMessage = $('<p></p>').text('test in progress...');
    $('body').append(loadingMessage);

    // check periodically whether data has been outputted
    intervalId = setInterval(function() {checkForOutput(username);}, 2000);
  });
};

// ask the server if the out put file has been written yet
function checkForOutput(username) {
  $.post('action/testReturnData.php',
    {
      'username' : username
    },
    function(data, status) {
      if (data != 'no results') {
        alert('mission success:\n' + data);
      }
      else {
        alert('nothing found just yet');
      }
  });
}
