<div class="entry-content">
<p>Remember <a href="https://nakedsecurity.sophos.com/2015/07/28/the-stagefright-hole-in-android-what-you-need-to-know/">Stagefright</a>?</p>
<p>It was a security hole, or more accurately a cluster of holes, in Android's core media-handling library, known as<tt> libstagefright</tt>.</p>
<p>The official name of the buggy library quickly morphed into the media-friendly moniker of the bugs themselves, <b>Stagefright</b>.</p>
<p>In operating system terms, a "library" (usually known as a DLL, or <i>dynamic link library</i>, on Windows, and as a <i>shared library</i> on Unix-like systems) is a sort of sub-program that can be shared between many applications.</p>
<p>Libraries take care of all sorts of useful functions, such as reading and writing files and directories, performing cryptographic operations, and&nbsp;&ndash; as here&nbsp;&ndash; handling multimedia objects like movies and songs.</p>
<p>Sharing a programming library across many applications saves disk space and memory; it brings consistency in how applications behave; it means that application developers don't have to keep reinventing the wheel; and it simplifies updating.</p>
<p>Of course, it also often means that a vulnerability in one library file could <a href="https://nakedsecurity.sophos.com/2015/07/09/the-openssl-cve-2015-1793-certificate-verification-bug-what-you-need-to-know/">open up exploitable holes</a> in dozens of applications at the same time, following the "injury to one is an injury to all" principle.</p>
<h4>The Stagefright risk</h4>
<p>In theory, opening a booby-trapped MP4 (movie) file could have given a cybercrook a way of running unauthorised, untrusted program code on your Android, without so much as an "Are you sure?"</p>
<p>Worse still, the default settings on Android meant that if someone sent you an MMS (a sort of multimedia SMS) that referenced a booby-trapped movie, your phone would probably download and display it automatically.</p>
<p>Early headlines about Stagefright, driven by the PR efforts of Zimperium, the company that found the bugs, talked in brash terms about how <a href="https://nakedsecurity.sophos.com/2015/07/28/the-stagefright-hole-in-android-what-you-need-to-know/">950,000,000 Androids</a> could theoretically be at risk.</p>
<p>Fortunately, the bugs <a href="https://nakedsecurity.sophos.com/2015/09/11/androids-stagefright-back-in-the-limelight-what-you-need-to-know/">weren't easy to exploit</a> in practice, with the result that very little harm was done during the time it took for Google to get out patches.</p>
<p>The first wave of Stagefright patches appeared in September 2015, as part of Google's promised <a href="https://nakedsecurity.sophos.com/2015/08/06/stagefrightened-google-samsung-to-push-out-monthly-android-fixes/">move towards monthly updates</a>&nbsp;&ndash; a promise that seems to have been extracted from Google largely because of the Stagefright story.</p>
<h4>Stagefright 2</h4>
<p>Guess what? </p>
<p>Zimperium <a href="https://blog.zimperium.com/zimperium-zlabs-is-raising-the-volume-new-vulnerability-processing-mp3mp4-media/" rel="nofollow">didn't stop looking</a> after finding its first tranche of<tt> libstagefright </tt>bugs.</p>
<p>Unfortunately, many programmers are creatures of habit, especially of sloppy habits.</p>
<p>Where you find a coding error that produces an integer overflow, or an unchecked buffer, or a mismanaged memory pointer, you may very well find similar errors nearby.</p>
<p>It's a bit like spelling errors: once your fingers get used to typing, say, "kernal" instead of "kernel", you find yourself making the same mistake repeatedly.</p>
<p style='background-color:#e8e8e8;font-size:80%;padding:.71em;padding-left:2em;text-indent:-1.29em;'>&rarr; Google is no stranger to this effect. In 2013, <a href="https://nakedsecurity.sophos.com/2013/07/10/anatomy-of-a-security-hole-googles-android-master-key-debacle-explained/">poor error checking</a> in how Android processed APK files (Android Packages&nbsp;&ndash; the standard distribution format for apps) resulted in three separately discovered <a href="https://nakedsecurity.sophos.com/2013/07/17/anatomy-of-another-android-hole-chinese-researchers-claim-new-code-verification-bypass/">bugs</a> in app <a href="https://nakedsecurity.sophos.com/2013/11/06/anatomy-of-a-file-format-problem-yet-another-code-verification-bypass-in-android/">verification</a>. All these vulnerabilities caused almost identical security problems: you could feed Android a legitimate, digitally-signed app during verification, but trick the operating system into running malware during execution of the app. Similarly, the programmer who <a href="https://nakedsecurity.sophos.com/2014/07/02/anatomy-of-a-buffer-overflow-googles-keystore-security-module-for-android/">wrote buggy code</a> for Android's KeyStore library repeatedly forgot to allocate space in his text strings for the extra NUL (zero-byte) character that C needs in every string to denote where it ends.</p>
<p>And Zimperium did, indeed, find <a href="https://blog.zimperium.com/zimperium-zlabs-is-raising-the-volume-new-vulnerability-processing-mp3mp4-media/" rel="nofollow">yet more vulnerabilities</a> in Android's media file handing, this time affecting both MP3 (audio) and MP4 (video) files.</p>
<h4>Google's response</h4>
<p>The good news is that Google has now patched these "Stagefright 2" bugs, in the <a href="https://groups.google.com/forum/#!topic/android-security-updates/_Rm-lKnS2M8" rel="nofollow">official security fixes</a> for October 2015.</p>
<p>The bad news is that even Google's own devices, such as the Nexus family of phones and tablets, haven't all actually received their patches yet.</p>
<p>My Nexus 7 from 2012, for instance, has <a href="https://developers.google.com/android/nexus/images" rel="nofollow">firmware updates</a> that stop at Android Lollipop 5.1.1<tt> LMY47V</tt>. [As at 2015-10-06T21:00Z.]</p>
<p>But it seems that you require a build number starting<tt> LMY48 </tt>to have any Stagefright fixes at all, with<tt> LMY48T </tt>or later also giving you fixes against the newer "Stagefright 2" holes.</p>
<p>So the Nexus 5, which has fixes up to<tt> LMY48M</tt>, is probably a bit safer than my Nexus 7, but not as safe as, say, the Nexus 6, which is up to<tt> LMY48W</tt>.</p>
<h4>The bottom line</h4>
<p>Android updates, despite Google's "monthly-at-a-minimum" commitment, still seem to be all over the place.</p>
<p>Making sure you have the latest patches available from your Android vendor or network carrier is easy, because you can check from the<tt> Settings </tt>page.</p>
<p>But finding out what's actually fixed in those patches is a lot less obvious, so if you aren't sure, you're going to have to ask.</p>
<p>Additionally, as we've suggested before, you should probably <a href="https://nakedsecurity.sophos.com/2015/07/28/the-stagefright-hole-in-android-what-you-need-to-know/">turn off</a> the auto-download of MMS messages on your Android.</p>
<p>Even without the Stagefright bugs&nbsp;&ndash; and, anyway, who knows but that we might yet see Stagefright 3?&nbsp;&ndash; it's a bad idea to allow an outsider to force untrusted remote content to load on your phone simply by sending you a message.</p>
<p>That's sounds a bit too much like email attachments that get opened up whether you want them or not, so they're ready as soon as you read the mail.</p>
</div>
</div>
                                                                        </div><!-- .entry-content -->
