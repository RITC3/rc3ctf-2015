Throwback - 50 Points
=====================
This one is simple but not many details are given so it can be tricky. To get the flag you have to enter the Konami cheat code (up, up, down, down left, right, left, right, B, A). The flag is embedded in the real foundation.topbar.min.js as an array of integers that get converted to characters and concatenated together. The konami code script which has been called foundation.dropdown.min.js, compressed and had variables renamed will only be included on the page on the challenge board. The challenge can also be solved by invoking $(document).foundation('topbar', 'xlarge'); in the script console because that is all that the konami code does.<br>
Flag: **RC3-JSPT-8574**
