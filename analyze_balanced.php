<html>
<head>
	<title>MoneyMap</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="assets/css/main.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>
<body>

	<!-- Page Wrapper -->
	<div id="page-wrapper">

		<!-- Header -->
		<header id="header">
			<h1><a href="index.html">MoneyMap</a></h1>
			<nav id="nav">
				<ul>
					<li class="special">
						<a href="#menu" class="menuToggle"><span>Menu</span></a>
						<div id="menu">
							<ul>
								<li><a href="index.html">Home</a></li>
								<li><a href="form.html">Getting Started</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</nav>
		</header>

		<!-- Main -->
		<article id="main">
			<header>
				<h2>Results</h2>
				<p>Scroll down to see your results</p>
			</header>
			<section class="wrapper style5">
				<div class="inner">

					<p>

						<center><h3>Net Worth</h3></center>

						<script src="Chart.min.js"></script>
						<h3 id = 'ylabel'>Y Label</h3>
						<canvas id="myChart" width="1200" height="600"></canvas>

						<!-- Primary Javascript Scripting -->
						<script src="assets/js/useful_functions.js"></script>
						<script type="text/javascript">
							// Load relevant variables

							var start_age = processNum("<?php echo $_POST["start"] ?>");
							var retirement_age = processNum("<?php echo $_POST["retirement"] ?>");
							var starting_salary = processNum("<?php echo $_POST["salary"] ?>");
							var salary_appreciation = processPercentage("<?php echo $_POST["salary_appreciation"] ?>"); // Value entered is a percentage
							var house_cost = processNum("<?php echo $_POST["house_cost"] ?>");
							var house_appreciation = processPercentage("<?php echo $_POST["house_appreciation"] ?>"); // Value entered is a percentage
							var rent = processNum("<?php echo $_POST["rent"] ?>");
							var monthly_spending = processNum("<?php echo $_POST["monthly_spending"] ?>");
							var investment = processPercentage("<?php echo $_POST["investment"] ?>");// Value entered is a percentage
							var homeownersTax = "<?php echo $_POST["homeowners_tax"] ?>"; // checkbox "on" or "off"
							var tax_status_input = "<?php echo $_POST["tax_status"] ?>";
							var mortgage_rate = processPercentage("<?php echo $_POST["mortgage_rate"] ?>");
							var mortgage_length_input = "<?php echo $_POST["mortgage_length"] ?>";
							var mortgage_length = 0;
							var downpayment_percentage = processPercentage("<?php echo $_POST["downpayment"] ?>");

							marketRate = investment; // Value entered is a percentage
							var years = [];
							var duration = retirement_age-start_age; // Iterate 1 to final age	
							var homeownersInsurance = 0;
							var propertyTax = 0;
							var tax_status = 0;

							if (tax_status_input == 'ind'){
								tax_status = 1;
							}else if(tax_status_input == 'tog'){
								tax_status = 2;
							}else{
								tax_status = 3;
							}

							if (mortgage_length_input == '10yr'){
								mortgage_length = 10;
							}else if(mortgage_length_input == '20yr'){
								mortgage_length = 20;
							}else{
								mortgage_length = 30;
							}

							if (homeownersTax == 'on'){
								homeownersInsurance = 0.003;
								propertyTax = .01;
							}						

							for (var i = 0; i < duration; i++) {
								years[i] = i + start_age;
							};
							
							function analyze() {
								var netWorth = [];
								var investment = [];
								var mortgage = [];
								var salary = [];
								var house_value = [];
								var afterTaxIncomeArr = [];
								var interest = [];
								var hasPurchased = 0;
								var home_equity = []
								investment[0] = 0;
								salary[0] = starting_salary;
								house_value[0] = house_cost;
								home_equity[0] = 0;
								var housePaidOffYear = 999;
								var housePurchasedYear = 999;



								for (var i = 0; i < duration; i++) {

									if (i==0){
										investment[i] = 0;
										mortgage[i] = 0;
										netWorth[i] = 0;
										interest[i] = 0;
										afterTaxIncomeArr[i] = afterTaxIncome(starting_salary,tax_status);
									}else{
										salary[i] = salary[i-1]*(1+salary_appreciation);
										afterTaxIncomeArr[i] = afterTaxIncome(salary[i],tax_status);

										if (investment[i-1] > house_cost*downpayment_percentage && hasPurchased == 0){ // just purchased house
											hasPurchased = 1;
											housePurchasedYear = i+start_age;
											investment[i] = investment[i-1] - house_cost*downpayment_percentage + investment[i-1]*marketRate + afterTaxIncomeArr[i] - monthly_spending*12 - rent*12 - (propertyTax + homeownersInsurance)*house_value[i-1];
											mortgage[i] = house_cost*(1-downpayment_percentage);
											house_value[i] = house_value[i-1];
											home_equity[i] = house_value[i] - mortgage[i];
											interest[i] = 0;
											// Calculate amount needed to pay each month to achieve desired payoff time
											var mortgage_payment = mortgage[i]*(mortgage_rate*Math.pow((1+mortgage_rate),mortgage_length))/(Math.pow((1+mortgage_rate), mortgage_length) -1);


										}else if(hasPurchased == 1 && mortgage[i-1] > 0){
											interest[i] = mortgage[i-1] * mortgage_rate; // average mortgage rate
											mortgage[i] = mortgage[i-1] - mortgage_payment + interest[i];
											investment[i] = investment[i-1]*(1+marketRate) + afterTaxIncomeArr[i] - mortgage_payment - (propertyTax+homeownersInsurance)*house_value[i-1] - monthly_spending*12; // Leftover cash for investing = total - expenses - house costs
											if(mortgage[i] < 0){
												mortgage[i] = 0;
											}
											house_value[i] = house_value[i-1]*(1+house_appreciation);
											home_equity[i] = house_value[i] - mortgage[i];

										}else if(hasPurchased == 1){ //if house is payed off, you don't pay rent
										
										housePaidOffYear = i + start_age;
										investment[i] = investment[i-1] + investment[i-1]*marketRate + afterTaxIncomeArr[i] - monthly_spending*12 - (propertyTax + homeownersInsurance)*house_value[i-1];
										mortgage[i] = mortgage[i-1];
										house_value[i] = house_value[i-1]*(1+house_appreciation);
										home_equity[i] = house_value[i];
										interest[i] = 0;
									}else{ // saving for house
										
										investment[i] = investment[i-1]*(1+marketRate) + afterTaxIncomeArr[i] - monthly_spending*12 - rent*12;
										mortgage[i] = mortgage[i-1];
										house_value[i] = house_value[i-1];
										home_equity[i] = home_equity[i-1];
										interest[i] = 0;
									}

									netWorth[i] = investment[i] + home_equity[i];
									
								}


							};

							var out = [];
							out[0] = years;
							out[1] = netWorth;
							out[2] = mortgage;
							out[3] = salary;
							out[4] = home_equity;
							out[5] = afterTaxIncomeArr;
							out[6] = investment;
							out[7] = interest;

							return out;
						}
						var output = analyze();

						function vectorSum(input1, input2){
							var output1 = [];
							for(var i = 0; i < input1.length-1 ; i++){
								output1[i] = input1[i] + input2[i];
							}
							return output1;
						}

						output[8] = vectorSum(output[7], output[2]);

						
						var orderOfMagnitude = 1;
						maxElem = Math.max.apply(Math,output[1]);
						orderOfMagnitude = Math.floor(Math.log(maxElem) / Math.LN10 + 0.000000001);

						if (orderOfMagnitude == 1){
							document.getElementById("ylabel").innerHTML = 'Value (Tens of Dollars)';
						}else if (orderOfMagnitude == 2){
							document.getElementById("ylabel").innerHTML = 'Value (Hundreds)';
						}else if(orderOfMagnitude == 3){
							document.getElementById("ylabel").innerHTML = 'Value (Thousands)';
						}else if(orderOfMagnitude == 4){
							document.getElementById("ylabel").innerHTML = 'Value (Ten Thousands)';
						}else if(orderOfMagnitude == 5){
							document.getElementById("ylabel").innerHTML = 'Value (Hundred Thousands)';
						}else if(orderOfMagnitude == 6){
							document.getElementById("ylabel").innerHTML = 'Value (Millions)';
						}else if(orderOfMagnitude == 7){
							document.getElementById("ylabel").innerHTML = 'Value (Ten Millions)';
						}else if(orderOfMagnitude == 8){
							document.getElementById("ylabel").innerHTML = 'Value (Hundred Millions)';
						}else{
							document.getElementById("ylabel").innerHTML = 'Value 10^' + orderOfMagnitude;
						}						

						output = roundToPrecision(output,3);


						var data1 = {
							labels : years,
							datasets : [
							{
								label: "Net Worth",
								fillColor : "rgba(58,172,178,0.6)",
								pointColor: "#fff",
								pointStrokeColor: "#fff",
								pointHighlightFill: "#fff",
								pointHighlightStroke: "rgba(58,172,178,0.4)",
								pointStrokeColor: "rgba(58,172,178,0.4)",
								data : output[1],

							},
							]
						};						
						</script>
						<center><h3>Age</h3></center>

					</p>

					<hr />

					<center><h3>Mortgage Debt</h3></center>
					<h3 id = 'ylabel'>Mortgage</h3>
					<script src="Chart.min.js"></script>
					<canvas id="mortgageID" width="1200" height="600"></canvas>

					<script type="text/javascript">
					var data2 = {
						labels : years,
						datasets : [

						{
							label: "Mortgage + Interest",
							fillColor : "rgba(58,172,178,0.4)",
							pointColor: "#fff",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(58,172,178,0.8)",
							pointStrokeColor: "rgba(58,172,178,0.8)",
							data : output[8],
						},
						{
							label: "Mortgage",
							fillColor : "rgba(255,103,42,0.6)",
							pointColor: "#fff",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(255,103,42,0.8)",
							pointStrokeColor: "rgba(255,103,42,0.8)",
							data : output[2],
						},
						
						]
					};

					</script>
					<center><h3>Age</h3></center>

					<hr />


					<center><h3>Salary Over Time</h3></center>
					<h3 id = 'ylabel'>Salary</h3>
					<script src="Chart.min.js"></script>
					<canvas id="salaryID" width="1200" height="600"></canvas>

					<script type="text/javascript">
					var data3 = {
						labels : years,
						datasets : [
						{
							label: "Salary",
							fillColor : "rgba(58,172,178,0.4)",
							pointColor: "#fff",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(58,172,178,0.4)",
							pointStrokeColor: "rgba(58,172,178,0.4)",
							data : output[3],
						},
						{
							label: "After Tax Income",
							fillColor : "rgba(255,103,42,0.6)",
							pointColor: "#fff",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(255,103,42,0.8)",
							pointStrokeColor: "rgba(255,103,42,0.8)",
							data : output[5],
						},
						]
					};

					</script>
					<center><h3>Age</h3></center>

					<hr />

					<center><h3>Home Equity</h3></center>
					<h3 id = 'ylabel'>Equity</h3>
					<script src="Chart.min.js"></script>
					<canvas id="homeEquityID" width="1200" height="600"></canvas>

					<script type="text/javascript">
					var data4 = {
						labels : years,
						datasets : [
						{
							label: "Salary",
							fillColor : "rgba(255,103,42,0.6)",
							pointColor: "#fff",
							pointStrokeColor: "#fff",
							pointHighlightFill: "#fff",
							pointHighlightStroke: "rgba(255,103,42,0.8)",
							pointStrokeColor: "rgba(255,103,42,0.8)",
							data : output[4],
						},
						]
					};

					</script>
					<center><h3>Age</h3></center>

					<hr />

					<center><h3>Final Asset Allocation</h3></center>
					<!-- <h3 id = 'ylabel'>Salary</h3> -->
					<script src="Chart.min.js"></script>
					<canvas id="assetAllocationID" width="1200" height="600"></canvas>

					<script type="text/javascript">
					var data5 = [
					{
						value: output[4][output[4].length-1],
						color:"rgba(255,103,42,0.8)",
						highlight: "rgba(255,103,42,0.4)",
						label: "Home Equity"
					},
					{
						value: output[6][output[6].length-1],
						color: "rgba(58,172,178,0.8)",
						highlight: "rgba(58,172,178,0.4)",
						label: "Market Investment"
					}
					];

					

					var myChart = document.getElementById('myChart').getContext('2d');
					var mortgageChart = document.getElementById('mortgageID').getContext('2d');
					var salaryChart = document.getElementById('salaryID').getContext('2d');
					var equityChart = document.getElementById('homeEquityID').getContext('2d');
					var assetAllocationChart = document.getElementById('assetAllocationID').getContext('2d');



					window.onload = function(){

						new Chart(myChart).Line(data1,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
						});		

						new Chart(mortgageChart).Line(data2,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
						});	

						new Chart(salaryChart).Line(data3,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
						});	

						new Chart(equityChart).Line(data4,{
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
						});	

						new Chart(assetAllocationChart).Doughnut(data5,{
							responsive: true,
						});
					}

					</script>

					<hr />

					
				</div>
			</section>
		</article>

		<!-- Footer -->
		<footer id="footer">
			<ul class="icons">
				<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
				<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
				<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
				<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
				<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
			</ul>
			<ul class="copyright">
				<li>&copy; Connor Anderson</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
			</ul>
		</footer>

	</div>

	<!-- Scripts -->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.scrollex.min.js"></script>
	<script src="assets/js/jquery.scrolly.min.js"></script>
	<script src="assets/js/skel.min.js"></script>
	<script src="assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="assets/js/main.js"></script>

</body>
</html>