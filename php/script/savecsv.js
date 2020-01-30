$(document).ready(function() {
$('#export').click(function() {
  var th = [];
  var data = [];
  $('.table th').each(function() {
    th.push($(this).text());
  });
  $('.table td').each(function() {
    data.push($(this).text());
  });
  var CSVString = csvRow(th, th.length, '');
  CSVString = csvRow(data, th.length, CSVString);
  var link = document.createElement("a");
  var blob = new Blob(["\ufeff", CSVString]);
  var url = URL.createObjectURL(blob);
  link.href = url;
  link.download = "GaugeData.csv";
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
});
function csvRow(array, coloumns, init) {
  var row = '';
  var delimeter = ',';
  var newLine = '\r\n';
  function splitArray(_array, _count) {
    var splitted = [];
    var result = [];
    _array.forEach(function(item, i) {
      if ((i + 1) % _count === 0) {
        splitted.push(item);
        result.push(splitted);
        splitted = [];
      } else {
        splitted.push(item);
      }
    });
    return result;
  }
  var arraySplit = splitArray(array, coloumns);
  arraySplit.forEach(function(item1) {
    item1.forEach(function(item, i) {
      row += item + ((i + 1) === item1.length ? '' : delimeter);
    });
    row += newLine;
  });
  return init + row;
}
});
