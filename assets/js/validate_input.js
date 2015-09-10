function validateForm() {

	var elements = document.getElementById("analyze").elements;
	for(var i = 0; i < elements.length; i++)
	{
		if (isNumeric(elements[i].value) && elements[i].getAttribute('type').toLowerCase() != "radio" && elements[i].getAttribute('type').toLowerCase() != "submit") {
			alert(elements[i].getAttribute('name').toLowerCase());
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

function isNumeric(n) {
  return !isNaN(partseInt(n) || parseFloat(n)) && isFinite(n);
}

function afterTaxIncome(income, taxStatus) {
  if(taxStatus == 1){
  	if(income < 9225){
  		income = income - 0.1*income;
  	}else if (income < 37450){
  		income = income - 922.5 - 0.15*(income-9225);
  	}else if (income < 90750){
  		income = income -  5156.25 - 0.25*(income-37450);
  	}else if (income < 189300){
  		income = income - 18481 -  0.28*(income-90750);
  	}else if (income < 411500){
  		income = income - 46075 -  0.33*(income-189300);
  	}else if (income < 413200){
  		income = income - 199401 -  0.35*(income-411500);
  	}else {
  		income = income - 199996 -  0.396*(income-413200);
  	}


  }else if(taxStatus == 2){

  	if(income < 18450){
  		income = income - 0.1*income;
  	}else if (income < 74900){
  		income = income - 1845 - 0.15*(income-18450);
  	}else if (income < 151200){
  		income = income -  10312 - 0.25*(income-74900);
  	}else if (income < 230450){
  		income = income - 29387-  0.28*(income-151200);
  	}else if (income < 411500){
  		income = income - 51577 -  0.33*(income-230450);
  	}else if (income < 464850){
  		income = income - 111324 -  0.35*(income-411500);
  	}else {
  		income = income - 129996 -  0.396*(income-464850);
  	}

  }else{


  	if(income < 9225){
  		income = income - 0.1*income;
  	}else if (income < 37450){
  		income = income - 922.5 - 0.15*(income-9225);
  	}else if (income < 75600){
  		income = income -  5156.25 - 0.25*(income-37450);
  	}else if (income < 115225){
  		income = income - 14693 -  0.28*(income-75600);
  	}else if (income < 205750){
  		income = income - 25788 -  0.33*(income-115225);
  	}else if (income < 232425){
  		income = income - 55662-  0.35*(income-205750);
  	}else {
  		income = income - 64998 -  0.396*(income-232425);
  	}



  }


  return income;
}
