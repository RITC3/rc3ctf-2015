<div class='post-content' itemprop='articleBody'>
Following our notification, Symantec published <a href="https://www-secure.symantec.com/connect/sites/default/files/Test_Certificates_Incident_Final_Report_10_13_2015v3b.pdf">a report</a> in response to our inquiries and disclosed that 23 test certificates had been issued without the domain owner&#8217;s knowledge covering five organizations, including Google and Opera.<br />
<br />
However, we were still able to find several more questionable certificates using only the Certificate Transparency logs and a few minutes of work. We shared these results with other root store operators on October 6th, to allow them to independently assess and verify our research.<br />
<br />
Symantec performed another audit and, on October 12th, announced that they had found an additional <a href="https://www-secure.symantec.com/connect/sites/default/files/TestCertificateIncidentReportOwnedDomains.pdf">164 certificates</a> over 76 domains and <a href="https://www-secure.symantec.com/connect/sites/default/files/TestCertificateIncidentReportUnregisteredv2.pdf">2,458 certificates</a> issued for domains that were never registered.<br />
<span class="byline-author"><br /></span>
It&#8217;s obviously concerning that a CA would have such a long-running issue and that they would be unable to assess its scope after being alerted to it and conducting an audit. Therefore we are firstly going to require that as of June 1st, 2016, all certificates issued by Symantec itself will be required to support Certificate Transparency. In this case, logging of non-EV certificates would have provided significantly greater insight into the problem and may have allowed the problem to be detected sooner.</span><br />
<span class="byline-author"><br /></span>
After this date, certificates newly issued by Symantec that do not conform to the Chromium Certificate Transparency policy may result in interstitials or other problems when used in Google products.</span><br />
<span class="byline-author"><br /></span>
More immediately, we are requesting of Symantec that they further update their public incident report with:</span><br />
<ol>
<li>A post-mortem analysis that details why they did not detect the additional certificates that we found.</li>
<li>Details of each of the failures to uphold the relevant Baseline Requirements and EV Guidelines and what they believe the individual root cause was for each failure.</li>
</ol>
We are also requesting that Symantec provide us with a detailed set of steps they will take to correct and prevent each of the identified failures, as well as a timeline for when they expect to complete such work. Symantec may consider this latter information to be confidential and so we are not requesting that this be made public.<br />
<div>
<br /></div>
<div>
Following the implementation of these corrective steps, we expect Symantec to undergo a Point-in-time Readiness Assessment and a third-party security audit. The point-in-time assessment will establish Symantec&#8217;s conformance to each of these standards:</div>
<div>
<ul>
<li>WebTrust Principles and Criteria for Certification Authorities</li>
<li>WebTrust Principles and Criteria for Certification Authorities &#8211; SSL Baseline with Network Security</li>
<li>WebTrust Principles and Criteria for Certification Authorities &#8211; Extended Validation SSL</li>
</ul>
<div>
<br /></div>
<div>
The third-party security audit must assess:&nbsp;</div>
<div>
<ul>
<li>The veracity of Symantec&#8217;s claims that at no time private keys were exposed to Symantec employees by the tool.</li>
<li>That Symantec employees could not use the tool in question to obtain certificates for which the employee controlled the private key.</li>
<li>That Symantec&#8217;s audit logging mechanism is reasonably protected from modification, deletion, or tampering, as described in Section 5.4.4 of their CPS.</li>
</ul>
</div>
<div>
<br /></div>
<div>
We may take further action as additional information becomes available to us.</div>
</div>
</div>
</div>
