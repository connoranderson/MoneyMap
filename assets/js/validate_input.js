function validateForm() {
  alert("Validation Started");

	var elements = document.getElementById("analyze").elements;

  var start_age = parseInt(document.forms["analyze"]["start"].value);
  var end_age = parseInt(document.forms["analyze"]["retirement"].value);

  alert(start_age.toString());  
  alert(end_age.toString());

  if(end_age<= start_age){
    alert("Make sure you pick a retirement age after your current age!");
    return false;
  }

	for(var i = 0; i < elements.length; i++)
    alert("In loop");
	{
		if (isNumeric(elements[i].value) && elements[i].getAttribute('type').toLowerCase() != "checkbox" && elements[i].getAttribute('type').toLowerCase() != "radio" && elements[i].getAttribute('type').toLowerCase() != "submit") {
			alert(elements[i].getAttribute('name').toLowerCase());
			alert("Make sure you are submitting valid numbers!");
			return false;
		}
	}

	return true;
}

function isNumeric(n) {
  // n = correctPercentageInput(correctIntInput(n));
  return !isNaN(partseInt(n) || parseFloat(n)) && isFinite(n);
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
    var input_double = parseFloat(input)/100;
    input = input_double.toString();
  }
  // else if(parseFloat(input) >= 1){
  //   var input_double = parseFloat(input)/100;
  //   input = input_double.toString();
  // }               
  return input;
}
