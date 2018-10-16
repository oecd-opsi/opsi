
          
          </div><!-- close main content -->

      </div>
    </div>
    <div class="footarea">
      <div class="row-fluid">
         
        <div id="footer" class="clearfix">
          <div class="footer_inner">
            <div class="container">
              <div class="row rowfooter">
                
                <div class="col-md-4 col-md-push-4 col-sm-12 col-xs-12">
                  <div class="clearfix footerelements footer2">
                    <div class="row">
                    
                      <div class="col-md-12 visible-xs">
                        <?php echo do_shortcode('[mc4wp_form id="274"]'); ?>
                      </div>
                    
                      <div class="col-sm-6 col-xs-10">
                    <?php 
                      wp_nav_menu( array(
                        'menu'   => 'Footer Menu'
                      ) );
                    ?>
                      </div>
                      <div class="col-sm-6 col-xs-2">
                      
                        <div class="social-wrap">
                          <a href="https://twitter.com/OPSIgov" title="OPSI on Twitter">
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-circle fa-stack-2x"></i>
                              <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                            </span><span class="hidden-xs">
                            @OPSIgov</span></a>
                          <br />
                          
                          <a href="https://www.facebook.com/theOECD/" title="OPSI on Facebook">
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-circle fa-stack-2x"></i>
                              <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                            </span><span class="hidden-xs">
                            OPSI on Facebook</span></a>
                          <br />
                          
                          <a href="https://www.linkedin.com/company/165285/" title="OPSI on LinkedIn">
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-circle fa-stack-2x"></i>
                              <i class="fa fa-linkedin fa-stack-1x fa-inverse"></i>
                            </span><span class="hidden-xs">
                            OPSI on LinkedIn</span></a>
                          
                        </div>
                      </div>
                      
                      <div class="col-md-12 hidden-xs">
                        <?php echo do_shortcode('[mc4wp_form id="274"]'); ?>
                      </div>
                      
                    </div>
                  </div>
                </div>
                
                
                <div class="col-md-4 col-md-pull-4 col-sm-12 col-xs-12">
                  <div class="clearfix footerelements footer1 text-center-xs">
                    <a href="https://www.oecd.org/" title="OECD" class="blank">
                      <img class="footer-logo" src="<?php echo get_template_directory_uri().'/images/oecd-logo.png'; ?>" alt="OECD" width="238" height="74">
                    </a>
                    <div class="small text-center-xs">
                      © Organisation for Economic Cooperation and Development
                      <address class="small">
                        2 rue André Pascal, 75775 Paris CEDEX 16, France 
                      </address>
                    </div>
                  </div>
                </div>
                
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="clearfix footerelements footer3">
                    <a href="https://ec.europa.eu/programmes/horizon2020/" title="Horizon 2020 Framework Programme of the European Union" class="blacklink blank">
                      <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/eu-flag.png" alt="European Union flag" width="80" height="54" class="pull-right euflag" >
                    </a>
                    <p class="h2020  text-right">
                      <a href="https://ec.europa.eu/programmes/horizon2020/" title="Horizon 2020 Framework Programme of the European Union" class="blacklink blank">
                        <?php echo __('Co-funded by the Horizon 2020 Framework Programme of the European Union', 'opsi'); ?>
                      </a>
                    </p>
                    <div class="cc_copy small text-right text-center-xs">
                      <a href="https://creativecommons.org/licenses/by-sa/3.0/igo/" title="Creative Commons Attribution" class="blacklink blank">
                        <?php echo __('This work is licensed under a Creative Commons Attribution - ShareAlike 3.0 IGO (CC BY-SA 3.0 IGO)', 'opsi'); ?>
                      </a>
                    </div>
                  </div>
                </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
        

      
      
      
      </div><!-- close main_cont_wrap -->
        
      
    </div><!-- close mainwrapper container -->    

    
    
  
  <?php wp_footer(); ?>
  </body>
</html>