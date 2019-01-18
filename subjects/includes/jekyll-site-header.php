<?php
/**
 * Created by PhpStorm.
 * User: pvillanueva
 * Date: 06/05/18
 */
?>

<!--CONTENT for header-->
<section class="slivernav" style="background-color:#000000;">
    <div class="container-fluid no-padding">
        <div class="row no-gutters">
            <div class="col-1">
                <button class="sliverbutton sliver-accessibility-toggle" aria-label="Toggle Accessibility Options" title="Toggle Accessibility Options">
                    <i class="custom-eye"></i>
                </button>
            </div>
            <div class="col um-svg">
                <a href="http://www.miami.edu/" target="_blank" title="University of Miami" class="no-decoration">
                    <img src="https://www.library.miami.edu/assets/common-images/um-logo-sliver.svg" alt="University of Miami Sliver-logo">
                </a>
            </div>
            <div class="col-1">
                <button class="sliverbutton sliver-toggle-menu" aria-label="Toggle Sliver Menu" title="Toggle Sliver Menu">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</section>
<div class="sliver-content" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form action="https://welcome.miami.edu/search/search-results.html">
                    <input name="client" value="$sSearchClient" type="hidden">
                    <input name="resTemp" value="/search/search-results.html" type="hidden">
                    <input name="domain" value="https://library.miami.edu" type="hidden">
                    <label class="show-for-sr" for="sliverSearchSmall">Search UM</label>
                    <input class="umsearchfield-sm" id="sliverSearchSmall" name="q" placeholder="Search UM" type="text">
                    <button aria-label="Search" class="search-sliver"><i class="fa fa-search"></i></button>
                </form>
            </div>

            <div class="col-md-8 order-md-first">
                <ul class="list-unstyled mt-3 mt-md-0 mb-md-0 d-md-flex flex-md-nowrap flex-md-row justify-content-md-between justify-content-xl-start">
                    <li><a href="https://welcome.miami.edu/apply-now/index.html"><button class="apply-btn">Apply</button></a></li>
                    <li><a href="https://welcome.miami.edu/about-um/index.html">About UM</a></li>
                    <li><a href="https://welcome.miami.edu/give/index.html">Give to UM</a></li>
                    <li><a href="https://welcome.miami.edu/academics/schools-and-colleges/index.html">Schools</a></li>
                    <li><a href="https://alumni.miami.edu/index.html">Alumni</a></li>
                    <li><a href="http://roadmap.miami.edu/index.html">Roadmap</a></li>
                    <li class="d-md-none"><a href="https://welcome.miami.edu/academics/index.html">Academics</a></li>
                    <li class="d-md-none"><a href="https://welcome.miami.edu/admissions/index.html">Admissions</a></li>
                    <li class="d-md-none"><a href="https://welcome.miami.edu/student-life/index.html">Student Life</a></li>
                    <li class="d-md-none"><a href="https://welcome.miami.edu/research/index.html">Research</a></li>
                    <li class="d-md-none"><a href="https://welcome.miami.edu/uhealth/index.html">UHealth</a></li>
                    <li class="d-md-none"><a href="https://welcome.miami.edu/athletics/index.html">Athletics</a></li>
                </ul>
            </div>
            <div class="col-md-1">
                <a href="https://welcome.miami.edu/tools/index.html" class="um-last-link">Tools</a>
            </div>
        </div>

        <script>
            // Sliver Scripts
            $( function(){
                $('.sliver-toggle-menu').click(function(){
                    $('.sliver-content').toggle();
                    $('.sliver-toggle-menu i').toggleClass('fa-plus fa-minus');
                });
            });
        </script>
    </div>
</div>
<div class="sliver-accessibility" style="display: none;">
    <div class="container">
        <h6>Accessibility Options:</h6>
        <ul class="list-unstyled mt-3 mt-md-0 d-sm-flex flex-sm-nowrap flex-sm-row justify-content-sm-start">
            <li><a href="https://camnercenter.miami.edu/disability-services/index.html">Office of Disability Services</a></li>
            <li><a href="https://camnercenter.miami.edu/disability-services/request-services/index.html">Request Assistance</a></li>
            <li>305-284-2374</li>
        </ul>

        <script>
            // Accessibility Scripts
            $( function(){
                $('.sliver-accessibility-toggle').click(function(){
                    $('.sliver-accessibility').toggle();
                });
            });
        </script>
    </div>
</div>
<nav class="navbar-expand-xl site-navbar" style="background-color:#4f5b65;">
    <div class="container position-relative">
        <a class="navbar-brand" href="https://www.library.miami.edu">
            <img src="https://www.library.miami.edu/assets/common-images/uml-logo_vision2.png" alt="University of Miami Libraries" class="d-inline-block d-sm-none">
            <img src="https://www.library.miami.edu/assets/common-images/uml-logo_vision-full2.png" alt="University of Miami Libraries" class="d-none d-sm-inline-block d-lg-block">
        </a>
        <button class="navbar-toggler js-sidebar-toggler d-block d-xl-none" data-button-options='{"modifiers":"left","wrapText":false}' aria-label="Toggle sidebar">
            <div class="nav-icon">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </button>

        <div class="nav-account d-none d-lg-inline-block">
            <a class="no-decoration mr-3" href="https://www.library.miami.edu/research/ask-a-librarian.html"><i class="fas fa-question-circle"></i> Ask A Librarian</a>
            <a class="no-decoration account" href="#"><i class="fas fa-user-circle"></i> Accounts</a>
        </div>
    </div>

    <div class="container">
        <div class="collapse navbar-collapse" id="siteNavigation" data-set="bs">
            <ul class="navbar-nav site-nav js-append-around">
                <li class="nav-item active d-inline-block d-xl-none">
                    <a class="nav-link no-decoration" href="#"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link no-decoration dropdown-toggle" href="#" id="dropdown-find-read" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-chevron-circle-down d-xl-none"></i> Find & Read</a>
                    <div class="dropdown-menu mega-menu" aria-labelledby="dropdown-find-read">
                        <div class="section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4">

                                        <h5>Books</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/search?&tab=default_tab&search_scope=default_scope&vid=uml_new&offset=0">uSearch Library Catalog <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/floor-maps.html">Floor Maps (Richter)</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://uofmiami.overdrive.com/">Overdrive E-Books & Audiobooks</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/specialcollections/index.html">Rare Books (Special Collections)</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/search?vid=uml_new">Renew your Books (My Account)</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/stack-tower-guide.html">Stack Tower Guide (Richter)</a></li>

                                        </ul>



                                        <h5>Articles</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/search?&tab=everything&search_scope=Everything&vid=uml_new&offset=0">uSearch Articles <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/databases.php">Databases A-Z</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/jsearch?vid=uml_new&lang=en_US">eJournals</a></li>

                                        </ul>

                                    </div>
                                    <div class="col-12 col-xl-4">

                                        <h5>Music & Film</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/search?&tab=default_tab&search_scope=default_scope&vid=uml_new&offset=0">uSearch Library Catalog <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/musiclib/index.html">Music Library</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/musiclib/browsing-guide.html">Score Browsing Guide</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/musiclib/streamingaudioreserves.html">Streaming Music</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/databases.php?letter=bytype&type=Video">Streaming Video</a></li>

                                        </ul>



                                        <h5>Archives & Images</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/search?&tab=digital_collections&search_scope=Digital_Collections&vid=uml_new&offset=0">uSearch Digital Collections <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://atom.library.miami.edu/">Archival Finding Aids</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/chc/index.html">Cuban Heritage Collection</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://merrick.library.miami.edu/cdm/">Digital Collections</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/specialcollections/index.html">Special Collections</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/universityarchives/index.html">University Archives</a></li>

                                        </ul>

                                    </div>
                                    <div class="col-12 col-xl-4">

                                        <h5>Non-Book Materials</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/find/games-maps.html">Maps, Games & more</a></li>

                                        </ul>



                                        <h5>Related Resources</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/find/borrowing.html">Access & Borrowing Policies</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/find/course-reserves.html">Course Reserves</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://triton.library.miami.edu/">Interlibrary Loan (ILL)</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/suggest-a-purchase/">Recommend a Purchase</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://scholarlyrepository.miami.edu/">Scholarly Repository</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/copyright.html">Understanding Copyright</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/guide.php?subject=U2">uSearch Help</a></li>

                                        </ul>

                                    </div>
                                </div>
                                <div class="panel-close d-none d-xl-block">
                                    <div class="close-mm" title="Close"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link no-decoration dropdown-toggle" href="#" id="dropdown-research-write" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-chevron-circle-down d-xl-none"></i> Research & Write</a>
                    <div class="dropdown-menu mega-menu" aria-labelledby="dropdown-research-write">
                        <div class="section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4">

                                        <h5>Get Started</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://www.library.miami.edu/learningcommons/index.html">Learning Commons <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/ask-a-librarian.html">Ask a Librarian</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/consultations.html">Research Consultations</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/index.php">Research Guides</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/copyright.html">Understanding Copyright</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/workshops-tutorials.html">Workshops</a></li>

                                        </ul>



                                        <h5>Find Sources</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/databases.php">Databases A-Z</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/lib-collections/index.html">Distinctive Collections</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/search?&tab=everything&search_scope=Everything&vid=uml_new&offset=0">Find Books & Articles</a></li>

                                        </ul>

                                    </div>
                                    <div class="col-12 col-xl-4">

                                        <h5>Analyze Data</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/analyze-data.html#dh">Digital Humanities Data</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/analyze-data.html#gis">Geospatial Data (GIS)</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/analyze-data.html#stats">Statistics</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/analyze-data.html#survey">Survey / Census Datasets</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/analyze-data.html#ta">Textual Analysis & Survey</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/analyze-data.html#viz">Visualization</a></li>

                                        </ul>



                                        <h5>Organize & Design</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/citation-help.html">Citation Help</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/data-services">Data Management Strategies</a></li>

                                        </ul>

                                    </div>
                                    <div class="col-12 col-xl-4">

                                        <h5>Write</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="http://www.as.miami.edu/writingcenter/">Writing Center</a> <span class="d-none d-xl-inline-block">/</span> <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/learningcommons/index.html">Learning Commons</a></li>

                                        </ul>



                                        <h5>Publish & Promote</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/etd">Dissertation/Thesis Formatting Support (ETDs)</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/scholarly-communications.html">Scholarly Communications & Publishing</a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://scholarlyrepository.miami.edu/">Scholarly Repository</a></li>

                                        </ul>



                                        <h5>Apply for Fellowships</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/library-research-scholars.html">Library Research Scholars & Adobe Scholars</a></li>

                                        </ul>


                                    </div>
                                </div>
                                <div class="panel-close d-none d-xl-block">
                                    <div class="close-mm" title="Close"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link no-decoration dropdown-toggle" href="#" id="dropdown-learn-create" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-chevron-circle-down d-xl-none"></i> Learn & Create</a>
                    <div class="dropdown-menu mega-menu" aria-labelledby="dropdown-learn-create">
                        <div class="section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-8">

                                        <h5>Learn & Create</h5>
                                        <hr align="left" class="hr-decoration">
                                        <div class="row">
                                            <div class="col-12 col-xl-6">
                                                <ul>

                                                    <li><a class="dropdown-item no-decoration cta" href="https://www.library.miami.edu/learningcommons/index.html">Learning Commons <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                                    <li><a class="dropdown-item no-decoration" href="http://umarc.miami.edu/arc/Index.html">Camner Center for Academic Resources</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/reserve-equipment.html">Check Out Equipment</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/computers.html">Computers</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/creative-studio/index.html">Creative Studio</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/data-services">Data Services</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/scholars-lab.html">Digital Scholars' Lab</a></li>

                                                </ul>
                                            </div>
                                            <div class="col-12 col-xl-6">
                                                <ul>

                                                    <li><a class="dropdown-item no-decoration" href="http://www.math.miami.edu/resources/math-laboratory/">Math Lab</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="http://www.as.miami.edu/labs/">Modern Languages Lab</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/faculty-exploratory.html">One-Button Studio</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/consultations.html">Research Consultations</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/computers.html">Software</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="http://studentsupport.it.miami.edu/sthd/">Student Technology Help Desk</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="http://www.as.miami.edu/writingcenter/">Writing Center</a></li>

                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-12 col-xl-4">
                                        <script>
                                            var cardItems = [

                                                ['https://www.library.miami.edu/images/card-mango.jpg'],

                                                ['https://www.library.miami.edu/images/card-lynda.jpg']

                                            ];
                                            var cardLink = [

                                                ['http://access.library.miami.edu/login?url=http://connect.mangolanguages.com/univ-miami/start'],

                                                ['http://access.library.miami.edu/login?url=http://www.miami.edu/lynda']

                                            ];
                                            var cardTitle = [

                                                ['Mango Languages'],

                                                ['Lynda.com']

                                            ];
                                            var cardContent = [

                                                ['UM students, staff and faculty have access to Mango Languages which offers online learning in 71 languages.'],

                                                ['Tutorials just a click away. We offer access to lynda\'s 95,000+ \"how-to\" videos on a wide range of software applications.']

                                            ];

                                            function getRandomCardLearn() {
                                                var dataIndex = Math.floor(Math.random() * cardItems.length);
                                                var img = '<div class=\"card d-none d-xl-block\"><a href=\"' + cardLink[dataIndex] + '\" title=\"' + cardTitle[dataIndex] + '\"><img src="';
                                                img += cardItems[dataIndex];
                                                img += '\" alt=\"' + cardTitle[dataIndex] + '\" class=\"card-img-top d-none d-xl-block no-decoration\" /></a><div class=\"card-body\"><h5 class=\"card-title\"><a class=\"no-decoration default\" href=\"' + cardLink[dataIndex] + '\">' + cardTitle[dataIndex] + '</a></h5><p>' + cardContent[dataIndex] + '</p></div></div>';
                                                return img;
                                            }
                                            document.write(getRandomCardLearn());
                                        </script>
                                    </div>
                                </div>
                                <div class="panel-close d-none d-xl-block">
                                    <div class="close-mm" title="Close"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link no-decoration dropdown-toggle" href="#" id="dropdown-spaces-tech" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-chevron-circle-down d-xl-none"></i> Spaces & Technology</a>
                    <div class="dropdown-menu mega-menu" aria-labelledby="dropdown-spaces-tech">
                        <div class="section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-3">

                                        <h5>Rooms & Spaces</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://www.library.miami.edu/spaces/index.html">See All <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://humanities.miami.edu/">Center for Humanities</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/floor-maps.html">Floor Maps (Richter)</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/info-lit-lab.html">Information Literacy Lab</a></li>

                                            <li><a class="dropdown-item no-decoration" href="/kislak-center/index.html">Kislak Center</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/rsmaslib/rsmas-room-reservations.html">Marine & Atmospheric Library Rooms</a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://libcal.miami.edu/booking/music-study">Music Library Rooms</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/stack-tower-guide.html">Stack Tower Guide</a></li>

                                        </ul>

                                    </div>
                                    <div class="col-12 col-xl-3">

                                        <h5>Study Spaces</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://libcal.miami.edu/booking/richter-study">Reserve Study Room <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/graduate-study.html">Graduate Study Areas</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/group-study.html">Group Study Use Policy</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/noise.html">Noise & Courtesy Policy</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/study-zones.html">Study Zones / Quiet Areas</a></li>

                                        </ul>

                                    </div>

                                    <div class="col-12 col-xl-3">

                                        <h5>Consultation Spaces</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://www.library.miami.edu/learningcommons/index.html">Learning Commons <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://umarc.miami.edu/arc/Index.html">Camner Center for Academic Resources</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/creative-studio/index.html">Creative Studio</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/scholars-lab.html">Digital Scholars' Lab</a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://www.math.miami.edu/resources/math-laboratory/">Math Lab</a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://www.as.miami.edu/labs/">Modern Languages Lab</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/research/ask-a-librarian.html">Research Support</a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://www.as.miami.edu/writingcenter/">Writing Center</a></li>

                                        </ul>

                                    </div>

                                    <div class="col-12 col-xl-3">

                                        <h5>Technology</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/reserve-equipment.html">Check Out Equipment</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/computers.html">Computers</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/printing.html">Printing, Copying & Scanning</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/computers.html">Software</a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://studentsupport.it.miami.edu/sthd/">Student Technology Help Desk</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/faq.php?faq_id=26">Wireless FAQs</a></li>

                                        </ul>

                                    </div>
                                </div>
                                <div class="panel-close d-none d-xl-block">
                                    <div class="close-mm" title="Close"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link no-decoration dropdown-toggle" href="#" id="dropdown-lib-coll" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-chevron-circle-down d-xl-none"></i> Libraries & Collections</a>
                    <div id="mm-lib-coll-container" class="dropdown-menu mega-menu" aria-labelledby="dropdown-lib-coll">
                        <div class="section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4">
                                        <h5>Libraries</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/richter-lib-sm.jpg" alt="Richter Library" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/richter/index.html">Richter Library<br />(Interdisciplinary)</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/richter/hours.html"><span data-calendar="richter"></span></a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/architecture-lib-sm.jpg" alt="Architecture Library" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/architecture/index.html">Architecture Library</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/architecture/hours.html"><span data-calendar="architecture"></span></a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/business-lib-sm.jpg" alt="Business Library" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/business/index.html">Business Library</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/business/hours.html"><span data-calendar="business"></span></a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/law-lib-sm.jpg" alt="Law Library" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="http://www.law.miami.edu/library/">Law Library</a>
                                                    <div class="mm-lib-time"><a href="https://www.law.miami.edu/iml/library/library-contact-and-hours" class="no-decoration default">See Hours</a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/marine-lib-sm.jpg" alt="Marine and Atmospheric Science Library" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/rsmaslib/index.html">Marine and Atmospheric Science Library</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/rsmaslib/hours.html"><span data-calendar="rsmaslib"></span></a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/med-lib-sm.jpg" alt="Medical Library" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="http://calder.med.miami.edu/">Medical Library</a>
                                                    <div class="mm-lib-time"><a href="http://calder.med.miami.edu/libraryhours.html" class="no-decoration default">See Hours</a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/music-lib-sm.jpg" alt="Music Library" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/musiclib/index.html">Music Library</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/musiclib/hours.html"><span data-calendar="musiclib"></span></a></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-xl-4">
                                        <h5>Collections</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/chc-sm.jpg" alt="Cuban Heritage Collection" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/chc/index.html">Cuban Heritage Collection<br />(Richter Library)</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/chc/hours.html"><span data-calendar="chc"></span></a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/digital-collections-sm.jpg" alt="Digital Collections" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="http://merrick.library.miami.edu/">Digital Collections</a>
                                                    <div class="mm-lib-time">Online</div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/scholarly-repository2-sm.jpg"  alt="Scholarly Repository" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="http://scholarlyrepository.miami.edu/">Scholarly Repository</a>
                                                    <div class="mm-lib-time">Online</div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/special-collections-sm.jpg" alt="Special Collections" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/specialcollections/index.html">Special Collections<br />(Kislak Center)</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/specialcollections/hours.html"><span data-calendar="specialcollections"></span></a></div>
                                                </div>
                                            </li>
                                            <li class="mm-lib">
                                                <img src="https://www.library.miami.edu/assets/common-images/mm-libraries/archives-sm.jpg" alt="University Archives" class="mm-lib-img d-none d-xl-inline-block">
                                                <div class="nested-items">
                                                    <a class="dropdown-item no-decoration" href="https://www.library.miami.edu/universityarchives/index.html">University Archives<br />(Kislak Center)</a>
                                                    <div class="mm-lib-time"><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/universityarchives/hours.html"><span data-calendar="universityarchives"></span></a></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-xl-4">
                                        <script>
                                            var cardCollectionItems = [

                                                ['https://www.library.miami.edu/images/card-chc-ochumare-sm.jpg'],

                                                ['https://www.library.miami.edu/images/card-panam-sm.jpg']

                                            ];
                                            var cardCollectionLink = [

                                                ['http://scholar.library.miami.edu/pozo/'],

                                                ['http://merrick.library.miami.edu/specialCollections/asm0341/']

                                            ];
                                            var cardCollectionTitle = [

                                                ['Alberto del Pozo\'s Orichas'],

                                                ['Pan American World Airways, Inc.']

                                            ];
                                            var cardCollectionContent = [

                                                ['The Oricha Collection by Alberto del Pozo pays homage to the primary gods and goddesses that comprise the Afro-Cuban religion of Santer&iacute;a. Each pen, crayon, and ink illustration measures 40 by 30 inches. The Oricha Collection is held in the University of Miami Libraries Cuban Heritage Collection.'],

                                                ['The University of Miami Libraries Special Collections is home to the Pan American World Airways, Inc. Records.']

                                            ];

                                            function getRandomCardCollection() {
                                                var dataIndex = Math.floor(Math.random() * cardCollectionItems.length);
                                                var img = '<div class=\"card d-none d-xl-block\"><a href=\"' + cardCollectionLink[dataIndex] + '\" title=\"' + cardCollectionTitle[dataIndex] + '\"><img src="';
                                                img += cardCollectionItems[dataIndex];
                                                img += '\" alt=\"' + cardCollectionTitle[dataIndex] + '\" class=\"card-img-top d-none d-xl-block no-decoration\" /></a><div class=\"card-body\"><h5 class=\"card-title\"><a class=\"no-decoration default\" href=\"' + cardCollectionLink[dataIndex] + '\">' + cardCollectionTitle[dataIndex] + '</a></h5><p>' + cardCollectionContent[dataIndex] + '</p></div></div>';
                                                return img;
                                            }
                                            document.write(getRandomCardCollection());
                                        </script>


                                        <h5>Related Resources</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://atom.library.miami.edu/">Archival Finding Aids</a></li>

                                            <li><a class="dropdown-item no-decoration" href="http://merrick.library.miami.edu/digitalprojects/exhibits.php">Digital Exhibits</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/oral-histories.html">Oral Histories</a></li>

                                        </ul>

                                    </div>
                                </div>
                                <div class="panel-close d-none d-xl-block">
                                    <div class="close-mm" title="Close"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link no-decoration dropdown-toggle" href="#" id="dropdown-about" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-chevron-circle-down d-xl-none"></i> About</a>
                    <div class="dropdown-menu mega-menu" aria-labelledby="dropdown-about">
                        <div class="section">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-4">

                                        <h5>About</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration cta" href="https://www.library.miami.edu/about/index.html">About Us <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/talkback.php">Comments</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/departments/index.html">Departments</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/workhere/index.html">Employment</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/forms.html">Forms</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://news.miami.edu/libraries/index.html">Library News & Events</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/message-from-dean.html">Message from the Dean</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/vision-mission-and-values.html">Mission / Vision</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/policies.html">Policies</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/staff.php">Staff List</a></li>

                                        </ul>

                                    </div>
                                    <div class="col-12 col-xl-4">

                                        <h5>Support the Libraries</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/chc/amigos.html">Amigos of the Cuban Heritage Collection</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/support-the-libraries.html">Donating Materials</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/friends-of-the-libraries.html">Friends of the Libraries</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/donating-materials.html">Gifts & Donations Policy</a></li>

                                        </ul>



                                        <h5>Information For...</h5>
                                        <hr align="left" class="hr-decoration">
                                        <ul>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/alumnus.html">Alumni</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/faculty.html">Faculty</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/graduate.html">Graduate</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/undergrad.html">Undergraduate</a></li>

                                            <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/visitors.html">Visitor</a></li>

                                        </ul>

                                    </div>
                                    <div class="col-12 col-xl-4">
                                        <div class="card">
                                            <img src="https://www.library.miami.edu/images/card-about-richter-lib-sm.jpg" alt="Richter Library" class="card-img-top d-none d-xl-block">
                                            <div class="card-body">

                                                <h5 class="card-title">Planning a Visit?</h5>
                                                <hr align="left" class="hr-decoration d-block d-xl-none">
                                                <ul>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/ada.html">ADA / Disability Services</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://sp.library.miami.edu/subjects/faq.php">FAQs</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/floor-maps.html">Floor Maps (Richter)</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/richter/hours.html">Hours</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/about/maps-directions.html">Maps & Directions</a></li>

                                                    <li><a class="dropdown-item no-decoration" href="https://www.library.miami.edu/spaces/stack-tower-guide.html">Stack Tower Guide (Richter)</a></li>

                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-close d-none d-xl-block">
                                    <div class="close-mm" title="Close"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="mega-menu account-dropdown" aria-labelledby="dropdown-accounts">
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <h5>Accounts</h5>
                        <hr align="left" class="hr-panel">
                        <ul class="d-inline-block">

                            <li><a class="dropdown-item no-decoration cta" href="https://miami-primo.hosted.exlibrisgroup.com/primo-explore/search?vid=uml_new">Library Account (uSearch) <i class="fas fa-chevron-right d-none d-xl-inline-block"></i></a></li>

                            <li><a class="dropdown-item no-decoration" href="https://aeon.library.miami.edu/aeon/">Aeon (materials in our collections)</a></li>

                            <li><a class="dropdown-item no-decoration" href="https://www.courses.miami.edu/webapps/portal/execute/tabs/tabAction?tab_tab_group_id=_259_1">Blackboard</a></li>

                            <li><a class="dropdown-item no-decoration" href="https://myum.miami.edu/">Cane Link</a></li>

                            <li><a class="dropdown-item no-decoration" href="https://triton.library.miami.edu/">Interlibrary Loan (ILL)</a></li>

                        </ul>

                    </div>
                    <div class="col-12 col-lg-8 d-none d-lg-block">
                        <h5>My Favorite Links</h5>
                        <hr align="left" class="hr-panel">
                        <div class="my-favorite-links uml-quick-links" data-type="account-list">
                            <h6><a href="https://www.library.miami.edu/favorites.html" class="no-decoration default">(How to use this feature)</a></h6>
                        </div>
                    </div>
                </div>
                <div class="panel-close d-none d-xl-block">
                    <div class="close-mm" title="Close"></div>
                </div>
            </div>
        </div>
    </div>
</nav>