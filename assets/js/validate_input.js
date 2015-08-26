function validateForm() {

	var elements = document.getElementById("analyze").elements;
	for(var i = 0; i < elements.length; i++)
	{
		if (isNaN(elements[i].value) && elements[i].getAttribute('type').toLowerCase() != "radio" && elements[i].getAttribute('type').toLowerCase() != "submit") {
			alert("Make sure you are submitting valid numbers!");
			return false;
		}
	}
	var start_age = parseInt(document.forms["analyze"]["start"].value);
	var end_age = parseInt(document.forms["analyze"]["retirement"].value);

	if(end_age<= start_age){
		alert("Make sure you pick a retirement age after your current age!");
		return false;
	}

	return true;
}
