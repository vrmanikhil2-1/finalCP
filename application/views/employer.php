<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $pageTitle."|Backoffice-CampusPuppy"; ?></title>

    <?php echo $headerFiles; ?>

  </head>

  <body>

    <nav class="navbar fixed-top nav-bg navbar-expand-lg navbar-dark bg-dark fixed-top" style="height: 70px;">
      <div class="container">
        <a class="navbar-brand" href="<?php echo base_url(); ?>">
          <img class="img-responsive" src="<?php echo base_url('assets/images/cp_logo_white.png'); ?>" style="width: 14%; margin: 10px;">
        </a>

      </div>
    </nav>

    <header>
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner" role="listbox">
          <!-- Slide One - Set the background image for this slide in the line below -->
          <div class="carousel-item active" style="background-image: url('<?php echo base_url('assets/images/employer.jpg'); ?>')">
            <div class="carousel-caption d-none d-md-block">
            </div>
          </div>

        </div>

      </div>
    </header>


    <div class="container" style="margin-top: 10px;">


      <?php if($message['content']!=''){?>
      <ol class="breadcrumb" style="background-color: white !important; margin-top: 20px; border: 1px solid <?=$message['color']?>;">
        <li style="color: <?=$message['color']?>;"><?=$message['content']?></li>
      </ol>
    	<?php }?>

      <div class="row">

        <div class="col-lg-8 mb-4">

        <div class="col-lg-12 mb-4">


          <a href="#" class="btn btn-primary" style="float: right; margin: 10px;">Filter Job Offer(s)</a>

          <h5 style="margin: 20px;"><b>Job Offer(s)</b></h5>
          <div class="clearfix"></div>
          <div class="col-lg-12 mb-4">
          <div class="card">
            <h6 class="card-header" style="font-size: 15px;"><b>PHP Developer Required</b></h6>

            <div class="card-body">
                <p class="card-text" style="font-size: 13px;"><b>Skill(s) Required: </b>General Aptitude, PHP, HTML</p>
                <p class="card-text" style="font-size: 13px;"><b>Compensation: </b>INR 18000 per month</p>
                <p class="card-text" style="font-size: 13px;"><b>Joining Date: </b>18th May 2018</p>
                <p class="card-text" style="font-size: 13px;"><b>Application Deadline: </b>30th April 2018 <label style="color: green"><b>Application(s) Open</b><label></p>
            </div>

            <div class="card-footer">
              <a href="#" class="btn btn-primary" style="float: right;">View Offer</a>
            </div>
          </div>
          </div>

          <div class="col-lg-12 mb-4">
          <div class="card">
            <h6 class="card-header" style="font-size: 15px;"><b>PHP Developer Required</b></h6>

            <div class="card-body">
                <p class="card-text" style="font-size: 13px;"><b>Skill(s) Required: </b>General Aptitude, PHP, HTML</p>
                <p class="card-text" style="font-size: 13px;"><b>Compensation: </b>INR 18000 per month</p>
                <p class="card-text" style="font-size: 13px;"><b>Joining Date: </b>18th May 2018</p>
                <p class="card-text" style="font-size: 13px;"><b>Application Deadline: </b>30th April 2018 <label style="color: red"><b>Application(s) Closed</b><label></p>
            </div>

            <div class="card-footer">
              <a href="#" class="btn btn-primary" style="float: right;">View Offer</a>
            </div>
          </div>
          </div>

        </div>

        </div>

        <div class="col-lg-4 mb-4">

          <div class="card h-100">
            <h6 class="card-header"><center><b>Company Profile</b></center></h6>
            <div class="card-body">
              <center><img style="width: 70%;" src="<?php echo base_url('assets/images/cp_logo.png'); ?>"></center>
              <p><b>About Us</b></p>
              <p class="card-text" style="font-size: 14px; text-align: justify; text-justify: inter-word;">Eradicating the gap between employers and potential candidates, we, at CampusPuppy, consider it a need in this era to connect an individual with a company, based on the individual’s specific skill set and expertise in it. Linking together a social community that leaves fake profiles in a galaxy far far away, we intend to provide a hassle free experience of recruitment to both students and employers, at the same time. And what’s more? This professionally socialized environment allows candidates to take various tests to validate individualistic skills. Our sole intention is to bring out a genuine and relaxed recruitment environment and we believe that is what gives us our special place in the market.</p>
              <p><b>Website</b></p>
              <p class="card-text" style="font-size: 14px;"><a>http://www.campuspuppy.com/</a></p>
              <p><b>Location</b></p>
              <p class="card-text" style="font-size: 14px;">New Delhi</p>
              <p><b>Contact Details</b></p>
              <p class="card-text" style="font-size: 14px;">hello@campuspuppy.com<br>+91-7503705892</p>
            </div>
          </div>


        </div>

      </div>

    </div>

    <?php echo $footer; ?>

    <?php echo $footerFiles; ?>

    <script src="<?= base_url('assets/ckeditor/ckeditor.js')?>"></script>
    <script>
      $(document).ready(function(){
        editor = CKEDITOR.replace('careerObjective');
      });
      </script>

  </body>

</html>
