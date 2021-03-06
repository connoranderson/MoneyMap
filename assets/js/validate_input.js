function validateForm() {

	var elements = document.getElementById("analyze").elements;

  var start_age = parseInt(document.forms["analyze"]["start"].value);
  var end_age = parseInt(document.forms["analyze"]["retirement"].value);

  if(end_age<= start_age){
    alert("Make sure you pick a retirement age after your current age!");
    return false;
  }

	for(var i = 0; i < elements.length; i++)	{
		if (!isNumeric(elements[i].value) && elements[i].getAttribute('type').toLowerCase() != "checkbox" && elements[i].getAttribute('type').toLowerCase() != "radio" && elements[i].getAttribute('type').toLowerCase() != "submit") {
			alert("Make sure you submit valid numbers (no letters, just numbers and decimals)");
			return false;
		}
	}

	return true;
}
function isNumeric(n) {
  n = correctPercentageInput(correctIntInput(n)); // Allows commas and percentage signs in form data
  return (!isNaN(parseInt(n) || parseFloat(n)) && isFinite(n));
}

function correctIntInput(input){
  input = input.replace(/\$/g, '');
  input = input.replace(/,/g , '');
  return input;
}


function correctPercentageInput(input){
  if(input.indexOf("%") != -1)
  {
    input = input.replace('%', '');
  }              
  return input;
}
