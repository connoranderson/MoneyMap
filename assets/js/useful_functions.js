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


function roundToPrecision(data,precision){
  var orderOfMagnitude = 0;

  for(var i=0; i<data.length; i++) {
    maxElem = Math.max.apply(Math,data[i]);
    orderOfMagnitude = Math.floor(Math.log(maxElem) / Math.LN10 + 0.000000001);
    for(var j=0; j<data[i].length; j++) {
      if(i != 1){
        data[i][j] /= Math.pow(10,orderOfMagnitude-precision);
        data[i][j] = Math.round(data[i][j]);
        data[i][j] /= Math.pow(10,-(orderOfMagnitude-precision));
      }else{
        data[i][j] /= Math.pow(10,orderOfMagnitude-precision);
        data[i][j] = Math.round(data[i][j]);
        data[i][j] /= Math.pow(10,precision);
      }
    }
  }

  return data;
}

function correctIntInput(input){
  input = input.replace(/\$/g, '');
  input = input.replace(/,/g , '');
  return input;
}


function correctPercentageInput(input){
  if(input.indexOf("%") != -1){
    input = input.replace('%', '');
    var input_double = parseFloat(input)/100;
    input = input_double.toString();
  }else if(parseFloat(input)>1){
    var input_double = parseFloat(input)/100;
    input = input_double.toString();
  }              
  return input;
}

function processNum(input){
  input = correctIntInput(input);
  output = parseFloat(input);
  return output;
}

function processPercentage(input){
  input = correctPercentageInput(input);
  output = parseFloat(input);
  return output;
}
