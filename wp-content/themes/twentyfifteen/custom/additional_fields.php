<?php
//========================

function add_custom_scripts( $hook ) {
	if ( in_array( $hook, array( 'profile.php', 'user-edit.php' ) ) ) {
		// Para crear variables javascript equivalentes a las obtenidas mediante PHP:
		wp_register_script( 'custom_script.js', get_template_directory_uri() . '/js/custom_script.js');
		$var_list = array( 
			'template_url' => get_template_directory_uri()
		);
		wp_localize_script( 'custom_script.js', 'WPURLS', $var_list );

		// Para el datepicker
		wp_enqueue_style( 'jquery-ui.min.css', get_template_directory_uri() . '/css/jquery-ui.min.css' );
		wp_enqueue_script( 'jquery-ui.min.js', get_template_directory_uri() . '/js/jquery-ui.min.js' );
		// Para la funcion de autocompletar:
		wp_enqueue_style( 'jquery.tokenize.css', get_template_directory_uri() . '/css/jquery.tokenize.css' );
		wp_enqueue_script( 'jquery.tokenize.js', get_template_directory_uri() . '/js/jquery.tokenize.js' );
		wp_enqueue_script( 'custom_script.js' ); // No ingresamos el segundo paramentro porque ya se lo especifico en wp_register_script
	}
}

add_action( 'admin_enqueue_scripts', 'add_custom_scripts' );

function add_custom_fields( $user ) {
	$is_job_seeker = false;
	$is_premium_member = false;
	foreach ($user->roles as $role) {
		if ( in_array( $role, array( 'subscriber', 'premiummember' ) ) ) {
			$is_job_seeker = true;
			$is_premium_member = $role == 'premiummember';
		}
	}

	if ($is_job_seeker) {
		// Lista de elementos por defecto para el autocompletado

		$industries_tags = array(
			"Advertising", "Animation", "Architecture", "Art History", "Business", "Communications", "Computer Science/Engineering", "Creative Writing", "Entertainment", "Entrepreneurship",
			"Event Planning", "Fashion", "Fine Art", "Graphic Design", "Journalism", "Marketing", "Music", "Photography", "Production", "Product Design", "Public Relations",
			"Publishing", "Social Media", "Theater", "TV / Film", "Undecided", "Other"
		);

		$school_tags = array(
			"Academy of Art University",
			"Adelphi University",
			"Allegheny College",
			"American University in Dubai",
			"Amherst College",
			"Aoyama Gakuin University",
			"Arizona State University",
			"Art institute of New York",
			"Ateneo de Manila University",
			"Athens University of Economics and Business",
			"Attended College of Staten Island",
			"Auburn University",
			"Audencia Nantes School of Managementàç",
			"Audencia School of Management",
			"Azusa Pacific University",
			"Babson College",
			"Bahria University",
			"Ball State University",
			"Bangalore University, New York University",
			"Barnard College, Columbia University",
			"Barry University",
			"Baruch College",
			"Bates College",
			"Bay State College",
			"Baylor University",
			"Bell Language School",
			"Belmont University",
			"Bennington College",
			"Berkeley College",
			"Bernard M. Baruch City University of New York",
			"Bethune-Cookman University",
			"Billy Blue (Melbourne, Australia)",
			"Binghamton University",
			"BMCC",
			"Borough of manhattan community college",
			"Boston College",
			"Boston University",
			"Bowling Green State University",
			"Brandeis University",
			"Briarcliffe Coolege",
			"Bronx Community College",
			"Bronx Compass High School",
			"Brookdale Community College",
			"Brooklyn College, CUNY",
			"Brown University",
			"Brunel University",
			"Bryant University",
			"Bucknell University",
			"Budapest University of Technology and Economics",
			"Buffalo State",
			"Business Academy Smilevski, Skopje, R.Macedonia",
			"California College of the Arts",
			"California Polytechnic University",
			"California State University",
			"Carnegie Mellon University",
			"Católica University",
			"Cedarville University",
			"Centenary College",
			"Central Connecticut State University",
			"Central Michigan University",
			"Champlain College",
			"Chapman University",
			"Charles Péguy College Paris",
			"Chemeketa Community College",
			"City College of New York",
			"Claremont McKenna College",
			"Clark Atlanta University",
			"Clark University",
			"Cleveland State University",
			"Colby College",
			"Colegiatura Colombiana de Diseño",
			"Colegio Americano de Fotografia Ansel Adams",
			"Colgate University",
			"College of Charleston",
			"College of Mount Saint Vincent",
			"College of Saint Elizabeth",
			"College of Saint Rose",
			"College of Staten Island",
			"College of the Holy Cross",
			"College of William & Mary",
			"Colorado College",
			"Colorado State University",
			"Columbia Journalism School",
			"Columbia University",
			"Columbus College of Art and Design",
			"Concord University",
			"Concordia University",
			"Cornell University",
			"Creative Circus",
			"CUNY",
			"Curtin University",
			"Darmstadt University",
			"Dartmouth College",
			"Davidson College",
			"Deakin University",
			"Delhi University",
			"DePaul University",
			"DeVry University",
			"Dickinson College",
			"Dominican University",
			"Dowling College",
			"Drake University",
			"Drexel University",
			"DSK International school of Design",
			"Duke University",
			"Dundalk Institute of Technology",
			"East Carolina University",
			"East Stroudsburg University",
			"East Tennessee State University",
			"Eckerd College",
			"Ecole de commerce européenne INSEEC,",
			"École supérieur de gestion Paris",
			"Edinburgh Napier University",
			"El Camino College",
			"Elegance School of Professional Make-up",
			"ELISAVA, Barcelona",
			"Elon University",
			"EM LYON Business School",
			"Emerson College",
			"Emory University",
			"Empire State College",
			"ENS Cachan, University of Paris-Sud",
			"ESC DIJON",
			"ESGCI - Ecole supérieure de gestion et commerce international",
			"ESSCA Paris Business School",
			"ESSEC BUSINESS SCHOOL PARIS",
			"Essex County College",
			"Eureka College",
			"European School of Economics",
			"Everest University Online",
			"Fairfield University",
			"Fairleigh Dickinson University",
			"Farmingdale State College",
			"FIT",
			"Fashion Institute of Design and Merchandising Los Angeles",
			"Fitchburg State University",
			"Florida Agricultural & Mechanical University",
			"Florida Atlantic University",
			"Florida International University",
			"Florida State University",
			"Fordham University",
			"Franklin & Marshall College",
			"Franklin and Marshall College",
			"Free University of Brussels",
			"Fresno City College",
			"Full Sail University",
			"General Assembly, NYC Tech-Design-Business School",
			"George Mason University",
			"George Washington University",
			"Georgetown University",
			"Georgia College & State University",
			"Georgia Institute of Technology",
			"Georgia State University",
			"Goucher College",
			"Grand Valley State University",
			"Grenoble Business School",
			"Grove City College",
			"Guttman Community College",
			"Hamilton College",
			"Hampshire College",
			"Hampton University",
			"Harvard University",
			"Hebrew University of Jerudalem",
			"HEC Paris",
			"Herbert H. Lehman University",
			"High Point University",
			"High School",
			"High School Paul Langevin",
			"Higher School of Economics",
			"Hobart and William Smith Colleges",
			"Hofstra University",
			"Hogeschool Rotterdam",
			"Houdegbe North American University, Benin Republic",
			"Howard University",
			"Hudson County Community College",
			"Hult International Business School, Master's of Social Entrepreneurship",
			"Hunter College",
			"IAE Aix-Marseille Graduate School of Management",
			"IDC Herzliya",
			"Illinois Wesleyan University",
			"Indiana University",
			"INSEEC Business School Paris",
			"INSEEC Business School, Bordeaux, France",
			"INSEEC BUSINESS SCHOOL, FRANCE",
			"Institucion Universitaria Colegiatura Colombiana de Diseno",
			"Institute of Business Administration of Aix en Provence",
			"Instituto superior de Economia e Gestão",
			"Iowa State University",
			"ISC PARIS, Business school",
			"ISCOM Paris",
			"ISG Business School Paris",
			"Ithaca College",
			"Jackson State University",
			"John Jay College of Criminal Justice",
			"Johns Hopkins University",
			"Johnson & Wales University",
			"Kansas State University",
			"Kansas State University, Wichita State University",
			"Kansas University",
			"Kean University",
			"KEDGE Business School",
			"Kedge Business School (Marseilles, France)",
			"Keene State College",
			"Keller School of Management, MS in Information Technology",
			"Kennesaw State University",
			"Kent State University",
			"Kenyon College",
			"Keystone College",
			"Kingsborough Community College",
			"Kobe University",
			"Kutztown University",
			"Kyiv Slavonic University",
			"La Guardia Community College",
			"Laboratory Institute of Merchandising College",
			"Laboratory Institute of Technology",
			"Lafayette College",
			"LaGuardia Community College",
			"Lake Superior College",
			"Lang",
			"Laval University",
			"Lehigh University",
			"Lehman College (M.S.Ed. Anticipated)), Case Western University (B.A.)",
			"Lincoln University",
			"London college of fashion",
			"Long Island University - CW Post Campus",
			"Long Island University Post Campus",
			"Long Island University- brooklyn",
			"Louisiana State University",
			"Loyola Marymount Universitiy",
			"Loyola University",
			"Macalester College",
			"Macaulay Honors College at John Jay College of Criminal Justice",
			"Manhattan College",
			"Manhattanville College",
			"Marist College",
			"Marquette University",
			"Maryland Institute Collage of Art",
			"Marymount Manhattan College",
			"Massachusetts College of Art and Design",
			"McGill University",
			"Mercy College",
			"Mercyhurst University",
			"Merrimack College",
			"Metropolitan College of New York",
			"Miami University",
			"Michigan State University",
			"Middlebury College",
			"Middlesex University, London",
			"Millennium High School",
			"Molloy College",
			"Monroe College",
			"Montana State University",
			"Montclair State University",
			"Montclair University",
			"Monterrey Institute of Technology",
			"Moore College of Art & Design",
			"Morgan State University",
			"Mount Holyoke College",
			"Mount Saint Mary College",
			"Muhelnberg College (B.A)  Rutgers University (M.A.)",
			"Muhlenberg College",
			"Mumbai  University",
			"Other",
			"Nanyang Academy of Fine Arts, Singapore",
			"Nassau Community College",
			"National Insititute Of Fashion Technology, Hyderabad",
			"National University of Ireland Galway",
			"National University of Singapore",
			"NDDU",
			"Neoma Business school",
			"The New School",
			"New York Conservatory for the Dramatic Arts",
			"New York Film Academy",
			"New York Institute of technology",
			"New York University Stern School of Business",
			"New York University, Stony Brook University",
			"New York University, The New School",
			"New York University, Tisch School of the Arts",
			"Norfolk State University",
			"North Carolina A&T State University",
			"North Carolina State University",
			"Northampton Community College",
			"Northeastern State University",
			"Northeastern University",
			"Northern Illinois University",
			"Northumbria University, Newcastle, UK",
			"Northwestern University",
			"Norwich University of the Arts",
			"NYIT",
			"NYU Polytechnic School of engineering",
			"Oakwood University",
			"Ocean County College",
			"Ohio University",
			"Old Dominion University",
			"Old Dominion University, New York University",
			"Otis College of Art and Design",
			"Oxford Brookes",
			"Pace University",
			"Parsons The Newschool for Design",
			"Pennsylvania State University",
			"Pepperdine University",
			"Philadelphia University",
			"Point Park University",
			"Pontificia Universidad Javeriana (Bogotá)",
			"Pratt Institute, New York, Ny",
			"premiere career college",
			"Princeton University",
			"Principia College",
			"Purchase College",
			"Purdue University",
			"Queen's University",
			"Queens College",
			"Quinnipiac University",
			"Raffles Lasalle International Design College",
			"Ramapo College of New Jersey",
			"Randolph College",
			"Regents Business School of London",
			"Rennes 2 University",
			"Rhode Island School of Design",
			"Rice University",
			"Richmond, the American International University in London",
			"Rider University",
			"Rietveld International School of Art & Design",
			"Ringling College of Art & Design",
			"Roberts Wesleyan College",
			"Rochester Institute of Technology",
			"Roger Williams University",
			"Rowan University",
			"Ruprecht-Karls-Universität Heidelberg",
			"Rutgers University",
			"Ryerson University",
			"S.I. Newhouse School of Public Communications",
			"Sacred Heart University",
			"SAE Intitute of NY",
			"Sage College of Albany",
			"Saint Francis College",
			"Saint Joseph's University",
			"Saint Peter's University",
			"San Diego State University",
			"San Francisco State University",
			"Sarah Lawrence College",
			"Savannah College of Art and Design",
			"School of Arts, University of Nova Gorica",
			"SVA",
			"Scripps College, Claremont Colleges",
			"SCU",
			"Seattle University",
			"Seton Hall University",
			"Sheridan College / University of Toronto",
			"Shillington Schol",
			"Shillington School, New York State College of Ceramics at Alfred University",
			"Shippensburg University",
			"Siena College",
			"Simmons College",
			"Skidmore College",
			"Smith College",
			"Southeastern University",
			"Spelman College",
			"St .Francis College",
			"St Johns University",
			"St. Lawrence University",
			"St. Mary's College of CA, New York University",
			"Stanford University",
			"State Univeristy of New York the College at Old Westbury",
			"State University at Albany",
			"State University at Buffalo State College",
			"State University of Londrina",
			"Stella and Charles Guttman Community College",
			"Stonestreet studios",
			"Stony Brook University",
			"Suffolk University",
			"Sungshin woman's university",
			"SUNY",
			"Susquehanna University",
			"Symbiosis International University",
			"Syracuse University",
			"Syracuse University S.I. Newhouse School of Public Communications",
			"Syracuse University, London College of Fashion",
			"Teachers College, Columbia University",
			"Tecnologico de Monterrey",
			"Telecom ParisTech",
			"Temple University",
			"Texas A&M University",
			"The Art Institute of Philadelphia",
			"The Art Institute of Pittsburgh",
			"The Catholic University of America",
			"The City College of New York",
			"The College at Old Westbury",
			"The College of New Jersey",
			"The College of Saint Rose",
			"The Cooper Union",
			"The Fashion Institute of Technology",
			"The George Washington University",
			"The Illinois Institute of Art - Chicago",
			"The Juilliard School",
			"The King's College",
			"The New School",
			"The Ohio State University",
			"The Pennsylvania state university",
			"The Richard Stockton College of New Jersey",
			"The Savannah College of Art and Design",
			"The University of Arizona",
			"The University of Georgia",
			"The University of Melbourne",
			"The University of North Carolina Greensboro",
			"The University of Scranton",
			"The University of Southern Mississippi",
			"The University of Texas at Austin",
			"The University of Virginia",
			"Toulouse Business School (France)",
			"Toulouse university",
			"Touro College",
			"Tribeca Flashpoint Media Arts Academy",
			"Trinity College",
			"Tufts University",
			"Tulane University",
			"Tunisia Engineering University",
			"UC Davis",
			"UCLA",
			"UFRGS",
			"UNIACC",
			"Union County College",
			"Union University",
			"Univer",
			"Universidad Argentina de la Empresa, Universidat Autónoma de Barcelona, Wimbledon School of English",
			"Universidad Autonoma de Madrid",
			"Universidad de Palermo",
			"Universidad Iberoamericana",
			"Universidad Jorge Tadeo Lozano de Bogotá",
			"Universidad Nacional de Educacion a Distancia (Spain)",
			"Universidad Santa Maria, Venezuela",
			"Università Statale di Milano",
			"Université De Montréal",
			"Université de Savoie",
			"Université de Strasbourg",
			"University At Albany",
			"University at Buffalo",
			"University at Buffalo, State University of New York",
			"University Carlos III of Madrid",
			"University College Cork, Cork Ireland",
			"University for the Creative Arts, Farnham, UK",
			"University Inholland in the Netherlands",
			"University of Antwerp",
			"University of Bridgeport",
			"University of British Columbia",
			"University of California at Riverside",
			"University of Central Arkansas",
			"University of Central Florida",
			"University of Central Oklahoma",
			"University of Cincinnati",
			"University of Colorado",
			"University of Connecticut",
			"University of Delaware",
			"University of Denver",
			"University of Edinburgh",
			"University of Florida",
			"University of Georgia",
			"University of Huddersfield",
			"University of Illinois at Chicago",
			"University of Iowa",
			"University of Kentucky",
			"University of Law",
			"University of Leeds",
			"University of Lees",
			"University of Louisville",
			"University of Manchester",
			"University of Maryland",
			"University of Massachusetts Amherst",
			"University of Miami",
			"University of Michigan",
			"University of Minnesota - Twin Cities",
			"University of Mississippi",
			"University of Missouri - Columbia",
			"University of Montana",
			"University of Navarre",
			"University of Nebraska",
			"University of New Hampshire",
			"University of New Haven",
			"University Of North Carolina at Asheville",
			"University of North Carolina at Chapel Hill",
			"University of North Carolina Wilmington",
			"University of North Texas",
			"University of Northwestern",
			"University of Nottingham",
			"University of Pennsylvania",
			"University of Pittsburgh",
			"University of Rhode Island",
			"University of Richmond",
			"University of Rochester",
			"University of Salford",
			"University of San Diego",
			"University of San Francisco",
			"University of Science and Arts Oklahoma",
			"University of Scranton",
			"University of South Carolina",
			"University of South Florida",
			"University of Southern California",
			"University of Tampa",
			"University of Tennessee",
			"University of Texas at Austin",
			"University of the Arts",
			"University of the Pacific",
			"University of the Philippines",
			"University of Utah",
			"University of Vermont",
			"University of Victoria",
			"University of Virginia",
			"University of Virginia, Rollins College",
			"University of Washington",
			"University Of Western Sydney",
			"University of Westminster",
			"University of Wisconsin - Madison",
			"Universtiy of Iowa",
			"UPenn",
			"Ursinus College",
			"Uuniversity of California at Berkeley",
			"Valencia University",
			"Vanderbilt University",
			"Vassar College",
			"Victoria University Wellington",
			"Villanova University",
			"Virginia Commonwealth University",
			"Virginia State University",
			"Virginia Tech",
			"Wagner College",
			"Wake Forest University",
			"Washington and Lee University",
			"Washington State University",
			"Washington University in Saint Louis",
			"Webster University",
			"Wellesley College",
			"Wesleyan University",
			"West Virginia University",
			"Westchester Community College",
			"Western Kentucky University",
			"Western Univerisity",
			"Westmont College",
			"Wheaton College",
			"Wheaton College, MA",
			"Wilfrid Laurier University",
			"William Paterson University",
			"Williams College",
			"Winthrop University",
			"Wood Tobe-Coburn",
			"Worcester State University",
			"Xavier University",
			"Yale University",
			"Yeshiva University",
			"Yonsei University",
			"York University",
			"Youngstown State University",
			"Zurich University"
		);

		$ethnicity_tags = array(
			"Asian", "American Indian or Alaskin Native", "Black or African American", "Hispanic or Latino ", "Native Hawian or Pacific Islander", "White", "Two or more races"
		);

		$skill_tags = array(
			"Management",
			"Business",
			"TBD",
			"Sales ",
			"Marketing",
			"TBD",
			"Communication",
			"Microsoft Office",
			"Customer Service",
			"Training",
			"Microsoft Excel",
			"Project Management",
			"Designs",
			"Analysis",
			"Research",
			"Websites",
			"Budgets",
			"Organization",
			"Leadership",
			"Time Management",
			"Project Planning",
			"Computer Program",
			"Strategic Planning",
			"Business Services",
			"Applications",
			"Reports",
			"Microsoft Word",
			"Program Management",
			"Powerpoint",
			"Negotation",
			"Software",
			"Networking",
			"Offices",
			"TBD",
			"English",
			"Data",
			"TBD",
			"Education",
			"Events",
			"International",
			"Testing",
			"Writing",
			"Vendors",
			"Advertising",
			"Databases",
			"Technology",
			"TBD",
			"Finance",
			"Retail",
			"accounting",
			"social media",
			"Teaching",
			"Engineering",
			"Performance Tuning",
			"Problem Solving",
			"Marketing Strategy",
			"Materials",
			"Recruiting",
			"Order Fulfillment",
			"Corporate Law",
			"Photoshop",
			"TBD",
			"New business development",
			"Human resources",
			"Public speaking",
			"Manufacturing",
			"Internal Audit",
			"strategy",
			"Employees",
			"Cost",
			"Business Development",
			"Windows",
			"TBD",
			"Public Relations",
			"Product Development",
			"Auditing",
			"Business Strategy",
			"Presentations",
			"Construction",
			"Real Estate",
			"Editing",
			"Sales Management",
			"Team Building",
			"Healthcare",
			"TBD",
			"Revenue",
			"Compliance",
			"Legal",
			"Innovation",
			"Policy",
			"Mentoring",
			"Commercial Real Estate",
			"Consulting",
			"Information Technology",
			"Process Improvement",
			"Change management",
			"Heavy Equipment",
			"Teamwork",
			"Promotions",
			"Facilities Management"
		);		

		// Inicializamos los valores de cada campo con autocompletado

		$target_industries = get_user_meta( $user->ID, 'target_industries', true ); // get_user_meta "deserializa" el valor almacenado
		if (!is_array($target_industries)) {
			$target_industries = array();
		}

		$college_attended = get_user_meta( $user->ID, 'college_attended', true );
		if (!is_array($college_attended)) {
			$college_attended = array();
		}

		$ethnicity = get_user_meta( $user->ID, 'ethnicity', true );
		if (!is_array($ethnicity)) {
			$ethnicity = array();
		}

		$skills = get_user_meta( $user->ID, 'main_skills', true );
		if (!is_array($skills)) {
			$skills = array();
		}

		$pic_data = get_user_meta( $user->ID, 'profile_pic', true );

		/*
		echo "pic_data:";
		echo "<pre>";
		print_r($pic_data);
		echo "</pre>";
		*/		

		if (!empty($pic_data) && !isset($pic_data['error'])) {
			$profile_pic_url = $pic_data['url'];
		} else {
			$profile_pic_url = get_template_directory_uri() . "/images/profile-default.png";
		}

		// Formamos la URL del perfil publico del usuario actual (formato: home/profile/username)
		$public_profile_url = site_url() . "/profile/" . $user->user_nicename;
?>
	<input type="hidden" id="current-user-role" value="<?php echo $user->roles[0]; ?>" />
	<h3 id="basic-info">Basic Info</h3>
	<table class="form-table">
		<tbody>
			<tr>
      	<th>
      		<label for="profile-pic"><?php _e('Profile Image', 'shr') ?><p class="description">(it will only be shown in your public profile)</p></label>
      	</th>
      	<td>      		
					<img width="125px" height="125px" id="profile-pic-preview" src="<?php echo $profile_pic_url; ?>" alt="Your profile image" style="border-radius: 50%" />
					<br/>
					<input type="file" id="profile-pic" name="profile-pic" onchange="readURL(this);" accept=".jpg,.jpeg,.png,.gif" />
					<p class="description">(Recommended 250x250 pixels)</p>
      	</td>
      </tr>
			<tr>
				<th>Public Profile URL<p class="description">(Not editable)</p></th>
				<td>
					<a id="public-profile-url" href="<?php echo $public_profile_url; ?>" target="_blank"><?php echo $public_profile_url; ?></a>
				</td>
			</tr>		
      <tr>
				<th><label for="college-attended">College Attended</label></th>
				<td>
					<select id="college-attended" name="college-attended[]" multiple="multiple" class="profile-field">
						<?php foreach($school_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $college_attended) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="year-graduation">Year of Graduation</label></th>
				<td><input type="text" maxlength="4" id="year-graduation" name="year-graduation" value="<?php echo esc_attr( get_the_author_meta( 'year_graduation', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
		</tbody>
	</table>
	<h3 id="career-background">Career Background</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="job-title">Current Job</label></th>
				<td><input type="text" id="job-title" name="job-title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="job-company">Current Company</label></th>
				<td><input type="text" id="job-company" name="job-company" value="<?php echo esc_attr( get_the_author_meta( 'job_company', $user->ID ) ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="main-skills">What are your main skills?</label><p class="description">(Up to 5)</p></th>
				<td>
					<select id="main-skills" name="main-skills[]" multiple="multiple" class="profile-field">
						<?php foreach($skill_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $skills) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="target-industries">What are your target industries?</label><p class="description">(Up to 5)</p></th>
				<td>
					<select id="target-industries" name="target-industries[]" multiple="multiple" class="profile-field">
						<?php foreach($industries_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $target_industries) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="career-situation">What best describes your current career situation?</label></th>
				<td>
					<div>
						<input type="radio" id="career-situation-1" name="career-situation" value="1" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />
						<label for="career-situation-1">Student, actively seeking internships</label>					
					</div>
					<div>
						<input type="radio" id="career-situation-2" name="career-situation" value="2" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />
						<label for="career-situation-2">Student, actively seeking first full-time job</label>
					</div>
					<div>
						<input type="radio" id="career-situation-3" name="career-situation" value="3" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />
						<label for="career-situation-3">Unemployed, actively seeking full-time employment</label>
					</div>
					<div>
						<input type="radio" id="career-situation-4" name="career-situation" value="4" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 4 ? 'checked="checked"' : '' ; ?> />
						<label for="career-situation-4">Employed, actively seeking new full-time opportunities</label>					
					</div>
					<div>
						<input type="radio" id="career-situation-5" name="career-situation" value="5" <?php echo esc_attr( get_the_author_meta( 'career_situation', $user->ID ) ) == 5 ? 'checked="checked"' : '' ; ?> />
						<label for="career-situation-5">Employed, open to new opportunities</label>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<h3 id="demographic-info">Demographic Info</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="birth-date">Date of Birth</label></th>
				<td><input type="text" id="birth-date" name="birth-date" value="<?php echo esc_attr( get_the_author_meta( 'birth_date', $user->ID ) ); ?>" class="regular-text" autocomplete="off" /></td>
			</tr>
			<tr>
				<th><label for="ethnicity">Ethnicity</label></th>
				<td>					
					<select id="ethnicity" name="ethnicity[]" multiple="multiple" class="profile-field">
						<?php foreach($ethnicity_tags as $tag) { ?>
							<option value="<?php echo $tag; ?>" <?php echo in_array($tag, $ethnicity) ? 'selected="selected"' : ''; ?>><?php echo $tag; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<?php //if ($is_premium_member) {	?>
	<!--h3>(Will only be available for Premium Members)</h3-->
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="resume">Upload Resume</label></th>
				<td>
					<?php $resume = get_user_meta( $user->ID, 'resume', true ); ?>
					<div id="resume-preview" <?php echo empty($resume['url']) ? 'style="display: none; "' : '' ?>>
						<img width="48px" height="64px" src="<?php echo includes_url() . "images/media/document.png"; ?>" alt="Your Resume" />
						<br/>
						<span id="resume-name">
							<?php echo !empty($resume['url']) ? basename($resume['url']) : ''; ?>
							<br/>
						</span>
						<input id="resume-change" type="button" value="Change File" onclick="changeFile()" />
					</div>
					<input <?php echo !empty($resume['url']) ? 'style="display: none; "' : '' ?> type="file" onchange="validateFile(this);" id="resume" name="resume" class="regular-text" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
				</td>
			</tr>
		</tbody>
	</table>
	<?php //} ?>
	<h3 id="privacity-settings">Privacity Settings</h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="public-profile-status">Public Profile</label></th>
				<td>
					<div>
						<input type="radio" id="public-profile-status-0" name="public-profile-status" value="0" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 0 ? 'checked="checked"' : '' ; ?> />
						<label for="public-profile-status-0">Everyone can view my profile</label>
					</div>
					<div>
						<input type="radio" id="public-profile-status-1" name="public-profile-status" value="1" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />
						<label for="public-profile-status-1">Only FindSpark members can view my profile</label>						
					</div>
					<?php //if ($is_premium_member) { // Restringimos para que las otras opciones esten disponibles SOLO para miembros premium ?>
					<div>
						<input type="radio" id="public-profile-status-2" name="public-profile-status" value="2" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />
						<label for="public-profile-status-2">Only FindSpark employers can view my profile</label>
					</div>
					<div>
						<input type="radio" id="public-profile-status-3" name="public-profile-status" value="3" <?php echo esc_attr( get_the_author_meta( 'public_profile_status', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />
						<label for="public-profile-status-3">FindSpark members &amp; employers can view my profile</label>
					</div>
					<?php //} ?>
				</td>
			</tr>
			<tr>
				<th><label for="contact-me-status">Contact me</label></th>
				<td>
					<div>
						<input type="radio" id="contact-me-status-0" name="contact-me-status" value="0" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 0 ? 'checked="checked"' : '' ; ?> />
						<label for="contact-me-status-0">Everyone can contact me</label>						
					</div>
					<div>
						<input type="radio" id="contact-me-status-1" name="contact-me-status" value="1" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 1 ? 'checked="checked"' : '' ; ?> />
						<label for="contact-me-status-1">Only FindSpark members can contact me</label>
					</div>
					<?php //if ($is_premium_member) { // Restringimos para que las otras opciones esten disponibles SOLO para miembros premium ?>
					<div>
						<input type="radio" id="contact-me-status-2" name="contact-me-status" value="2" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 2 ? 'checked="checked"' : '' ; ?> />
						<label for="contact-me-status-2">Only FindSpark employers can contact me</label>
					</div>
					<div>
						<input type="radio" id="contact-me-status-3" name="contact-me-status" value="3" <?php echo esc_attr( get_the_author_meta( 'contact_me_status', $user->ID ) ) == 3 ? 'checked="checked"' : '' ; ?> />
						<label for="contact-me-status-3">FindSpark members &amp; employers can contact me</label>
					</div>
					<?php //} ?>
				</td>
			</tr>
		</tbody>
	</table>
<?php
	}
}

add_action( 'show_user_profile', 'add_custom_fields' );
add_action( 'edit_user_profile', 'add_custom_fields' );

function save_custom_fields( $user_id ) {
	$is_job_seeker = false;
	$is_premium_member = false;
	$user = get_userdata( $user_id );
	foreach ($user->roles as $role) {
		if (in_array($role, array('subscriber', 'premiummember'))) {
			$is_job_seeker = true;
			$is_premium_member = $role == 'premiummember';
		}
	}

	if ($is_job_seeker) {
		update_user_meta( $user_id, 'college_attended', $_POST['college-attended'] );
		update_user_meta( $user_id, 'year_graduation', sanitize_text_field( $_POST['year-graduation'] ) );
		update_user_meta( $user_id, 'job_title', sanitize_text_field( $_POST['job-title'] ) );
		update_user_meta( $user_id, 'job_company', sanitize_text_field( $_POST['job-company'] ) );
		update_user_meta( $user_id, 'career_situation', sanitize_text_field( $_POST['career-situation'] ) );
		update_user_meta( $user_id, 'main_skills', $_POST['main-skills'] );
		update_user_meta( $user_id, 'target_industries', $_POST['target-industries'] );
		
		if ( $_FILES['profile-pic']['error'] === UPLOAD_ERR_OK ) {
			// Obtenemos el tipo del archivo subido. Esto es retornado como "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES['profile-pic']['name']));
			$uploaded_file_type = $arr_file_type['type'];

			// Lista de formatos aceptados
			$allowed_file_types = array('image/jpg','image/jpeg','image/gif','image/png');
			// Verificamos si el tipo de archivo se encuentra entre los aceptados
			if( in_array($uploaded_file_type, $allowed_file_types) ) {
				$max_img_size = 2; // MB
				// Verificamos que el tamanho del archivo no se exceda del limite
				if ( $_FILES['profile-pic']['size'] <= ($max_img_size * 1024 * 1024) ) {
					// Por defecto Wordpress hara fallar la subida si no se pone: 'test_form' => false
					$upload_overrides = array( 'test_form' => false );
					// Subimos la imagen mediante wordpress (la guarda en wp-contents/uploads/anho/mes/)
				 	$img = wp_handle_upload( $_FILES['profile-pic'], $upload_overrides );
				 	// Actualizamos la informacion del campo respectivo
					update_user_meta( $user_id, 'profile_pic', $img );
				}
			}
		}

		update_user_meta( $user_id, 'birth_date', sanitize_text_field( $_POST['birth-date'] ) );
		update_user_meta( $user_id, 'ethnicity', $_POST['ethnicity'] );

		if ( $_FILES['resume']['error'] === UPLOAD_ERR_OK ) {
			// Obtenemos el tipo del archivo subido. Esto es retornado como "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES['resume']['name']));
			$uploaded_file_type = $arr_file_type['type'];

			// Lista de formatos aceptados (pdf, doc y docx)
			$allowed_file_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			// Verificamos si el tipo de archivo se encuentra entre los aceptados
			if( in_array($uploaded_file_type, $allowed_file_types) ) {
				$max_file_size = 10; // MB
				// Verificamos que el tamanho del archivo no se exceda del limite
				if ( $_FILES['resume']['size'] <= ($max_file_size * 1024 * 1024) ) {
					// Por defecto Wordpress hara fallar la subida si no se pone: 'test_form' => false
					$upload_overrides = array( 'test_form' => false );
					// Subimos la imagen mediante wordpress (la guarda en wp-contents/uploads/anho/mes/)
				 	$file = wp_handle_upload( $_FILES['resume'], $upload_overrides );
				 	// Actualizamos la informacion del campo respectivo
					update_user_meta( $user_id, 'resume', $file );
				}
			}
		}

		$public_profile_status = sanitize_text_field( $_POST['public-profile-status'] );
		$contact_me_status = sanitize_text_field( $_POST['contact-me-status'] );
		/*
		if (!$is_premium_member) { // Si no es miembro premium, no debe poder escoger las opciones adicionales de los radiobutton (value = 2 y 3)
			if ($public_profile_status >= 2) {
				$public_profile_status = 0;
			}
			if ($contact_me_status >= 2) {
				$contact_me_status = 0;
			}
		}
		*/
		update_user_meta( $user_id, 'public_profile_status', $public_profile_status );
		update_user_meta( $user_id, 'contact_me_status', $contact_me_status );
	}
}

add_action( 'personal_options_update', 'save_custom_fields' );
add_action( 'edit_user_profile_update', 'save_custom_fields' );

function make_form_accept_uploads() {
	// Para poder subir archivos en el formulario del profile, se debe agregar la siguiente propiedad:
	echo ' enctype="multipart/form-data"';
}

add_action('user_edit_form_tag', 'make_form_accept_uploads');

function my_user_contactmethods($user_contactmethods) { 
  unset($user_contactmethods['aim']);
  unset($user_contactmethods['yim']);
  unset($user_contactmethods['jabber']);
  unset($user_contactmethods['googleplus']);
  unset($user_contactmethods['twitter']);
  unset($user_contactmethods['facebook']);

	$user_contactmethods['twitter'] = "Twitter";
  $user_contactmethods['linkedin'] = "LinkedIn";

  return $user_contactmethods;
}
add_filter('user_contactmethods', 'my_user_contactmethods');

//========================