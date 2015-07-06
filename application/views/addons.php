<section class="content-with-submenu">
    <section class="submenu">
        <nav class="secondary">
                <ul>
                    <li><h3>Categories</h3></li>
                    <?php
                    $initialactive = ($section === false) ? ' class="active"' : '';
                    echo '<li'.$initialactive.'><a href="/index.php/addons/">All</a></li>';
                    foreach ($tags as $tagname => $tag) {
                        $active = ($section == $tagname) ? ' class="active"' : '';
                        echo '<li'.$active.'><a href="/index.php/addons/category/'.$tagname.'/">'.$tag.'</a></li>';
                    }
                    ?>
                </ul>
        </nav>

    </section>
    <section class="rightcontent">
        <?php //$this->load->view( 'status', array( 'statustype' => 'mini' ) ); ?>
        <section id="addons" class="body section1">
            
            <section class="content">
                <div class="tabs addons">
                  <ul>
                    <li class="active"><a href="#tabs-1">Available</a></li>
                    <li><a href="#tabs-2">Installed</a></li>
                    <li><a href="#tabs-3">Get more</a></li>
                  </ul>
                    <div class="tab_container">
                      <div class="addontab active" id="tabs-1">
                        <div class="inner transition hidescale showscale">
                            <div class="addon_item usenet">
                                <h2>Sabnzbd</h2>
                                <p>
                                    SABnzbd is an Open Source Binary Newsreader written in Python. It's totally free, incredibly easy to use, and works practically everywhere. SABnzbd makes Usenet as simple and streamlined as possible by automating everything we can. All you have to do is add an .nzb. SABnzbd takes over from there, where it will be automatically downloaded, verified, repaired, extracted and filed away with zero human interaction.
                                    <span>Tags: media usenet</span>
                                </p>
                                <div class="package_details">
                                    Owner: dawiki<br />Last Updated: 10 days ago<br /><a href="" class="button greenbutton">Install now</a>
                                </div>
                            </div>
                            <hr />
                            <div class="addon_item">
                                <h2>CouchPotato</h2>
                                <p>Download movies automatically, easily and in the best quality as soon as they are available.  Awesome PVR for usenet and torrents. Just fill in what you want to see and CouchPotato will add it to your "want to watch"-list. Every day it will search through multiple NZBs & Torrents sites, looking for the best possible match. If available, it will download it using your favorite download software.</p>
                                <div class="package_details">
                                    Owner: dawiki<br />Last Updated: 10 days ago<br /><span class="installed">Already Installed</span>
                                </div>
                            </div>
                            <hr />
                            <div class="addon_item">
                                <h2>Sickbeard</h2>
                                <p>Sick Beard is a PVR for newsgroup users (with limited torrent support). It watches for new episodes of your favorite shows and when they are posted it downloads them, sorts and renames them, and optionally generates metadata for them. It currently supports NZBs.org, NZBMatrix, NZBs'R'Us, Newzbin, Womble's Index, NZB.su, TVTorrents and EZRSS and retrieves show information from theTVDB.com and TVRage.com.</p>
                                <div class="package_details">
                                    Owner: dawiki<br />Last Updated: 10 days ago<br /><a href="" class="button greenbutton">Install now</a>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="addontab" id="tabs-2">
                        <div class="inner transition hidescale">
                            <div class="addon_item">
                                <h2>CouchPotato</h2>
                                <p>Download movies automatically, easily and in the best quality as soon as they are available.  Awesome PVR for usenet and torrents. Just fill in what you want to see and CouchPotato will add it to your "want to watch"-list. Every day it will search through multiple NZBs & Torrents sites, looking for the best possible match. If available, it will download it using your favorite download software.</p>
                                <div class="package_details">
                                    Owner: dawiki<br />Last Updated: 10 days ago<br /><a href="" class="button redbutton">Remove addon</a>
                                </div>
                            </div>
                            <hr />
                            <div class="addon_item">
                                <h2>Plex Media Server</h2>
                                <p>The Plex Media Server enriches your life by organizing all your personal media, presenting it beautifully and streaming it to all of your devices. It's easy to use, it's awesome, and it's free!</p>
                                <div class="package_details">
                                    Owner: dawiki<br />Last Updated: 10 days ago<br /><a href="" class="button redbutton">Remove addon</a>
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="addontab" id="tabs-3">
                        <div class="inner transition hidescale">
                            <div class="addon_item">
                            <h2>Boiler - The missing package manager for unRAID</h2>
                            <p>We use the <a href="http://getboiler.com">boiler package manager</a> to handle the addons, so please get involved and help us make packages available</p>
                        </div>
                      </div>
                    </div>
                  </div>
            
            </section>
            <div class="hr"></div>

        </section>
    </section>
</section>

