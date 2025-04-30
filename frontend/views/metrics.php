<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - eStartup Bootstrap Template</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <link href="../img/favicon.png" rel="icon">
  <link href="../img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <link href="../css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/metrics.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/modal.css">

</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="../views/metrics.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="../img/logo.png" alt="">
        <h1 class="sitename"><span>Job</span> Role Evaluation</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="../index.php">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="../views/metrics.php" class="active">Metrics</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#features">Features</a></li>
          <li><a href="#pricing">Pricing</a></li>
          <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>
  
  <main class="main">

    <section id="metrics" class="features section light-background">

      <div class="welcome-container position-relative">

        <h1 id="welcome-text">WELCOME</h1>
        <p class="subtext">EXPLORE INSIGHTFUL METRICS AND MAKE DATA-DRIVEN DECISIONS.</p>
        <div class="buttons-container">

          <div class="metric-button-wrapper">
            <button class="metric-button" onclick="scrollToSection('performance')">PERFORMANCE</button>
            <div class="metric-button-bar-1"></div>
          </div>
          
          <div class="metric-button-wrapper">
            <button class="metric-button" onclick="scrollToSection('finance')">COST</button>
            <div class="metric-button-bar-2"></div>
          </div>
          
          <div class="metric-button-wrapper">
            <button class="metric-button" onclick="scrollToSection('revenue')">REVENUE</button>
            <div class="metric-button-bar-3"></div>
          </div>
          
          <div class="metric-button-wrapper">
            <button class="metric-button" onclick="scrollToSection('market')">MARKET<br>TRENDS</button>
            <div class="metric-button-bar-4"></div>
          </div>
          
          <div class="metric-button-wrapper">
            <button class="metric-button" onclick="scrollToSection('susceptible')">TECHNOLOGICAL SUSCEPTIBILITY</button>
            <div class="metric-button-bar-5"></div>
          </div>
          
          <div class="metric-button-wrapper">
            <button class="metric-button" onclick="scrollToSection('dependence')">INTERDEPARTMENTAL DEPENDENCY</button>
            <div class="metric-button-bar-6"></div>
          </div>

        </div>

      </div>

  </section>





    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <p class="who-we-are">Who We Are</p>
            <h3>Unleashing Potential with Creative Strategy</h3>
            <p class="fst-bold">
              Unlock each role's potential with data-driven insights tailored to industry demands, empowering strategic
              decisions that align talent with your organization’s goals.
            </p>
            <ul>
              <li><i class="bi bi-check-circle"></i> <span>Gain real-time insights into the relevance of each job role
                  within your organization.</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Make data-driven decisions to optimize workforce planning,
                  strategic hiring and resource management.</span></li>
              <li><i class="bi bi-check-circle"></i> <span>Customize evaluation metrics based on industry trends, skill
                  demand, and market salaries for a tailored analysis.</span></li>
            </ul>
            <a href="#metrics" class="read-more"><span>Select Metrics</span><i class="bi bi-arrow-right"></i></a>
          </div>

          <div class="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">
              <div class="col-lg-6">
                <img src="../img/about-company-1.jpg" class="img-fluid" alt="">
              </div>
              <div class="col-lg-6">
                <div class="row gy-4">
                  <div class="col-lg-12">
                    <img src="../img/about-company-2.jpg" class="img-fluid" alt="">
                  </div>
                  <div class="col-lg-12">
                    <img src="../img/about-company-3.jpg" class="img-fluid" alt="">
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section><!-- /About Section -->

    <section id="performance" class="performance section light-background">
      <div class="metric-container section-title-1" data-aos="fade-up">
        <div class="info">
          <span><h2>PERFORMANCE</h2></span><br>
          <span>Statisitcs on </span> <br><span class="description-title-1">Performance per Role</span>
        </div>
      </div>

      <div class="chart-container-1">
        
        <!-- 📌 Clean "+" Icon Button -->
        <button id="addRoleBtn1" class="btn-add-role-icon-1">
          <svg class="plus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
          
        <!-- 📌 Clean "-" Icon Button -->
        <button id="removeRoleBtn1" class="btn-remove-role-icon-1">
          <svg class="minus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
        
        <button id="addDescriptionBtn1" class="btn-add-description-1">
          <i class="fa-solid fa-pencil-alt"></i> <!-- Font Awesome Pencil Icon -->
        </button>          
        
        <button id="reloadButton1" class="btn-reload-chart-icon-1">
          <i class="bi bi-arrow-repeat reload-icon"></i>
        </button>
        
        <h2><?php echo $_SESSION['organization_name']; ?></h2>
        <h3>Market Demand Statistics</h3>
        
        <canvas id="Chart1" class="chart-canvas"></canvas>
      </div>

      <!-- Modal 1: Add Role -->
      <div id="modalRole1" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRole1">&times;</span>
          <iframe id="iframeRole1" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 2: Add Description -->
      <div id="modalDescription1" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalDescription1">&times;</span>
          <iframe id="iframeDescription1" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 3: Remove Role -->
      <div id="modalRemove1" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRemove1">&times;</span>
          <iframe id="iframeRemove1" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

    </section>







    
    <section id="finance" class="finance section light-background">

      <div class="metric-container section-title-2" data-aos="fade-up">
        <div class="info">
          <span><h2>COST</h2></span><br>
          <span>Statisitcs on </span> <br><span class="description-title-1">Cost per Role</span>
        </div>
      </div>

      <div class="chart-container-2">
        
        <!-- 📌 Clean "+" Icon Button -->
        <button id="addRoleBtn2" class="btn-add-role-icon-2">
          <svg class="plus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
          
        <!-- 📌 Clean "-" Icon Button -->
        <button id="removeRoleBtn2" class="btn-remove-role-icon-2">
          <svg class="minus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
        
        <button id="addDescriptionBtn2" class="btn-add-description-2">
          <i class="fa-solid fa-pencil-alt"></i> <!-- Font Awesome Pencil Icon -->
        </button>          
        
        <button id="reloadButton2" class="btn-reload-chart-icon-2">
          <i class="bi bi-arrow-repeat reload-icon"></i>
        </button>
        
        <h2><?php echo $_SESSION['organization_name']; ?></h2>
        <h3>Market Demand Statistics</h3>
        
        <canvas id="Chart2" class="chart-canvas"></canvas>
      </div>


      <!-- Modal 1: Add Role -->
      <div id="modalRole2" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRole2">&times;</span>
          <iframe id="iframeRole2" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 2: Add Description -->
      <div id="modalDescription2" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalDescription2">&times;</span>
          <iframe id="iframeDescription2" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 3: Remove Role -->
      <div id="modalRemove2" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRemove2">&times;</span>
          <iframe id="iframeRemove2" src="" width="100%" height="500px"></iframe>
        </div>
      </div>
    </section>







    
    <section id="revenue" class="revenue section light-background">

      <div class="metric-container section-title-3" data-aos="fade-up">
        <div class="info">
          <span><h2>REVENUE</h2></span><br>
          <span>Statisitcs on </span> <br><span class="description-title-1">Revenue per Role</span>
        </div>
      </div>

      <div class="chart-container-1">
      
        <!-- 📌 Clean "+" Icon Button -->
        <button id="addRoleBtn3" class="btn-add-role-icon-3">
          <svg class="plus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
      
        <!-- 📌 Clean "-" Icon Button -->
        <button id="removeRoleBtn3" class="btn-remove-role-icon-3">
          <svg class="minus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
        
        <button id="addDescriptionBtn3" class="btn-add-description-3">
          <i class="fa-solid fa-pencil-alt"></i> <!-- Font Awesome Pencil Icon -->
        </button>          
        
        <button id="reloadButton3" class="btn-reload-chart-icon-3">
          <i class="bi bi-arrow-repeat reload-icon"></i>
        </button>
        
        <h2><?php echo $_SESSION['organization_name']; ?></h2>
        <h3>Market Demand Statistics</h3>
        
        <canvas id="Chart3" class="chart-canvas"></canvas>
      </div>

      <!-- Modal 1: Add Role -->
      <div id="modalRole3" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRole3">&times;</span>
          <iframe id="iframeRole3" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 2: Add Description -->
      <div id="modalDescription3" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalDescription3">&times;</span>
          <iframe id="iframeDescription3" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 3: Remove Role -->
      <div id="modalRemove3" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRemove3">&times;</span>
          <iframe id="iframeRemove3" src="" width="100%" height="500px"></iframe>
        </div>
      </div>
    </section>







    
    <section id="market" class="market section light-background">
      <div class="metric-container section-title-4" data-aos="fade-up">
        <div class="info">
          <span><h2>DEMAND</h2></span><br>
          <span>Statisitcs on </span> <br><span class="description-title-1">Demand per Role</span>
        </div>
      </div>

      <div class="chart-container-2">
      
        <!-- 📌 Clean "+" Icon Button -->
        <button id="addRoleBtn4" class="btn-add-role-icon-4">
          <svg class="plus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
      
        <!-- 📌 Clean "-" Icon Button -->
        <button id="removeRoleBtn4" class="btn-remove-role-icon-4">
          <svg class="minus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
        
        <button id="addDescriptionBtn4" class="btn-add-description-4">
          <i class="fa-solid fa-pencil-alt"></i> <!-- Font Awesome Pencil Icon -->
        </button>          
        
        <button id="reloadButton4" class="btn-reload-chart-icon-4">
          <i class="bi bi-arrow-repeat reload-icon"></i>
        </button>
        
        <h2><?php echo $_SESSION['organization_name']; ?></h2>
        <h3>Market Demand Statistics</h3>
        
        <canvas id="Chart4" class="chart-canvas"></canvas>
      </div>

      <!-- Modal 1: Add Role -->
      <div id="modalRole4" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRole4">&times;</span>
          <iframe id="iframeRole4" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 2: Add Description -->
      <div id="modalDescription4" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalDescription4">&times;</span>
          <iframe id="iframeDescription4" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 3: Remove Role -->
      <div id="modalRemove4" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRemove4">&times;</span>
          <iframe id="iframeRemove4" src="" width="100%" height="500px"></iframe>
        </div>
      </div>
      
    </section>







    
    <section id="susceptible" class="susceptible section light-background">

      <div class="metric-container section-title-5" data-aos="fade-up">
        <div class="info">
          <span><h2>SUSCEPTIBILITY</h2></span><br>
          <span>Statisitcs on </span> <br><span class="description-title-1">Technological Susceptibility</span> <br> 
          <span>Per Role</span>
        </div>
      </div>

      <div class="chart-container-1">
      
        <!-- 📌 Clean "+" Icon Button -->
        <button id="addRoleBtn5" class="btn-add-role-icon-5">
          <svg class="plus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
      
        <!-- 📌 Clean "-" Icon Button -->
        <button id="removeRoleBtn5" class="btn-remove-role-icon-5">
          <svg class="minus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
        
        <button id="addDescriptionBtn5" class="btn-add-description-5">
          <i class="fa-solid fa-pencil-alt"></i> <!-- Font Awesome Pencil Icon -->
        </button>          
        
        <button id="reloadButton5" class="btn-reload-chart-icon-5">
          <i class="bi bi-arrow-repeat reload-icon"></i>
        </button>
        
        <h2><?php echo $_SESSION['organization_name']; ?></h2>
        <h3>Market Demand Statistics</h3>
        
        <canvas id="Chart5" class="chart-canvas"></canvas>
      </div>

      <!-- Modal 1: Add Role -->
      <div id="modalRole5" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRole5">&times;</span>
          <iframe id="iframeRole5" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 2: Add Description -->
      <div id="modalDescription5" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalDescription5">&times;</span>
          <iframe id="iframeDescription5" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 3: Remove Role -->
      <div id="modalRemove5" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRemove5">&times;</span>
          <iframe id="iframeRemove5" src="" width="100%" height="500px"></iframe>
        </div>
      </div>
    </section>








    <section id="dependence" class="dependence section light-background">

      <div class="metric-container section-title-6" data-aos="fade-up">
        <div class="info">
          <span><h2>INTER-DEPENDENCE</h2></span> <br>
          <span>Statisitcs on </span> <br> 
          <span class="description-title-1">Interdepartmental Dependence</span> <br> 
          <span>Per Role</span>
        </div>
      </div>

      <div class="chart-container-2">
      
        <!-- 📌 Clean "+" Icon Button -->
        <button id="addRoleBtn6" class="btn-add-role-icon-6">
          <svg class="plus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
      
        <!-- 📌 Clean "-" Icon Button -->
        <button id="removeRoleBtn6" class="btn-remove-role-icon-6">
          <svg class="minus-icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="white"
            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
        </button>
        
        <button id="addDescriptionBtn6" class="btn-add-description-6">
          <i class="fa-solid fa-pencil-alt"></i> <!-- Font Awesome Pencil Icon -->
        </button>          
        
        <button id="reloadButton6" class="btn-reload-chart-icon-6">
          <i class="bi bi-arrow-repeat reload-icon"></i>
        </button>
        
        <h2><?php echo $_SESSION['organization_name']; ?></h2>
        <h3>Market Demand Statistics</h3>
        
        <canvas id="Chart6" class="chart-canvas"></canvas>
      </div>

      <!-- Modal 1: Add Role -->
      <div id="modalRole6" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRole6">&times;</span>
          <iframe id="iframeRole6" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 2: Add Description -->
      <div id="modalDescription6" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalDescription6">&times;</span>
          <iframe id="iframeDescription6" src="" width="100%" height="500px"></iframe>
        </div>
      </div>

      <!-- Modal 3: Remove Role -->
      <div id="modalRemove6" class="modal">
        <div class="modal-content">
          <span class="close-btn" data-target="modalRemove6">&times;</span>
          <iframe id="iframeRemove6" src="" width="100%" height="500px"></iframe>
        </div>
      </div>
    </section>







    
    <!-- Pricing Section -->
    <section id="financial-section" class="pricing section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Pricing</h2>
        <div><span>Check Our</span> <span class="description-title">Pricing</span></div>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
            <div class="pricing-tem">
              <h3 style="color: #20c997;">Free Plan</h3>
              <div class="price"><sup>$</sup>0<span> / mo</span></div>
              <div class="icon">
                <i class="bi bi-box" style="color: #20c997;"></i>
              </div>
              <ul>
                <li>Aida dere</li>
                <li>Nec feugiat nisl</li>
                <li>Nulla at volutpat dola</li>
                <li class="na">Pharetra massa</li>
                <li class="na">Massa ultricies mi</li>
              </ul>
              <a href="#" class="btn-buy">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="pricing-tem">
              <span class="featured">Featured</span>
              <h3 style="color: #0dcaf0;">Starter Plan</h3>
              <div class="price"><sup>$</sup>19<span> / mo</span></div>
              <div class="icon">
                <i class="bi bi-send" style="color: #0dcaf0;"></i>
              </div>
              <ul>
                <li>Aida dere</li>
                <li>Nec feugiat nisl</li>
                <li>Nulla at volutpat dola</li>
                <li>Pharetra massa</li>
                <li class="na">Massa ultricies mi</li>
              </ul>
              <a href="#" class="btn-buy">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="pricing-tem">
              <h3 style="color: #fd7e14;">Business Plan</h3>
              <div class="price"><sup>$</sup>29<span> / mo</span></div>
              <div class="icon">
                <i class="bi bi-airplane" style="color: #fd7e14;"></i>
              </div>
              <ul>
                <li>Aida dere</li>
                <li>Nec feugiat nisl</li>
                <li>Nulla at volutpat dola</li>
                <li>Pharetra massa</li>
                <li>Massa ultricies mi</li>
              </ul>
              <a href="#" class="btn-buy">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="pricing-tem">
              <h3 style="color: #0d6efd;">Ultimate Plan</h3>
              <div class="price"><sup>$</sup>49<span> / mo</span></div>
              <div class="icon">
                <i class="bi bi-rocket" style="color: #0d6efd;"></i>
              </div>
              <ul>
                <li>Aida dere</li>
                <li>Nec feugiat nisl</li>
                <li>Nulla at volutpat dola</li>
                <li>Pharetra massa</li>
                <li>Massa ultricies mi</li>
              </ul>
              <a href="#" class="btn-buy">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->

        </div><!-- End pricing row -->

      </div>

    </section><!-- /Pricing Section -->

    <!-- Faq Section -->
    <section id="workforce-section" class="faq section light-background">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="content px-xl-5">
              <h3><span>Frequently Asked </span><strong>Questions</strong></h3>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Duis aute irure dolor in reprehenderit
              </p>
            </div>
          </div>

          <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">

            <div class="faq-container">
              <div class="faq-item faq-active">
                <h3><span class="num">1.</span> <span>Non consectetur a erat nam at lectus urna duis?</span></h3>
                <div class="faq-content">
                  <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur
                    gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">2.</span> <span>Feugiat scelerisque varius morbi enim nunc faucibus a
                    pellentesque?</span></h3>
                <div class="faq-content">
                  <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet
                    id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque
                    elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">3.</span> <span>Dolor sit amet consectetur adipiscing elit pellentesque?</span>
                </h3>
                <div class="faq-content">
                  <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar
                    elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque
                    eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis
                    sed odio morbi quis</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">4.</span> <span>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</span>
                </h3>
                <div class="faq-content">
                  <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet
                    id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque
                    elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span class="num">5.</span> <span>Tempus quam pellentesque nec nam aliquam sem et tortor
                    consequat?</span></h3>
                <div class="faq-content">
                  <p>Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in.
                    Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est.
                    Purus gravida quis blandit turpis cursus in</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div>
        </div>

      </div>

    </section><!-- /Faq Section -->



  </main>

  <footer id="footer" class="footer light-background">

    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">Job Role Evaluation</strong> <span>All Rights
            Reserved</span></p>
      </div>
      <div class="social-links d-flex justify-content-center">
        <a href=""><i class="bi bi-twitter-x"></i></a>
        <a href=""><i class="bi bi-facebook"></i></a>
        <a href=""><i class="bi bi-instagram"></i></a>
        <a href=""><i class="bi bi-linkedin"></i></a>
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- SweetAlert CDN must come first -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Your alerts.js file -->
  <script src="../../assets/js/alerts.js"></script>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="../javascript/script.js"></script>
  <script src="../javascript/main.js"></script>
  <script src="../javascript/metrics.js"></script>
  <script src="../javascript/performance_modal.js"></script>
  <script src="../javascript/cost_modal.js"></script>
  <script src="../javascript/revenue_modal.js"></script>
  <script src="../javascript/market_modal.js"></script>
  <script src="../javascript/susceptible_modal.js"></script>
  <script src="../javascript/dependence_modal.js"></script>

</body>

</html>

